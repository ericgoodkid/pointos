<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class BusinessController extends BaseController
{
    
    public function __construct(Request $oRequest)
    {
        $this->setParams($oRequest->all());
        $this->instantiateBusinessProvider();
    }

    public function getBusiness()
    {
        return $this->oBusinessProvider->getBusiness();
    }

    public function createBusiness(Request $oRequest)
    {
        return $this->oBusinessProvider->createBusiness($this->mParams);
    }

}
