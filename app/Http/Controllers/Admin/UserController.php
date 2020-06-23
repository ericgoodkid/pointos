<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class UserController extends BaseController
{
    
    public function __construct(Request $oRequest)
    {
        $this->setParams($oRequest->all());
        $this->instantiateUserProvider();
    }

    public function getUser($sCode)
    {
        $this->setParams(['sCode' => $sCode]);
        return $this->oUserProvider->getUser($this->mParams);
    }

    public function getUsers(Request $oRequest)
    {
        return $this->oUserProvider->getUsers($this->mParams);
    }

    public function createUser(Request $oRequest)
    {
        return $this->oUserProvider->createUser($this->mParams);
    }

    public function updateUser(Request $oRequest, $sUsername)
    {
        $this->setParams(['sUsername' => $sUsername]);
        return $this->oUserProvider->updateUser($this->mParams);
    }

    public function changePassword(Request $oRequest)
    {
        return $this->oUserProvider->changePassword($this->mParams);
    }

    public function resetUser(Request $oRequest, $sUsername)
    {
        $this->setParams(['sUsername' => $sUsername]);
        return $this->oUserProvider->resetUser($this->mParams);
    }

    public function deleteUser(Request $oRequest, $sUsername)
    {
        $this->setParams(['sUsername' => $sUsername]);
        return $this->oUserProvider->deleteUser($this->mParams);
    }

    public function getUserList(Request $oRequest)
    {
        return $this->oUserProvider->getUserList($this->mParams);
    }

}
