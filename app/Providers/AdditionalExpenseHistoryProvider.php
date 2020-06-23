<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Constants\LibraryConstant;

class AdditionalExpenseHistoryProvider extends CommonProvider
{
    public function __construct()
    {
        $this->instantiateAdditionalExpenseHistory();
        $this->instantiateAdditionalExpense();
    }

    public function getAdditionalExpenseHistory($mParams)
    {
        $sCode = $mParams['sCode'];
        $oAdditionalExpenseHistory = $this->oAdditionalExpenseHistory->getAdditionalExpenseHistory($sCode);
        if ($oAdditionalExpenseHistory === null) {
            $oParam = $this->setItemParam('aMessage', ['not exist' => $sCode . ' do not exist']);
            return $this->setErrorResponse($oParam); 
        }

        $oParam = $this->setItemParam('oItem', $oAdditionalExpenseHistory);
        return $this->setSuccessResponse($oParam);
    }
    
    public function getAdditionalExpenseHistorys()
    {
        $oParam = $this->setItemParam('aAdditionalExpenseHistory', $this->oAdditionalExpenseHistory->getAdditionalExpenseHistorys());
        return $this->setSuccessResponse($oParam);
    }

    public function getAdditionalExpenseHistoryList()
    {
        $aResult = $this->oAdditionalExpenseHistory->getAdditionalExpenseHistoryList();
        $aResult = $this->replaceKey($aResult);
        $aResult = $this->readableByDatatable($aResult, LibraryConstant::ADDITIONAL_EXPENSE_HISTORY_NEEDED_KEY);
        $aResult = $this->prependActionInDatatable(LibraryConstant::DEFAULT_ACTION_BUTTON, $aResult);
        return ['data' => $aResult];
    }

    private function replaceKey($aItem)
    {
        $aTempItem = [];
        foreach ($aItem as $oItem) {
            $sType = $oItem->getAdditionalExpenseType['type'];
            $oItem['type'] = $sType;
            $oItem['date'] = $oItem->created_at->format(self::DATE_FORMAT);
            if ($oItem->created_at === $oItem->updated_at) {
                $oItem['date'] = $oItem->updated_at->format(self::DATE_FORMAT);
            }

            $aTempItem[] = $oItem;
        }
       
        return $aTempItem;
    }
    

    public function createAdditionalExpenseHistory($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $mResult = $this->validateExpenseHistory($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        $oType = $this->oAdditionalExpense->getAdditionalExpense($oItem['type_code']);
        $sCode = $this->getCode();
        $oExpenseHistory['code'] = $sCode;
        $oExpenseHistory['additional_expense_id'] = $oType->id;
        $oExpenseHistory = array_merge($oItem, $oExpenseHistory);
        return $this->oAdditionalExpenseHistory->createAdditionalExpenseHistory($oExpenseHistory);
    }

    public function updateAdditionalExpenseHistory($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $mResult = $this->validateExpenseHistory($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }
        
        $oType = $this->oAdditionalExpense->getAdditionalExpense($oItem['type_code']);
        $oExpenseHistory['additional_expense_id'] = $oType->id;
        $oExpenseHistory = array_merge($oItem, $oExpenseHistory);
        return $this->oAdditionalExpenseHistory->updateAdditionalExpenseHistory($oExpenseHistory, $oItem['code']);
    }

    public function deleteAdditionalExpenseHistory($mParams)
    {
        $oItem = $mParams['oItem'];
        $sCode = $oItem['code'];
        $this->oAdditionalExpenseHistory->deleteAdditionalExpenseHistory($sCode);
        return $this->setSuccessResponse(['sMessage' =>  'Transaction Success']);
    }

    private function validateExpenseHistory($oItem)
    {
        $aRules = $this->getRules();
        $mValidator = Validator::make($oItem, $aRules);
        if ($mValidator->fails()) {
            return $mValidator->getMessageBag()->toarray();
        }

        return true;
    }

    private function getRules()
    {
        return array(
            'type_code' => 'required|exists:Additional_expense,code',
            'amount' => 'required|numeric|min:1|max:9999999',
            'remarks' => 'nullable|string|min:0|max:255',
        );
    }

    private function getCode()
    {
        $iCount = $this->oAdditionalExpenseHistory->countAdditionalExpenseHistory();
        return $this->createCode(LibraryConstant::ADDITIONAL_EXPENSE_HISTORY_CODE, $iCount);
    }
}
