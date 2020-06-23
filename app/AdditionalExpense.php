<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdditionalExpense extends Model
{
    use SoftDeletes;
    protected $table = 'Additional_Expense';
    protected $hidden = [
        'id', 
        'status', 
        'created_at', 
        'updated_at'
    ];

    protected $fillable = [
        'code',
        'type'
    ];

    
    public function getAdditionalExpenseList()
    {
        return AdditionalExpense::all();
    }
    public function getAdditionalExpenses()
    {
        return AdditionalExpense::all();
    }

    public function countAdditionalExpense()
    {
        return AdditionalExpense::withTrashed()->count();
    }

    public function createAdditionalExpense($oItem)
    {
        return AdditionalExpense::create([
            'type' => $oItem['type'],
            'code' => $oItem['code']
        ]);
    }

    public function getAdditionalExpense($sCode) 
    {
        return AdditionalExpense::where('code', $sCode)->get()->first();
    }
    
    public function updateAdditionalExpense($oItem, $sCode) 
    {
        $oOldItem = $this->getAdditionalExpense($sCode);
        $oOldItem->type = $oItem['type'];
        return $oOldItem->save();
    }

    public function deleteAdditionalExpense($sCode) 
    {
        $oItem = $this->getAdditionalExpense($sCode);
        return $oItem->delete();
    }
}
