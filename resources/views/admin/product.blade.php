@extends('layouts.admin')
@section('additional-css')
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6 float-left">
                                <button type="button" id="btnUpload" class="btn bg-gradient-primary btn-md" data-toggle="modal" data-target="#modal-upload">
                                    <i class="fas fa-upload"></i>
                                </button>
                                <button type="button" id="btnDownload" class="btn bg-gradient-success btn-md">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                            <div class="col-lg-6 float-right">
                                <button type="button" id="btnAdd" class="btn bg-gradient-info btn-md float-right" data-toggle="modal" data-target="#modal-lg">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tblItems" class="table productTable table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px"></th>
                                    <th style="width: 15%">#</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Price</th>
                                    <th>Capital</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('additional-script')
    <script src="{{ asset('/bower_components/admin-lte/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('/bower_components/admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('/js/admin/product.js') }}"></script>
@endsection

@section('modal')
<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"> 
             <label class="lead" id="lblAction" style="font-weight:700 !important"> Add </label>
             <label class="lead">{{ $sTitle }}</label>
             
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="formItem">
                <div class="row">
                    <div class="col-lg-8 pb-0">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" class="form-control" placeholder="Enter Product Name here" id="txtName">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6  py-0">
                        <div class="form-group">
                            <label>Category</label>
                            <select id="selCategory" class="form-control select2" style="width: 100%;">
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6  py-0">
                        <div class="form-group">
                            <label>Brand</label>
                            <select id="selBrand" class="form-control select2" style="width: 100%;">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 py-0">
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" class="form-control" placeholder="Enter Price here" id="txtPrice">
                        </div>
                    </div>
                    <div class="col-lg-6  py-0">
                        <div class="form-group">
                            <label>Low Level</label>
                            <input type="number" class="form-control" placeholder="Enter Low Level number here" id="txtLowLevel">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6  py-0">
                        <div class="form-group">
                            <label>Barcode</label>
                            <input type="text" class="form-control" placeholder="Scan barcode or manually enter here" id="txtBarcode">
                        </div>
                    </div>
                    <div class="col-lg-6 py-0">
                        <div class="form-group">
                            <label>SKU</label>
                            <input type="text" class="form-control" placeholder="Enter SKU here" maxlength="15" id="txtSku">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-primary" id="btnSave">Save</button>
        </div>
      </div>
    </div>
  </div>
<div class="modal fade" id="modal-inventory">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title"> 
            <label class="lead" style="font-weight:700 !important"> Inventory </label>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <form id="formInventoryItem">
            <div class="row">
                <div class="col-lg-6 pb-0">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" class="form-control" id="txtInvName" disabled>
                    </div>
                </div>
                <div class="col-lg-6 py-0">
                    <div class="form-group">
                        <label>Supplier</label>
                        <select id="selInvSupplier" class="form-control select2" style="width: 100%;">
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 py-0">
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" class="form-control" placeholder="Enter Quantity here" id="txtInvQuantity">
                    </div>
                </div>
                <div class="col-lg-6  py-0">
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" class="form-control" placeholder="Enter Price here" id="txtInvPrice">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12  py-0">
                    <div class="form-group">
                        <label>Remarks</label>
                        <input type="text" class="form-control" placeholder="Enter Remarks here" id="txtInvRemarks">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-primary" id="btnInventorySave">Save</button>
    </div>
    </div>
</div>
</div>
<div class="modal fade" id="modal-dispose">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"> 
             <label class="lead" style="font-weight:700 !important"> Item </label>
             <label class="lead"> Disposal</label>
             
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
           <form id="formDisposal">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Product</label>
                            <input type="text" class="form-control" disabled placeholder="Enter Remarks here" id="txtDisName">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" class="form-control" placeholder="Enter Quantity here" id="txtDisQuantity">
                        </div>
                    </div>
                </div>
           </form>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-primary" id="btnDisSave">Save</button>
        </div>
     </div>
    </div>
  </div>

  <div class="modal fade" id="modal-return">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"> 
                <label class="lead" style="font-weight:700 !important"> Return Item </label> 
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="formReturnItem">
                <div class="row">
                    <div class="col-lg-8 pb-0">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" class="form-control" id="txtRetName" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 py-0">
                        <div class="form-group">
                            <label>Supplier</label>
                            <select id="selRetSupplier" class="form-control select2" style="width: 100%;">
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 py-0">
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" class="form-control" placeholder="Enter Quantity here" id="txtRetQuantity">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12  py-0">
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" class="form-control" placeholder="Enter Remarks here" id="txtRetRemarks">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-primary" id="btnReturnSave">Save</button>
        </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-upload">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"> 
                <label class="lead" style="font-weight:700 !important"> </label> Upload Products
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="formReturnItem">
                <div class="row">
                    <div class="col-lg-12 pb-0">
                        <label for="exampleInputFile">CSV File</label>
                        <div class="input-group">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="inputCsv">
                            <label class="custom-file-label" for="inputCsv">Choose file</label>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <table class="table table-bordered d-none" id="tblUploadResult">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Messages</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-primary" id="btnUploadSave">Save</button>
        </div>
        </div>
    </div>
</div>
@endsection
