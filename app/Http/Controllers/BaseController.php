<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\UserProvider;
use App\Providers\CategoryProvider;
use App\Providers\ProductProvider;
use App\Providers\SupplierProvider;
use App\Providers\BrandProvider;
use App\Providers\InventoryProvider;
use App\Providers\InventoryHistoryProvider;
use App\Providers\DisposalProvider;
use App\Providers\ReturnProvider;
use App\Providers\DiscountProvider;
use App\Providers\OrderProvider;
use App\Providers\AdditionalExpenseProvider;
use App\Providers\AdditionalExpenseHistoryProvider;
use App\Providers\BusinessProvider;
use App\Providers\User;

abstract class BaseController extends Controller
{
    protected $mParams = null;
    protected $oUserProvider = null;
    protected $oCategoryProvider = null;
    protected $oProductProvider = null;
    protected $oSupplierProvider = null;
    protected $oBrandProvider = null;
    protected $oInventoryProvider = null;
    protected $oInventoryHistoryProvider = null;
    protected $oDisposalProvider = null;
    protected $oReturnProvider = null;
    protected $oDiscountProvider = null;
    protected $oOrderProvider = null;
    protected $oBusinessProvider = null;
    protected $oAdditionalExpenseProvider = null;
    protected $oAdditionalExpenseHistoryProvider = null;

    protected function setParams($mParams)
    {
        if ($this->mParams === null) {
            $this->mParams = $mParams;
            return;
        }

        $this->mParams = array_merge($this->mParams, $mParams);
    }

    protected function instantiateUserProvider()
    {
        $this->oUserProvider = new UserProvider($this->mParams);
    }

    protected function instantiateCategoryProvider()
    {
        $this->oCategoryProvider = new CategoryProvider($this->mParams);
    }

    protected function instantiateProductProvider()
    {
        $this->oProductProvider = new ProductProvider($this->mParams);
    }

    protected function instantiateSupplierProvider()
    {
        $this->oSupplierProvider = new SupplierProvider($this->mParams);
    }

    protected function instantiateBrandProvider()
    {
        $this->oBrandProvider = new BrandProvider($this->mParams);
    }

    protected function instantiateInventoryProvider()
    {
        $this->oInventoryProvider = new InventoryProvider($this->mParams);
    }

    protected function instantiateInventoryHistoryProvider()
    {
        $this->oInventoryHistoryProvider = new InventoryHistoryProvider($this->mParams);
    }

    protected function instantiateDisposalProvider()
    {
        $this->oDisposalProvider = new DisposalProvider($this->mParams);
    }
    
    protected function instantiateReturnProvider()
    {
        $this->oReturnProvider = new ReturnProvider($this->mParams);
    }

    protected function instantiateDiscountProvider()
    {
        $this->oDiscountProvider = new DiscountProvider($this->mParams);
    }

    protected function instantiateOrderProvider()
    {
        $this->oOrderProvider = new OrderProvider($this->mParams);
    }

    protected function instantiateBusinessProvider()
    {
        $this->oBusinessProvider = new BusinessProvider($this->mParams);
    }

    protected function instantiateAdditionalExpenseProvider()
    {
        $this->oAdditionalExpenseProvider = new AdditionalExpenseProvider($this->mParams);
    }

    protected function instantiateAdditionalExpenseHistoryProvider()
    {
        $this->oAdditionalExpenseHistoryProvider = new AdditionalExpenseHistoryProvider($this->mParams);
    }

    protected function instantiateUser()
    {
        $this->oUser = new UserProvider($this->mParams);
    }

    protected function setItemParam($sItemName, $mItem)
    {
        return [
            $sItemName => $mItem
        ];

    }

    protected function setErrorResponse($aResponse)
    {
        return array_merge($this->oFalse, $aResponse);
    }

    protected function setSuccessResponse($aResponse)
    {
        return array_merge($this->oTrue, $aResponse);
    }
}
