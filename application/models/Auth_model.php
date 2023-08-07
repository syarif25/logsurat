<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model{	
	
	public function m_register() {

        $data = array('id_user' =>$this->input->post('id_user'));

        return $this->db->insert('user',$data);

	}

     public function m_cek_mail() {

     return $this->db->get_where('mahasiswa',array('nik' => $this->input->post('nik')));

     }	

}


	
