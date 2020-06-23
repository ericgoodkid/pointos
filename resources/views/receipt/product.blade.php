<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ $oOrder->code }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{
      font-family: 'Courier New', Courier, monospace
    }
    .header-name {
      font-size: 10px;
      font-weight: bold;
      width: 100%;
    }
    .header-address {
      font-size: 8px;
      width:100%;
    }
    .row {
      display: flex;
      -ms-flex-wrap: wrap;
      flex-wrap: wrap;
    }
    .text-center {
      text-align: center;
    }
    .text-right {
      text-align: right !important;
    }
    .text-left {
      text-align: left !important;
    }
    .order-item {
      font-size: 8px;
      text-align: left;
    }
    body {
      width: 155px;
    }
    .quotes {
      font-style:italic;
      font-size: 6px;
    }

  </style>
  
</head>
<body class="text-center">
  <div id="target">
  <div class="row">
    <label>----------------</label>
  </div>
    <div class="row" >
      <label class="header-name">{{ $oBusiness->name }}</label>
    </div>
    <div class="row">
      <label class="header-address">{{ $oBusiness->address }}</label>
    </div>
    <div class="row">
      <label class="header-address">Receipt # : {{ $oOrder->code }}</label>
    </div>
    @php
      $iTotalOrderItem = 0;
      $iTotalOrderAmount = 0;
    @endphp

  <div class="row" style="margin-top:5px;">
  </div>
   <label style="font-style:italic;">================</label>
    <table style="width: 100%">
      {{-- <tr class="order-item">
        <td style="width:65px">
          Desc.
        </td>
        <td>
          Qty
        </td>
        <td>
          Price
        </td>
        <td>
          Total
        </td>
        <td>
          Disc.
        </td>
      </tr> --}}
      @foreach($oOrder->getOrderItem as $oOrderItem)
        @php
          $iDiscount = $oOrderItem->discount_percentage;
          $iQuantity = $oOrderItem->quantity;
          $iTotalOrderItem += $iQuantity;
          $sDiscountable = $iDiscount === null ? 'N' : 'Y';
          $iDiscountPercentage = $iDiscount === null ? 1 : $iDiscount / 100; 
          $iPrice = ((float) $oOrderItem->product_price) * $iDiscountPercentage;
          $iTotalPrice = $iPrice * $iQuantity;
          $iTotalOrderAmount += $iTotalPrice;
        @endphp
        <tr class="order-item">
          <td style="width:65px" colspan="4">
            {{ $oOrderItem->product_sku }}
          </td>
        </tr>  
        <tr class="order-item text-right">
          <td style="width:15%">
            {{ $oOrderItem->quantity }}
          </td>
          <td style="width:33%">
            {{ number_format($iPrice, 2) }}
          </td>
          <td style="width:45%">
            {{ number_format($iTotalPrice, 2) }}
          </td>
          <td style="width:8%">
            {{ $sDiscountable }}
          </td>
        </tr>
      @endforeach
    </table>
   <label style="font-style:italic;">================</label>

    <div class="row" style="margin-top:5px;">
      @if ($iTotalOrderItem === 1)
        <label class="order-item">Item Purchased : </label>
      @else
        <label class="order-item">Items Purchased # :</label>
      @endif
      <label class="order-item text-right" style="width:63px;text-align:right !important">{{ $iTotalOrderItem }}</label>
    </div>

    <div class="row" style="margin-top:5px;">
        <label class="order-item text-left">TOTAL &nbsp;&nbsp;:</label>
        <label class="order-item" style="width:111px;text-align:right !important">{{ '₱ ' . number_format($iTotalOrderAmount, 2) }}</label>
    </div>

    <div class="row">
        <label class="order-item text-left">CASH &nbsp;&nbsp; :</label>
        <label class="order-item" style="width:111px;text-align:right !important">{{ '₱ ' . number_format($oOrder->cash, 2) }}</label>
    </div>

    @php
      $iChange = ((float) $oOrder->cash) - $iTotalOrderAmount;
    @endphp

    <div class="row">
        <label class="order-item text-left">CHANGE &nbsp;:</label>
        <label class="order-item" style="width:111px;text-align:right !important">{{ '₱ ' . number_format($iChange, 2) }}</label>
    </div>

    <div class="row" style="margin-top:5px;">
      <label class="order-item text-left">CASHIER :</label>
      <label class="order-item" style="width:111px;text-align:right !important">{{ ucwords($oOrder->getUser->username) }}</label>
    </div>

    <div class="row" style="margin-top:5px;font-family: default;">
      <label class="quotes" style="margin-left:5px">"This serves as your temporary receipt"</label>
    </div>    
    <div class="row" >
      <label class="quotes text-center" style="margin-left:37px">Thank you, Come again</label>
    </div>

    <div class="row" >
      <label>----------------</label>
    </div>
    <div id="editor"></div>
</body>
    <script>
      // window.print();
    </script>
</html>
