<?php

namespace App\Repository\Web;

use Auth;
use Mail;
use Artisan;
use App\Models\Web\Cart;
use App\Mail\ForgetPassword;
use App\Traits\ApiResponser;
use App\Models\Admin\Setting;
use App\Models\Admin\Customer;
use App\Services\Admin\PointService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Blade;
use App\Services\Admin\AccountService;
use App\Models\Admin\EmailTemplateSetting;
use App\Contract\Web\CustomerAuthInterface;
use App\Jobs\WelcomeMail;

class CustomerAuthRepository implements CustomerAuthInterface
{
    use ApiResponser;
    public function store(array $parms)
    {
        // return "i am in repository";
        $input = $parms;
        $input['password'] = bcrypt($input['password']);
        $input['hash'] = str_replace("/", '1', Hash::make('time'));
        $user = Customer::create($input);
        config(['auth.guards.api.provider' => 'customer']);
        $token = $user->createToken('MyApp', ['customer'])->accessToken;
        $cookie = $this->getCookieDetails($token);
        if (isset($parms['session_id']) && $parms['session_id'] != '') {
            Cart::where('session_id', $parms['session_id'])->update(['customer_id' => $user->id, 'session_id' => '']);
        }
        $points = new PointService;
        $points->customerPoints($parms, $user->id);

        $user['token'] = $token;
        $accounts = new AccountService;
        $accounts->createAccount('CUSTOMER',$input['first_name'].' '.$input['last_name'], $user->id,'customer');

        $welcome_template = Setting::where('key',['welcome_email_template'])->pluck('value');
        $welcome_template = $welcome_template[0];
        
        $message = "";
        $email = $input['email'];

        dispatch(new WelcomeMail($email, $welcome_template));

        return response()
            ->json([
                'status' => 'Success',
                'data' => $user,
            ], 200)
            ->cookie($cookie['name'], $cookie['value'], $cookie['minutes'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
    }

    public function forgetPassword(array $parms)
    {
        $link = str_replace("/", '1', Hash::make('time'));
        Customer::where('email', $parms['email'])->update(['forget_hash' => $link]);
        // $data_set = array('link' => $link);
        $data_set = url('/reset-password?token='.$link);

        $forget_email_template = Setting::where('key',['forget_email_template'])->pluck('value');

        $forget_email_template = $forget_email_template[0];

        $forget_email_with_link = str_replace("forget_link",$data_set,$forget_email_template);

        $message = "";
        $email = $parms['email'];
        
        Mail::to($email)->send(new ForgetPassword($forget_email_with_link));


        // Mail::send('emails.forget-email-template', ['forget_email_with_link' => $forget_email_with_link], function ($message) use ($email) {
        //     $message->to($email)
        //             ->subject('Forget Password');
        // });
        return $this->successResponseArray($link, 'Email Sent Successfully! Against this Link');
    }

    public function resetPassword(array $parms)
    {
        Customer::where('forget_hash', $parms['forget_id'])->update([
            'password' => bcrypt($parms['password']),
            'forget_hash' => null
        ]);
        return $this->successResponse('', 'Password Change Successfully!');
    }


    public function changePassword(array $parms)
    {
        
        Customer::where('password', Hash::make($parms['current_password']))->update([
            'password' =>$parms['new_password']
        ]);
        return $this->successResponse('', 'Password Change Successfully!');
    }

    

    public function loginWithProvider($users)
    {
        auth()->guard('customer')->loginUsingId($users->id);

        config(['auth.guards.api.provider' => 'customer']);

        $user = Customer::select('customers.*')->find(auth()->guard('customer')->user()->id);
        $success =  $user;

        $points = new PointService;
        $points->checkinPoints($success['id']);

        $success['token'] =  $user->createToken('MyApp', ['customer'])->accessToken;
        $cookie = $this->getCookieDetails($success['token']);
        if ($users['callFromWeb'])
        {
           return $user; 
        } else {
            return response()->json([
                'status' => 'Success',
                'data' => $user,
            ], 200)
                ->cookie(
                    $cookie['name'],
                    $cookie['value'],
                    $cookie['minutes'],
                    $cookie['path'],
                    $cookie['domain'],
                    $cookie['secure'],
                    $cookie['httponly']
                );
        }    
    }

    public function login(array $parms)
    {
        if (auth()->guard('customer')->attempt(['email' => request('email'), 'password' => request('password')])) {

            config(['auth.guards.api.provider' => 'customer']);

            $user = Customer::select('customers.*')->find(auth()->guard('customer')->user()->id);
            if (isset($parms['session_id']) && $parms['session_id'] != '') {
                Cart::where('session_id', $parms['session_id'])->update(['customer_id' => auth()->guard('customer')->user()->id, 'session_id' => '']);
            }
            $success =  $user;

            $points = new PointService;
            $points->checkinPoints($success['id']);

            $success['token'] =  $user->createToken('MyApp', ['customer'])->accessToken;
            $cookie = $this->getCookieDetails($success['token']);
            return response()->json([
                'status' => 'Success',
                'data' => $user,
            ], 200)
                ->cookie(
                    $cookie['name'],
                    $cookie['value'],
                    $cookie['minutes'],
                    $cookie['path'],
                    $cookie['domain'],
                    $cookie['secure'],
                    $cookie['httponly']
                );
        } else {
            return $this->errorResponse('Email Or Password is Wrong.', 422);
        }
    }
    public function getCookieDetails($token)
    {
        return [
            'name' => '_customer_token',
            'value' => $token,
            'minutes' => 1440,
            'path' => null,
            'domain' => null,
            // 'secure' => true, // for production
            'secure' => null, // for localhost
            'httponly' => true,
            'type' => 'customer'
        ];
    }

    public function logout(array $parms)
    {
        $token = auth()->user()->token();
        $token->revoke();
        $cookie = \Cookie::forget('_customer_token');
        return response()->json([
            'status' => 'Success',
        ])->withCookie($cookie);
    }
}
