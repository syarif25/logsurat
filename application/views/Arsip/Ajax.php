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
            "url": "<?php echo site_url('arsip/ajax_list')?>",
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




  function reload_table()
  {
      table.ajax.reload(null,false); //reload datatable ajax 
  }

</script>

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

