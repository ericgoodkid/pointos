<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class SupplierController extends BaseController
{
    
    public function __construct(Request $oRequest)
    {
        $this->setParams($oRequest->all());
        $this->instantiateSupplierProvider();
    }

    public function createSupplier(Request $oRequest)
    {
        return $this->oSupplierProvider->createSupplier($this->mParams);
    }

    public function updateSupplier(Request $oRequest)
    {
        return $this->oSupplierProvider->updateSupplier($this->mParams);
    }

    public function deleteSupplier(Request $oRequest)
    {
        return $this->oSupplierProvider->deleteSupplier($this->mParams);
    }

    public function getSupplierList(Request $oRequest)
    {
        return $this->oSupplierProvider->getSupplierList($this->mParams);
    }

    public function getSuppliers(Request $oRequest)
    {
        return $this->oSupplierProvider->getSuppliers($this->mParams);
    }

    public function getSupplier($sCode)
    {
        $this->setParams(['sCode' => $sCode]);
        return $this->oSupplierProvider->getSupplier($this->mParams);
    }

}
