
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1></h1>
            <?php if($this->session->userdata('level') == '1' or $this->session->userdata('level') == '9'){ ?>
            <button class="btn btn-info"  onclick="add_surat()">Add Surat Masuk</button>
            <?php }else {} ?>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Data Surat Masuk</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
         
          <div class="card">
           
            <div class="card-body">
              
              <div class="table-responsive">
                <table id="tabel_view" class="table  table-sm table-hover table-striped">
                  <thead>
                  <tr>
                    <!-- <th>Nomor</th> -->
                    <th>Pengirim</th>
                    <th>Jenis Surat</th>
                    <th>Perihal</th>
                    <th>Posisi</th>
                    <th>Tanggal</th>
                    <th>File</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                  </tbody>
                  <tfoot>
                  <tr>
                    <!-- <th>Nomor</th> -->
                    <th>Pengirim</th>
                    <th>Jenis Surat</th>
                    <th>Perihal</th>
                    <th>Posisi</th>
                    <th>Tanggal</th>
                    <th>File</th>
                    <th>Aksi
                  </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
  </section>
    <!-- /.content -->
  </div>
