<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class AdditionalExpenseController extends BaseController
{
    
    public function __construct(Request $oRequest)
    {
        $this->setParams($oRequest->all());
        $this->instantiateAdditionalExpenseProvider();
    }

    public function getAdditionalExpense($sCode)
    {
        $this->setParams(['sCode' => $sCode]);
        return $this->oAdditionalExpenseProvider->getAdditionalExpense($this->mParams);
    }

    public function getAdditionalExpenses(Request $oRequest)
    {
        return $this->oAdditionalExpenseProvider->getAdditionalExpenses($this->mParams);
    }

    public function createAdditionalExpense(Request $oRequest)
    {
        return $this->oAdditionalExpenseProvider->createAdditionalExpense($this->mParams);
    }

    public function updateAdditionalExpense(Request $oRequest)
    {
        return $this->oAdditionalExpenseProvider->updateAdditionalExpense($this->mParams);
    }

    public function deleteAdditionalExpense(Request $oRequest)
    {
        return $this->oAdditionalExpenseProvider->deleteAdditionalExpense($this->mParams);
    }

    public function getAdditionalExpenseList(Request $oRequest)
    {
        return $this->oAdditionalExpenseProvider->getAdditionalExpenseList($this->mParams);
    }

}
