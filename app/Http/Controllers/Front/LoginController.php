<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends BaseController
{
    use AuthenticatesUsers;
    
    public function __construct(Request $oRequest)
    {
       $this->instantiateUser();
    }

    public function loginUser(Request $oRequest)
    {
        $oParams = $oRequest->all();
        $sUsername = $oParams['username'];
        $sPassword = $oParams['password'];
        $oUser = $this->oUser->getUserByUsername($sUsername);
        if ($oUser['bResult'] === false) {
            return [
                'bResult' => false,
                'sType' => 'username'
            ];
        }

        $oUser = $this->oUser->getUserByUsernameAndPassword($sUsername, $sPassword);
        if ($oUser['bResult'] === false) {
            return [
                'bResult' => false,
                'sType' => 'password'
            ];
        }
        

        // dd($)
        // return $this->login();
        session()->put('user', $oUser['oItem']);
        return $oUser;
    }

    public function redirect()
    {
        $oUser = session()->get('user');
        return redirect('/Dashboard');
    }

}
