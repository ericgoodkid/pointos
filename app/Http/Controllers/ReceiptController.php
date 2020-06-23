<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use App;
use PDF;

class ReceiptController extends BaseController
{
    
    public function __construct(Request $oRequest)
    {
        $this->setParams($oRequest->all());
        $this->instantiateOrderProvider();
        $this->instantiateBusinessProvider();
    }

    public function getReceipt(Request $oRequest, $sCode)
    {
        $oOder = $this->oOrderProvider->getOrder(['sCode' => $sCode]);
        if ($oOder['bResult'] === false) {
            return 'not exist';
        }

        $oBusiness = $this->oBusinessProvider->getBusiness();
        $oBusiness = $oBusiness['oItem'];
        $oOrder = $oOder['oItem'];
        // dd($oOrder->toArray());
        return view('receipt.product',  [
            'oOrder' => $oOrder,
            'oBusiness' => $oBusiness,
        ]);
    }

    public function getReceipt2(Request $oRequest, $sCode)
    {
        $oOder = $this->oOrderProvider->getOrder(['sCode' => $sCode]);
        if ($oOder['bResult'] === false) {
            return 'not exist';
        }

        $oBusiness = $this->oBusinessProvider->getBusiness();
        $oBusiness = $oBusiness['oItem'];
        $oOrder = $oOder['oItem'];

        $oPdf = PDF::loadView('receipt.product', [
            'oOrder' => $oOrder,
            'oBusiness' => $oBusiness,
        ]);
        return $oPdf->stream();
        return $oPdf->download('123.pdf');
    }    
    
    public function getReceipt3(Request $oRequest, $sCode)
    {
        $oPdf = App::make('dompdf.wrapper');
        $aCustomPaper = array(0, 0 , 216, 279);
        $oPdf->loadHTML(
            '
            <h6 style="float:left">Hello</h5>
            
            '
        )->setPaper($aCustomPaper, 'portrait');
        return $oPdf->download();
        return $oPdf->stream();
    }

}
