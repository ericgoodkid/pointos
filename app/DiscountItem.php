<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountItem extends Model
{
    use SoftDeletes;
    protected $table = 'discount_item';
    protected $hidden = [
        'id', 
        'status', 
        'created_at', 
        'updated_at'
    ];

    protected $fillable = [
        'discount_id',
        'product_id',
    ];

    public function createDiscountItem($oItem)
    {
        return DiscountItem::create([
            'product_id' => $oItem['product_id'],
            'discount_id' => $oItem['discount_id'],
        ]);
    }

    public function deleteRecentDiscount($iId)
    {
        $aOrder = DiscountItem::where('discount_id', $iId);
        return $aOrder->delete();
    }

    public function getProduct()
    {
        return $this->hasOne('App\Product', 'id', 'product_id');
    }

}
