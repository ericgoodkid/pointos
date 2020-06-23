<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Constants\LibraryConstant;

class SupplierProvider extends CommonProvider
{
    public function __construct()
    {
        $this->instantiateSupplier();
    }
    
    public function getSupplier($mParams)
    {
        $sCode = $mParams['sCode'];
        $oSupplier = $this->oSupplier->getSupplier($sCode);
        if ($oSupplier === null) {
            $oParam = $this->setItemParam('aMessage', ['not exist' => $sCode . ' do not exist']);
            return $this->setErrorResponse($oParam); 
        }

        $oParam = $this->setItemParam('oItem', $oSupplier);
        return $this->setSuccessResponse($oParam);
    }

    public function getSuppliers()
    {
        $oParam = $this->setItemParam('aSupplier', $this->oSupplier->getSuppliers());
        return $this->setSuccessResponse($oParam);
    }

    public function getSupplierList()
    {
        $aResult = $this->oSupplier->getSupplierList();
        $aResult = $this->readableByDatatable($aResult, LibraryConstant::SUPPLIER_NEEDED_KEY);
        $aResult = $this->prependActionInDatatable(LibraryConstant::DEFAULT_ACTION_BUTTON, $aResult);
        return ['data' => $aResult];
    }

    public function createSupplier($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $mResult = $this->validateProduct($oItem);
        $oItem['name'] = $this->upperCase($oItem['name']);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        $sCode = $this->getCode();
        $oProduct = array_merge($oItem, ['code' => $sCode]);
        return $this->oSupplier->createSupplier($oProduct);
    }

    public function updateSupplier($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $mResult = $this->validateProduct($oItem);
        $oItem['name'] = $this->upperCase($oItem['name']);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }
        
        $this->oSupplier->updateSupplier($oItem, $oItem['code']);
        return $this->setSuccessResponse(['sMessage' =>  'Transaction Success']);
    }

    public function deleteSupplier($mParams)
    {
        $oItem = $mParams['oItem'];
        $sCode = $oItem['code'];
        $this->oSupplier->deleteSupplier($sCode);
        return $this->setSuccessResponse(['sMessage' =>  'Transaction Success']);
    }

    private function validateProduct($oItem)
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
            'name' => 'required|string|min:5|max:255',
            'contact_person' => 'nullable|string|min:5|max:255',
            'contact_number' => 'nullable|string|min:11|max:255',
            'address' => 'nullable|string|min:5|max:255'
        );
    }

    private function getCode()
    {
        $iCount = $this->oSupplier->countSupplier();
        return $this->createCode(LibraryConstant::SUPPLIER_CODE, $iCount);
    }
}
