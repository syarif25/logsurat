<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lembaga extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Scurity_model');
		$this->load->model('Lembaga_model');
	}

	public function index()
	{
		$this->Scurity_model->getsqurity() ;
		$isi['content'] = 'Lembaga/Lembaga';
		$isi['ajax'] = 'Lembaga/Ajax';
		$this->load->view('Template',$isi);
	}

	public function data_list()
	{
		$this->load->helper('url');

		$list = $this->Lembaga_model->get_datatables();
		$no =1;
		$data = array();
		foreach ($list as $datanya) {
			
			$row = array();
			// $row[] = $datanya->nama_bidang;
			if ($datanya->nama_bidang == 1) {
				$row[] = "Bidang DIKTI";
			} elseif ($datanya->nama_bidang ==  2) {
				$row[] = "Bidang Dikjar";
			} elseif ($datanya->nama_bidang == 3) {
				$row[] = "Bidang Kepesantrenan";
			} elseif ($datanya->nama_bidang == 4) {
				$row[] = "Bidang KAMTIB";
			} else {
				$row[] = "Bidang Usaha";
			}

			$row[] = $datanya->nama_lembaga;
			//add html for action
			$row[] = '<a type="button" class="btn btn-outline-danger btn-sm" href="#" 
			title="Track" onclick="edit_lembaga('."'".$datanya->id_lembaga."'".')"><i class="fas fa-edit" ></i> Edit</a>';
		$data[] = $row;
		}
			$output = array("data" => $data);
		echo json_encode($output);
	}

	public function ajax_add()
	{
		// $this->_validate();
		$data = array(
				'nama_bidang' 	=> $this->input->post('nama_bidang'),
				'nama_lembaga' 	=> $this->input->post('nama_lembaga'),
				);

		$simpan = $this->Lembaga_model->create('lembaga',$data);
		echo json_encode(array("status" => TRUE));
	}

		public function ajax_update()
	{
		// $this->_validate();
		$data = array(
				'nama_bidang' 	=> $this->input->post('nama_bidang'),
				'nama_lembaga' 	=> $this->input->post('nama_lembaga'),
			);
		$this->Lembaga_model->update(array('id_lembaga' => $this->input->post('id_lembaga')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		$data = $this->Lembaga_model->get_by_id($id);
		echo json_encode($data);
	}
}
