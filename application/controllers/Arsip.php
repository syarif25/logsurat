<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Arsip extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Scurity_model');
		$this->load->model('Surat_model');
		$this->load->library('Pdf'); 
	}

	public function index()
	{
		$this->Scurity_model->getsqurity() ;
		$isi['content'] = 'Arsip/Arsip';
		$isi['ajax'] = 'Arsip/Ajax';
		$this->load->view('Template',$isi);
	}

public function ajax_list()
  {
    $model = $this->Surat_model->arsip_filter();
    $list = $model['result'];
    $count_all = $model['result'];
    $count_filtered = $model['count_filtered'];
    $count_all = $model['count_all'];

    $data = array();
    foreach ($list as $datanya) {
      
      $row = array();
       $row[] = $datanya->nomor_pengajuan;
      $row[] = $datanya->nama_lembaga;
      $row[] = $datanya->jenis_surat;  
      $row[] = $datanya->perihal;
      $row[] = '<b>'.$datanya->posisi.'</b> <br> <small>'.$this->date_lengkap($datanya->tgl_log).'</small>';
	  if ($datanya->file == '') { 
		$row[] = '<a href='.base_url('Upload/kosong.pdf').' target="blank" title="File Pengantar" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf"></i> Berkas</a>';
	 	} else {
			$row[] = '<a href='.base_url('Upload/'.$datanya->file).' target="blank" title="File Pengantar" class="btn btn-outline-danger btn-sm"><i class="fas fa-file-pdf"></i> Berkas</a>';
		} 
    //   $row[] = $this->date_lengkap($datanya->tgl_log);
      //add html for action

      	if ($datanya->posisi == 'Kasir' || $datanya->posisi == 'Lembaga') { //jika finish
      		$row[] = '<div class="mailbox-controls">
                <div class="btn-group">
				  <button type="button" class="btn btn-success btn-sm"  title="Finish" ><i class="fa fa-check"></i> Finish</button>
				</div>';
      	} else {
          $row[] = '<div class="mailbox-controls">
                <div class="btn-group">
				  <button type="button" class="btn btn-warning btn-sm"  title="Proses" ><i class="fa fa-spinner"></i> Proses</button>
			  	</div>';
      	}
		$row[] = '<div class="mailbox-controls">
		  			<div class="btn-group">
						<button type="button" class="btn btn-outline-info btn-sm"  href="javascript:void(0)" title="Lacak Posisi Surat" onclick="trackbaru('."'".$datanya->id_pengajuan."'".')"><i class="fa fa-search-plus"></i> Lihat</button>
						</div>
					</div>';
    
    
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
					$html_item .= '<td><h6>Surat dikembalikan ke Lembaga (Finish) </h6></td>';
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

}
