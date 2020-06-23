@extends('layouts.admin')
@section('additional-css')
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <table id="tblItems" class="table posTable">
                            <thead style="display:none;">
                                <tr>
                                    <th style="width: 45%">#</th>
                                    <th style="width: 50%">#</th>
                                    <th style="width: 30%">Name</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div> 
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                           <div class="row">
                            <div class="col-lg-6">
                                <h3>TOTAL DUE:</h4>
                            </div>
                            <div class="col-lg-6">
                                <h3 class="float-right" id="lblTotalDue">₱ 0.00</h4>
                            </div>

                           </div>
                        </div>
                        <div class="card-body">
                            <table class="table posTable table-bordered " id="tblTotalItem">
                                <thead>
                                    <tr>
                                        <th style="width: 5%"></th>
                                        <th style="width: 25%">Description</th>
                                        <th style="width: 15%">Cost/Item</th>
                                        <th style="width: 18%">Amount</th>
                                        <th style="width: 10%">Quantity</th>
                                        <th style="width: 15%">Sub-total</th>
                                    </tr>
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
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <a class="btn btn-app btn-block bg-gradient-danger btn-lg h1" style="color: rgb(238, 237, 234);">
                                            <i class="fas fa-window-close"></i> Cancel
                                        </a>
                                        {{-- <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#modal-lg" id="btnSubmit">
                                            <span><i class="fas fa-shopping-bag"></i> Proceed</span>
                                       </button> --}}
                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <a class="btn btn-app btn-block bg-gradient-success btn-lg h1" style="color: rgb(238, 237, 234);" id="btnSubmit">
                                            <i class="fas fa-shopping-bag"></i> Proceed
                                        </a>
                                    </div>
                                </div>
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
    <script src="{{ asset('/js/admin/pos.js') }}"></script>
@endsection

@section('modal')
<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"> 
             <label class="lead" id="lblAction" style="font-weight:700 !important"> Payables </label>
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
            <div class="row">
                <div class="col-lg-6">
                    <h4>TOTAL DUE:</h4>
                </div>
                <div class="col-lg-6">
                    <h3 class="float-right" id="lblPayTotalDue">₱ 0.00</h4>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-6">
                    <h5>CHANGE:</h5>
                </div>
                <div class="col-lg-6">
                    <h3 class="float-right" id="lblPayChange">₱ 0.00</h4>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="col-lg-12 text-right">
                <a class="btn btn-app btn-block bg-gradient-success btn-lg h1" style="color: rgb(238, 237, 234);" id="btnSaveOrder">
                    <i class="fas fa-cash-register"></i> Pay
                </a>
            </div>
            {{-- <div class="row">
                <div class="col-lg-12">
                    <button type="button" class="btn btn-primary" id="btnSaveOrder">Pay</button>
                </div>
            </div> --}}
        </div>
      </div>
    </div>
  </div>
@endsection
