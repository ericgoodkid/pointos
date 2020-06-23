<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReturnProduct extends Model
{
    use SoftDeletes;
    protected $table = 'return_product';
    protected $fillable = [
        'product_id', 'supplier_id', 'quantity', 'code', 'remarks', 'status'
    ];

    protected $hidden = [
         'id', 'updated_at', 'deleted_at'
    ];

    protected $appends = ['remarks'];

    public function getRemarksAttribute()
    {
        $sRemarks = $this->attributes['remarks'];
        return $sRemarks === null ? '-' : $sRemarks;
    }

    
    public function getReturnList()
    {
        return ReturnProduct::with('getProduct', 'getSupplier')->get();

    } 

    public function countReturn()
    {
        return ReturnProduct::withTrashed()->count();
    }

    public function createReturn($oItem)
    {
        return ReturnProduct::create([
            'code' => $oItem['code'],
            'supplier_id' => $oItem['supplier_id'],
            'product_id' => $oItem['product_id'],
            'quantity' => $oItem['quantity'],
            'remarks' => $oItem['remarks']
        ]);
    }

    public function getReturn($sCode) 
    {
        return ReturnProduct::with('getProduct')
        ->where('code', $sCode)
        ->get()
        ->first();
    }
    
    public function updateReturn($oItem) 
    {
      return true;
    }

    public function approvalReturn($oItem) 
    {
        $oOldItem = $this->getReturn($oItem['code']);
        $oOldItem->status = $oItem['status'];
        return $oOldItem->save();
    }
    

    public function deleteReturn($sCode) 
    {
        $oItem = $this->getReturn($sCode);
        return $oItem->delete();
    }

    public function getProduct()
    {
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }

    public function getSupplier()
    {
        return $this->belongsTo('App\Supplier', 'supplier_id', 'id')
        ->withDefault([
            'name' => 'No Supplier',
            'code' => 'none'
        ]);
    }
}
