<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\User;
use App\Category;
use App\Product;
use App\Supplier;
use App\Brand;
use App\Inventory;
use App\InventoryHistory;
use App\Disposal;
use App\ReturnProduct;
use App\Discount;
use App\Order;
use App\OrderItem;
use App\DiscountItem;
use App\Business;
use App\AdditionalExpense;
use App\AdditionalExpenseHistory;
use Constants\LibraryConstant;

abstract class CommonProvider extends ServiceProvider
{
    protected $oUser = null;
    protected $oCategory = null;
    protected $oProvider = null;
    protected $oProduct = null;
    protected $oSupplier = null;
    protected $oBrand = null;
    protected $oInventory = null;
    protected $oInventoryHistory = null;
    protected $oDisposal = null;
    protected $oReturn = null;
    protected $oDiscount = null;
    protected $oDiscountItem = null;
    protected $oOrder = null;
    protected $oOrderItem = null;
    protected $oBusiness = null;
    protected $oAdditionalExpense = null;
    protected $oAdditionalExpenseHistory = null;
    protected const DATE_FORMAT = 'M d Y, h:m A';

    public function __construct() 
    {
        date_default_timezone_set('Asia/Manila');
    }
    

    private $oFalse = [
        'bResult' => false
    ];

    private $oTrue = [
        'bResult' => true
    ];

    protected function instantiateUser()
    {
        $this->oUser = new User();
    }

    protected function instantiateCategory()
    {
        $this->oCategory = new Category();
    }

    protected function instantiateProduct()
    {
        $this->oProduct = new Product();
    }
    
    protected function instantiateSupplier()
    {
        $this->oSupplier = new Supplier();
    }

    protected function instantiateBrand()
    {
        $this->oBrand = new Brand();
    }

    protected function instantiateInventory()
    {
        $this->oInventory = new Inventory();
    }

    protected function instantiateInventoryHistory()
    {
        $this->oInventoryHistory = new InventoryHistory();
    }

    protected function instantiateDisposal()
    {
        $this->oDisposal = new Disposal();
    }

    protected function instantiateReturn()
    {
        $this->oReturn = new ReturnProduct();
    }

    protected function instantiateDiscount()
    {
        $this->oDiscount = new Discount();
    }

    protected function instantiateDiscountItem()
    {
        $this->oDiscountItem = new DiscountItem();
    }
    
    protected function instantiateOrder()
    {
        $this->oOrder = new Order();
    }

    protected function instantiateOrderItem()
    {
        $this->oOrderItem = new OrderItem();
    }

    protected function instantiateBusiness()
    {
        $this->oBusiness = new Business();
    }

    protected function instantiateAdditionalExpense()
    {
        $this->oAdditionalExpense = new AdditionalExpense();
    }

    protected function instantiateAdditionalExpenseHistory()
    {
        $this->oAdditionalExpenseHistory = new AdditionalExpenseHistory();
    }

    protected function setErrorResponse($aResponse)
    {
        return array_merge($this->oFalse, $aResponse);
    }

    protected function setSuccessResponse($aResponse)
    {
        return array_merge($this->oTrue, $aResponse);
    }

    protected function getCurrentUser()
    {
        $oSession = $this->getSession();
        return $oSession->get('user');
    }

    protected function getSession()
    {
        return session();
    }
    
    protected function setMessageParam($sMessage)
    {
        return [
            'sMessage' => $sMessage
        ];

    }

    protected function setUrlParam($sUrl)
    {
        return [
            'sUrl' => $sUrl
        ];

    }

    protected function setItemParam($sItemName, $mItem)
    {
        return [
            $sItemName => $mItem
        ];

    }

    protected function mapParams($mParams)
    {
        $aMappedParams = LibraryConstant::PARAMS_KEY;
        $aNewParams = [];
        foreach ($mParams as $sKey => $mValue) {
            $aNewParams = array_merge($aNewParams, [$aMappedParams[$sKey] => $mValue]);
        }

        return $aNewParams;
    }

    protected function mapArrayParams($mParams){
        $aNewParams = [];
        foreach ($mParams as $mValue) {
            $aNewParams[] = $this->mapParams($mValue);
        }
        return $aNewParams;
    }

    protected function createCode($sPrefix, $iCount)
    {
        $iMaxCodeLength = 5;
        $iCount = $iCount + 1;
        $sCode = $this->getAlphaCode($iCount, '');
        $sCode = '00000' . $sCode;
        return $sPrefix . substr($sCode, 1);

    }

    protected function getAlphaCode($iKey, $sCode) {
        $aValue = [
          'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q',
          'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
        ];
    
        if ($iKey < 1) {
            return $sCode;
        }
    
        if ($iKey <= 26) {
            return $sCode . $aValue[$iKey - 1];
        }
    
        $iRemainder = $iKey % 26;
        $iFactor = parseInt($iKey / 26, 10);
        $sFirstPart = $sCode;
        if ($iRemainder === 0) {
          $sFirstPart = $this->getAlphaCode($iFactor - 1, '');
          return $sFirstPart . $aValue[25];
        } 
    
        $sFirstPart = $this->getAlphaCode($iFactor, $sCode);
        return $sFirstPart . $aValue[$iRemainder - 1];
    }

    protected function upperCase($sWord)
    {
        return ucwords($sWord);
        return strtoupper(substr($sWord, 0, 1)) . \strtolower(substr($sWord, 1));
    }

    protected function readableByDatatable($aItem, $aNeededKey)
    {
        $aList = [];
        foreach ($aItem as $sKey => $mValue) {
            $aTempList = [];
            foreach ($aNeededKey as $sValue) {
                $aTempList[] = $mValue[$sValue];
            }
            $aList[] = $aTempList;
        }

        return $aList;
    }

    protected function removeParams($mParams) 
    {
        $aRemoveableKey = LibraryConstant::DATA_TABLE_REMOVE_KEY;
        foreach ($aRemoveableKey as $sKey) {
            unset($mParams[$sKey]);

        }
        return $mParams;
    }

    protected function prependActionInDatatable($sAction, $aItem)
    {
        $aList = [];
        foreach ($aItem as $aValue) {
            array_unshift($aValue, $sAction);
            $aList[] = $aValue;
        }

        return $aList;
    }

    protected function prependApprovalActionInDatatable($sUsedAction, $sUnusedAction, $aItem, $iIndex)
    {
        $aList = [];
        foreach ($aItem as $aValue) {
            $sAction = $sUnusedAction;
            if (strpos($aValue[$iIndex], 'Pending') === false) {
                $sAction = $sUsedAction;
            }
            
            array_unshift($aValue, $sAction);
            $aList[] = $aValue;
        }

        return $aList;
    }
    
    protected function sanitizeInputs($oItem)
    {

    }
}
