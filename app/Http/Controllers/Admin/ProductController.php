<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class ProductController extends BaseController
{
    public function __construct(Request $oRequest)
    {
        $this->setParams($oRequest->all());
        $this->instantiateProductProvider();
    }

    public function createProduct()
    {
        return $this->oProductProvider->createProduct($this->mParams);
    }

    public function updateProduct()
    {
        return $this->oProductProvider->updateProduct($this->mParams);
    }

    public function deleteProduct()
    {
        return $this->oProductProvider->deleteProduct($this->mParams);
    }

    public function getProductList()
    {
        return $this->oProductProvider->getProductList();
    }

    public function getProducts()
    {
        return $this->oProductProvider->getProducts();
    }

    public function getProductForSale()
    {
        return $this->oProductProvider->getProductForSale();
    }

    public function uploadProduct(Request $oRequest)
    {
        return $this->oProductProvider->uploadProduct($oRequest);
    }

    public function exportProduct(Request $oRequest)
    {
        return $this->oProductProvider->exportProduct($oRequest);
    }

    public function getProduct($sCode)
    {
        $this->setParams(['sCode' => $sCode]);
        return $this->oProductProvider->getProduct($this->mParams);
    }

}
