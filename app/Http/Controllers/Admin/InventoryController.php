<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class InventoryController extends BaseController
{
    
    public function __construct(Request $oRequest)
    {
        $this->setParams($oRequest->all());
        $this->instantiateInventoryProvider();
    }

    public function saveInventory(Request $oRequest)
    {
        return $this->oInventoryProvider->saveInventory($this->mParams);
    }

}
