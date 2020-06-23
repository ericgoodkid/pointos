@extends('layouts.admin')
@section('additional-css')
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tblItems" class="table tblInventoryHistpry table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px"></th>
                                    <th style="width: 20%">#</th>
                                    <th>Cashier</th>
                                    <th>Amount Paid</th>
                                    <th>Total</th>
                                    <th>Change</th>
                                    <th>Quantity</th>
                                    <th style="width: 15%">Date</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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
    <script src="{{ asset('/js/admin/order_history.js') }}"></script>
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

  <div class="modal fade" id="modal-order">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"> 
             <label class="lead" id="lblCode" style="font-weight:700 !important"> INV00001 </label>
             <label class="lead"> Preview</label>
             
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <table class='table table-bordered' id="tblPreview">
              <thead>
                <th style="width: 25%">Description</th>
                <th style="width: 15%">Cost/Item</th>
                <th style="width: 18%">Amount</th>
                <th style="width: 10%">Quantity</th>
                <th style="width: 15%">Sub-total</th>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                  <tr>
                      <th colspan="1">
                          Totals:
                      </th>
                      <th>
                          ₱ 0.00
                      </th>
                      <th>
                          ₱ 0.00
                      </th>
                      <th>
                          0
                      </th>
                      <th>
                          ₱ 0.00
                      </th>
                  </tr>
              </tfoot>
            </table>
        </div>
     </div>
    </div>
  </div>

<div class="modal fade" id="modal-edit">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"> 
             <label class="lead" style="font-weight:700 !important"> Edit </label>
             <label class="lead"> Order </label>
             
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-7">
                <h4>CASH:</h4>
            </div>
            <div class="col-lg-5">
                <div class="input-group mb-3">
                    <div class="input-group-prepend ">
                      <span class="input-group-text">₱ </span>
                    </div>
                    <input type="text" class="form-control text-right" id="txtPayCash" placeholder="0.00">
                </div>
            </div>
        </div>
          <table class='table table-bordered' id="tblEdit">
            <thead>
              <th style="width: 5%"></th>
              <th style="width: 25%">Description</th>
              <th style="width: 15%">Cost/Item</th>
              <th style="width: 18%">Amount</th>
              <th style="width: 10%">Quantity</th>
              <th style="width: 15%">Sub-total</th>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">
                        Totals:
                    </th>
                    <th>
                        ₱ 0.00
                    </th>
                    <th>
                        ₱ 0.00
                    </th>
                    <th>
                        0
                    </th>
                    <th>
                        ₱ 0.00
                    </th>
                </tr>
            </tfoot>
          </table>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-primary" id="btnSaveEdit">Save</button>
        </div>
      </div>
    </div>
</div>
@endsection
