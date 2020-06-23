<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class AdditionalExpenseHistoryController extends BaseController
{
    
    public function __construct(Request $oRequest)
    {
        $this->setParams($oRequest->all());
        $this->instantiateAdditionalExpenseHistoryProvider();
    }


    public function getAdditionalExpenseHistory($sCode)
    {
        $this->setParams(['sCode' => $sCode]);
        return $this->oAdditionalExpenseHistoryProvider->getAdditionalExpenseHistory($this->mParams);
    }

    // public function getAdditionalExpenseHistorys(Request $oRequest)
    // {
    //     return $this->oAdditionalExpenseHistoryProvider->getAdditionalExpenseHistorys($this->mParams);
    // }

    public function createAdditionalExpenseHistory(Request $oRequest)
    {
        return $this->oAdditionalExpenseHistoryProvider->createAdditionalExpenseHistory($this->mParams);
    }

    public function updateAdditionalExpenseHistory(Request $oRequest)
    {
        return $this->oAdditionalExpenseHistoryProvider->updateAdditionalExpenseHistory($this->mParams);
    }

    public function deleteAdditionalExpenseHistory(Request $oRequest)
    {
        return $this->oAdditionalExpenseHistoryProvider->deleteAdditionalExpenseHistory($this->mParams);
    }

    public function getAdditionalExpenseHistoryList(Request $oRequest)
    {
        return $this->oAdditionalExpenseHistoryProvider->getAdditionalExpenseHistoryList($this->mParams);
    }

}
