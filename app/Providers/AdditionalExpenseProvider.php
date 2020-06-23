<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Constants\LibraryConstant;

class AdditionalExpenseProvider extends CommonProvider
{
    public function __construct()
    {
        $this->instantiateAdditionalExpense();
    }

    public function getAdditionalExpense($mParams)
    {
        $sCode = $mParams['sCode'];
        $oAdditionalExpense = $this->oAdditionalExpense->getAdditionalExpense($sCode);
        if ($oAdditionalExpense === null) {
            $oParam = $this->setItemParam('aMessage', ['not exist' => $sCode . ' do not exist']);
            return $this->setErrorResponse($oParam); 
        }

        $oParam = $this->setItemParam('oItem', $oAdditionalExpense);
        return $this->setSuccessResponse($oParam);
    }
    
    public function getAdditionalExpenses()
    {
        $oParam = $this->setItemParam('aAdditionalExpense', $this->oAdditionalExpense->getAdditionalExpenses());
        return $this->setSuccessResponse($oParam);
    }

    public function getAdditionalExpenseList()
    {
        $aResult = $this->oAdditionalExpense->getAdditionalExpenseList();
        $aResult = $this->readableByDatatable($aResult, LibraryConstant::ADDITIONAL_EXPENSE_NEEDED_KEY);
        $aResult = $this->prependActionInDatatable(LibraryConstant::DEFAULT_ACTION_BUTTON, $aResult);
        return ['data' => $aResult];
    }

    public function createAdditionalExpense($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $oItem['type'] = $this->upperCase($oItem['type']);
        $mResult = $this->validateAdditionalExpense($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        $sCode = $this->getCode();
        $oAdditionalExpense = array_merge($oItem, ['code' => $sCode]);
        return $this->oAdditionalExpense->createAdditionalExpense($oAdditionalExpense);
    }

    public function updateAdditionalExpense($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $oItem['type'] = $this->upperCase($oItem['type']);
        $mResult = $this->validateAdditionalExpense($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }
        
        $this->oAdditionalExpense->updateAdditionalExpense($oItem, $oItem['code']);
    }

    public function deleteAdditionalExpense($mParams)
    {
        $oItem = $mParams['oItem'];
        $sCode = $oItem['code'];
        $this->oAdditionalExpense->deleteAdditionalExpense($sCode);
        return $this->setSuccessResponse(['sMessage' =>  'Transaction Success']);
    }

    private function validateAdditionalExpense($oItem)
    {
        $aRules = $this->getAdditionalExpenseRules();
        $mValidator = Validator::make($oItem, $aRules);
        if ($mValidator->fails()) {
            return $mValidator->getMessageBag()->toarray();
        }

        return true;
    }

    private function getAdditionalExpenseRules()
    {
        return array(
            'type' => 'required|string|min:2|max:255'
        );
    }

    private function getCode()
    {
        $iCount = $this->oAdditionalExpense->countAdditionalExpense();
        return $this->createCode(LibraryConstant::ADDITIONAL_EXPENSE_CODE, $iCount);
    }

}
