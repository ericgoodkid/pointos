<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class BrandController extends BaseController
{
    
    public function __construct(Request $oRequest)
    {
        $this->setParams($oRequest->all());
        $this->instantiateBrandProvider();
    }


    public function getBrand($sCode)
    {
        $this->setParams(['sCode' => $sCode]);
        return $this->oBrandProvider->getBrand($this->mParams);
    }

    public function getBrands(Request $oRequest)
    {
        return $this->oBrandProvider->getBrands($this->mParams);
    }

    public function createBrand(Request $oRequest)
    {
        return $this->oBrandProvider->createBrand($this->mParams);
    }

    public function updateBrand(Request $oRequest)
    {
        return $this->oBrandProvider->updateBrand($this->mParams);
    }

    public function deleteBrand(Request $oRequest)
    {
        return $this->oBrandProvider->deleteBrand($this->mParams);
    }

    public function getBrandList(Request $oRequest)
    {
        return $this->oBrandProvider->getBrandList($this->mParams);
    }

}
