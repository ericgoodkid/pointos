<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $table = 'Order';
    protected $hidden = [
        'id', 
        'status', 
        // 'created_at', 
        'updated_at'
    ];

    protected $fillable = [
        'code',
        'user_id',
        'cash'
    ];

    protected $appends = ['total_price', 'total_quantity'];
    public static $withoutAppends = true;
    protected function getArrayableAppends()
    {
        if(self::$withoutAppends){
            return [];
        }
        return parent::getArrayableAppends();
    }

    public function getTotalPriceAttribute()
    {
        $aOrderItem = $this->relations['getOrderItem'];
        $iTotalAmount = 0;
        foreach($aOrderItem as $oOrderItem) {
            $iQuantity = $oOrderItem['quantity'];
            $iPrice = $oOrderItem['product_price'];
            $iPercentage = $oOrderItem['discount_percentage'] === null ? 0 : $oOrderItem['discount_percentage'];
            $iDiscountedPrice = $iPrice - ($iPrice * ($iPercentage / 100));
            $iTotalItemPrice = $iDiscountedPrice * $iQuantity;
            $iTotalAmount += $iTotalItemPrice;
        }

        return $iTotalAmount;
    }

    public function getTotalQuantityAttribute()
    {
        $aOrderItem = $this->relations['getOrderItem'];
        $iTotalQuantity = 0;
        foreach($aOrderItem as $oOrderItem) {
            $iQuantity = $oOrderItem['quantity'];
            $iTotalQuantity += $iQuantity;
        }

        return $iTotalQuantity;
    }

    public function getOrderList()
    {
        return Order::with('getOrderItem','getUser')->get();
    }

    public function getOrders()
    {
        return Order::all();
    }

    public function countOrder()
    {
        return Order::withTrashed()->count();
    }

    public function createOrder($oItem)
    {
        return Order::create([
            'code'    => $oItem['code'],
            'cash'    => $oItem['cash'],
            'user_id' => $oItem['user_id'],
        ]);
    }

    public function getOrder($sCode) 
    {
        return Order::with('getOrderItem', 'getOrderItem.getProduct', 'getUser')
        ->where('code', $sCode)
        ->get()
        ->first();
    }
    
    public function updateOrder($oItem, $sCode) 
    {
        $oOldItem = $this->getOrder($sCode);
        $oOldItem->cash = $oItem['cash'];
        $oOldItem->user_id = $oItem['user_id'];
        return $oOldItem->save();
    }

    public function deleteOrder($sCode) 
    {
        $oItem = $this->getOrder($sCode);
        return $oItem->delete();
    }

    public function getOrderItem()
    {
        return $this->hasMany('App\OrderItem');
    }

    public function getUser()
    {
        return $this->belongsTo('App\User','user_id', 'id');
    }

}
