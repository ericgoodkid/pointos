<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Constants\LibraryConstant;

class BusinessProvider extends CommonProvider
{
    public function __construct()
    {
        $this->instantiateBusiness();
    }

    public function getBusiness()
    {
        $oBusiness = $this->oBusiness->getBusiness();
        $oParam = $this->setItemParam('oItem', $oBusiness);
        return $this->setSuccessResponse($oParam);
    }
    
    public function createBusiness($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $mResult = $this->validateBusinessInformation($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        $oItem = $this->sanitizeInputs($oItem);
        $oBusiness = $this->oBusiness->getBusiness();
        if ($oBusiness === null) {
            return $this->oBusiness->createBusiness($oItem);
        }

        return $this->oBusiness->updateBusiness($oItem);
    }

    protected function sanitizeInputs($oItem)
    {
        $oItem['name'] = $this->upperCase($oItem['name']);
        $oItem['address'] = $this->upperCase($oItem['address']);
        return $oItem;
    }

    private function validateBusinessInformation($oItem)
    {
        $aRules = $this->getBusinessInformationRules();
        $mValidator = Validator::make($oItem, $aRules);
        if ($mValidator->fails()) {
            return $mValidator->getMessageBag()->toarray();
        }

        return true;
    }

    private function getBusinessInformationRules()
    {
        return array(
            'name' => 'required|string|min:2|max:255',
            'address' => 'required|string|min:2|max:255'
        );
    }
}
