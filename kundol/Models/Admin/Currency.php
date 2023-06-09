<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;
    protected $table = 'currency';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'code', 'symbol_position','decimal_point', 'thousand_point', 'decimal_place', 'is_default', 'status', 'country_id', 'created_by', 'updated_by', 'exchange_rate'
    ];

    public function country()
    {
        return $this->belongsTo('App\Models\Admin\Country', 'country_id', 'id');
    }

    public function scopeSearchParameter($query, $parameter)
    {
        return $query->where('title', 'like', '%' . $parameter . '%');
    }

    public function scopeDefaultCurrency($query)
    {
        return $query->where('is_default', '1');
    }

    public function scopeNotCurrencyId($query, $id)
    {
        return $query->where('id', '!=', $id);
    }

    public function scopeFindByCurrencyId($query, $id)
    {
        return $query->where('id', $id);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeinActive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeOrderByStatus($query)
    {

        $query1 = "CASE WHEN status = 'active' THEN 1 ";
        $query1 .= "WHEN status = 'inactive' THEN 2 ";
        $query1 .= "ELSE 3 END ASC";
 

        return $query->orderByRaw($query1);
    }

}
