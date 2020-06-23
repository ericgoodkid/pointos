<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    protected $table = 'Business';
    protected $hidden = [
        'id', 
        'status', 
        'created_at', 
        'updated_at'
    ];

    protected $fillable = [
        'name', 'address'
    ];

    public function createBusiness($oItem)
    {
        return Business::create([
            'name' => $oItem['name'],
            'address' => $oItem['address'],
        ]);
    }

    public function getBusiness() 
    {
        return Business::all()
        ->first();
    }
    
    public function updateBusiness($oItem) 
    {
        $oOldItem = $this->getBusiness();
        $oOldItem->name = $oItem['name'];
        $oOldItem->address = $oItem['address'];
        return $oOldItem->save();
    }
}
