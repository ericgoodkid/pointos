<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Discount extends Model
{
    use SoftDeletes;
    protected $table = 'discount';
    protected $hidden = [
        'product_id',
        'id', 
        'status', 
        'created_at', 
        'updated_at'
    ];

    protected $fillable = [
        'product_id', 'code', 'amount', 'minimum', 'start_date', 'end_date', 'name'
    ];

    protected $appends = ['timespan', 'formatted_start_date', 'formatted_end_date'];
    
    public function getDiscountList()
    {
        return Discount::all();
    }

    public function getTimespanAttribute()
    {
        $sFormat = 'MMMM D YYYY';
        return Carbon::parse($this->attributes['start_date'])->isoFormat($sFormat) . ' to ' . Carbon::parse($this->attributes['end_date'])->isoFormat($sFormat);
    }

    public function getFormattedStartDateAttribute()
    {
        $sFormat = 'MM D YY';
        return Carbon::parse($this->attributes['start_date'])->isoFormat($sFormat);
    }

    public function getFormattedEndDateAttribute()
    {
        $sFormat = 'MM D YY';
        return Carbon::parse($this->attributes['end_date'])->isoFormat($sFormat);
    }


    public function getDiscounts()
    {
        date_default_timezone_set('Asia/Manila');
        $sCurrentDate = date('Y-m-d H:i:s');
        return Discount::where('start_date', '<=', $sCurrentDate)
        ->where('end_date', '>=', $sCurrentDate)
        ->with('getDiscountItem.getProduct')
        ->get();
    }
    
    public function countDiscount()
    {
        return Discount::withTrashed()->count();
    }

    public function createDiscount($oItem)
    {
        return Discount::create([
            'code' => $oItem['code'],
            'name' => $oItem['name'],
            'amount' => $oItem['amount'],
            'minimum' => $oItem['minimum'],
            'start_date' => $oItem['start_date'],
            'end_date' => $oItem['end_date']
        ]);
    }

    public function getDiscount($sCode) 
    {
        return Discount::where('code', $sCode)
        ->with('getDiscountItem.getProduct')
        ->get()
        ->first();
    }
    
    public function updateDiscount($oItem) 
    {
        $oOldItem = $this->getDiscount($oItem['code']);
        $oOldItem->name = $oItem['name'];
        $oOldItem->amount = $oItem['amount'];
        $oOldItem->minimum = $oItem['minimum'];
        $oOldItem->start_date = $oItem['start_date'];
        $oOldItem->end_date = $oItem['end_date'];
        return $oOldItem->save();
    }

    public function deleteDiscount($sCode) 
    {
        $oItem = $this->getDiscount($sCode);
        return $oItem->delete();
    }

    public function getProduct()
    {
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }

    public function getDiscountItem()
    {
        return $this->hasMany('App\DiscountItem', 'discount_id', 'id');
    }
}
