<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Constants\LibraryConstant;

class OrderProvider extends CommonProvider
{
    public function __construct()
    {
        $this->instantiateOrder();
        $this->instantiateOrderItem();
        $this->instantiateProduct();
        $this->instantiateDiscount();
    }

    public function getOrder($mParams)
    {
        $sCode = $mParams['sCode'];
        $oOrder = $this->oOrder->getOrder($sCode);
        if ($oOrder === null) {
            $oParam = $this->setItemParam('aMessage', ['not exist' => $sCode . ' do not exist']);
            return $this->setErrorResponse($oParam); 
        }

        $oParam = $this->setItemParam('oItem', $oOrder);
        return $this->setSuccessResponse($oParam);
    }
    
    public function getOrders()
    {
        $oParam = $this->setItemParam('aOrder', $this->oOrder->getOrders());
        return $this->setSuccessResponse($oParam);
    }

    public function getOrderList()
    {
        $aResult = $this->oOrder->getOrderList();
        $aResult = $this->getOrderTotalAmount($aResult);
        $aResult = $this->getOrderPaid($aResult);
        $aResult = $this->getOrderChange($aResult);
        $aResult = $this->replaceKey($aResult);
        $aResult = $this->readableByDatatable($aResult, LibraryConstant::ORDER_NEEDED_KEY);
        $aResult = $this->prependActionInDatatable(LibraryConstant::ORDER_ACTION_BUTTON, $aResult);
        return ['data' => $aResult];
    }

    private function getOrderTotalAmount($aOrder)
    {
        $aTempProduct = [];
        foreach ($aOrder as $oOrder) {
            $iTotalPrice = $oOrder['total_price'];
            $oOrder['total_price2'] = '<h5> ₱ ' . number_format($iTotalPrice, 2) . ' </h4>';
            
            $aTempProduct[] = $oOrder;
        }

        return $aTempProduct;
    }

    private function getOrderPaid($aOrder)
    {
        $aTempProduct = [];
        foreach ($aOrder as $oOrder) {
            $iTotalPrice = $oOrder['cash'];
            $oOrder['cash2'] = '<h5> ₱ ' . number_format($iTotalPrice, 2) . ' </h4>';
            
            $aTempProduct[] = $oOrder;
        }

        return $aTempProduct;
    }

    private function getOrderChange($aOrder)
    {
        $aTempProduct = [];
        foreach ($aOrder as $oOrder) {
            $iTotalCash = $oOrder['cash'];
            $iTotalPrice = $oOrder['total_price'];
            $iChange = $iTotalCash - $iTotalPrice;
            $oOrder['change'] = '<h5> ₱ ' . number_format($iChange, 2) . ' </h4>';
            
            $aTempProduct[] = $oOrder;
        }

        return $aTempProduct;
    }

    private function replaceKey($aOrder)
    {
        $aTempItem = [];
        foreach ($aOrder as $oItem) {
            $oItem['date'] = $oItem->created_at->format(self::DATE_FORMAT);
            $oItem['cashier'] = ucwords($oItem->getUser['name']);
            $sCode = $oItem->code;
            $aTempItem[] = $oItem;
        }
       
        return $aTempItem;
    }

    public function createOrder($mParams)
    {
        $iUserId = $this->getCurrentUser()['id'];
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        if (count($oItem['order_item']) === 0) {
            $oParam = $this->setItemParam('aMessage', [['message' => 'Please submit a proper order']]);
            return $this->setErrorResponse($oParam);
        }

        $aItem = $this->mapArrayParams($oItem['order_item']);
        $mResult = $this->validateCash($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', [$mResult]);
            return $this->setErrorResponse($oParam);
        }

        $mResult = $this->validateOrder($aItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', [$mResult]);
            return $this->setErrorResponse($oParam);
        }

        $sCode = $this->getCode();
        $oOrder = [
            'code'    => $sCode,
            'cash'    => $oItem['cash'],
            'user_id' => $iUserId
        ];
        $mResult = $this->oOrder->createOrder($oOrder);
        $sOrderCode = $mResult->code;
        if ($mResult === false) {
            $oParam = $this->setItemParam('aMessage', [$mResult]);
            return $this->setErrorResponse($oParam);
        }

        $iId = $mResult->id;
        $mResult = $this->createOrderItem($aItem, $iId);
        if ($mResult === false) {
            $oParam = $this->setItemParam('aMessage', [$mResult]);
            return $this->setErrorResponse($oParam);
        }

        $oParam = $this->setItemParam('aMessage',  $sOrderCode);
        return $this->setSuccessResponse($oParam);
    }

    public function createOrderItem($aItem, $iId)
    {
        $aErrorMessage = [];
        foreach($aItem as $oItem) {
            $sProductCode = $oItem['product_code'];
            $mDiscountCode = $oItem['discount_code'];
            $oProduct = $this->oProduct->getProduct($sProductCode);
            $oOrderItem['id'] = $iId;
            $oOrderItem['quantity'] = $oItem['quantity'];
            $oOrderItem['product_id'] = $oProduct->id;
            $oOrderItem['product_name'] = $oProduct->name;
            $oOrderItem['product_sku'] = $oProduct->sku;
            $oOrderItem['product_brand'] = $oProduct->getBrand->name;
            $oOrderItem['product_price'] = $oProduct->price;
            $oOrderItem['product_capital'] = $oProduct->current_capital;
            $oOrderItem['discount_name'] = null;
            $oOrderItem['discount_percentage'] = null;
            if ($mDiscountCode !== null) {
                $oDiscount = $this->oDiscount->getDiscount($mDiscountCode);
                $oOrderItem['discount_name'] = $oDiscount->name;
                $oOrderItem['discount_percentage'] = $oDiscount->amount;
            }

            $mResult = $this->oOrderItem->createOrderItem($oOrderItem);
            if ($mResult === false) {
                $aErrorMessage[] = $mResult;
            }
        }

        if (count($aErrorMessage) !== 0) {
            return $aErrorMessage;
        }

        return true;
    }

