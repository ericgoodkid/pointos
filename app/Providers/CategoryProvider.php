<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Constants\LibraryConstant;
use Constants\MessageConstant;

class CategoryProvider extends CommonProvider
{
    public function __construct()
    {
        $this->instantiateCategory();
    }
    
    public function getCategory($mParams)
    {
        $sCode = $mParams['sCode'];
        $oCategory = $this->oCategory->getCategory($sCode);
        if ($oCategory === null) {
            $oParam = $this->setItemParam('aMessage', ['not exist' => $sCode . ' do not exist']);
            return $this->setErrorResponse($oParam); 
        }

        $oParam = $this->setItemParam('oItem', $oCategory);
        return $this->setSuccessResponse($oParam);
    }

    public function getCategories()
    {
        $oParam = $this->setItemParam('aCategory', $this->oCategory->getCategories());
        return $this->setSuccessResponse($oParam);
    }

    public function getCategoryList()
    {
        $aResult = $this->oCategory->getCategoryList();
        $aResult = $this->readableByDatatable($aResult, LibraryConstant::CATEGORY_NEEDED_KEY);
        $aResult = $this->prependActionInDatatable(LibraryConstant::DEFAULT_ACTION_BUTTON, $aResult);
        return ['data' => $aResult];
    }

    public function createCategory($mParams)
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
        return $this->oCategory->createCategory($oProduct);
    }

    public function updateCategory($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $mResult = $this->validateProduct($oItem);
        $oItem['name'] = $this->upperCase($oItem['name']);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }
        
        $this->oCategory->updateCategory($oItem, $oItem['code']);
        return $this->setSuccessResponse(MessageConstant::SUCCESS_RESPONSE);
    }

    public function deleteCategory($mParams)
    {
        $oItem = $mParams['oItem'];
        $sCode = $oItem['code'];
        $this->oCategory->deleteCategory($sCode);
        return $this->setSuccessResponse(MessageConstant::SUCCESS_RESPONSE);
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
        $iCount = $this->oCategory->countCategory();
        return $this->createCode(LibraryConstant::CATEGORY_CODE, $iCount);
    }
}
