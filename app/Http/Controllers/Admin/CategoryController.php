<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class CategoryController extends BaseController
{
    
    public function __construct(Request $oRequest)
    {
        $this->setParams($oRequest->all());
        $this->instantiateCategoryProvider();
    }

    public function getCategory($sCode)
    {
        $this->setParams(['sCode' => $sCode]);
        return $this->oCategoryProvider->getCategory($this->mParams);
    }

    public function getCategories(Request $oRequest)
    {
        return $this->oCategoryProvider->getCategories($this->mParams);
    }

    public function createCategory(Request $oRequest)
    {
        return $this->oCategoryProvider->createCategory($this->mParams);
    }

    public function updateCategory(Request $oRequest)
    {
        return $this->oCategoryProvider->updateCategory($this->mParams);
    }

    public function deleteCategory(Request $oRequest)
    {
        return $this->oCategoryProvider->deleteCategory($this->mParams);
    }

    public function getCategoryList(Request $oRequest)
    {
        return $this->oCategoryProvider->getCategoryList($this->mParams);
    }

}
