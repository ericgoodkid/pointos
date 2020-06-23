<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'Point of Sale') }} | {{ $sTitle }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/timepicker/bootstrap-timepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
  <link rel="icon" href="{{ url('/images/icon.png') }}">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
    label {
      font-weight:500 !important;
    }

    .productTable td:nth-child(7){
      text-align: center;
    }

    .tblInventoryHistpry td:nth-child(6){
      text-align: center;
    }
    
    .select2-selection--single {
      height: 38px !important;
      background-color: #fff !important;
      background-clip: padding-box !important;
      border: 1px solid #ced4da !important;
      border-radius: .25rem !important;
      box-shadow: inset 0 0 0 transparent !important;
    }

    .info-box-icon {
      width: 300px;
      box-shadow:none;
    }

    .info-box {
      padding: 0;
    } 

    .posTable tr td:nth-child(1) {
      width: 450px;
    }    
    
    .posTable tr td:nth-child(2) {
      width: 275px;
    }  

    .posTable tr td:nth-child(3) {
      width: 125px;
    }
    .posTable tr .dataTables_empty {
      width: 750px !important;
    }

  </style>
  @yield('additional-css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" id="btnSidebar"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-cog"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right text-center">
          {{-- <span class="dropdown-item dropdown-header">15 Notifications</span> --}}
          <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-password">
            Change Password
          </a>
          <div class="dropdown-divider"></div>
          <a href="/logout" class="dropdown-item">
            Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/Dashboard" class="brand-link">
      <img src="{{ url('/images/icon.png') }}"
           alt="AdminLTE Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Point of sale</span>
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ url('/images/user.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
        <a href="#" class="d-block">{{ ucwords(session()->get('user')['name']) }}</a>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-layer-group"></i>
              <p>
                Transaction
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/Transaction/POS" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Point on sale</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/Transaction/AdditionalExpense" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Additional Expense</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ $sTitle }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">{{ $sFrom }}</a></li>
              <li class="breadcrumb-item active">{{ $sTitle }}</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      @yield('content')
    </section>
  </div>

</div>
@yield('modal')
<div class="modal fade" id="modal-password">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"> 
           <label class="lead" id="lblAction" style="font-weight:700 !important"> Change </label>
           <label class="lead">Password</label>
           
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
          <form id="formChangePassword">
              {{-- <div class="row">
                  <div class="col-lg-12 pb-0">
                      <div class="form-group">
                          <label>Current Password</label>
                          <input type="password" class="form-control" placeholder="Enter Current Password here" id="txtCurrentPassword" autocomplete="off">
                      </div> 
                  </div>
              </div> --}}
              <div class="row">
                  <div class="col-lg-12 pb-0">
                      <div class="form-group">
                          <label>New Password</label>
                          <input type="password" class="form-control" placeholder="Enter New Password here" id="txtNewPassword" autocomplete="off">
                      </div> 
                  </div>
              </div>
              <div class="row">
                  <div class="col-lg-12 pb-0">
                      <div class="form-group">
                          <label>Confirm Password</label>
                          <input type="password" class="form-control" placeholder="Enter Confirm Password here" id="txtConfirmPassword" autocomplete="off">
                      </div> 
                  </div>
              </div>
          </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-primary" id="btnChangePassword">Save</button>
      </div>
    </div>
  </div>
</div>
<script src="{{ asset('/bower_components/admin-lte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('/bower_components/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/bower_components/admin-lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('/bower_components/admin-lte/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('/bower_components/admin-lte/dist/js/demo.js') }}"></script>
<script src="{{ asset('/bower_components/admin-lte/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('/bower_components/admin-lte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('/bower_components/admin-lte/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('/bower_components/admin-lte/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('/bower_components/admin-lte/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/bower_components/admin-lte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script src="{{ asset('/bower_components/admin-lte/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<script src="{{ asset('/bower_components/axios/dist/axios.min.js') }}"></script>
<script src="{{ asset('/js/admin/tabs.js') }}"></script>
<script>
  const getLayoutConstant = function(){
    const promptMessage = function(oData, bBoth = true) {
      if (oData.bResult === false) {
          promptError(Object.values(oData.aMessage)[0])
          return;
      }

      if (bBoth === false) {
        return;
      }

      promptSuccess();
    }

    const promptError = function(sMessage) {
      toastr.error(sMessage);

    }

    const promptSuccess = function(sMessage = 'Transaction Success') {
      toastr.success(sMessage);
    }

    const createSelectOptions = function (aItem, sTitle = '', bRequired = false) {
      let sRequired = '';
      if (bRequired === true) {
        sRequired = `disabled='disabled'`;
      }
      let sOption = `<option  value='none' selected ${sRequired}> Please select ${sTitle} here  </option>`;

      for (const oItem of aItem) {
          let {code, name} = oItem;
          name = (typeof name === 'undefined' ? oItem.type : oItem.name);
          sOption += `<option value='${code}'> ${name} </option>`;
      }

      return sOption;
    }

    const createMultipleSelectOptions = function (aItem) {
      let sOption = ``;

      for (const oItem of aItem) {
          const {code, name} = oItem;
          sOption += `<option value='${code}'> ${name} </option>`;
      }

      return sOption;
    }

    const getBrandList = async function() {
      return await axios.get('/api/admin/brands').then(oResponse => oResponse.data.aBrand);
    }

    const getCategoryList = async function() {
      return await axios.get('/api/admin/categories').then(oResponse => oResponse.data.aCategory);
    }

    const getSupplierList = async function() {
      return await axios.get('/api/admin/suppliers').then(oResponse => oResponse.data.aSupplier);
    }

    const getProductList = async function() {
      return await axios.get('/api/admin/products').then(oResponse => oResponse.data.aProduct);
    }

    const getDiscountList = async function() {
      return await axios.get('/api/admin/discounts').then(oResponse => oResponse.data.aDiscount);
    }

    const getAdditionalExpenseTypeList = async function() {
      return await axios.get('/api/admin/additionalExpenseTypes').then(oResponse => oResponse.data.aAdditionalExpense);
    }

    const formatMoney = function(sMoney) {
      if (isNaN(sMoney)) {
        return '0.00';
      }
      return sMoney.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }


    const resetSelect2 = function () {
      const oSelect2 = $('.select2');
      oSelect2.val('none')
      oSelect2.trigger('change');  
    }

    const setSelect2Value = function (oSelector, sVal) {
      oSelector.val(sVal)
      oSelector.trigger('change');  
    }

    return {
      sFrom          : '{{ $sFrom }}',
      sTitle         : '{{ $sTitle }}',
      promptMessage  : promptMessage,
      sLoadingIcon   : `<i class="fa fa-spinner fa-spin"></i>`,
      createSelectOptions : createSelectOptions,
      createMultipleSelectOptions : createMultipleSelectOptions,
      getBrandList : getBrandList,
      getSupplierList : getSupplierList,
      getCategoryList : getCategoryList,
      getProductList : getProductList,
      getAdditionalExpenseTypeList : getAdditionalExpenseTypeList,
      resetSelect2 : resetSelect2,
      setSelect2Value : setSelect2Value,
      getDiscountList : getDiscountList,
      formatMoney : formatMoney,
    }
  }();

  $(document).ready(function() {
    $('#btnChangePassword').click(async function() {
      const oItem = {
        sCurrentPassword : $('#txtCurrentPassword').val(),
        sNewPassword : $('#txtNewPassword').val(),
        sConfirmPassword : $('#txtConfirmPassword').val(),
      }

      // if (oItem['sCurrentPassword'].length === 0) {
      //    toastr.error(`Your current password is empty`);
      //    return;
      // }

      if (oItem['sNewPassword'].length === 0) {
         toastr.error(`Your new password is empty`);
         return;
      }

      if (oItem['sConfirmPassword'].length === 0) {
         toastr.error(`Your confirm password is empty`);
         return;
      }

      if (oItem['sConfirmPassword'] !== oItem['sNewPassword']) {
         toastr.error(`Your new password do not match`);
         return;
      }

      const oResult = await axios.post(`/api/admin/user/changepassword`, {
      oItem
      }).then(oResponse => oResponse.data);

      getLayoutConstant.promptMessage(oResult);

      console.log(oResult, typeof oResult, oResult === 1)
      if (oResult === 1) {
          $('#modal-password').modal('hide');
          $('#formChangePassword').trigger('reset');
      }

    
    })

  })
</script>
@yield('additional-script')

</body>
</html>
