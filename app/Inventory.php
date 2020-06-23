<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Inventory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;
    protected $table = 'Inventory';
    protected $fillable = [
        'name', 'product_id', 'quantity'
    ];

    protected $hidden = [
        'created_at', 'id', 'updated_at', 'deleted_at'
    ];

    public function getInventory($iId) 
    {
        return Inventory::where('product_id', $iId)->get()->first();
    }
    
    public function createInventory($oInventory)
    {
        return Inventory::create([
            'product_id' => $oInventory['product_id'],
            'quantity' => $oInventory['quantity']
        ]);
    }

    public function updateInventory($oInventory, $iId)
    {
        $oOldInventory = $this->getInventory($iId);
        $oOldInventory->product_id = $oInventory['product_id'];
        $oOldInventory->quantity = $oInventory['quantity'];
        return $oOldInventory->save();
    }

    public function countInventory()
    {
        return Inventory::withTrashed()->count();
    }
}
