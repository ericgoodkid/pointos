<?php

namespace Constants;

class LibraryConstant
{
    public const PARAMS_KEY =[
        'sCode' => 'code',
        'sSku' => 'sku',
        'sName' => 'name',
        'sUsername' => 'username',
        'sType' => 'sType',
        'iPrice' => 'price',
        'iQuantity' => 'quantity',
        'iAmount' => 'amount',
        'iMinimum' => 'minimum',
        'iLowLevel' => 'low_level',
        'sBarcode' => 'barcode',
        'sId' => 'id',
        'sCategoryCode' => 'category_code',
        'sContactNumber' => 'contact_number',
        'sContactPerson' => 'contact_person',
        'sAddress' => 'address',
        'sProductCode' => 'product_code',
        'sTypeCode' => 'type_code',
        'aProductCode' => 'product_code',
        'sDiscountCode' => 'discount_code',
        'sSupplierCode' => 'supplier_code',
        'sBrandCode' => 'brand_code',
        'sRemarks' => 'remarks',
        'sBrandId' => 'brand_id',
        'sSupplierId' => 'supplier_id',
        'sProductId' => 'product_id',
        'sStartDate' => 'start_date',
        'sEndDate' => 'end_date',
        'bParams' => 'params',
        'sStatus' => 'status',
        'sCash' => 'cash',
        'aOrderItem' => 'order_item',
        'sType' => 'type',
    ];

    public const PRODUCT_CODE = 'PRODUCT';
    public const CATEGORY_CODE = 'CATEGORY';
    public const SUPPLIER_CODE = 'SUPPLIER';
    public const BRAND_CODE = 'BRAND';
    public const RETURN_CODE = 'RETURN';
    public const INVENTORY_CODE = 'INVENTORY';
    public const DISPOSAL_CODE = 'DISPOSAL';
    public const DISCOUNT_CODE = 'PROMO';
    public const ORDER_CODE = 'ORDER';
    public const ADDITIONAL_EXPENSE_CODE = 'EXPENSE';
    public const ADDITIONAL_EXPENSE_HISTORY_CODE = 'EXPHIS';

    public const DATA_TABLE_REMOVE_KEY = [
        'draw',
        'columns',
        'order',
        'start',
        'length',
        'search'
    ];

    public const SUPPLIER_NEEDED_KEY = [
        'code',
        'name',
        'contact_person',
        'contact_number'
    ];

    public const USER_NEEDED_KEY = [
        'username',
        'name',
        'type'
    ];

    public const ADDITIONAL_EXPENSE_NEEDED_KEY = [
        'code',
        'type'
    ];    

    public const ADDITIONAL_EXPENSE_HISTORY_NEEDED_KEY = [
        'code',
        'type',
        'formatted_amount',
        'formatted_remarks',
        'date',
    ];

    public const RETURN_NEEDED_KEY = [
        'code',
        'product',
        'supplier',
        'details',
        'remarks_chip',
        'status_chip',
        'date',
    ];

    public const DISCOUNT_NEEDED_KEY = [
        'code',
        'name',
        'amount',
        'minimum',
        'timespan',
    ];    
    
    public const ORDER_NEEDED_KEY = [
        'code',
        'cashier',
        'cash2',
        'total_price2',
        'change',
        'total_quantity',
        'date'
    ];
    

    public const BRAND_NEEDED_KEY = [
        'code',
        'name'
    ];

    public const CATEGORY_NEEDED_KEY = [
        'code',
        'name'
    ];

    public const PRODUCT_NEEDED_KEY = [
        'code',
        'name',
        'category',
        'brand',
        'price',
        'product_capital',
        'quantity',
    ];

    public const POS_PRODUCT_NEEDED_KEY = [
        'icon',
        'quantity',
        'addButton'
    ];

    public const INVENTORY_HISTORY_NEEDED_KEY = [
        'code',
        'product',
        'supplier',
        'details',
        'remarks_chip',
        'date'
    ];


