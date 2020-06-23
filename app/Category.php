<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $table = 'category';
    protected $hidden = [
        'id', 
        'status', 
        'created_at', 
        'updated_at'
    ];

    protected $fillable = [
        'name', 'code'
    ];
    
    protected $appends = ['text', 'value'];

    public function getTextAttribute()
    {
        return $this->attributes['name'];
    }

    public function getValueAttribute()
    {
        return $this->attributes['code'];
    }

    public function getCategoryList()
    {
        return Category::all();
    }

    public function getCategories()
    {
        return Category::all();
    }

    public function countCategory()
    {
        return Category::withTrashed()->count();
    }

    public function createCategory($oItem)
    {
        return Category::create([
            'name' => $oItem['name'],
            'code' => $oItem['code'],
        ]);
    }

    public function getCategory($sCode) 
    {
        return Category::where('code', $sCode)->get()->first();
    }

    public function getCategoryByName($sName) 
    {
        return Category::whereRaw("LOWER(REPLACE(`name`, ' ' ,''))  = ?", [strtolower (str_replace(' ', '', $sName))])
            ->get()
            ->first();
    }
    
    public function updateCategory($oItem, $sCode) 
    {
        $oOldItem = $this->getCategory($sCode);
        $oOldItem->name = $oItem['name'];
        return $oOldItem->save();
    }

    public function deleteCategory($sCode) 
    {
        $oItem = $this->getCategory($sCode);
        return $oItem->delete();
    }
}
