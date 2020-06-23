<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class DiscountController extends BaseController
{
    
    public function __construct(Request $oRequest)
    {
        $this->setParams($oRequest->all());
        $this->instantiateDiscountProvider();
    }


    public function getDiscount($sCode)
    {
        $this->setParams(['sCode' => $sCode]);
        return $this->oDiscountProvider->getDiscount($this->mParams);
    }

    public function getDiscounts(Request $oRequest)
    {
        return $this->oDiscountProvider->getDiscounts($this->mParams);
    }

    public function createDiscount(Request $oRequest)
    {
        return $this->oDiscountProvider->createDiscount($this->mParams);
    }

    public function updateDiscount(Request $oRequest)
    {
        return $this->oDiscountProvider->updateDiscount($this->mParams);
    }

    public function deleteDiscount(Request $oRequest)
    {
        return $this->oDiscountProvider->deleteDiscount($this->mParams);
    }

    public function getDiscountList(Request $oRequest)
    {
        return $this->oDiscountProvider->getDiscountList($this->mParams);
    }

}
