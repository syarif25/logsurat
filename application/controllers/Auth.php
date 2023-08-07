<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function index()
	{
		$this->load->view('Login');
		$this->load->model('Auth_model');
	}

	function aksi_login(){
		$username 		= $this->input->post('username');
		$password 	= $this->input->post('password');
		// $level 		= $this->input->post('level');

		
			$user = $this->db->get_where('user', ['username' => $username])->row_array();
		

		if ($user) {
            // jika password yg diinput sesuai dgn didatabase
            if (password_verify($password, $user['password'])) {
              
                    $data['username']   = $user['username'];
                    // $data['stts']  = 'Dosen';
                    $data['level']  = $user['level'];
                    $data['nama']  = $user['nama_lengkap'];
                    $this->session->set_userdata($data);
                
		 	 redirect('Surat_masuk');
                
        } else {
                // jika password yg diinput tidak sesuai dengan didatabase
                $this->session->set_flashdata('login-failed-1', 'Gagal');
                redirect('Auth');
            }
        } else {
            // jika username dan passsword salah
            $this->session->set_flashdata('login-failed-2', 'Gagal');
            redirect('Auth');
        }
	
		
	}
 
	function logout(){
	  // hapus session
        $this->session->unset_userdata('username');

        // tampilkan flash message
        $this->session->set_flashdata('logout-success', 'Berhasil');
        redirect('auth');
	}
}
