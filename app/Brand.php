<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;
    protected $table = 'Brand';
    protected $hidden = [
        'id', 
        'status', 
        'created_at', 
        'updated_at'
    ];

    protected $fillable = [
        'name', 'code'
    ];

    // protected $appends = ['text', 'value'];
    
    public function getBrandList()
    {
        return Brand::all();
    }

    public function getTextAttribute()
    {
        return $this->attributes['name'];
    }

    public function getValueAttribute()
    {
        return $this->attributes['code'];
    }

    public function getBrands()
    {
        return Brand::all();
    }

    public function countBrand()
    {
        return Brand::withTrashed()->count();
    }

    public function createBrand($oItem)
    {
        return Brand::create([
            'name' => $oItem['name'],
            'code' => $oItem['code'],
        ]);
    }

    public function getBrand($sCode) 
    {
        return Brand::where('code', $sCode)->get()->first();
    }

    public function getBrandByName($sName) 
    {
        return Brand::whereRaw("LOWER(REPLACE(`name`, ' ' ,''))  = ?", [strtolower (str_replace(' ', '', $sName))])
            ->get()
            ->first();
    }
    
    public function updateBrand($oItem, $sCode) 
    {
        $oOldItem = $this->getBrand($sCode);
        $oOldItem->name = $oItem['name'];
        return $oOldItem->save();
    }

    public function deleteBrand($sCode) 
    {
        $oItem = $this->getBrand($sCode);
        return $oItem->delete();
    }
}
