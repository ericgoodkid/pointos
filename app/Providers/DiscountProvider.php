<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Constants\LibraryConstant;
use Carbon\Carbon;

class DiscountProvider extends CommonProvider
{
    public function __construct()
    {
        $this->instantiateDiscount();
        $this->instantiateDiscountItem();
        $this->instantiateProduct();
    }

    public function getDiscount($mParams)
    {
        $sCode = $mParams['sCode'];
        $oDiscount = $this->oDiscount->getDiscount($sCode);
        if ($oDiscount === null) {
            $oParam = $this->setItemParam('aMessage', ['not exist' => $sCode . ' do not exist']);
            return $this->setErrorResponse($oParam); 
        }

        $oParam = $this->setItemParam('oItem', $oDiscount);
        return $this->setSuccessResponse($oParam);
    }
    
    public function getDiscounts()
    {
        $oParam = $this->setItemParam('aDiscount', $this->oDiscount->getDiscounts());
        return $this->setSuccessResponse($oParam);
    }

    public function getDiscountList()
    {
        $aResult = $this->oDiscount->getDiscountList();
        $aResult = $this->readableByDatatable($aResult, LibraryConstant::DISCOUNT_NEEDED_KEY);
        $aResult = $this->prependActionInDatatable(LibraryConstant::DEFAULT_ACTION_BUTTON, $aResult);
        return ['data' => $aResult];
    }

    public function createDiscount($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $aProduct = $oItem['product_code'];
        $mResult = $this->validateProduct($aProduct);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        $mResult = $this->validateDiscount($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        $mResult = $this->createDiscountHead($oItem);
        $iId = $mResult->id;
        $mResult = $this->createDiscountBody($aProduct, $iId);
        if ($mResult === false) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        return $mResult;
    }

    private function createDiscountHead($oItem)
    {
        $sCode = $this->getCode();
        $oItem['start_date'] = Carbon::parse($oItem['start_date']);
        $oItem['end_date'] = Carbon::parse($oItem['end_date']);
        $oItem = array_merge($oItem, ['code' => $sCode]);
        return $this->oDiscount->createDiscount($oItem);
    }

    private function createDiscountBody($aItem, $iId)
    {
        $aErrorMessage = [];
        foreach($aItem as $sProductCode) {
            $oProduct = $this->oProduct->getProduct($sProductCode);
            $oOrderItem['product_id'] = $oProduct->id;
            $oOrderItem['discount_id'] = $iId;
            $mResult = $this->oDiscountItem->createDiscountItem($oOrderItem);
            if ($mResult === false) {
                $aErrorMessage[] = $mResult;
            }
        }

        if (count($aErrorMessage) !== 0) {
            return $aErrorMessage;
        }

        return true;
    }

    public function updateDiscount($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $aProduct = $oItem['product_code'];
        $mResult = $this->validateProduct($aProduct);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        $mResult = $this->validateDiscount($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        $mResult = $this->oDiscount->getDiscount($oItem['code']);
        $iId = $mResult->id;
        $oItem['start_date'] = Carbon::parse($oItem['start_date']);
        $oItem['end_date'] = Carbon::parse($oItem['end_date']);
        $mUpdateDiscountResult = $this->oDiscount->updateDiscount($oItem);
        $mDeleteResult = $this->oDiscountItem->deleteRecentDiscount($iId);
        $mCreateDiscountBodyResult = $this->createDiscountBody($aProduct, $iId);
        if ($mCreateDiscountBodyResult === false) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        return $mResult;
    }

    public function deleteDiscount($mParams)
    {
        $oItem = $mParams['oItem'];
        $sCode = $oItem['code'];
        $this->oDiscount->deleteDiscount($sCode);
        return $this->setSuccessResponse(['sMessage' =>  'Transaction Success']);
    }

    private function validateProduct($aItem)
    {
        $aErrorMessage = [];
        foreach ($aItem as $sCode) {
            $aRules = $this->getProductRules();

            $mValidator = Validator::make([
                'product_code' => $sCode
            ], $aRules);
            if ($mValidator->fails()) {
                $aErrorMessage[] = $mValidator->getMessageBag()->toarray();
            }
        }

        if (count($aErrorMessage) !== 0) {
            return $aErrorMessage;
        }

        return true;
    }

    private function getProductRules()
    {
        return array(
            'product_code' => 'required|exists:Products,code'
        );
    }

    private function validateDiscount($oItem)
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
            'name' => 'required|string|min:2|max:255',
            'amount' => 'numeric|required|min:1|max:99.9',
            'minimum' => 'numeric|required|max:9999999999|min:1',
            'start_date' => 'date|required',
            'end_date' => 'date|required'
        );
    }

    private function getCode()
    {
        $iCount = $this->oDiscount->countDiscount();
        return $this->createCode(LibraryConstant::DISCOUNT_CODE, $iCount);
    }
}
