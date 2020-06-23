<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class ReturnController extends BaseController
{
    
    public function __construct(Request $oRequest)
    {
        $this->setParams($oRequest->all());
        $this->instantiateReturnProvider();
    }


    public function getReturn($sCode)
    {
        $this->setParams(['sCode' => $sCode]);
        return $this->oReturnProvider->getReturn($this->mParams);
    }

    public function createReturn(Request $oRequest)
    {
        return $this->oReturnProvider->createReturn($this->mParams);
    }

    public function updateReturn(Request $oRequest)
    {
        return $this->oReturnProvider->updateReturn($this->mParams);
    }

    public function deleteReturn(Request $oRequest)
    {
        return $this->oReturnProvider->deleteReturn($this->mParams);
    }

    public function getReturnList(Request $oRequest)
    {
        return $this->oReturnProvider->getReturnList($this->mParams);
    }

    public function approvalReturn($sCode, $sStatus)
    {
        $this->setParams([
            'sCode' => $sCode,
            'sStatus' => $sStatus
        ]);
        return $this->oReturnProvider->approvalReturn($this->mParams);
    }


}
