
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistem Pemantauan Posisi Surat Bendahara</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page" style="">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="<?php echo base_url(); ?>assets/index2.html" class="h1"><b>Bendahara</b> P2S3</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>
      <?= $this->session->flashdata('message') ?>

      <form action="<?php echo base_url('Auth/aksi_login'); ?>" method="post">
        <div class="input-group mb-3">
          <input type="text" name="username"  class="form-control" value="<?= set_value('username') ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          <?= form_error('username', '<small class="text-danger">', '</small>') ?>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" value="<?= set_value('password') ?>" />
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
            <?= form_error('password', '<small class="text-danger">', '</small>') ?>
        </div>
        <div class="row">
          <!-- <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div> -->
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
 <!-- Sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


 <?php if ($this->session->flashdata('login-failed-1')) : ?>
        <script>
            swal.fire({
                icon: "error",
                title: "Gagal!",
                text: "Login gagal, password salah!"
            })
        </script>
    <?php endif; ?>

    <?php if ($this->session->flashdata('login-failed-2')) : ?>
        <script>
            swal.fire({
                icon: "error",
                title: "Gagal!",
                text: "Login gagal, username salah!"
            })
        </script>
    <?php endif; ?>
</body>
</html>
