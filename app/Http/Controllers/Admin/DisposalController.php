<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class DisposalController extends BaseController
{
    
    public function __construct(Request $oRequest)
    {
        $this->setParams($oRequest->all());
        $this->instantiateDisposalProvider();
    }

    public function disposeProduct()
    {
        return $this->oDisposalProvider->disposeProduct($this->mParams);
    }

}
