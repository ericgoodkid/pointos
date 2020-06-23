<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdditionalExpenseHistory extends Model
{
    use SoftDeletes;
    protected $table = 'Additional_Expense_History';
    protected $hidden = [
        'id', 
        'status', 
        'created_at', 
        'updated_at'
    ];

    protected $fillable = [
        'additional_expense_id', 'code', 'amount','remarks'
    ];

    protected $appends = [
        'formatted_remarks',
        'formatted_amount'
    ];

    public function getFormattedRemarksAttribute()
    {
        return $this->attributes['remarks'] === null ? 'None' : $this->attributes['remarks'];
    }

    public function getFormattedAmountAttribute()
    {
        $iAmount = $this->attributes['amount'];
        return '₱ ' . number_format($iAmount, 2);
    }

    public function getAdditionalExpenseHistoryList()
    {
        return AdditionalExpenseHistory::with('getAdditionalExpenseType')
        ->get();
    }

    public function countAdditionalExpenseHistory()
    {
        return AdditionalExpenseHistory::withTrashed()->count();
    }

    public function createAdditionalExpenseHistory($oItem)
    {
        return AdditionalExpenseHistory::create([
            'additional_expense_id' => $oItem['additional_expense_id'],
            'code' => $oItem['code'],
            'amount' => $oItem['amount'],
            'remarks' => $oItem['remarks'],
        ]);
    }

    public function getAdditionalExpenseHistory($sCode) 
    {
        return AdditionalExpenseHistory::with('getAdditionalExpenseType')
        ->where('code', $sCode)
        ->get()
        ->first();
    }
    
    public function updateAdditionalExpenseHistory($oItem, $sCode) 
    {
        $oOldItem = $this->getAdditionalExpenseHistory($sCode);
        $oOldItem->additional_expense_id = $oItem['additional_expense_id'];
        $oOldItem->amount = $oItem['amount'];
        $oOldItem->remarks = $oItem['remarks'];
        return $oOldItem->save();
    }

    public function deleteAdditionalExpenseHistory($sCode) 
    {
        $oItem = $this->getAdditionalExpenseHistory($sCode);
        return $oItem->delete();
    }

    public function getAdditionalExpenseType()
    {
        return $this->belongsTo('App\AdditionalExpense', 'additional_expense_id', 'id');
    }
}
