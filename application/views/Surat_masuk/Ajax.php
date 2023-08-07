<!-- SweetAlert2 -->
<script src="<?php echo base_url();?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>

<script type="text/javascript">
    var save_method; //for save method string
    var table;

    $(document).ready(function(){
     
     table_item =   $('#tabel2').DataTable({ 
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": false,
        "info": true,
        "destroy": true,
        "deferRender": true
      });
   });

    //datatables
    table = $('#tabel_view').DataTable({ 

        "processing": false, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [[4, "desc" ]], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('surat_masuk/ajax_list')?>",
            "type": "POST",
            "dataSrc": function (json) {
              json.draw = json.draw;
              json.recordsTotal = json.show;
              json.recordsFiltered = json.all;
              return json.data;
            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 5  ], //last column
            "orderable": false, //set not orderable
        },
        ],
            "responsive": true,
            "autoWidth": false
    });


  function add_surat()  
  {
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $.post( "<?php echo site_url('surat_masuk/kode')?>", function( data ) 
    {
      $('#id_pengajuan').val(data);
    });
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal-add').modal({
        backdrop: 'static',
        keyboard: false},'show');
  }


  function trackbaru(id)
  {
    $.ajax({
      url : "<?php echo site_url('surat_masuk/histori')?>/" + id,
      type: "POST",
      dataType: "JSON",
      success: function(data)
      {
              $('#nama_lembaga').text(data.data_elemen.nama_lembaga);
              $('#jenis_surat').text(data.data_elemen.jenis_surat);
              $('#nomor_surat').text(data.data_elemen.nomor_pengajuan);
              $('#perihal').text(data.data_elemen.perihal);
              // $('#tgl_upload').text(data.data_elemen.tgl_upload);
              table_item.clear();
              table_item.rows.add($(data.html_item)).draw();
              $('#track').modal('show'); // show bootstrap modal when complete loaded
              $('.modal-title').text(''); // Set title to Bootstrap modal title
      },
         error: function (jqXHR, textStatus, errorThrown)
      {
         alert('Error get data from ajax');
      }
    });
  }

  function lanjutkan(id) //berubah
  {
    $('#form_ljtkn')[0].reset(); // reset form on modals
   
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('surat_masuk/lanjut')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
          $('#id_pengajuan_lanjut').val(data.id_pengajuan);
          $('#nama_lembaga_ljtkn').html(data.nama_lembaga);
          $('#tujuan_ljt').val(data.tujuan);
          $('#jenis_surat_ljtkn').html(data.jenis_surat);
          $('#perihal_ljtkn').html(data.perihal);

          // var status = data.status;
          // if (status == 'k') {
          //   $('#status').val('');
          // } else if(status == 't') { 
          //   $('#status').val('');
          // } else {
          //   $('#status').val('k');
          // }

          $('#lanjutkan').modal('show'); // show bootstrap modal when complete loaded
          $('.modal-title').text('Edit Person'); // Set title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}



  function save()
  {
    $('#btnSave').text('Proses menyimpan...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;
    if(save_method == 'add') {
        url = "<?php echo site_url('surat_masuk/ajax_add')?>";
    } else {
        url = "<?php echo site_url('surat_masuk/ajax_update')?>";
    }

    var formData = new FormData($('#form')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal-add').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    }); 
  }

  function lanjutkan_simpan()
  {
    const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        });
    $('#btnljtkn').text('saving...'); //change button text
    $('#btnljtkn').attr('disabled',true); //set button disable 

    var url;
    url = "<?php echo site_url('surat_masuk/lanjutkan')?>";

    // ajax adding data to database
    var formData = new FormData($('#form_ljtkn')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#lanjutkan').modal('hide');
                Toast.fire({
                    type: 'success',
                    title: 'Data Sukses Disimpan'
                  });
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnljtkn').text('save'); //change button text
            $('#btnljtkn').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnljtkn').text('save'); //change button text
            $('#btnljtkn').attr('disabled',false); //set button enable 

        }
    }); 
  }

  function terima(id)
  {
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('surat_masuk/lanjut')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
          $('#id_pengatar_trm').val(data.id_pengajuan);
          $('#nama_lembaga_trm').html(data.nama_lembaga);
          $('#jenis_surat_trm').html(data.jenis_surat);
          $('#posisi_trm').html(data.posisi);
          $('#posisi_trma').val(data.posisi);
          $('#perihal_trm').html(data.perihal);
          $('#terima').modal('show'); // show bootstrap modal when complete loaded
          $('.modal-title').text('Edit Person'); // Set title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert('Error get data from ajax');
        }
    });
  }

    function diterima()
  {
    const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        });
    $('#btntrm').text('saving...'); //change button text
    $('#btntrm').attr('disabled',true); //set button disable 
    var url;
    url = "<?php echo site_url('surat_masuk/terima')?>";
    var formData = new FormData($('#formdtr')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#terima').modal('hide');
                Toast.fire({
                    type: 'success',
                    title: 'Surat Permohonan Diterima'
                  });
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btntrm').text('save'); //change button text
            $('#btntrm').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btntrm').text('save'); //change button text
            $('#btntrm').attr('disabled',false); //set button enable 
        }
    }); 
  }

   function finish(id)
  {
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('surat_masuk/lanjut')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
          $('#id_pengatar_fnsh').val(data.id_pengajuan);
          $('#nama_lembaga_fnsh').html(data.nama_lembaga);
          $('#jenis_surat_fnsh').html(data.jenis_surat);
          $('#posisi_fnsh').html(data.posisi);
          $('#posisi_finish').val(data.posisi);
          $('#perihal_fnsh').html(data.perihal);
          $('#finish').modal('show'); // show bootstrap modal when complete loaded
          $('.modal-title').text('Edit Person'); // Set title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert('Error get data from ajax');
        }
    });
  }

  function finisf_simpan()
  {
    const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        });
    $('#btntrm').text('saving...'); //change button text
    $('#btntrm').attr('disabled',true); //set button disable 
    var url;
    url = "<?php echo site_url('surat_masuk/finish')?>";
    var formData = new FormData($('#formfnsh')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#finish').modal('hide');
                Toast.fire({
                    type: 'success',
                    title: 'Surat Permohonan Diterima'
                  });
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btntrm').text('save'); //change button text
            $('#btntrm').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btntrm').text('save'); //change button text
            $('#btntrm').attr('disabled',false); //set button enable 
        }
    }); 
  }

  function save_kembalikan()
  {
    $('#btnkembali').text('Proses menyimpan...'); //change button text
    $('#btnkembali').attr('disabled',true); //set button disable 
    var url;
   
        url = "<?php echo site_url('surat_masuk/kembalikan')?>";
   

    var formData = new FormData($('#form_kembali')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#kembalikan').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnkembali').text('save'); //change button text
            $('#btnkembali').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnkembali').text('save'); //change button text
            $('#btnkembali').attr('disabled',false); //set button enable 

        }
    }); 
  }
  
 function kembalikan(id) //berubah
  {
    $('#form_kembali')[0].reset(); // reset form on modals
   
    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('surat_masuk/lanjut')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
          $('#id_pengajuan_kembali').val(data.id_pengajuan);
          $('#nama_lembaga_kembali').html(data.nama_lembaga);
          $('#jenis_surat_kembali').html(data.jenis_surat);
          $('#perihal_kembali').html(data.perihal);
        //   $('#nomor_surat_kembali').text(data.data_elemen.nomor_pengajuan);
            
            table_item.clear();
            table_item.rows.add($(data.html_item)).draw();
          $('#kembalikan').modal('show'); // show bootstrap modal when complete loaded
          $('.modal-title').text('Edit Person'); // Set title to Bootstrap modal title
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

  function reload_table()
  {
      table.ajax.reload(null,false); //reload datatable ajax 
  }

</script>

  <div class="modal fade" id="kembalikan"> 
    <div class="modal-dialog modal-xl">
          <div class="modal-content">
              <div class="modal-header">
                <div class="row">
                    <div class="col-12">
                      <h4>
                        <i class="fas fa-book"></i> Form Kembali Berkas
                      </h4>
                    </div>
                  </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="invoice p-3 mb-3">
                 
                  <!-- info row -->
                  <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                      Pengirim
                      <address>
                        <h4 id="nama_lembaga_kembali"></h4><br>
                        </address>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3 invoice-col">
                      Jenis Surat
                      <address>
                        <h4 id="jenis_surat_kembali" ></h4><br>
                      </address>
                    </div>
                    <div class="col-sm-5 invoice-col">
                     Perihal
                      <address>
                        <h4 id="perihal_kembali" ></h4><br>
                      </address>
                    </div>
                  </div>
                  <div class="row">
                  </div>
                  <div class="row">
                    <div class="card card-info col-12">
                      <div class="card-header">
                        <h3 class="card-title">Kembalikan Pengajuan</h3>
                      </div>
                      <form role="form_kmbl" action="#" id="form_kembali">
                      <div class="card-body">
                        <input type="hidden" name="id_pengajuan_kembali" id="id_pengajuan_kembali">
                        <div class="form-group">
                          <label>Tujuan</label>
                          <select class="form-control"  name="tujuan_kembali">
                                <option>Staff Evaluasi</option>
                          </select>
                           <span class="help-block text-danger"></span>
                        </div>
                         <div class="form-group">
                          <label for="catatan">Catatan</label>
                          <!-- <input type="text" name="status" id="status"> -->
                          <textarea row="3" class="form-control" name="catatan" id="catatan" placeholder="Catatan"> </textarea>
                           <span class="help-block text-danger"></span>
                        </div>
                        
                      </div>
                      <div class="card-footer">
                        <button type="submit" id="btnkembali" onclick="save_kembalikan()" class="btn btn-primary" >Simpan</button>
                      </div>
                      </form>
                    </div>
                  </div><!-- /.row -->
                </div>
              </div>
            </div>
    </div>
  </div>

  <div class="modal fade" id="lanjutkan"> 
    <div class="modal-dialog modal-xl">
          <div class="modal-content">
              <div class="modal-header">
                <div class="row">
                    <div class="col-12">
                      <h4>
                        <i class="fas fa-book"></i> Form Lanjut Berkas
                      </h4>
                    </div>
                  </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="invoice p-3 mb-3">
                 
                  <!-- info row -->
                  <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                      Pengirim
                      <address>
                        <h4 id="nama_lembaga_ljtkn"></h4><br>
                        </address>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3 invoice-col">
                      Jenis Surat
                      <address>
                        <h4 id="jenis_surat_ljtkn" ></h4><br>
                      </address>
                    </div>
                    <div class="col-sm-5 invoice-col">
                     Perihal
                      <address>
                        <h4 id="perihal_ljtkn" ></h4><br>
                      </address>
                    </div>
                  </div>
                  <div class="row">
                  </div>
                  <div class="row">
                    <div class="card card-info col-12">
                      <div class="card-header">
                        <h3 class="card-title">Lanjutkan Pengajuan</h3>
                      </div>
                      <form role="form" action="#" id="form_ljtkn">
                      <div class="card-body">
                        <input type="hidden" name="id_pengajuan_ljtkn" id="id_pengajuan_lanjut">
                        <div class="form-group">
                          <label>Tujuan</label>
                          <select class="form-control"  name="tujuan_ljt">
                             <?php if ($this->session->userdata('level') == '1') {  ?>
                              <option>Kabag Evaluasi</option>
                                 <?php } elseif ($this->session->userdata('level') == '4') { ?>
                                <option>Kasir</option>
                                <!--<option>Kabag Evaluasi</option>-->
                                <option>Lembaga</option>
                                  <?php } elseif ($this->session->userdata('level') == '2') { ?>
                                <option>Kasir</option>
                                <option>Waka Bendahara</option>
                                  <?php } else { }?>
                          </select>
                           <span class="help-block text-danger"></span>
                        </div>
                         <div class="form-group">
                          <label for="catatan">Catatan</label>
                          <!-- <input type="text" name="status" id="status"> -->
                          <textarea row="3" class="form-control" name="catatan" id="catatan" placeholder="Catatan"> </textarea>
                           <span class="help-block text-danger"></span>
                        </div>
                        <div class="form-group">
                          <label for="posisi">Berkas</label>
                          <input type="file" class="form-control" name="file" id="file">
                          <span class="help-block text-danger"></span>
                        </div>
                      </div>
                      <div class="card-footer">
                        <button type="submit" id="btnljtkn" onclick="lanjutkan_simpan()" class="btn btn-primary" >Simpan</button>
                      </div>
                      </form>
                    </div>
                  </div><!-- /.row -->
                </div>
              </div>
            </div>
    </div>
  </div>

  <div class="modal fade" id="modal-add">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="card card-danger">
            <div class="modal-header">
              <h4 class="modal-title">Form Surat Masuk</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
          <div class="modal-body">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title"></h3>
              </div>
                  <!-- form start -->
              <form role="form" action="#" id="form">
              <div class="card-body">
                <div class="row">
                  <div class="col col-6">
                    <div class="form-group">
                      <label for="nomor_surat">Nomor Surat</label>
                      <input type="hidden" class="form-control" name="id_pengajuan" id="id_pengajuan">
                      <input type="text" class="form-control" name="nomor_surat" placeholder="Nomor Surat">
                       <span class="help-block text-danger"></span>
                    </div>    
                  </div>
                  <div class="col col-6">
                    <div class="form-group">
                      <label for="jenis_surat">Jenis Surat</label>
                      <select class="form-control" name="jenis_surat">
                        <option></option>
                        <?php $query = $this->db->get('jenis_surat');
                        $hasil = $query->result();
                        foreach ($hasil as $row) {?>
                        <option value="<?php echo $row->id_jenis_surat; ?>"><?php echo $row->jenis_surat; ?></option>
                        <?php }
                         ?>
                      </select>
                       <span class="help-block text-danger"></span>
                    </div>    
                  </div>
                </div>
                <div class="row">
                  <div class="col col-6">
                    <div class="form-group">
                      <label for="perihal">Perihal</label>
                      <input type="text" class="form-control" name="perihal" placeholder="Perihal">
                       <span class="help-block text-danger"></span>
                    </div>   
                  </div>
                  <div class="col col-6">
                    <div class="form-group">
                      <label for="pengirim">Pengirim</label>
                      <select class="form-control" name="pengirim">
                      <option></option>
                      <?php $query = $this->db->get('lembaga');
                        $hasil = $query->result();
                        foreach ($hasil as $row) {?>
                        <option value="<?php echo $row->id_lembaga; ?>"><?php echo $row->nama_lembaga; ?></option>  
                        <?php }
                         ?>
                      </select>
                       <span class="help-block text-danger"></span>
                    </div>    
                  </div>
                </div>
                <div class="row">
                  <div class="col col-6">
                    <div class="form-group">
                      <label for="posisi">Dilanjutkan Ke</label>
                      <select class="form-control" name="posisi">
                        <!-- <option></option> -->
                        <option>Kabag Evaluasi</option>  
                      <!--   <option>Kasir</option>
                        <option>Kabag AUK</option> -->
                      </select>
                       <span class="help-block text-danger"></span>
                    </div>    
                  </div>
                  <div class="col col-6">
                    <div class="form-group">
                      <label for="posisi">Berkas</label>
                      <input type="file" class="form-control" name="file" id="file">
                      <span class="help-block text-danger"></span>
                    </div>    
                  </div>
                </div>
              </div>
              </form>
            </div> 
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="btnSave" onclick="save()">Save changes</button>
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="track">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
              <div class="modal-header">
                <div class="row">
                    <div class="col-12">
                      <h4>
                        <i class="fas fa-book"></i> Detail Surat
                      </h4>
                    </div>
                  </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="invoice p-3 mb-3">
                 
                  <!-- info row -->
                  <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                      Pengirim
                      <h4 id="nama_lembaga">
                        
                      </h4>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3 invoice-col">
                      Jenis Surat
                      <h4 id="jenis_surat">
                      </h4>
                    </div>
                    <div class="col-sm-5 invoice-col">
                      Perihal 
                      <h4 id="perihal"></h4>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12 table-responsive">
                      <table class="table table-striped" id="tabel2">
                          <thead>
                              <tr>
                                  <th>Tanggal</th>
                                  <th>Posisi</th>
                                  <th>Catatan</th>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                    </div>
                    <!-- /.col -->
                  </div>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class=""></button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
          </div>
        <!-- /.modal-content -->
      </div>
  </div>

  <div class="modal fade" id="terima">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
              <div class="modal-header">
                <div class="row">
                    <div class="col-12">
                      <h4>
                        <i class="fas fa-book"></i> Terima Berkas
                      </h4>
                    </div>
                  </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="invoice p-3 mb-3">
                 
                  <!-- info row -->
                  <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                      Pengirim
                      <h4 id="nama_lembaga_trm">
                        
                      </h4>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-3 invoice-col">
                      Jenis Surat
                      <h4 id="jenis_surat_trm">
                      </h4>
                    </div>
                    <div class="col-sm-5 invoice-col">
                      Perihal 
                      <h4 id="perihal_trm"></h4>
                    </div>
                  </div>
                  <div class="row">
                    <form role="form" action="#" id="formdtr">
                      <input type="hidden" name="id_pengatar_trm" id="id_pengatar_trm">
                      <input type="hidden" name="posisi_trma" id="posisi_trma">
                      <br><br><br>
                    <div class="col-xs-12 table-responsive text-center">
                      <h2>Berkas Telah Diterima di <label id="posisi_trm"></label></h2>
                     </div><!-- /.col -->
                    </form>
                    <!-- /.col -->
                  </div>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class=""></button>
                <button type="submit" id="btntrm" onclick="diterima()" class="btn btn-primary" >Simpan</button>
              </div>
          </div>
        <!-- /.modal-content -->
      </div>
  </div>

    <div class="modal fade" id="finish">
      <div class="modal-dialog modal-xl">
          <div class="modal-content">
              <div class="modal-header">
                <div class="row">
                    <div class="col-12">
                      <h4>
                        <i class="fas fa-book"></i> Finish Berkas
                      </h4>
                    </div>
                  </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="invoice p-3 mb-3">
                 
                  <!-- info row -->
                  <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                      Pengirim
                      <h4 id="nama_lembaga_fnsh">
                        
                      </h4>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-4 invoice-col">
                      Jenis Surat
                      <h4 id="jenis_surat_fnsh">
                      </h4>
                    </div>
                    <div class="col-sm-4 invoice-col">
                      Perihal 
                      <h4 id="perihal_fnsh"></h4>
                    </div>
                  </div>
                  <form role="form" action="#" id="formfnsh">
                    <div class="row">
                      <div class="col-12">
                          <input type="hidden" name="id_pengatar_fnsh" id="id_pengatar_fnsh">
                          <input type="hidden" name="posisi_finish" id="posisi_finish">
                          <br><br>
                        <div class="text-center">
                          <h2>Berkas siap di cairkan</h2>
                        </div><!-- /.col -->
                      </div>
                      <div class="form-group">
                            <label for="posisi">Berkas</label>
                            <input type="file" class="form-control" name="file" id="file">
                            <span class="help-block text-danger"></span>
                        </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class=""></button>
                <button type="submit" id="btntrm" onclick="finisf_simpan()" class="btn btn-primary" >Simpan</button>
              </div>
          </div>
        <!-- /.modal-content -->
      </div>
  </div>

