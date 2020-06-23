<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'Point of Sale') }} | Login</title>
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
  
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>Point of Sale</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>
  
        <form action="../../index3.html" method="post">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Username" id="txtValue" value="51">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" id="txtConvert" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="col-lg-12 px-0">
            <button type="button" class="btn btn-primary btn-block" id="btnLogin">Convert</button>
          </div>
        </form>
  
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>

<script src="{{ asset('/bower_components/admin-lte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('/bower_components/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('/bower_components/admin-lte/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('/bower_components/axios/dist/axios.min.js') }}"></script>


<script>
  const oTxtVal = $('#txtValue');
  $('#btnLogin').click(function() {
    let iCount = parseInt(oTxtVal.val(), 10);
    alert(getCode(iCount, ''));
  })

  function getCode(iKey, sCode) {
    const aValue = [
      'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q',
      'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
    ];

    if (iKey < 1) {
        return sCode;
    }

    if (iKey <= 26) {
        return sCode + aValue[iKey - 1];
    }

    const iRemainder = iKey % 26;
    const iFactor = parseInt(iKey / 26, 10);
    let sFirstPart = sCode;
    if (iRemainder === 0) {
      sFirstPart = getCode(iFactor - 1, '');
      return sFirstPart + aValue[25];
    } 

    sFirstPart = getCode(iFactor, sCode);
    return sFirstPart + aValue[iRemainder - 1];
  }

</script>

</body>
</html>
