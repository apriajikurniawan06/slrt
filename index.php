
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SLRT Login</title>
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="theme-color" content="#f8fafc" />
    <link rel="manifest" href="manifest.json">
    <link rel="apple-touch-icon" href="icons/slrt1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQ00pDgOa6rfB6nU0Q8bZZ4p8Fz8aPtaa1Yk2xkXzY6p0t5P6T5t5T5t5" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/jpg/png" href="logo/slrt.png">


  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page shadow-lg" style="background-color: #ffffff;">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="mt-3 text-center">
      <img src="logo/slrt.png" width="50px" height="50px">
    </div>
    <div class="card-body">
      <h5 class="login-box-msg">SLRT KOTA TANJUNGBALAI</h5>

      <form action="cek_login.php" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Username" name="username" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>" autofocus required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        
 
        <div class="input-group mb-3">
          <input
            type="password"
            class="form-control"
            placeholder="Password"
            name="password"
            id="passwordField"
            value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; ?>"
            required
          />
          <div class="input-group-append">
            <span class="input-group-text">
              <i class="fa-solid fa-eye" id="togglePassword" style="cursor: pointer;"></i>
            </span>
          </div>
        </div>

        <!-- Script toggle -->
        <script>
          document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('passwordField');

            togglePassword.addEventListener('click', function () {
              const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
              passwordField.setAttribute('type', type);
              this.classList.toggle('fa-eye');
              this.classList.toggle('fa-eye-slash');
            });
          });
        </script>



        <div class="input-group mb-3">
            <select id="level" name="level" class="form-control" required>
                <option value="" selected disabled>Pilih Jabatan</option>
                <option value="fasilitator">Fasilitator</option>
                <option value="tksk">TKSK</option>
                <option value="operator">Operator</option>
                <option value="koordinator">Koordinator</option>
                <option value="sekretaris">Sekretaris</option>
                <option value="manajer">Manajer</option>
            </select>
            <div class="input-group-append">
            <div class="input-group-append">
                <div class="input-group-text">
                <span class="fas fa-user-tag"></span>
                </div>
            </div>
            </div>
        </div>


          <div class="d-flex justify-content-center mt-2">
            <div class="col-12 col-md-12 col-lg-12">
                <button type="submit" id="btn" name="simpan" value="LOGIN" class="btn btn-primary btn-block">LOGIN</button>
            </div>
        </div>


      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/dist/js/adminlte.min.js"></script>
</body>
</html>
