<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
class PageController extends BaseController
{
    
    public function __construct()
    {
    }
    
    public function user()
    {
        return view('admin.user', [
            'sTitle' => 'User',
            'sFrom' => 'System Setup'
        ]);
    }
    
    public function supplier()
    {
        return view('admin.supplier', [
            'sTitle' => 'Supplier',
            'sFrom' => 'System Setup'
        ]);
    }

    public function business()
    {
        $this->instantiateBusinessProvider();
        $mBusinessInfo = $this->oBusinessProvider->getBusiness();

        return view('admin.business', [
            'sTitle' => 'Business Information',
            'sFrom' => 'System Setup',
            'mBusinessInfo' => $mBusinessInfo['oItem']
        ]);
    }

    public function brand()
    {
        return view('admin.brand', [
            'sTitle' => 'Brand',
            'sFrom' => 'System Setup'
        ]);
    }

    public function additionalExpenseType()
    {
        return view('admin.additional_Expense_type', [
            'sTitle' => 'Additional Expense Types',
            'sFrom' => 'System Setup'
        ]);
    }

    public function additionalExpense()
    {
        return view('admin.additional_Expense', [
            'sTitle' => 'Additional Expense',
            'sFrom' => 'Transaction'
        ]);
    }

    public function category()
    {
        return view('admin.category', [
            'sTitle' => 'Category',
            'sFrom' => 'System Setup'
        ]);
    }

    public function discount()
    {
        return view('admin.discount', [
            'sTitle' => 'Discount',
            'sFrom' => 'System Setup'
        ]);
    }

    public function product()
    {
        return view('admin.product', [
            'sTitle' => 'Product',
            'sFrom' => 'System Setup'
        ]);
    }

    public function inventoryHistory()
    {
        return view('admin.inventory_history', [
            'sTitle' => 'Inventory History',
            'sFrom' => 'Reports'
        ]);
    }

    public function pos()
    {
        return view('common.pos', [
            'sTitle' => 'Point of Sale',
            'sFrom' => 'Transaction'
        ]);
    }

    public function returnHistory()
    {
        return view('admin.return_history', [
            'sTitle' => 'Return History',
            'sFrom' => 'Reports'
        ]);
    }

    public function orderHistory()
    {
        return view('admin.order_history', [
            'sTitle' => 'Order History',
            'sFrom' => 'Reports'
        ]);
    }

    public function login()
    {
        if (session()->get('user') !== null) {
            return redirect()->route('PointOfSale');
        }

        return view('auth.login');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('Login');
    }

    public function dashboard()
    {
        return $this->isAdmin() === true ? redirect()->route('PointOfSale') : redirect()->route('PointOfSale');
    }

    public function try()
    {
        return view('auth.try');;
    }

    private function isAdmin()
    {
        $oUser = session()->get('user');
        return $oUser['type'] === 'Admin';
    }
}
