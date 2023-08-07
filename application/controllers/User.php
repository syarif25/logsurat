<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Scurity_model');
		$this->load->model('User_model');
	}

	public function index()
	{
		$this->Scurity_model->getsqurity() ;
		$isi['content'] = 'User/User';
		$isi['ajax'] = 'User/Ajax';
		$this->load->view('Template',$isi);
	}

	public function data_list()
	{
		$this->load->helper('url');

		$list = $this->User_model->get_datatables();
		$no =1;
		$data = array();
		foreach ($list as $datanya) {
			
			$row = array();
			$row[] = $datanya->username;
			$row[] = $datanya->jabatan;
			$row[] = $datanya->nomor_hp;
			//add html for action
			$row[] = '<a type="button" class="btn btn-outline-danger btn-sm" href="#" 
			title="Track" onclick="edit_user('."'".$datanya->id_user."'".')"><i class="fas fa-edit" ></i> Edit</a>';
		$data[] = $row;
		}
			$output = array("data" => $data);
		echo json_encode($output);
	}

	public function ajax_add()
	{
		// $this->_validate();
		$data = array(
				'username' 		=> $this->input->post('username'),
				'nomor_hp' 		=> $this->input->post('nomor_hp'),
				'jabatan' 		=> $this->input->post('jabatan'),
				'level' 		=> $this->input->post('jabatan'),
				'password' 		=> password_hash($this->input->post('password'), PASSWORD_DEFAULT),
				);

		$simpan = $this->User_model->create('user',$data);
		echo json_encode(array("status" => TRUE));
	}

		public function ajax_update()
	{
		// $this->_validate();
		$data = array(
				'username' 		=> $this->input->post('username'),
				'nomor_hp' 		=> $this->input->post('nomor_hp'),
				'jabatan' 		=> $this->input->post('jabatan'),
				'level' 		=> $this->input->post('level'),
				'password' 		=> password_hash($this->input->post('password'), PASSWORD_DEFAULT),
			);
		$this->User_model->update(array('id_user' => $this->input->post('id_user')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_edit($id)
	{
		$data = $this->User_model->get_by_id($id);
		echo json_encode($data);
	}
}
