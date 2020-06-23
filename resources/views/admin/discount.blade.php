@extends('layouts.admin')
@section('additional-css')
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice
        {
            background-color: #007bff !important;
            border-color: #006fe6 !important;
            color: #fff !important;
            padding: 0 10px !important;
            margin-top: .31rem !important;
        }
    </style>
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
                                    <th style="width: 5%"></th>
                                    <th style="width: 15%">#</th>
                                    <th style="width: 25%">Name</th>
                                    <th style="width: 10%">Percentage</th>
                                    <th style="width: 15%">Minimum</th>
                                    <th style="width: 25%">Timespan</th>
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
    <script src="{{ asset('/js/admin/discount.js') }}"></script>
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
                    <div class="col-lg-8 py-0">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" placeholder="Enter Discount name" id="txtName">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 py-0">
                        <div class="form-group">
                            <label>Percentage</label>
                            <input type="number" class="form-control" placeholder="Enter Percentage here" id="txtAmount" max="99">
                        </div>
                    </div>
                    <div class="col-lg-6 py-0">
                        <div class="form-group">
                            <label>Minimum Pieces</label>
                            <input type="number" class="form-control" placeholder="Enter the minimum quantity here" id="txtMinimum">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label>Timespan</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <input type="text" class="form-control pull-right" autocomplete="off" placeholder="Click to set timespan" id="txtTimeSpan">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 py-0">
                        <div class="form-group">
                            <label>Product Included</label>
                            <select id="selProduct" class="form-control select2" multiple="multiple" data-placeholder="Select a Product included"  style="width: 100%;">
                              <option>Alabama</option>
                              <option>Alaska</option>
                              <option>California</option>
                              <option>Delaware</option>
                              <option>Tennessee</option>
                              <option>Texas</option>
                              <option>Washington</option>
                            </select>
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
