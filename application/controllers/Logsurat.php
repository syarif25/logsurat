<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logsurat extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Scurity_model');
		$this->load->model('Log_model');
	}

	public function index()
	{
		$this->Scurity_model->getsqurity() ;
		$isi['content'] = 'Logsurat/Logsurat';
		$isi['ajax'] = 'Logsurat/Ajax';
		$this->load->view('Template',$isi);
	}

	public function data_list()
	{
		$this->load->helper('url');

		$list = $this->Log_model->get_datatables();
		$no =1;
		$data = array();
		foreach ($list as $datanya) {
			
			$row = array();
			$row[] = $datanya->id_log;
			$row[] = $datanya->id_pengajuan;
            $row[] = $datanya->posisi;
			//add html for action
			$row[] = '<a type="button" class="btn btn-outline-danger btn-sm" href="#" 
			title="Track" onclick="delete_log('."'".$datanya->id_log."'".')"><i class="fas fa-trash" ></i> Hapus</a>';
		$data[] = $row;
		}
		
		// <a type="button" class="btn btn-outline-primary btn-sm" href="#" 
		// 	title="Track" onclick="edit_log('."'".$datanya->id_log."'".')"><i class="fas fa-edit" ></i> Edit</a>

			$output = array("data" => $data);
		echo json_encode($output);
	}

	public function ajax_update()
	{
		// $this->_validate();
		$data = array(
				'jenis_surat' 	=> $this->input->post('jenis_surat'),
			);
		$this->Log_model->update(array('id_jenis_surat' => $this->input->post('id_jenis_surat')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		$data = $this->Log_model->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_delete(){
		$data = array(
			$id_log = $this->input->post('id_log'),
		);
		$this->db->delete('log_surat', array('id_log' => $id_log));
		echo json_encode(array("status" => TRUE));
	} 
}
