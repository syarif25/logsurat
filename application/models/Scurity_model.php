<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scurity_model extends CI_Model {

	public function getsqurity()
	{
		$username = $this->session->userdata('username');
		if (empty($username)) 
		{
			$this->session->sess_destroy(); 
			redirect('Auth');
		}

	}
	
}
