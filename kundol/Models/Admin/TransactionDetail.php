<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{

    protected $table="transaction_detail";

    protected $fillable = [
        'transaction_id','description', 'account_id', 'reference_id','type','dr_amount','cr_amount','user_id','warehouse_id'
    ];

    public function ScopeReferenceId($query, $id)
    {
        $query->where('reference_id', $id);
    }

    public function scopeSumOfCredit($query)
    {
        return $query->where('reference_id', Auth::id())->sum('cr_amount');
    }

    public function scopeSumOfDebit($query)
    {
        return $query->where('reference_id', Auth::id())->sum('dr_amount');
    }

    public function scopeGetTotalFromDebitAndCredit()
    {
        $credit = $this->SumOfCredit();
        $debit = $this->SumOfDebit();
        $total = $credit - $debit;
        return number_format($total, 2, '.', '');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Models\Admin\Transaction');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Admin\Customer');
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Admin\Account');
    }

    public function scopeSearchParameter($query, $parameter)
    {
        return $query->where('transaction_id', 'like', '%' . $parameter . '%')
        ->Orwhere('account_id', 'like', '%' . $parameter . '%')
        ->Orwhere('dr_amount', 'like', '%' . $parameter . '%')
        ->Orwhere('cr_amount', 'like', '%' . $parameter . '%');
    }
}