    public function updateOrder($mParams)
    {
        $iUserId = $this->getCurrentUser()['id'];
        $oItem = $mParams['oItem'];
        $oItem = $this->mapParams($oItem);
        if (count($oItem['order_item']) === 0) {
            $oParam = $this->setItemParam('aMessage', [['message' => 'Please submit a proper order']]);
            return $this->setErrorResponse($oParam);
        }

        $aItem = $this->mapArrayParams($oItem['order_item']);
        $mResult = $this->validateCash($oItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', [$mResult]);
            return $this->setErrorResponse($oParam);
        }

        $mResult = $this->validateOrder($aItem);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', [$mResult]);
            return $this->setErrorResponse($oParam);
        }

        $oItem['user_id'] = $iUserId;
        $this->oOrder->updateOrder($oItem, $mParams['sCode']);

        $oOrder = $this->oOrder->getOrder($mParams['sCode']);
        $mDeleteResult = $this->oOrderItem->deleteRecentOrder($oOrder->id);

        $mResult = $this->createOrderItem($aItem, $oOrder->id);
        if ($mResult === false) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }


        $oParam = $this->setItemParam('aMessage', $mResult);
        return $this->setSuccessResponse($oParam);    }

    public function deleteOrder($mParams)
    {
        $oItem = $this->mapParams($mParams);
        $oOrder = $this->oOrder->getOrder($oItem['code']);
        $mDeleteResult = $this->oOrderItem->deleteRecentOrder($oOrder->id);
        $this->oOrder->deleteOrder($oItem['code']);
        return $this->setSuccessResponse(['sMessage' =>  'Transaction Success']);
    }

    private function validateOrder($aItem)
    {
        $aErrorMessage = [];
        foreach ($aItem as $oItem) {
            $aRules = $this->getRules($oItem);
            $mValidator = Validator::make($oItem, $aRules);
            if ($mValidator->fails()) {
                $aErrorMessage[] = $mValidator->getMessageBag()->toarray();
            }
        }

        if (count($aErrorMessage) !== 0) {
            return $aErrorMessage;
        }

        return true;
    }    
    
    private function validateCash($oItem)
    {
        $aRules = $this->getCashRules($oItem);
        $mValidator = Validator::make($oItem, $aRules);
        if ($mValidator->fails()) {
            return $mValidator->getMessageBag()->toarray();
        }

        return true;
    }

    private function getCashRules($oItem)
    {
        $aOrderItem = $oItem['order_item'];
        return array(
            'cash' => [
                'required',
                'numeric',
                function ($sAttribute, $iValue, $mFail) use ($aOrderItem) {
                    $iTotalPrice = 0;
                    $iCash = (float) $iValue;
                    foreach($aOrderItem as $oOrderItem) {
                        $sProductCode = $oOrderItem['sProductCode'];
                        $sDiscountCode = $oOrderItem['sDiscountCode'];
                        $iQuantity = $oOrderItem['iQuantity'];
                        $aProduct = $this->oProduct->getProduct([
                            'sCode' => $sProductCode
                        ]);

                        $iPercentage = 0;
                        if ($sDiscountCode !== null) {
                            $aDiscount = $this->oDiscount->getDiscount([
                                'sCode' => $sDiscountCode
                            ]);
                             $iPercentage = (float) $aDiscount->amount / 100;

                        }

                        $iProductPrice = (float) $aProduct->price;
                        $iDiscountedPrice = $iProductPrice - ($iProductPrice * ($iPercentage));
                        $iTotalPrice += $iDiscountedPrice * $iQuantity;
                    }
                    

                    if ($iCash < $iTotalPrice) {
                        $mFail('Amount ₱ ' . number_format($iCash, 2) . ' is not sufficient for ₱ ' . number_format($iTotalPrice, 2));
                    }

                }
            ]
        );
    }

    private function getRules($oItem)
    {
        $sProductCode = $oItem['product_code'];
        return array(
            'product_code' => 'required|exists:Products,code',
            'discount_code' => 'nullable|exists:Discount,code',
            'quantity' =>  [
                'required',
                'integer',
                'min:1',
                function ($sAttribute, $iValue, $mFail) use ($sProductCode) {
                    $aProduct = $this->oProduct->getProduct([
                        'sCode' => $sProductCode
                    ]);
                    if ($aProduct['total_quantity'] < $iValue) {
                        $mFail('Quantity of ' . $aProduct->name . ' is greater than current inventory');
                    }

                }
            ]
        );
    }

    private function getCode()
    {
        $iCount = $this->oOrder->countOrder();
        return $this->createCode(LibraryConstant::ORDER_CODE, $iCount);
    }
}
