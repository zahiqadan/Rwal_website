<?php

namespace App\Repository\Web;

use App\Contract\Web\OrderInterface;
use App\Traits\ApiResponser;
use Illuminate\Support\Collection;
use App\Jobs\OrderProcess;
use App\Models\Admin\OrderComment;
use App\Models\Admin\PaymentMethodSetting;
use App\Models\Web\Order;
use App\Services\Admin\OrderService;
use Session;
use App\Models\Web\OrderHistory;
use Illuminate\Support\Facades\Http;

class OrderRepository implements OrderInterface
{
    use ApiResponser;

    public function store(array $parms)
    {
        try {
            $cartItemValidation = new OrderService;
            $cartItemValidation = $cartItemValidation->CartItemValidation($parms);

            if ($cartItemValidation == 0)
                return $this->errorResponse('Empty Cart!');
         
            return OrderProcess::dispatchNow($parms);
            return $this->successResponse(OrderProcess::dispatchNow($parms), 'Data Get Successfully!');
        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }

    public function update($order, array $parms)
    {
        
        try {
            
            $order->update(['order_status'=>$parms['order_status']]);
            if($parms['order_status'] == 'Return' || $parms['order_status'] == 'Cancel'){
                $orderService = new OrderService;
                $orderService = $orderService->revertOrderProductsQuantity($order);
            }
            OrderHistory::create([
                'order_id'=>$order->id,
                'order_status' =>  $parms['order_status']
            ]);
            return $this->successResponse('', 'Status Updated Successfully!');

        } catch (Exception $e) {
            return $this->errorResponse();
        }
    }

    public function addOrderComments(array $parms){
        // return \Auth::id();
        $data = array('order_id'=>1,'message'=>$parms['comment'],'customer_id' => \Auth::id());
        try {
            $orderComments = OrderComment::create($data);
        } catch (Exception $e) {
            return $this->errorResponse();
        }

        if ($orderComments) {
            // return $orderNotes;  
            return $this->successResponseArray([$orderComments], 'Order Update Successfully!');
        } else {
            return $this->errorResponse();
        }
    }


    public function getBraintreeAuthToken()
    {
        $this->gateway = \Omnipay\Omnipay::create('Braintree');
        $payment = PaymentMethodSetting::where('payment_method_id',5)->get();
        $this->gateway->setMerchantId(isset($payment[0]->value) ? $payment[0]->value : '');
        $this->gateway->setPublicKey(isset($payment[1]->value) ? $payment[1]->value : '');
        $this->gateway->setPrivateKey(isset($payment[2]->value) ? $payment[2]->value : '');
        $this->gateway->setTestMode(true); // here 'true' is for sandbox. Pass 'false' when go live

        $token = ['token' => $this->gateway->clientToken()->send()->getToken()];

        return $this->successResponseArray($token, 'Data Get Successfully!');
    }

    public function paystackAuthorization(array $parms){
        $payment = PaymentMethodSetting::where('payment_method_id',12)->get();
        $order = Order::where('id',$parms['order_id'])->first();
        $order_price = $order->order_price;
        $email = \Auth::user()->email;

        $url = "https://api.paystack.co/transaction/initialize";
        $fields = [
            'email' => $email,
            'amount' => (int)$order_price,
            'metadata' => [
                'order_id'=>$parms['order_id']
            ]
        ];
        
        $response = Http::withHeaders([
            "Authorization" => isset($payment[1]->value) ? "Bearer ".$payment[1]->value : '',
            "Cache-Control" =>"no-cache",
        ])->post($url, $fields);
        return $this->successResponseArray($response['data']['authorization_url'], 'Data Get Successfully!');
    }

    public function getfygaroButton()
    {
        $fygaro_button = PaymentMethodSetting::where('payment_method_id',13)->get();
        $fygaro_button = (($fygaro_button[2]->value) ? $fygaro_button[2]->value : '');

        return $this->successResponseArray($fygaro_button, 'Data Get Successfully!');
    }

    public function makeFygaroJWT(array $parms)
    {
        $order = Order::where('id',$parms['order_id'])->first();
        $order_price = $order->order_price;

        try {

            $fygaro = PaymentMethodSetting::where('payment_method_id', 13)->get();
            $fygaro_key = $fygaro[0]->value;
            $fygaro_secret = $fygaro[1]->value;

            // Create token header as a JSON string
            $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256', 'kid' => $fygaro_key]);

            // Create token payload as a JSON string
            $payload = json_encode(['amount' => $order_price]);

            // Encode Header to Base64Url String
            $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

            // Encode Payload to Base64Url String
            $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

            // Create Signature Hash
            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $fygaro_secret, true);

            // Encode Signature to Base64Url String
            $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

            // Create JWT
            $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

            $orderService = new OrderService;
            $orderService = $orderService->orderTransectionCredit($order);

            return $this->successResponseArray($jwt, 'Data Get Successfully!');
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
