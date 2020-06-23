<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Constants\LibraryConstant;
use Constants\MessageConstant;
use App\Imports\ProductImport;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductProvider extends CommonProvider
{
    public function __construct()
    {
        $this->instantiateProduct();
        $this->instantiateCategory();
        $this->instantiateBrand();
    }

    public function getProduct($mParams)
    {
        $sCode = $mParams['sCode'];
        $oProduct = $this->oProduct->getProduct($sCode);
        if ($oProduct === null) {
            $oParam = $this->setItemParam('aMessage', ['not exist' => $sCode . ' do not exist']);
            return $this->setErrorResponse($oParam); 
        }

        $oParam = $this->setItemParam('oItem', $oProduct);
        return $this->setSuccessResponse($oParam);
    }

    public function uploadProduct($mParams)
    {
        $oProductImport = new ProductImport();
        $oResult = Excel::import($oProductImport, $mParams->file('oExcel'));
        
        $oParam = $this->setItemParam('oItem', $oProductImport->aResult);
        return $this->setSuccessResponse($oParam);
    }

    public function exportProduct($mParams)
    {
        $sName = 'POS-' . date('m-d-Y_H:i:s') . '.csv';
        return Excel::download(new ProductExport, $sName);
    }

    public function uploadProductItem($oItem)
    {
        if (array_key_exists('code', $oItem) === false) {
            $oItem['code'] = $this->getProductCode();
            $this->oProduct->createProduct($oItem);
            return 'created';
        }

        $this->oProduct->updateProduct($oItem, $oItem['code']);
        return 'updated';
    }
    
    public function createProduct($mParams)
    {
        $oProduct = $mParams['oItem'];
        $oProduct = $this->mapParams($oProduct);
        $mResult = $this->validateProduct($oProduct);
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }

        $oCategory = $this->oCategory->getCategory($oProduct['category_code']);
        $oBrand = $this->oBrand->getBrand($oProduct['brand_code']);
        $oProduct['name'] = $this->upperCase($oProduct['name']);
        $oProduct['sku'] = strtoupper($oProduct['sku']);
        $oProduct['category_id'] = $oCategory['id'];
        $oProduct['brand_id'] = $oBrand['id'];
        $sCode = $this->getProductCode();
        $oProduct = array_merge($oProduct, ['code' => $sCode]);
        $this->oProduct->createProduct($oProduct);
        return $this->setSuccessResponse(MessageConstant::SUCCESS_RESPONSE);

    }

    public function updateProduct($mParams)
    {
        $oProduct = $mParams['oItem'];
        $oProduct = $this->mapParams($oProduct);
        $mResult = $this->validateProduct($oProduct);
        $oCategory = $this->oCategory->getCategory(['sCode' => $oProduct['category_code']]);
        $oBrand = $this->oBrand->getBrand(['sCode' => $oProduct['brand_code']]);
        $oProduct['name'] = $this->upperCase($oProduct['name']);
        $oProduct['sku'] = strtoupper($oProduct['sku']);
        $oProduct['category_id'] = $oCategory['id'];
        $oProduct['brand_id'] = $oBrand['id'];
        if ($mResult !== true) {
            $oParam = $this->setItemParam('aMessage', $mResult);
            return $this->setErrorResponse($oParam);
        }
        
        $this->oProduct->updateProduct($oProduct, $oProduct['code']);
        return $this->setSuccessResponse(MessageConstant::SUCCESS_RESPONSE);
    }
    
    public function getProductList()
    {
        $aResult = $this->oProduct->getProductList();
        $aResult = $this->replaceKey($aResult);
        $aResult = $this->getQuantityChip($aResult);
        $aResult = $this->readableByDatatable($aResult, LibraryConstant::PRODUCT_NEEDED_KEY);
        $aResult = $this->prependActionInDatatable(LibraryConstant::PRODUCT_ACTION_BUTTON, $aResult);
        return ['data' => $aResult];
    }

    private function replaceKey($aProduct)
    {
        $aTempProduct = [];
        foreach ($aProduct as $oProduct) {
            $sBrand = $oProduct->getBrand['name'];
            $sCategory = $oProduct->getCategory['name'];
            $sCapital = $oProduct['current_capital'];
            // dd($oProduct->getDiscount['amount']);
            // $sCategory = $oProduct->getDiscount['name'];
            $oProduct['brand'] = $sBrand;
            $oProduct['category'] = $sCategory;
            $oProduct['product_capital'] = $sCapital;
            $aTempProduct[] = $oProduct;
        }
       
        return $aTempProduct;
    }
    
    public function getProducts()
    {
        $oParam = $this->setItemParam('aProduct', $this->oProduct->getProducts());
        return $this->setSuccessResponse($oParam);
    }

    public function deleteProduct($mParams)
    {
        $oProduct = $mParams['oItem'];
        $sCode = $oProduct['code'];
        $this->oProduct->deleteProduct($sCode);
        return $this->setSuccessResponse(MessageConstant::SUCCESS_RESPONSE);
    }

    public function validateProduct($oProduct)
    {
        $iProductId = '';
        if (array_key_exists('code', $oProduct) === true) {
            $oOldProduct = $this->oProduct->getProduct($oProduct['code']);
            $iProductId = $oOldProduct['id'];
        }
        
        $aRules = $this->getRules($iProductId);
        $mValidator = Validator::make($oProduct, $aRules);
        if ($mValidator->fails()) {
            return $mValidator->getMessageBag()->toarray();
        }

        return true;
    }

    private function validateInventory($oInventory)
    {
        $aRules = $this->getInventoryRules();
        $mValidator = Validator::make($oInventory, $aRules);
        if ($mValidator->fails()) {
            return $mValidator->getMessageBag()->toarray();
        }

        return true;
    }

    // public function getProduct($sCode)
    // {
    //     return $this->oProduct->getProduct($sCode);
    // }

    public function getProductForSale()
    {
        $aResult = $this->oProduct->getProductForSale();
        $aResult = $this->replaceKey($aResult);
        $aResult = $this->getProductIcon($aResult);
        // return ['data' => $aResult];
        $aResult = $this->getPosQuantity($aResult);
        $aResult = $this->getProductAddButton($aResult);
        $aResult = $this->readableByDatatable($aResult, LibraryConstant::POS_PRODUCT_NEEDED_KEY);
        return ['data' => $aResult];
    }

    public function getRules($iProductId)
    {
        return array(
            'name' => 'required|string|min:2|max:255',
            'sku' => 'required|max:15|unique:products,sku,' . $iProductId,
            'category_code' => 'required|exists:Category,code',
            'brand_code' => 'nullable|exists:Brand,code',
            'price' => 'numeric|required|max:9999999999|min:1',
            'low_level' => 'integer|required|max:99999|min:1',
            'barcode' => 'string|required|unique:products,barcode,' . $iProductId
        );
    }

    public function getProductCode()
    {
        $iCount = $this->oProduct->countProduct();
        return $this->createCode(LibraryConstant::PRODUCT_CODE, $iCount);
    }


    private function getQuantityChip($aProduct)
    {
        $aTempProduct = [];
        foreach ($aProduct as $oProduct) {
            $iLowLevel = $oProduct['low_level'];
            $iQuantity = $oProduct['total_quantity'];

            if ($iQuantity <= $iLowLevel) {
                $oProduct['quantity'] = '<button type="button" class="btn bg-gradient-danger btn-md"> ' . $iQuantity . ' </button>';
                $aTempProduct[] = $oProduct;
                continue;
            }

            $iBeforeLowLevel = $iLowLevel + ($iLowLevel * .5);
            if ($iQuantity <= $iBeforeLowLevel) {
                $oProduct['quantity'] = '<button type="button" class="btn bg-gradient-warning btn-md" style="color:white;">  ' . $iQuantity . ' </button>';
                $aTempProduct[] = $oProduct;
                continue;
            }

            $oProduct['quantity'] = '<button type="button" class="btn bg-gradient-success btn-md"> ' . $iQuantity . ' </button>';
            $aTempProduct[] = $oProduct;
        }
       
        return $aTempProduct;
    }

    private function getProductIcon($aProduct)
    {
        $aTempProduct = [];
        foreach ($aProduct as $oProduct) {
            $iLowLevel = $oProduct['low_level'];
            $sName = $oProduct['name'];
            $iQuantity = $oProduct['total_quantity'];
            $sCategory = $oProduct['category'];
            $sBrand = $oProduct['brand'];
            $sIconName = strtoupper(substr($sName, 0, 1));
            $sClass = 'success';
           
            $iBeforeLowLevel = $iLowLevel + ($iLowLevel * .5);
            if ($iQuantity <= $iBeforeLowLevel) {
                $sClass = 'warning';
            }

            if ($iQuantity <= $iLowLevel) {
                $sClass = 'danger';
            }

            $oProduct['icon'] = '<div class="info-box  info-box-icon">
                                    <span class="info-box-icon bg-' .$sClass . '">
                                    '. $sIconName . '
                                    </span>
                                    <div class="info-box-content mt-2">
                                      <span class="info-box-number">' . $sName . '</span>
                                        <span class="info-box-text">' . $sBrand . ' - ' .$sCategory . '</span>
                                    </div>
                                 </div>';
            $aTempProduct[] = $oProduct;
        }
       
        return $aTempProduct;
    }

    private function getProductAddButton($aProduct)
    {
        $aTempProduct = [];
        foreach ($aProduct as $oProduct) {
            $iPrice = $oProduct['price'];
            $sCode = $oProduct['code'];
            $sName = $oProduct['name'];
            $sBrand = $oProduct['brand'];
            $iTotalQuantity = $oProduct['total_quantity'];

            $oProduct['addButton'] = '<a class="btn btn-app btn-block bg-gradient-primary btn-lg h1 btnAddProduct float-right mt-3" style="color: rgb(238, 237, 234);font-size:20px;"  data-code="' . $sCode . '" data-brand="' . $sBrand . '" data-max="' . $iTotalQuantity . '" data-price="' . $iPrice . '" data-name="' . $sName . '" style="margin-right: 5px;" >
                                            ₱ ' . $iPrice . '
                                       </a>';
            $aTempProduct[] = $oProduct;
            
        }
       
        return $aTempProduct;
    }

    private function getPosQuantity($aProduct)
    {
        $aTempProduct = [];
        foreach ($aProduct as $oProduct) {
            $iQuantity = $oProduct['total_quantity'];
            $oProduct['quantity'] = '<h4 class="mt-4"> ' . $this->getDetails($iQuantity) . '</h4>';
            $aTempProduct[] = $oProduct;
            
        }
       
        return $aTempProduct;
    }

    private function getDetails($iQuantity)
    {
        $sSuffix = 'pc';
        if ($iQuantity > 1) {
            $sSuffix = 'pcs';
        }

        return $iQuantity . ' ' . $sSuffix;
    }

}
