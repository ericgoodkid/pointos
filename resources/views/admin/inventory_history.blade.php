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
                        <div class="col-lg-2 offset-lg-10">
                            <button type="button" id="btnAdd" class="btn bg-gradient-info btn-md float-right" data-toggle="modal" data-target="#modal-filter">
                                <i class="fas fa-filter"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tblItems" class="table tblInventoryHistpry table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px"></th>
                                    <th style="width: 15%">#</th>
                                    <th>Product</th>
                                    <th>Supplier</th>
                                    <th>Details</th>
                                    <th>Remarks</th>
                                    <th>Date</th>
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
    <script src="{{ asset('/js/admin/inventory_history.js') }}"></script>
@endsection

@section('modal')
<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"> 
             <label class="lead" id="lblAction" style="font-weight:700 !important"> Edit </label>
             <label class="lead">{{ $sTitle }}</label>
             
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
          <button type="button" class="btn btn-primary" id="btnSave">Save</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-remarks">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"> 
             <label class="lead" id="lblCode" style="font-weight:700 !important"> INV00001 </label>
             <label class="lead"> Remarks</label>
             
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <p id="pRemarks">sample</p>
        </div>
     </div>
    </div>
  </div>
  

<div class="modal fade" id="modal-filter">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"> 
             <label class="lead" style="font-weight:700 !important"> Filter </label>
             <label class="lead"> {{$sTitle}} </label>
             
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
           <div class="row">
               <div class="col-lg-12">
                <div class="form-group">
                    <label>Timeline</label>
                    <div class="input-group">
                      <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                      </div>
                      <input type="text" class="form-control pull-right" id="txtTimeSpan">
                    </div>
                  </div>
               </div>
           </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-primary" id="btnSaveFilter">Save</button>
        </div>
      </div>
    </div>
</div>
@endsection
