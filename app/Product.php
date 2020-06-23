<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Category;
use App\Inventory;
use App\InventoryHistory;
use App\ReturnProduct;
use App\Disposal;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    // public static $withoutAppends = true;
    // protected function getArrayableAppends()
    // {
    //     if(self::$withoutAppends){
    //         return [];
    //     }
    //     return parent::getArrayableAppends();
    // }


    protected $fillable = [
        'name', 'category_id', 'brand_id', 'price', 'quantity', 'low_level', 'barcode', 'code', 'sku'
    ];

    protected $hidden = [
        'created_at'
    ];

    protected $appends = [
        'total_quantity',
        'discount_price',
        'current_capital'
    ];

    public function getTotalQuantityAttribute()
    {
        $iReturnCount = (int) $this->getReturnCount()->sum('quantity');
        $iDisposeCount = (int) $this->getDisposeCount()->sum('quantity');
        $iInventoryCount = (int) $this->getProductCount()->sum('quantity');
        $aOrder = $this->getOrderItem();
        // $iReturnCount = (int) $this->relations['getReturnCount']['quantity'];
        // $iDisposeCount = (int) $this->relations['getDisposeCount']['quantity'];
        // $iInventoryCount = (int) $this->relations['getProductCount']['quantity'];
        // $aOrder = $this->relations['getOrderItem'];
        $iTotalOrderQuantity = 0;
        foreach ($aOrder as $mValue) {
            $iTotalOrderQuantity += (int) $mValue['quantity'];
        }
        return ($iInventoryCount - ($iReturnCount + $iDisposeCount)) - $iTotalOrderQuantity;
    }

    
    public function getCurrentCapitalAttribute()
    {
        $iTotalCost = $this->getInventory()->get()->sum('actual_cost');
        $iTotalQuantity = $this->getInventory()->get()->sum('quantity');
        return $iTotalCost / $iTotalQuantity;
    }

    public function getDiscountPriceAttribute()
    {
        return $this->getDiscount();
        // return $this->relations['getDiscount'];
        // $iReturnCount = (int) ['quantity'];
        // $iDisposeCount = (int) $this->relations['getDisposeCount']['quantity'];
        // $iInventoryCount = (int) $this->relations['getProductCount']['quantity'];
        // return $iInventoryCount - ($iReturnCount + $iDisposeCount);
    }

    public function createProduct($oProduct)
    {
        return Product::create([
            'name' => $oProduct['name'],
            'sku' => $oProduct['sku'],
            'code' => $oProduct['code'],
            'category_id' => $oProduct['category_id'],
            'brand_id' => $oProduct['brand_id'],
            'price' => $oProduct['price'],
            'low_level' => $oProduct['low_level'],
            'barcode' => $oProduct['barcode']
        ]);
    }

    public function getProductList()
    {
        return Product::with('getCategory', 'getProductCount', 'getBrand', 'getReturnCount','getDisposeCount', 'getOrderItem')->get();
    }

    public function countProduct()
    {
        return Product::withTrashed()->count();
    }

    public function getProductBySku($sSku)
    {
        return Product::where('name', $sSku)
            ->get()
            ->first();

        // return   Product::whereRaw("LOWER(REPLACE(`sku`, ' ' ,''))  = ?", [strtolower (str_replace(' ', '', $sSku))])
        // ->get()
        // ->first();
    }

    public function updateProduct($oProduct, $sCode) 
    {
        $oOldProduct = $this->getProduct($sCode);
        $oOldProduct->sku = $oProduct['sku'];
        $oOldProduct->name = $oProduct['name'];
        $oOldProduct->category_id = $oProduct['category_id'];
        $oOldProduct->brand_id = $oProduct['brand_id'];
        $oOldProduct->price = $oProduct['price'];
        $oOldProduct->low_level = $oProduct['low_level'];
        $oOldProduct->barcode = $oProduct['barcode'];
        return $oOldProduct->save();
    }
    
    public function getProduct($sCode) 
    {
        return Product::where('code', $sCode)->with(
            'getCategory', 
            'getProductCount', 
            'getBrand', 
            'getReturnCount',
            'getDisposeCount', 
            'getOrderItem',
            'getInventory'
        )->get()->first();
    }    

    public function getProductForSale() 
    {
        return Product::with([
            'getCategory', 'getProductCount', 'getBrand', 'getReturnCount','getDisposeCount', 'getOrderItem',
            // 'getDiscount' => function($query) {
            //     date_default_timezone_set('Asia/Manila');
            //     $sCurrentDate = date('Y-m-d H:i:s');
            //     $query->join('products', 'product_id', '=', 'products.id');
            //     $query->where('start_date', '<=', $sCurrentDate);
            //     $query->where('end_date', '>=', $sCurrentDate);
            // }
        ]
        )
        ->get();
    }

    public function getProducts() 
    {
        return Product::with('getCategory', 'getProductCount', 'getBrand', 'getReturnCount','getDisposeCount', 'getOrderItem')->get();
    }


    public function deleteProduct($sCode) 
    {
        $oOldProduct = $this->getProduct($sCode);
        return $oOldProduct->delete();
    }

    public function getCategory()
    {
        return $this->belongsTo('App\Category', 'category_id', 'id');
    }

    public function getDiscount()
    {
        return $this->hasMany('App\Discount', 'product_id', 'id');
    }

    public function getOrderItem()
    {
        return $this->hasMany('App\OrderItem', 'product_id', 'id');
    }

    public function getBrand()
    {
        return $this->belongsTo('App\Brand', 'brand_id', 'id')
        ->withDefault([
            'name' => 'Not Branded',
            'code' => 'none'
        ]);
    }

    // public function getInventory()
    // {
    //     return $this->hasOne(Inventory::class, 'product_id', 'id')
    //     ->withDefault([
    //         'quantity' => 0
    //     ]);
    // }

    public function getInventoryHistory()
    {
        return $this->hasOne(InventoryHistory::class, 'product_id', 'id');
    }

    public function getInventory()
    {
        return $this->hasMany(InventoryHistory::class, 'product_id', 'id');
    }


    public function getDisposalHistory()
    {
        return $this->hasOne(Disposal::class, 'product_id', 'id');
    }

    public function getReturnHistory()
    {
        return $this->hasOne(ReturnProduct::class, 'product_id', 'id');
    }

    public function getProductCount()
    {
        return $this->getInventoryHistory()
            ->selectRaw('product_id,sum(quantity) as quantity')
            ->groupBy('product_id')
            ->withDefault([
                'quantity' => 0
            ]);
    }

    public function getReturnCount()
    {
        return $this->getReturnHistory() 
            ->selectRaw('product_id,sum(quantity) as quantity,remarks')
            ->where('status', '=', 'approve')
            ->groupBy('product_id')
            ->withDefault([
                'quantity' => 0,
                'remarks' => null
            ]);
    }

    public function getDisposeCount()
    {
        return $this->getDisposalHistory() 
            ->selectRaw('product_id,sum(quantity) as quantity')
            ->groupBy('product_id')
            ->withDefault([
                'quantity' => 0
            ]);
    }

    // public function getProductCount()
    // {
    //     return $this->getInventoryHistory()
    //         ->selectRaw('*,inventory_history.product_id,sum(inventory_history.quantity) - (select sum(product_dispose.quantity) from product_dispose group by product_id where product_dispose.product_id = inventory_history.product_id) as quantity')
    //         ->groupBy('product_id')
    //         ->withDefault([
    //             'quantity' => 0
    //         ]);
    // }
}
