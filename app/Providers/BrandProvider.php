<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Constants\LibraryConstant;

class BrandProvider extends CommonProvider
{
    public function __construct()
    {
        $this->instantiateBrand();
    }

    public function getBrand($mParams)
    {
        $sCode = $mParams['sCode'];
        $oBrand = $this->oBrand->getBrand($sCode);
        if ($oBrand === null) {
            $oParam = $this->setItemParam('aMessage', ['not exist' => $sCode . ' do not exist']);
            return $this->setErrorResponse($oParam); 
        }

        $oParam = $this->setItemParam('oItem', $oBrand);
        return $this->setSuccessResponse($oParam);
    }
    
    public function getBrands()
    {
        $oParam = $this->setItemParam('aBrand', $this->oBrand->getBrands());
        return $this->setSuccessResponse($oParam);
    }

    public function getBrandList()
    {
        $aResult = $this->oBrand->getBrandList();
        $aResult = $this->readableByDatatable($aResult, LibraryConstant::BRAND_NEEDED_KEY);
        $aResult = $this->prependActionInDatatable(LibraryConstant::DEFAULT_ACTION_BUTTON, $aResult);
        return ['data' => $aResult];
    }

    public function createBrand($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $oItem['name'] = $this->upperCase($oItem['name']);
        $mResult = $this->validateProduct($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        $sCode = $this->getCode();
        $oProduct = array_merge($oItem, ['code' => $sCode]);
        return $this->oBrand->createBrand($oProduct);
    }

    public function updateBrand($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $oItem['name'] = $this->upperCase($oItem['name']);
        $mResult = $this->validateProduct($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }
        
        $this->oBrand->updateBrand($oItem, $oItem['code']);
    }

    public function deleteBrand($mParams)
    {
        $oItem = $mParams['oItem'];
        $sCode = $oItem['code'];
        $this->oBrand->deleteBrand($sCode);
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
            'name' => 'required|string|min:2|max:255'
        );
    }

    private function getCode()
    {
        $iCount = $this->oBrand->countBrand();
        return $this->createCode(LibraryConstant::BRAND_CODE, $iCount);
    }
}
