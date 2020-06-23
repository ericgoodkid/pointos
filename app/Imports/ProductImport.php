<?php
namespace App\Imports;
  
use App\Providers\CommonProvider;
use App\Product;
use App\Providers\ProductProvider;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
  
class ProductImport extends CommonProvider implements ToModel, WithStartRow
{
    public $aSku = [];
    public $aResult = [];
    private $oProductModel;

    public function __construct()
    {
        $this->instantiateProduct();
        $this->instantiateCategory();
        $this->instantiateBrand();
    }

    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $aRow)
    {
        $oProduct = new ProductProvider();
        $this->setValue($aRow);
        $aResult['sku'] = $this->oProductModel['sku'];
        $mResult = $oProduct->validateProduct($this->oProductModel);
        if ($mResult !== true){
            $aResult['message'] = $mResult;
            $aResult['result'] = false;
            $this->aResult[] = $aResult;
            return;
        }

        $sResult = $oProduct->uploadProductItem($this->oProductModel);
        $aResult['result'] = $sResult;
        $this->aResult[] = $aResult;
        return;
    }

    private function setValue($aRow)
    {
        $sSku = $aRow[1];
        $sName = $aRow[0];
        $sCategoryName = $aRow[2];
        $sBrandName = $aRow[3];
        $mCategory = $this->oCategory->getCategoryByName($sCategoryName);
        $mBrand = $this->oBrand->getBrandByName($sBrandName);
        $mProduct = $this->oProduct->getProductBySku($sName);

        if ($mProduct !== null) {
            $this->oProductModel['code'] = $mProduct['code'];
        }

        // var_dump([$sSku, $this->oProductModel]);


        $this->oProductModel['name'] = $sName;
        $this->oProductModel['sku'] = $sSku;        
        $this->oProductModel['category_code'] = $mCategory === null ? null : $mCategory['code'];
        $this->oProductModel['category_id'] = $mCategory === null ? null : $mCategory['id'];        
        $this->oProductModel['brand_code'] = $mBrand === null ? null : $mBrand['code'];
        $this->oProductModel['brand_id'] = $mBrand === null ? null : $mBrand['id'];
        $this->oProductModel['price'] = $aRow[4];
        $this->oProductModel['low_level'] = $aRow[5];
        $this->oProductModel['barcode'] = $aRow[6];
    }
}