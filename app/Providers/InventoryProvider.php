<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Constants\LibraryConstant;
use Constants\MessageConstant;

class InventoryProvider extends CommonProvider
{
    public function __construct()
    {
        $this->instantiateInventory();
        $this->instantiateInventoryHistory();
        $this->instantiateSupplier();
        $this->instantiateProduct();
    }
    
    public function saveInventory($mParams)
    {
        $oInventory = $mParams['oItem'];
        $oInventory = $this->mapParams($oInventory);
        $bStatus = $this->getSupplierStatus($oInventory);
        $oInventory['supplier_id'] = null;
        if ($bStatus === false) {
            $oSupplier = $this->oSupplier->getSupplier($oInventory['supplier_code']);
            $oInventory['supplier_id'] = $oSupplier['id'];
        }

        $mResult = $this->createInventoryHistory($oInventory);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        return $this->setSuccessResponse(MessageConstant::SUCCESS_RESPONSE);
    }

    private function createInventoryHistory($oInventory)
    {
        $sCode = $oInventory['product_code'];
        $mResult = $this->validateInventoryHistory($oInventory);
        if ($mResult !== true) {
            return $mResult;
        }

        $oInventory['code'] = $this->getInventoryHistoryCode();
        $oProduct = $this->oProduct->getProduct($sCode);
        $oInventory['product_id'] = $oProduct['id'];
        $this->oInventoryHistory->createInventoryHistory($oInventory);
        return true;
    }

    private function getSupplierStatus($oInventory)
    {
        return ($oInventory['supplier_code'] === null || $oInventory['supplier_code'] === 'null');
    }

    private function insertInventory($oInventory)
    {
        $oInventory = $this->mapParams($oInventory);
        $sCode = $oInventory['product_code'];
        $mResult = $this->validateInventory($oInventory);
        if ($mResult !== true) {
            return $mResult;
        }

        $oProduct = $this->oProduct->getProduct($sCode);
        $oInventory['product_id'] = $oProduct['id'];
        $mInventory = $this->getInventory($oInventory['product_id']);
        if ($mInventory === null) {
            $this->oInventory->createInventory($oInventory);
            return;
        }

        $oInventory['quantity'] = $mInventory['quantity'] + $oInventory['quantity'];
        $this->oInventory->updateInventory($oInventory, $oInventory['product_id']);
        return;
    }

    private function validateInventory($oInventory)
    {
        $aRules = $this->getRules();
        $mValidator = Validator::make($oInventory, $aRules);
        if ($mValidator->fails()) {
            return $mValidator->getMessageBag()->toarray();
        }

        return true;
    }

    private function validateInventoryHistory($oInventory)
    {
        $aRules = $this->getInventoryHistoryRules();
        $mValidator = Validator::make($oInventory, $aRules);
        if ($mValidator->fails()) {
            return $mValidator->getMessageBag()->toarray();
        }

        return true;
    }

    public function getInventory($sCode)
    {
        return $this->oInventory->getInventory($sCode);
    }

    private function getRules()
    {
        return array(
            'product_code' => 'exists:Products,code',
            'quantity' => 'required|integer|max:99999|min:1'
        );
    }

    private function getInventoryHistoryRules()
    {
        return array(
            'product_code' => 'exists:Products,code',
            'supplier_code' => 'nullable|exists:Supplier,code',
            'remarks' => 'nullable|string|min:2',
            'quantity' => 'required|integer|max:99999|min:1',
            'price' =>'required|numeric|max:9999999999',
        );
    }

    private function getInventoryHistoryCode()
    {
        $iCount = $this->oInventoryHistory->countInventoryHistory();
        return $this->createCode(LibraryConstant::INVENTORY_CODE, $iCount);
    }

}
