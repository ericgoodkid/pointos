<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Constants\LibraryConstant;

class UserProvider extends CommonProvider
{
    public function __construct()
    {
        $this->instantiateUser();
    }

    public function getUserByUsername($sUsername)
    {
        // dd(Hash::make('default'));
        $oUser = $this->oUser->getUser($sUsername);
        if ($oUser === null) {
            $oParam = $this->setItemParam('aMessage', ['not exist' => $sUsername . ' do not exist']);
            return $this->setErrorResponse($oParam); 
        }

        $oParam = $this->setItemParam('oItem', $oUser);
        return $this->setSuccessResponse($oParam);
    }

    public function getUserByUsernameAndPassword($sUsername, $sPassword)
    {
        $oUser = $this->oUser->getUser($sUsername);
        if ($oUser === null) {
            $oParam = $this->setItemParam('aMessage', ['not exist' => $sUsername . ' do not exist']);
            return $this->setErrorResponse($oParam); 
        }

        $bPasswordResult = Hash::check($sPassword, $oUser->password);
        if ($bPasswordResult === false) {
            $oParam = $this->setItemParam('aMessage', ['not exist' =>' Mismatch password']);
            return $this->setErrorResponse($oParam); 
        }

        $oParam = $this->setItemParam('oItem', $oUser);
        return $this->setSuccessResponse($oParam);
    }
    
    public function getUsers()
    {
        $oParam = $this->setItemParam('aUser', $this->oUser->getUsers());
        return $this->setSuccessResponse($oParam);
    }
    
    public function getUser($sUsername)
    {
        $oUser = $this->oUser->getUser($sUsername);
        if ($oUser === null) {
            $oParam = $this->setItemParam('aMessage', ['not exist' => $sUsername . ' do not exist']);
            return $this->setErrorResponse($oParam); 
        }

        $oParam = $this->setItemParam('oItem', $oUser);
        return $this->setSuccessResponse($oParam);
    }

    public function getUserList()
    {
        $aResult = $this->oUser->getUsers();
        $aResult = $this->replaceKey($aResult);
        $aResult = $this->readableByDatatable($aResult, LibraryConstant::USER_NEEDED_KEY);
        $aResult = $this->prependActionInDatatable(LibraryConstant::USER_ACTION_BUTTON, $aResult);
        return ['data' => $aResult];
    }

    private function replaceKey($aItem)
    {

        $aTempItem = [];
        foreach ($aItem as $oItem) {
            $oItem['name'] = $this->upperCase($oItem['name']);
            $oItem['username'] = $this->upperCase($oItem['username']);
            $aTempItem[] = $oItem;
        }
       
        return $aTempItem;
    }

    public function createUser($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $oItem['name'] = strtolower($oItem['name']);
        $oItem['username'] = strtolower($oItem['username']);
        $oItem['password'] = Hash::make('default');
        $mResult = $this->validateProduct($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        return $this->oUser->createUser($oItem);
    }

    public function updateUser($mParams)
    {
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        $oItem['name'] = strtolower($oItem['name']);
        $oItem['username'] = strtolower($oItem['username']);
        $mResult = $this->validateProduct($oItem, $oItem['username']);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }
        
        $this->oUser->updateUser($oItem, $oItem['code']);
    }

    public function resetUser($mParams)
    {
        $oItem = $this->mapParams($mParams);
        $sPassword = Hash::make('default');
        return $this->oUser->resetUser($sPassword, $oItem['username']);
    }

    public function changePassword($mParams)
    {
        $oItem = $mParams['oItem'];
        $oUser = $this->getCurrentUser();
        // $sCurrentPassword = $oItem['sCurrentPassword'];
        $sConfirmPassword = $oItem['sConfirmPassword'];
        $sNewPassword = $oItem['sNewPassword'];
        // $bPasswordResult = Hash::check($sCurrentPassword, $oUser->password);
        // if ($bPasswordResult === false) {
        //     $oParam = $this->setItemParam('aMessage', ['not exist' =>'Current password is wrong']);
        //     return $this->setErrorResponse($oParam); 
        // }

        if ($sConfirmPassword !== $sNewPassword) {
            $oParam = $this->setItemParam('aMessage', ['not exist' =>'Your new password do not match']);
            return $this->setErrorResponse($oParam); 
        }

        $sPassword = Hash::make($sConfirmPassword);
        return $this->oUser->changePassword($oUser->username, $sPassword);
    }

    public function deleteUser($mParams)
    {
        $sUsername = $mParams['sUsername'];
        $this->oUser->deleteUser($sUsername);
        return $this->setSuccessResponse(['sMessage' =>  'Transaction Success']);
    }

    private function validateProduct($oItem, $sUsername = null)
    {
        $sUsername = $sUsername === null ? '' : $sUsername;
        $aRules = $this->getRules($sUsername);
        $mValidator = Validator::make($oItem, $aRules);
        if ($mValidator->fails()) {
            return $mValidator->getMessageBag()->toarray();
        }

        return true;
    }

    private function getRules($sUsername)
    {
        return array(
            'name' => 'required|string|min:2|max:255',
            'username' => 'required|max:255|unique:users,username,' . $sUsername ,
            'type' => 'in:Staff,Admin'
        );
    }

    private function getCode()
    {
        $iCount = $this->oUser->countUser();
        return $this->createCode(LibraryConstant::User_CODE, $iCount);
    }
}
