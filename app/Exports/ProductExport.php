<?php
namespace App\Exports;
  
use App\Providers\CommonProvider;
use App\Product;
use App\Providers\ProductProvider;
use Maatwebsite\Excel\Concerns\FromCollection;
  
class ProductExport extends CommonProvider implements FromCollection
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

    public function collection()
    {
       $aProduct = $this->oProduct->getProductList();
       $aBody = $this->getBody($aProduct);
       return collect($aBody);
    }

    private function getBody($aProduct)
    {
        $aHead = $this->getHead();
        $aMainTempProduct[] = $aHead;
        foreach ($aProduct as $oProduct) {
            $aTempProduct['name'] = $oProduct->name;
            $aTempProduct['sku'] = $oProduct->sku;
            $aTempProduct['category'] = $oProduct->getCategory->name;
            $aTempProduct['brand'] = $oProduct->getBrand === null ? null : $oProduct->getBrand->name;
            $aTempProduct['price'] = $oProduct->price;
            $aTempProduct['low_level'] = $oProduct->low_level;
            $aTempProduct['barcode'] = $oProduct->barcode;
            $aMainTempProduct[] = $aTempProduct;
        }

        return $aMainTempProduct;
    }

    private function getHead()
    {
     
        return [
            'Name' => 'Name',
            'SKU' => 'SKU',
            'Category' => 'Category',
            'Brand' => 'Brand',
            'Price' => 'Price',
            'Low Level' => 'Low Level',
            'Barcode' => 'Barcode'
        ];
    }
}