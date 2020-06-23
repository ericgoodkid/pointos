<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    protected $table = 'Supplier';
    protected $hidden = [
        'id', 
        'created_at', 
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'name', 'code', 'address', 'contact_person', 'contact_number'
    ];
    
    // protected $appends = ['text', 'value'];

    public function getTextAttribute()
    {
        return $this->attributes['name'];
    }

    public function getValueAttribute()
    {
        return $this->attributes['code'];
    }

    public function getSupplierList()
    {
        return Supplier::all();
    }

    public function getSuppliers()
    {
        return Supplier::all();
    }

    public function countSupplier()
    {
        return Supplier::withTrashed()->count();
    }

    public function createSupplier($oItem)
    {
        return Supplier::create([
            'name' => $oItem['name'],
            'code' => $oItem['code'],
            'address' => $oItem['address'],
            'contact_person' => $oItem['contact_person'],
            'contact_number' => $oItem['contact_number'],
        ]);
    }

    public function getSupplier($sCode)
    {
        return Supplier::where('code', $sCode)->get()->first();
    }
    
    public function updateSupplier($oItem, $sCode) 
    {
        $oOldItem = $this->getSupplier($sCode);
        $oOldItem->name = $oItem['name'];
        $oOldItem->address = $oItem['address'];
        $oOldItem->contact_person = $oItem['contact_person'];
        $oOldItem->contact_number = $oItem['contact_number'];
        return $oOldItem->save();
    }

    public function deleteSupplier($sCode) 
    {
        $oItem = $this->getSupplier($sCode);
        return $oItem->delete();
    }
}
