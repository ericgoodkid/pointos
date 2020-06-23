<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Inventory;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryHistory extends Model
{
    use SoftDeletes;
    protected $table = 'Inventory_History';
    protected $fillable = [
        'price', 'product_id', 'supplier_id', 'quantity', 'code', 'remarks'
    ];

    protected $hidden = [
         'id', 'updated_at', 'deleted_at'
    ];

    protected $appends = [
        'actual_cost'
    ];

    public function getActualCostAttribute()
    {
        return $this->quantity * (float) $this->price;
    }

    public function createInventoryHistory($oInventory)
    {
        return InventoryHistory::create([
            'code' => $oInventory['code'],
            'supplier_id' => $oInventory['supplier_id'],
            'product_id' => $oInventory['product_id'],
            'price' => $oInventory['price'],
            'quantity' => $oInventory['quantity'],
            'remarks' => $oInventory['remarks']
        ]);
    }

    public function countInventoryHistory()
    {
        return InventoryHistory::withTrashed()->count();
    }

    public function getInventoryHistoryList()
    {
        // dd(InventoryHistory::with('getProduct', 'getSupplier')->get());
        return InventoryHistory::with('getProduct', 'getSupplier')->get();
    }

    public function getInventoryHistoryListWithParams($oParams)
    {
        $oModel = InventoryHistory::with('getProduct', 'getSupplier');
        if (array_key_exists('supplier_code', $oParams) === true) {
            $oModel->where('supplier_id', $oParams['supplier_id']);
        }

        if (array_key_exists('product_code', $oParams) === true) {
            $oModel->where('product_id', $oParams['product_id']);
        }

        if (array_key_exists('start_date', $oParams) === true && array_key_exists('end_date', $oParams) === false) {
            $oModel->where('created_at', '>', $oParams['start_date']);
        } else if (array_key_exists('end_date', $oParams) === true && array_key_exists('start_date', $oParams) === false) {
            $oModel->where('created_at', '<', $oParams['end_date']);
        } else if (array_key_exists('end_date', $oParams) === true && array_key_exists('start_date', $oParams) === true) {
            $oModel->whereBetween('created_at', [$oParams['start_date'], $oParams['end_date']]);
        }

        return $oModel->get();
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

    public function getInventoryHistory($sCode) 
    {
        return InventoryHistory::where('code', $sCode)->with('getProduct', 'getSupplier')->get()->first();
    }

    public function deleteInventoryHistory($sCode) 
    {
        $oOldInventoryHistory = $this->getInventoryHistory($sCode);
        return $oOldInventoryHistory->delete();
    }

    public function updateInventoryHistory($oInventory, $sCode) 
    {
        $oOldInventory = $this->getInventoryHistory($sCode);
        $oOldInventory->supplier_id = $oInventory['supplier_id'];
        $oOldInventory->price = $oInventory['price'];
        $oOldInventory->quantity = $oInventory['quantity'];
        $oOldInventory->remarks = $oInventory['remarks'];
        return $oOldInventory->save();
    }
}
