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
                            <button type="button" id="btnAdd" class="btn bg-gradient-info btn-md float-right" data-toggle="modal" data-target="#modal-lg">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tblItems" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px"></th>
                                    <th style="width: 15%">#</th>
                                    <th>Type</th>
                                    <th>Amount</th>
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
    <script src="{{ asset('/js/admin/additional_expense_history.js') }}"></script>
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
                    <div class="col-lg-6 py-0">
                        <div class="form-group">
                            <label>Additional Expense Type</label>
                            <select id="selType" class="form-control select2" style="width: 100%;">
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 py-0">
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" class="form-control" placeholder="Enter Amount here" id="txtAmount">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 py-0">
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea id="txtRemark" class="form-control"></textarea>
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
@endsection
