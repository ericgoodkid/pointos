<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class InventoryHistoryController extends BaseController
{
    
    public function __construct(Request $oRequest)
    {
        $this->setParams($oRequest->all());
        $this->instantiateInventoryHistoryProvider();
    }

    public function getInventoryHistoryList()
    {
        return $this->oInventoryHistoryProvider->getInventoryHistoryList($this->mParams);
    }

    public function deleteInventoryHistory()
    {
        return $this->oInventoryHistoryProvider->deleteInventoryHistory($this->mParams);
    }

    public function updateInventoryHistory()
    {
        return $this->oInventoryHistoryProvider->updateInventoryHistory($this->mParams);
    }

    public function getInventoryHistory($sCode)
    {
        $this->setParams(['sCode' => $sCode]);
        return $this->oInventoryHistoryProvider->getInventoryHistory($this->mParams);
    }

    
}
