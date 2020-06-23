@extends('layouts.admin')
@section('additional-css')
    <link rel="stylesheet" href="{{ asset('/bower_components/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="row mx-3 mt-3">
                 <div class="col-lg-8">
                     <div class="form-group">
                         <h4>Business Name</h4>
                     <input type="text" class="form-control" placeholder="Enter Business Name " id="txtName" autocomplete="off" value="{{$mBusinessInfo === null ? '' : $mBusinessInfo['name']}}">
                     </div>
                 </div>
            </div>
            <div class="row m-3">
                <div class="col-lg-12">
                    <div class="form-group">
                        <h4>Business Address</h4>
                        <textarea class="form-control" placeholder="Enter Business Address" id="txtAddress" autocomplete="off">{{$mBusinessInfo === null ? '' : $mBusinessInfo['address']}}</textarea>
                    </div>
                </div>
           </div>
           <div class="row m-3">
                <div class="col-lg-2 offset-lg-10">
                    <button type="button" class="btn btn-primary float-right" id="btnSave">Save</button>
                </div>
            </div>
         </div>
    </div>
@endsection

@section('additional-script')
    <script src="{{ asset('/bower_components/admin-lte/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('/bower_components/admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('/js/admin/business.js') }}"></script>
@endsection

@section('modal')

@endsection
