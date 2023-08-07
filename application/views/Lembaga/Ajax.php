
<script type="text/javascript">
    var save_method; //for save method string
    var table;

        $(function () {
          // $("#example1").DataTable();
      table =   $('#tabel_view').DataTable({
          "ajax": {
            "url": "<?php echo site_url('lembaga/data_list')?>",
            "type": "POST"
          },

          "columnDefs": [
            { 
                "targets": [ -1 ], //last column
                "orderable": false, //set not orderable
            },
           
        ],  

          "paging": true,
          "searching": true,
          "ordering": true,
          });
        });

  function add_lembaga()  
  {
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal-add').modal({
        backdrop: 'static',
        keyboard: false},'show');
  }

  function save()
{
    $('#btnSave').text('Proses menyimpan...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;
    if(save_method == 'add') {
        url = "<?php echo site_url('lembaga/ajax_add')?>";
    } else {
        url = "<?php echo site_url('lembaga/ajax_update')?>";
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

  function edit_lembaga(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('lembaga/ajax_edit')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
          $('[name="id_lembaga"]').val(data.id_lembaga);
          $('[name="nama_bidang"]').val(data.nama_bidang);
          $('[name="nama_lembaga"]').val(data.nama_lembaga);
          $('#modal-add').modal('show'); // show bootstrap modal when complete loaded
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

  <div class="modal fade" id="modal-add">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="card card-danger">
            <div class="modal-header">
              <h4 class="modal-title">Data Lembaga</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </div>
          <div class="modal-body">
            <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title"></h3>
              </div>
                  <!-- form start -->
              <form role="form" action="#" id="form">
              <div class="card-body">
                <div class="row">
                  <div class="col col-6">
                    <div class="form-group">
                      <label for="username">Nama Bidang</label>
                      <input type="hidden" class="form-control" name="id_lembaga" >
                      <select class="form-control" name="nama_bidang">
                        <option></option>
                        <option value="1">Bidang DIKTI</option>
                        <option value="2">Bidang DIKJAR</option>
                        <option value="3">Bidang Kepesantrenan</option>
                        <option value="4">Bidang Kamtib</option>
                        <option value="5">Bidang Usaha</option>
                        <option value="6">BPK2M</option>
                        <option value="7">Sekretariat</option>
                        <option value="8">Yayasan</option>
                      </select>
                    </div>    
                  </div>
                  <div class="col col-6">
                    <div class="form-group">
                      <label for="nama_lembaga">Nama Lembaga</label>
                      <input type="text" class="form-control" name="nama_lembaga" placeholder="Nama Lembaga">
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