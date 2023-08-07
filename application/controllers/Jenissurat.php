<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenissurat extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Scurity_model');
		$this->load->model('Jenis_model');
	}

	public function index()
	{
		$this->Scurity_model->getsqurity() ;
		$isi['content'] = 'Jenissurat/Jenissurat';
		$isi['ajax'] = 'Jenissurat/Ajax';
		$this->load->view('Template',$isi);
	}

	public function data_list()
	{
		$this->load->helper('url');

		$list = $this->Jenis_model->get_datatables();
		$no =1;
		$data = array();
		foreach ($list as $datanya) {
			
			$row = array();
			$row[] = $no++;
			$row[] = $datanya->jenis_surat;
			//add html for action
			$row[] = '<a type="button" class="btn btn-outline-danger btn-sm" href="#" 
			title="Track" onclick="edit_lembaga('."'".$datanya->id_jenis_surat."'".')"><i class="fas fa-edit" ></i> Edit</a>';
		$data[] = $row;
		}
			$output = array("data" => $data);
		echo json_encode($output);
	}

	public function ajax_add()
	{
		// $this->_validate();
		$data = array(
				'jenis_surat' 	=> $this->input->post('jenis_surat'),
				);

		$simpan = $this->Jenis_model->create('jenis_surat',$data);
		echo json_encode(array("status" => TRUE));
	}

		public function ajax_update()
	{
		// $this->_validate();
		$data = array(
				'jenis_surat' 	=> $this->input->post('jenis_surat'),
			);
		$this->Jenis_model->update(array('id_jenis_surat' => $this->input->post('id_jenis_surat')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		$data = $this->Jenis_model->get_by_id($id);
		echo json_encode($data);
	}
}