    public const DEFAULT_ACTION_BUTTON = "
    <li class='nav-item dropdown' style='list-style: none'>
        <a class='nav-link ' data-toggle='dropdown' href='#' style='color:black !important;'>
            <i class='fa fa-ellipsis-h'></i>
        </a>
        <div class='dropdown-menu'>
            <a class='dropdown-item aEdit' tabindex='-1' href='#'>Edit</a>
            <div class='dropdown-divider'></div>
            <a class='dropdown-item aDelete' tabindex='-1' href='#'>Delete</a>
        </div>
    </li>
    ";   
    public const ORDER_ACTION_BUTTON = "
    <li class='nav-item dropdown' style='list-style: none'>
        <a class='nav-link ' data-toggle='dropdown' href='#' style='color:black !important;'>
            <i class='fa fa-ellipsis-h'></i>
        </a>
        <div class='dropdown-menu'>
            <a class='dropdown-item aEdit' tabindex='-1' href='#'>Edit</a>
            <a class='dropdown-item aPreview' tabindex='-1' href='#'>Preview</a>
            <div class='dropdown-divider'></div>
            <a class='dropdown-item aDelete' tabindex='-1' href='#'>Delete</a>
        </div>
    </li>
    ";
    public const USER_ACTION_BUTTON = "
    <li class='nav-item dropdown' style='list-style: none'>
        <a class='nav-link ' data-toggle='dropdown' href='#' style='color:black !important;'>
            <i class='fa fa-ellipsis-h'></i>
        </a>
        <div class='dropdown-menu'>
            <a class='dropdown-item aEdit' tabindex='-1' href='#'>Edit</a>
            <a class='dropdown-item aReset' tabindex='-1' href='#'>Reset</a>
            <div class='dropdown-divider'></div>
            <a class='dropdown-item aDelete' tabindex='-1' href='#'>Delete</a>
        </div>
    </li>
    ";

    public const PRODUCT_ACTION_BUTTON = "
    <li class='nav-item dropdown' style='list-style: none'>
        <a class='nav-link ' data-toggle='dropdown' href='#' style='color:black !important;'>
            <i class='fa fa-ellipsis-h'></i>
        </a>
        <div class='dropdown-menu'>
        <a class='dropdown-item aEdit' tabindex='-1' href='#'>Edit</a>
        <a class='dropdown-item aInventory' tabindex='-1' href='#'>Inventory</a>
        <a class='dropdown-item aDispose' tabindex='-1' href='#'>Item Disposal</a>
        <a class='dropdown-item aReturn' tabindex='-1' href='#'>Return</a>
        <div class='dropdown-divider'></div>
            <a class='dropdown-item aDelete' tabindex='-1' href='#'>Delete</a>
        </div>
    </li>
    ";

    public const RETURN_UNUSED_ACTION_BUTTON = "
    <li class='nav-item dropdown' style='list-style: none'>
        <a class='nav-link ' data-toggle='dropdown' href='#' style='color:black !important;'>
            <i class='fa fa-ellipsis-h'></i>
        </a>
        <div class='dropdown-menu'>
        <a class='dropdown-item aReject' tabindex='-1' href='#'>Reject</a>
        <a class='dropdown-item aApprove' tabindex='-1' href='#'>Approve</a>
        <div class='dropdown-divider'></div>
            <a class='dropdown-item aDelete' tabindex='-1' href='#'>Delete</a>
        </div>
    </li>
    ";

    public const RETURN_USED_ACTION_BUTTON = "
    <li class='nav-item dropdown' style='list-style: none'>
        <a class='nav-link ' data-toggle='dropdown' href='#' style='color:black !important;'>
            <i class='fa fa-ellipsis-h'></i>
        </a>
        <div class='dropdown-menu'>
            <a class='dropdown-item aDelete' tabindex='-1' href='#'>Delete</a>
        </div>
    </li>
    ";
}