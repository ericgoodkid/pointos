<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'Users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password','type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getUser($sUsername) 
    {
        return User::where('username', $sUsername)->get()->first();
    }

    public function getUsers() 
    {
        return User::all();
    }

    public function createUser($oItem) 
    {
        return User::create([
            'name' => $oItem['name'],
            'username' => $oItem['username'],
            'password' => $oItem['password']
        ]);
    }

    public function updateUser($oItem, $sOldUsername) 
    {
        $oOldItem = $this->getUser($sOldUsername);
        $oOldItem->name = $oItem['name'];
        $oOldItem->username = $oItem['username'];
        $oOldItem->type = $oItem['type'];
        return $oOldItem->save();
    }

    public function resetUser($sDefaultPassword, $sUsername) 
    {
        $oOldItem = $this->getUser($sUsername);
        $oOldItem->password = $sDefaultPassword;
        return $oOldItem->save();
    }

    public function deleteUser($sUsername) 
    {
        $oItem = $this->getUser($sUsername);
        return $oItem->delete();
    }

    public function changePassword($sUsername, $sPassword) 
    {
        $oItem = $this->getUser($sUsername);
        $oItem->password = $sPassword;
        return $oItem->save();
    }

    public function getUserByUsernameAndPassword($sUsername, $sPassword) 
    {
        return User::where('username', $sUsername)
        ->where('password', $sPassword)
        ->get()
        ->first();
    }
}
