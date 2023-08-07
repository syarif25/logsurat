<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_masuk extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Scurity_model');
		$this->load->model('Surat_model');
		$this->load->helper('string');
		$this->load->library('Pdf'); 
	}

	public function index()
	{
		$this->Scurity_model->getsqurity() ;
		$isi['content'] = 'Surat_masuk/Surat_masuk';
		$isi['ajax'] = 'Surat_masuk/Ajax';
		$this->load->view('Template',$isi);
	}

public function ajax_list()
  {
    $model = $this->Surat_model->queri_filter();
    $list = $model['result'];
    $count_all = $model['result'];
    $count_filtered = $model['count_filtered'];
    $count_all = $model['count_all'];

    $data = array();
    foreach ($list as $datanya) {
      
      $row = array();
      // $row[] = $datanya->nomor_pengajuan;
      $row[] = $datanya->nama_lembaga;
      $row[] = $datanya->jenis_surat;  
      $row[] = $datanya->perihal;
      $row[] = '<b>'.$datanya->posisi.'</b>';
      $row[] = $this->date_lengkap($datanya->tgl_log);
	  if ($datanya->file == '') { 
		$row[] = '<a href='.base_url('Upload/kosong.pdf').' target="blank" title="File Pengantar" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf"></i> Berkas</a>';
	 	} else {
			$row[] = '<a href='.base_url('Upload/'.$datanya->file).' target="blank" title="File Pengantar" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf"></i> Berkas</a>';
		} 
	  //add html for action

      	if ($this->session->userdata('level') == '2' and $datanya->status == 'k') { //kabag evaluasi
      		$row[] = '<div class="mailbox-controls">
                  <div class="btn-group">
                    <a type="button" class="btn btn-outline-success btn-sm" href="javascript:void(0)" title="Lanjutkan" onclick="terima('."'".$datanya->id_pengajuan."'".')"><i class="fas fa-check"></i> Terima Surat</a>
                   </div>
                </div>';
      	} elseif (($this->session->userdata('level') == '2' and $datanya->status == 't') or $this->session->userdata('level') == '2' and $datanya->status == '' ) {
      		 $row[] = '<div class="mailbox-controls">
                  <div class="btn-group">
                    <button type="button" class="btn btn-outline-info btn-sm"  href="javascript:void(0)" title="Lacak Posisi Surat" onclick="trackbaru('."'".$datanya->id_pengajuan."'".')"><i class="fa fa-search-plus"></i> Lacak</button>
                    <a type="button" class="btn btn-outline-success btn-sm" href="javascript:void(0)" title="Lanjutkan" onclick="lanjutkan('."'".$datanya->id_pengajuan."'".')"><i class="fas fa-share"></i> Lanjutkan</a>
                    <a type="button" class="btn btn-outline-danger btn-sm" href="javascript:void(0)" title="Kembalikan" onclick="kembalikan('."'".$datanya->id_pengajuan."'".')"><i class="fas fa-exclamation-triangle"></i> Kembalikan</a>
                   </div>
                </div>';
			} elseif ($this->session->userdata('level') =='1' and $datanya->posisi == 'Staff Evaluasi' and $datanya->status == 'KMB' ) { 
				$row[] = '<div class="mailbox-controls">
				  <div class="btn-group">
					<button type="button" class="btn btn-outline-danger btn-sm"  href="javascript:void(0)" title="Lacak Posisi Surat" onclick="trackbaru('."'".$datanya->id_pengajuan."'".')"><i class="fa fa-search-plus"></i> Lacak</button>&nbsp;
					<a type="button" class="btn btn-outline-warning btn-sm" href="surat_masuk/catatan" title="Catatan" ><i class="fas fa-exclamation-triangle"></i> Catatan</a>
					<a type="button" class="btn btn-outline-success btn-sm" href="javascript:void(0)" title="Lanjutkan" onclick="lanjutkan('."'".$datanya->id_pengajuan."'".')"><i class="fas fa-exclamation-triangle"></i> Lanjutkan</a>
				   </div>
				</div>';
			} elseif ($this->session->userdata('level') =='3') { //kasir
      		 $row[] = '<div class="mailbox-controls">
                  <div class="btn-group">
                  	<button type="button" class="btn btn-outline-danger btn-sm"  href="javascript:void(0)" title="Lacak Posisi Surat" onclick="trackbaru('."'".$datanya->id_pengajuan."'".')"><i class="fa fa-search-plus"></i> Lacak</button>&nbsp;
                    <a type="button" class="btn btn-outline-success btn-sm" href="javascript:void(0)" title="Finish" onclick="finish('."'".$datanya->id_pengajuan."'".')"><i class="fas fa-check"></i> Finish</a>
                   </div>
                </div>';
      	} elseif ($this->session->userdata('level') =='4') { 
      		  $row[] = '<div class="mailbox-controls">
                  <div class="btn-group">
                    <button type="button" class="btn btn-outline-info btn-sm"  href="javascript:void(0)" title="Lacak Posisi Surat" onclick="trackbaru('."'".$datanya->id_pengajuan."'".')"><i class="fa fa-search-plus"></i> Lacak</button>
                    <a type="button" class="btn btn-outline-success btn-sm" href="javascript:void(0)" title="Lanjutkan" onclick="lanjutkan('."'".$datanya->id_pengajuan."'".')"><i class="fas fa-share"></i> Lanjutkan</a>
                    <a type="button" class="btn btn-outline-danger btn-sm" href="javascript:void(0)" title="Kembalikan" onclick="kembalikan('."'".$datanya->id_pengajuan."'".')"><i class="fas fa-exclamation-triangle"></i> Kembalikan</a>
                   </div>
                </div>';
      	} elseif ($this->session->userdata('level') =='1' and $datanya->posisi == 'Staff Umum') { 
      		  $row[] = '<div class="mailbox-controls">
                  <div class="btn-group">
                    <button type="button" class="btn btn-outline-danger btn-sm"  href="javascript:void(0)" title="Lacak Posisi Surat" onclick="trackbaru('."'".$datanya->id_pengajuan."'".')"><i class="fa fa-search-plus"></i> Lacak</button>&nbsp;
                    <a type="button" class="btn btn-outline-success btn-sm" href="javascript:void(0)" title="Lanjutkan" onclick="lanjutkan('."'".$datanya->id_pengajuan."'".')"><i class="fas fa-exclamation-triangle"></i> Lanjutkan</a>
                   </div>
                </div>';
      	} elseif ($this->session->userdata('level') =='1' and $datanya->posisi == 'Staff Evaluasi') { 
      		  $row[] = '<div class="mailbox-controls">
                  <div class="btn-group">
                    <button type="button" class="btn btn-outline-danger btn-sm"  href="javascript:void(0)" title="Lacak Posisi Surat" onclick="trackbaru('."'".$datanya->id_pengajuan."'".')"><i class="fa fa-search-plus"></i> Lacak</button>&nbsp;
                    <a type="button" class="btn btn-outline-success btn-sm" href="javascript:void(0)" title="Lanjutkan" onclick="lanjutkan('."'".$datanya->id_pengajuan."'".')"><i class="fas fa-exclamation-triangle"></i> Lanjutkan</a>
                   </div>
                </div>';
      	} else {
          $row[] = '<div class="mailbox-controls">
                <div class="btn-group">
				  <button type="button" class="btn btn-warning btn-sm"  title="Proses" ><i class="fa fa-spinner"></i> Proses</button>
			  	</div>';
      	}
    
    
      $data[] = $row;
    }

    $output = array(
       "draw" => $_POST['draw'],
       "show" => $count_filtered,
       "all" => $count_all,
       "data" => $data,
      );
    //output to json format
    echo json_encode($output);
  }
	public function ajax_add()
	{
		$this->_validate();
		$timezone1 = time() + (60 * 60 * 7);
		$data = array(
				'id_pengajuan' 		=> $this->input->post('id_pengajuan'),
				'id_lembaga' 		=> $this->input->post('pengirim'),
				'id_jenis_surat' 	=> $this->input->post('jenis_surat'),
				'nomor_pengajuan' 	=> $this->input->post('nomor_surat'),
				'perihal' 			=> $this->input->post('perihal'),
				'tgl_masuk' 		=> gmdate('Y-m-d H:i:s', $timezone1),
				);
		$simpan = $this->db->insert('pengajuan',$data);

		$data_log = array(
				'id_pengajuan' 	=> $this->input->post('id_pengajuan'),
				'posisi' 		=> $this->input->post('posisi'),
				'status' 		=> 'k',
				'file' 			=> '',
				'tgl_log' 		=> gmdate('Y-m-d H:i:s', $timezone1),
				);

		$simpan = $this->db->insert('log_surat',$data_log);
		echo json_encode(array("status" => TRUE));
	}

	public function _do_upload()
	{
		$date = new DateTime();
		$timezone = time() + (60 * 60 * 7);
		$config['upload_path']          = 'Upload/';
        $config['allowed_types']        = 'pdf';
        $config['max_size']             = 0; //set max size allowed in Kilobyte
        $config['file_name']            = random_string('alnum',50).$date->getTimestamp(); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('file')) //upload and validate
        {
            $data['inputerror'][] = 'file';
			$data['error_string'][] = 'Upload error: File harus PDF  '; //show ajax error
			$data['status'] = FALSE;
			echo json_encode($data);
			exit();
		}
		return $this->upload->data('file_name');
	}

	public function kode()
	{
		$data = $this->Surat_model->kode();
		echo $data;
	}

	public function lanjut($id)
	{
		$data = $this->Surat_model->get_id($id);
		echo json_encode($data);
	}

		public function lanjutkan()
	{
		// $this->_validate();
		$timezone1 = time() + (60 * 60 * 7);
		$data_log = array(
				'id_pengajuan' 	=> $this->input->post('id_pengajuan_ljtkn'),
				'posisi' 		=> $this->input->post('tujuan_ljt'),
				'status' 		=> "",
				'catatan' 		=> $this->input->post('catatan'),
				'file' 			=> '',
				'tgl_log' 		=> gmdate('Y-m-d H:i:s', $timezone1),
				);

		if(!empty($_FILES['file']['name']))
		{
			$upload = $this->_do_upload();
			$data_log['file'] = $upload;
		}

		$simpan = $this->db->insert('log_surat',$data_log);
		echo json_encode(array("status" => TRUE));
	}
	
	public function kembalikan()
	{
		// $this->_validate();
		$timezone1 = time() + (60 * 60 * 7);
		$data_log = array(
		        'id_log'        => '',
				'id_pengajuan' 	=> $this->input->post('id_pengajuan_kembali'),
				'posisi' 		=> $this->input->post('tujuan_kembali'),
				'status' 		=> "KMB",
				'catatan' 		=> $this->input->post('catatan'),
				'tgl_log' 		=> gmdate('Y-m-d H:i:s', $timezone1),
				);
		$simpan = $this->db->insert('log_surat',$data_log);
		echo json_encode(array("status" => TRUE));
	}


	public function terima()
	{
		$timezone = time() + (60 * 60 * 7);
		$data = array(
				'id_pengajuan' 	=> $this->input->post('id_pengatar_trm'),
				'posisi' 		=> $this->input->post('posisi_trma'),
				'status' 		=> 't',
				'tgl_log' 		=> gmdate('Y-m-d H:i:s', $timezone),
				);
		$simpan = $this->db->insert('log_surat',$data);
		echo json_encode(array("status" => TRUE));
	}

	public function finish()
	{
		$timezone = time() + (60 * 60 * 7);
		$data_log = array(
				'id_pengajuan' 	=> $this->input->post('id_pengatar_fnsh'),
				'posisi' 		=> $this->input->post('posisi_finish'),
				'status' 		=> 'fn',
				'file' 			=> '',
				'tgl_log' 		=> gmdate('Y-m-d H:i:s', $timezone),
				);
		if(!empty($_FILES['file']['name']))
		{
			$upload = $this->_do_upload();
			$data_log['file'] = $upload;
		}
		$simpan = $this->db->insert('log_surat',$data_log);
		echo json_encode(array("status" => TRUE));
	}

	public function histori($id)
	{
		$this->load->helper('url');
		$data_elemen = $this->Surat_model->get_track($id);
		$list = $this->Surat_model->get_tracklagi($id);
		$data = array();
		$html_item = '';
		foreach ($list as $sow) {
			$html_item .= '<tr>';
				$html_item .= '<td><h6>'.$this->date_lengkap($sow->tgl_log).'</h6></td>';
				
				if ($sow->posisi == 'Kabag Evaluasi' and $sow->status == 'k') {
					$html_item .= '<td><h6>Surat dikirimkan ke Kabag Evaluasi</h6></td>';
				} elseif ($sow->posisi == 'Kabag Evaluasi' and $sow->status == 't' ) {
					$html_item .= '<td><h6>Surat diterima dan sedang dikoreksi Kabag Evaluasi</h6></td>';
             	}  elseif ($sow->posisi == 'Kasir' and $sow->status == 'fn' ) {
					$html_item .= '<td><h5 class="text-bold">Surat siap dicairkan !!</h5></td>';
             	} elseif ($sow->posisi == 'Kasir') {
					$html_item .= '<td><h6>Surat Diteruskan ke Kasir </h6></td>';
             	} elseif ($sow->posisi == 'Kabag Evaluasi' and $sow->status == '' ) {
					$html_item .= '<td><h6>Surat diteruskan ke Kabag Evaluasi</h6></td>';
             	} elseif ($sow->posisi == 'Waka Bendahara' ) {
					$html_item .= '<td><h6>Surat diteruskan ke Wakil Bendahara</h6></td>';
             	}  elseif ($sow->posisi == 'Staff Umum' and $sow->status == 'KMB' ) {
					$html_item .= '<td><h6>Surat dikembalikan ke Staff Umum </h6></td>';
             	} elseif ($sow->posisi == 'Staff Evaluasi' and $sow->status == 'KMB' ) {
					$html_item .= '<td><h6>Surat dikembalikan ke Staff Umum </h6></td>';
             	} elseif ($sow->posisi == 'Lembaga') {
					$html_item .= '<td><h6>Surat dikembalikan ke Lembaga (Finish)</h6></td>';
             	} else {
             		$html_item .= '<td><h6>Surat Hilang</h6></td>';
             	}

				$html_item .= '<td><h6>'.$sow->catatan.'</h6></td>';
			
        	$html_item .= '</tr>';
		}
		$this->output->set_output(json_encode(array("data_elemen" => $data_elemen, "html_item" => $html_item)));
	}

	function date_lengkap($date)
	{
		$tgl = date_create($date);
		return date_format($tgl, "d/m/y H:i:s");
	}

	public function catatan()
	{
		$this->load->view('Surat_masuk/Catatan');
	}

	public function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		
		if($this->input->post('nomor_surat') == '')
		{
			$data['inputerror'][] = 'nomor_surat';
			$data['error_string'][] = 'Harus diisi';
			$data['status'] = FALSE;
		}


		if($this->input->post('jenis_surat') == '')
		{
			$data['inputerror'][] = 'jenis_surat';
			$data['error_string'][] = 'Harus dipilih';
			$data['status'] = FALSE;
		}

		if($this->input->post('perihal') == '')
		{
			$data['inputerror'][] = 'perihal';
			$data['error_string'][] = 'Harus diisi';
			$data['status'] = FALSE;
		}

		if($this->input->post('pengirim') == '')
		{
			$data['inputerror'][] = 'pengirim';
			$data['error_string'][] = 'Harus dipilih';
			$data['status'] = FALSE;
		}

		if($this->input->post('posisi') == '')
		{
			$data['inputerror'][] = 'posisi';
			$data['error_string'][] = 'Harus dipilih';
			$data['status'] = FALSE;
		}		

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
}
