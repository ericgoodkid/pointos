<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class OrderController extends BaseController
{
    
    public function __construct(Request $oRequest)
    {
        $this->setParams($oRequest->all());
        $this->instantiateOrderProvider();
    }

    public function getOrder($sCode)
    {
        $this->setParams(['sCode' => $sCode]);
        return $this->oOrderProvider->getOrder($this->mParams);
    }

    // public function getBrands(Request $oRequest)
    // {
    //     return $this->oBrandProvider->getBrands($this->mParams);
    // }

    public function createOrder(Request $oRequest)
    {
        return $this->oOrderProvider->createOrder($this->mParams);
    }

    public function updateOrder(Request $oRequest, $sCode)
    {
        $this->setParams(['sCode' => $sCode]);
        return $this->oOrderProvider->updateOrder($this->mParams);
    }

    public function deleteOrder(Request $oRequest, $sCode)
    {
        $this->setParams(['sCode' => $sCode]);
        return $this->oOrderProvider->deleteOrder($this->mParams);
    }

    public function getOrderList(Request $oRequest)
    {
        return $this->oOrderProvider->getOrderList($this->mParams);
    }

}
