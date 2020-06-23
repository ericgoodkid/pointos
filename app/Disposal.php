<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disposal extends Model
{
    use SoftDeletes;
    protected $table = 'product_dispose';
    protected $hidden = [
        'id', 
        'created_at', 
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'code', 'product_id', 'quantity'
    ];

    public function disposeProduct($oItem)
    {
        return Disposal::create([
            'code' => $oItem['code'],
            'product_id' => $oItem['product_id'],
            'quantity' => $oItem['quantity']
        ]);
    }

    public function countDisposal()
    {
        return Disposal::withTrashed()->count();
    }


}
