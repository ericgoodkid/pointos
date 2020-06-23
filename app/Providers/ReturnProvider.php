<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Constants\LibraryConstant;

class ReturnProvider extends CommonProvider
{
    public function __construct()
    {
        $this->instantiateReturn();
        $this->instantiateSupplier();
        $this->instantiateProduct();
    }

    public function getReturn($mParams)
    {
        $sCode = $mParams['sCode'];
        $oReturn = $this->oReturn->getReturn($sCode);
        if ($oReturn === null) {
            $oParam = $this->setItemParam('aMessage', ['not exist' => $sCode . ' do not exist']);
            return $this->setErrorResponse($oParam); 
        }

        $oParam = $this->setItemParam('oItem', $oReturn);
        return $this->setSuccessResponse($oParam);
    }

    public function getReturnList()
    {
        $aResult = $this->oReturn->getReturnList();
        $aResult = $this->replaceKey($aResult);
        $aResult = $this->getRemarksChip($aResult);
        $aResult = $this->getStatusChip($aResult);
        $aResult = $this->readableByDatatable($aResult, LibraryConstant::RETURN_NEEDED_KEY);
        $aResult = $this->prependApprovalActionInDatatable(LibraryConstant::RETURN_USED_ACTION_BUTTON, LibraryConstant::RETURN_UNUSED_ACTION_BUTTON, $aResult, 5);

        return ['data' => $aResult];
    }

    private function replaceKey($aItem)
    {

        $aTempItem = [];
        foreach ($aItem as $oItem) {
            $sProductName = $oItem->getProduct['name'];
            $sSupplierName = $oItem->getSupplier['name'];
            $oItem['supplier'] = $sSupplierName;
            $oItem['product'] = $sProductName;
            $oItem['date'] = $oItem->created_at->format('M d Y, h:m A');
            $iQuantity =  $oItem['quantity'];
            $oItem['details'] = ($iQuantity > 1 ?  $iQuantity . ' pcs' :  $$iQuantity . ' - pc');
            $aTempItem[] = $oItem;
        }
       
        return $aTempItem;
    }

    public function createReturn($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $mResult = $this->validateProduct($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        $sCode = $this->getCode();
        $oItem['supplier_id'] = $this->getSupplier($oItem['supplier_code']);
        $oProduct = $this->oProduct->getProduct($oItem['product_code']);
        $oItem['product_id'] = $oProduct->id;
        $oItem = array_merge($oItem, ['code' => $sCode]);
        return $this->oReturn->createReturn($oItem);
    }

    public function updateReturn($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $mResult = $this->validateProduct($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }
        
        $oItem['supplier_id'] = $this->getSupplier($oItem['supplier_code']);
        $oProduct = $this->oProduct->getProduct($oItem['product_code']);
        $oItem['product_id'] = $oProduct->id;
        $this->oReturn->updateReturn($oItem);
        return $this->setSuccessResponse(['sMessage' =>  'Transaction Success']);
    }

    public function approvalReturn($mParams)
    {
        $oItem = $this->mapParams($mParams);
        $mResult = $this->validateApproval($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }
        
        $this->oReturn->approvalReturn($oItem);
        return $this->setSuccessResponse(['sMessage' =>  'Transaction Success']);
    }
    

    private function getSupplier($sSupplierCode)
    {
        if ($sSupplierCode === null || $sSupplierCode === 'null') {
            return null;
        }

        $oSupplier = $this->oSupplier->getSupplier($sSupplierCode);
        return $oSupplier['id'];
    }

    public function deleteReturn($mParams)
    {
        $oItem = $mParams['oItem'];
        $sCode = $oItem['code'];
        $this->oReturn->deleteReturn($sCode);
        return $this->setSuccessResponse(['sMessage' =>  'Transaction Success']);
    }

    private function validateProduct($oItem)
    {
        $aRules = $this->getRules($oItem);
        $mValidator = Validator::make($oItem, $aRules);
        if ($mValidator->fails()) {
            return $mValidator->getMessageBag()->toarray();
        }

        return true;
    }

    private function validateApproval($oItem)
    {
        $aRules = $this->getApprovalRules($oItem);
        $mValidator = Validator::make($oItem, $aRules);
        if ($mValidator->fails()) {
            return $mValidator->getMessageBag()->toarray();
        }

        return true;
    }

    private function getRules($oItem)
    {
        $sProductCode = $oItem['product_code'];
        return array(
            'supplier_code' => 'nullable|exists:Supplier,code',
            'product_code' => 'exists:Products,code',
            'remarks' => 'nullable|string|min:2',
            'quantity' => [
                'required',
                'integer',
                'min:1',
                function ($sAttribute, $iValue, $mFail) use ($sProductCode) {
                    $aProduct = $this->oProduct->getProduct([
                        'sCode' => $sProductCode
                    ]);
                    if ($aProduct['total_quantity'] < $iValue) {
                        $mFail('Quantity is greater than current inventory');
                    }

                }
            ]
        );
    }

    private function getApprovalRules($oItem)
    {
        $sReturnCode = $oItem['code'];
        return array(
            'code' => 'exists:Return_Product,code',
            'status' => [
                'required',
                'in:approve,reject',
                function ($sAttribute, $iValue, $mFail) use ($sReturnCode) {
                    if ($iValue === 'reject') {
                        return;
                    }

                    $oReturn = $this->oReturn->getReturn([
                        'sCode' => $sReturnCode
                    ]);

                    $sProductCode = $oReturn->getProduct['code'];

                    $oProduct = $this->oProduct->getProduct([
                        'sCode' => $sProductCode
                    ]);

                    if ($oProduct['total_quantity'] < $iValue) {
                        $mFail('Quantity is greater than current inventory');
                    }

                }

            ]
        );
    }

    private function getCode()
    {
        $iCount = $this->oReturn->countReturn();
        return $this->createCode(LibraryConstant::RETURN_CODE, $iCount);
    }

    private function getStatusChip($aItem)
    {
        $aTempItem = [];
        foreach ($aItem as $oItem) {
            $mRemarks = $oItem['status'];
            $sClass = 'success';
            if ($mRemarks === 'pending') {
                $sClass = 'warning';
            } else if ($mRemarks === 'reject') {
                $sClass = 'danger';
            }

            $mRemarks = $this->upperCase($mRemarks);
            $oItem['status_chip'] = '<button type="button" class="btn bg-gradient-' . $sClass . ' btn-md"> ' . $mRemarks . ' </button>';
            $aTempItem[] = $oItem;
        }
       
        return $aTempItem;
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
