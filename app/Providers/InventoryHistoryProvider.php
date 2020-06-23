<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Constants\LibraryConstant;
use Constants\MessageConstant;
use DateTime;
use Carbon\Carbon;

class InventoryHistoryProvider extends CommonProvider
{
    public function __construct()
    {
        $this->instantiateInventoryHistory();
        $this->instantiateSupplier();
        $this->instantiateProduct();
    }

    public function getInventoryHistoryList($oParams)
    {
        if (count($oParams) === 0) {
            return $this->getInventoryHistoryListNoParams();
        }

        return $this->getInventoryHistoryListWithParams($oParams);
    }

    public function getInventoryHistory($oParams)
    {
        $sCode = $oParams['sCode'];
        $oInventoryHistory = $this->oInventoryHistory->getInventoryHistory($sCode);
        if ($oInventoryHistory === null) {
            $oParam = $this->setItemParam('aMessage', ['not exist' => $sCode . ' do not exist']);
            return $this->setErrorResponse($oParam); 
        }

        $oParam = $this->setItemParam('oItem', $oInventoryHistory);
        return $this->setSuccessResponse($oParam);
    }

    private function getInventoryHistoryListNoParams()
    {
        $aInventoryHistory = $this->oInventoryHistory->getInventoryHistoryList();
        foreach ($aInventoryHistory as $iIndex => $oInventory) {
            $sDetails = $this->getDetails($oInventory);
            $aInventoryHistory[$iIndex]['details'] = $sDetails;
        }

        $aResult = $this->replaceKey($aInventoryHistory);
        $aResult = $this->getRemarksChip($aInventoryHistory);
        $aResult = $this->readableByDatatable($aResult, LibraryConstant::INVENTORY_HISTORY_NEEDED_KEY);
        $aResult = $this->prependActionInDatatable(LibraryConstant::DEFAULT_ACTION_BUTTON, $aResult);
        return ['data' => $aResult];
    }

    private function replaceKey($aInventoryHistory)
    {

        $aTempItem = [];
        foreach ($aInventoryHistory as $oItem) {
            $sProductName = $oItem->getProduct['name'];
            $sSupplierName = $oItem->getSupplier['name'];
            $oItem['supplier'] = $sSupplierName;
            $oItem['product'] = $sProductName;
            $oItem['date'] = $oItem->created_at->format(self::DATE_FORMAT);
            $iQuantity =  $oItem['quantity'];
            $iPrice =  $oItem['price'];
            $oItem['details'] = $iPrice . ' - ' . ($iQuantity > 1 ?  $iQuantity . ' pcs' :  $$iQuantity . ' - pc');
            $aTempItem[] = $oItem;
        }
       
        return $aTempItem;
    }

    private function getInventoryHistoryListWithParams($oParams)
    {
        $oParams = $this->removeParams($oParams);
        $oParams = $this->mapParams($oParams);
        $oParams = $this->applyFilter($oParams);
        $aInventoryHistory = $this->oInventoryHistory->getInventoryHistoryListWithParams($oParams);
        foreach ($aInventoryHistory as $iIndex => $oInventory) {
            $sDetails = $this->getDetails($oInventory);
            $aInventoryHistory[$iIndex]['details'] = $sDetails;
        }
        
        $aResult = $this->replaceKey($aInventoryHistory);
        $aResult = $this->getRemarksChip($aInventoryHistory);
        $aResult = $this->readableByDatatable($aResult, LibraryConstant::INVENTORY_HISTORY_NEEDED_KEY);
        $aResult = $this->prependActionInDatatable(LibraryConstant::DEFAULT_ACTION_BUTTON, $aResult);
        return ['data' => $aResult];
    }

    private function applyFilter($oParams) 
    {
        if (array_key_exists('supplier_code', $oParams) === true) {
            $oSupplier = $this->oSupplier->getSupplier($oParams['supplier_code']);
            $oParams['supplier_id'] = $oSupplier['id'];
        }
        
        if (array_key_exists('product_code', $oParams) === true) {
            $oSupplier = $this->oProduct->getProduct($oParams['product_code']);
            $oParams['product_id'] = $oSupplier['id'];
        }

        if (array_key_exists('start_date', $oParams) === true) {
            if (strtotime($oParams['start_date']) === false) {
                unset($oParams['start_date']);
            } else {
                $oParams['start_date'] = Carbon::parse($oParams['start_date']);
            }

        }

        if (array_key_exists('end_date', $oParams) === true) {
            if (strtotime($oParams['end_date']) === false) {
                unset($oParams['end_date']);
            } else {
                $oParams['end_date'] = Carbon::parse($oParams['end_date']);
            }
        }
        
        return $oParams;
    }

    public function deleteInventoryHistory($mParams)
    {
        $oProduct = $mParams['oItem'];
        $sCode = $oProduct['code'];
        $this->oInventoryHistory->deleteInventoryHistory($sCode);
        return $this->setSuccessResponse(MessageConstant::SUCCESS_RESPONSE);
    }

    public function updateInventoryHistory($mParams)
    {
        $oInventoryHistory = $mParams['oItem'];
        $oInventoryHistory = $this->mapParams($oInventoryHistory);
        $mResult = $this->validateInventoryHistory($oInventoryHistory);
        $oInventoryHistory['supplier_id'] = $this->getSupplier($oInventoryHistory['supplier_code']);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }
        
        $this->oInventoryHistory->updateInventoryHistory($oInventoryHistory, $oInventoryHistory['code']);
        return $this->setSuccessResponse(MessageConstant::SUCCESS_RESPONSE);
    }

    private function getDetails($oInventory)
    {
        $sSuffix = 'pc';
        if ($oInventory['quantity'] > 1) {
            $sSuffix = 'pcs';
        }

        return $oInventory['price'] . ' - ' . $oInventory['quantity'] . ' ' . $sSuffix;
    }

    private function getSupplier($sSupplierCode)
    {
        if ($sSupplierCode === null || $sSupplierCode === 'null') {
            return null;
        }

        $oSupplier = $this->oSupplier->getSupplier($sSupplierCode);
        return $oSupplier['id'];
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

    private function validateInventoryHistory($oInventory)
    {
        $aRules = $this->getInventoryHistoryRules();
        $mValidator = Validator::make($oInventory, $aRules);
        if ($mValidator->fails()) {
            return $mValidator->getMessageBag()->toarray();
        }

        return true;
    }

    private function getRemarksChip($aItem)
    {
        $aTempItem = [];
        foreach ($aItem as $oItem) {
            $mRemarks = $oItem['remarks'];
            if ($mRemarks === null) {
                $oItem['remarks_chip'] = '<button type="button" class="btn bg-gradient-success btn-md"> No </button>';
                $aTempItem[] = $oItem;
                continue;
            }

            $oItem['remarks_chip'] = '<button type="button" class="btn bg-gradient-danger btn-md btnRemarks" data-toggle="modal" data-target="#modal-remarks" data-code="' . $oItem->getProduct['code'] . '" data-remarks="' . $oItem['remarks'] . '"> Yes </button>';
            $aTempItem[] = $oItem;
        }
       
        return $aTempItem;
    }
}
