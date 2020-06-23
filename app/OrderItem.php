<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;
    protected $table = 'Order_item';
    protected $hidden = [
        'id', 
        'status', 
        'created_at', 
        'updated_at'
    ];

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'product_brand',
        'product_price',
        'product_capital',
        'discount_name',
        'discount_percentage',
        'quantity'
    ];

    public function createOrderItem($oItem)
    {
        return OrderItem::create([
            'order_id' => $oItem['id'],
            'product_id' => $oItem['product_id'],
            'product_name' => $oItem['product_name'],
            'product_sku' => $oItem['product_sku'],
            'product_brand' => $oItem['product_brand'],
            'product_capital' => $oItem['product_capital'],
            'product_price' => $oItem['product_price'],
            'discount_name' => $oItem['discount_name'],
            'discount_percentage' => $oItem['discount_percentage'],
            'quantity' => $oItem['quantity']
        ]);
    }

    public function deleteRecentOrder($iId)
    {
        $aOrder = OrderItem::where('order_id', $iId);

        return $aOrder->delete();
    }

    public function getProduct()
    {
        return $this->hasOne('App\Product', 'id', 'product_id');
    }

}
