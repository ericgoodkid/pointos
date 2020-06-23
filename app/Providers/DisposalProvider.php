<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Constants\LibraryConstant;
use Constants\MessageConstant;

class DisposalProvider extends CommonProvider
{
    public function __construct()
    {
        $this->instantiateDisposal();
        $this->instantiateProduct();
    }
    
    public function disposeProduct($oItem)
    {
        $oItem = $oItem['oItem'];
        $oItem = $this->mapParams($oItem);
        $mResult = $this->validateItem($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        $oProduct = $this->oProduct->getProduct($oItem['code']);
        $oItem['code'] = $this->getInventoryHistoryCode();
        $oItem['product_id'] = $oProduct['id'];
        $mResult = $this->oDisposal->disposeProduct($oItem);
        return $this->setSuccessResponse(MessageConstant::SUCCESS_RESPONSE);
    }

    private function validateItem($oItem)
    {
        $aRules = $this->getRules($oItem);
        $mValidator = Validator::make($oItem, $aRules);
        if ($mValidator->fails()) {
            return $mValidator->getMessageBag()->toarray();
        }

        return true;
    }

    private function getRules($oItem)
    {
        $sProductCode = $oItem['code'];
        return array(
            'code' => 'exists:Products,code',
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
    
    private function getInventoryHistoryCode()
    {
        $iCount = $this->oDisposal->countDisposal();
        return $this->createCode(LibraryConstant::DISPOSAL_CODE, $iCount);
    }

}
