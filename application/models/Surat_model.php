<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_model extends CI_Model {
	var $column_order = array(null,'l.nama_lembaga','j.jenis_surat','p.perihal','o.tgl_log' ,null); //set column field database for datatable orderable
	var $column_search = array('p.nomor_pengajuan','l.nama_lembaga','p.perihal','o.tgl_log');

	function queri_filter(){
	$search = '';
	$order = '';
    if (isset($_POST['search']['value'])) {
    	$i = 0;
		foreach ($this->column_search as $item) { // loop column
			if ($i === 0) {
			    $search .= ' AND ( ' . $item . ' LIKE "%' . $_POST['search']['value'] . '%"';
			} else {
			    $search .= ' OR ' . $item . ' LIKE "%' . $_POST['search']['value'] . '%"';
			}

			if (count($this->column_search) - 1 == $i) {
			    $search .= ' OR ' . $item . ' LIKE "%' . $_POST['search']['value'] . '%")';
			}
			$i++;
		}
    }
    if (isset($_POST['order'])) {
        $order .= ' ORDER BY ' . $this->column_order[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
    }
    if ($_POST['length'] != NULL || $_POST['start'] != NULL) {
        $len = ($_POST['length'] == -1 ? '' : ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length']);
    }
	
		if ($this->session->userdata('level') == '2') {
			$stringquery = "SELECT * 
				FROM pengajuan p
				LEFT JOIN (SELECT id_pengajuan, id_log, MAX(tgl_log) AS maxdate FROM log_surat GROUP BY id_pengajuan) AS x ON x.id_pengajuan = p.id_pengajuan
				LEFT JOIN log_surat o ON o.id_pengajuan=p.id_pengajuan
				LEFT JOIN jenis_surat j ON p.id_jenis_surat=j.id_jenis_surat
				LEFT JOIN lembaga l ON l.id_lembaga=p.id_lembaga
				WHERE o.tgl_log=x.maxdate and o.posisi ='Kabag Evaluasi' ";

		} elseif ($this->session->userdata('level') == '1') {
			$stringquery = "SELECT * 
				FROM pengajuan p
				LEFT JOIN (SELECT id_pengajuan, id_log, MAX(tgl_log) AS maxdate FROM log_surat GROUP BY id_pengajuan) AS x ON x.id_pengajuan = p.id_pengajuan
				LEFT JOIN log_surat o ON o.id_pengajuan=p.id_pengajuan
				LEFT JOIN jenis_surat j ON p.id_jenis_surat=j.id_jenis_surat
				LEFT JOIN lembaga l ON l.id_lembaga=p.id_lembaga
				WHERE (o.tgl_log=x.maxdate and o.posisi ='Staff Umum' ) or (o.tgl_log=x.maxdate and o.posisi ='Staff Evaluasi') ";
		} elseif ($this->session->userdata('level') == '3') {
			$stringquery = "SELECT * 
				FROM pengajuan p
				LEFT JOIN (SELECT id_pengajuan, id_log, MAX(tgl_log) AS maxdate FROM log_surat GROUP BY id_pengajuan) AS x ON x.id_pengajuan = p.id_pengajuan
				LEFT JOIN log_surat o ON o.id_pengajuan=p.id_pengajuan
				LEFT JOIN jenis_surat j ON p.id_jenis_surat=j.id_jenis_surat
				LEFT JOIN lembaga l ON l.id_lembaga=p.id_lembaga
				WHERE o.tgl_log=x.maxdate and o.posisi ='Kasir' and o.status !='fn'  ";
		} elseif ($this->session->userdata('level') == '4') {
			$stringquery = "SELECT * 
				FROM pengajuan p
				LEFT JOIN (SELECT id_pengajuan, id_log, MAX(tgl_log) AS maxdate FROM log_surat GROUP BY id_pengajuan) AS x ON x.id_pengajuan = p.id_pengajuan
				LEFT JOIN log_surat o ON o.id_pengajuan=p.id_pengajuan
				LEFT JOIN jenis_surat j ON p.id_jenis_surat=j.id_jenis_surat
				LEFT JOIN lembaga l ON l.id_lembaga=p.id_lembaga
				WHERE o.tgl_log=x.maxdate and o.posisi ='waka bendahara' ";
		} else {
		
		$stringquery = "SELECT * 
			FROM pengajuan p
			LEFT JOIN (SELECT id_pengajuan, id_log, MAX(tgl_log) AS maxdate FROM log_surat GROUP BY id_pengajuan) AS x ON x.id_pengajuan = p.id_pengajuan
			LEFT JOIN log_surat o ON o.id_pengajuan=p.id_pengajuan
			LEFT JOIN jenis_surat j ON p.id_jenis_surat=j.id_jenis_surat
			LEFT JOIN lembaga l ON l.id_lembaga=p.id_lembaga
			WHERE o.tgl_log=x.maxdate ";
		}

	$queryall = $this->db->query($stringquery);
	$queryfilter = $this->db->query($stringquery . $search . $order . $len);
    // print_r($this->db->last_query());
	$data['result'] = $queryfilter->result();
	$data['count_filtered'] = $queryfilter->num_rows();
	$data['count_all'] = $queryall->num_rows();
	return $data;
	
	}
	
	function arsip_filter(){
    	$search = '';
    	$order = '';
        if (isset($_POST['search']['value'])) {
        	$i = 0;
    		foreach ($this->column_search as $item) { // loop column
    			if ($i === 0) {
    			    $search .= ' AND ( ' . $item . ' LIKE "%' . $_POST['search']['value'] . '%"';
    			} else {
    			    $search .= ' OR ' . $item . ' LIKE "%' . $_POST['search']['value'] . '%"';
    			}
    
    			if (count($this->column_search) - 1 == $i) {
    			    $search .= ' OR ' . $item . ' LIKE "%' . $_POST['search']['value'] . '%")';
    			}
    			$i++;
    		}
        }
        if (isset($_POST['order'])) {
            $order .= ' ORDER BY ' . $this->column_order[$_POST['order']['0']['column']] . ' ' . $_POST['order']['0']['dir'] . ' ';
        }
        if ($_POST['length'] != NULL || $_POST['start'] != NULL) {
            $len = ($_POST['length'] == -1 ? '' : ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length']);
        }
	
		$stringquery = "SELECT * 
			FROM pengajuan p
			LEFT JOIN (SELECT id_pengajuan, id_log, MAX(tgl_log) AS maxdate FROM log_surat GROUP BY id_pengajuan) AS x ON x.id_pengajuan = p.id_pengajuan
			LEFT JOIN log_surat o ON o.id_pengajuan=p.id_pengajuan
			LEFT JOIN jenis_surat j ON p.id_jenis_surat=j.id_jenis_surat
			LEFT JOIN lembaga l ON l.id_lembaga=p.id_lembaga
			WHERE o.tgl_log=x.maxdate ";

    	$queryall = $this->db->query($stringquery);
    	$queryfilter = $this->db->query($stringquery . $search . $order . $len);
        // print_r($this->db->last_query());
    	$data['result'] = $queryfilter->result();
    	$data['count_filtered'] = $queryfilter->num_rows();
    	$data['count_all'] = $queryall->num_rows();
    	return $data;
	}

	public function kode()
	{
		  $this->db->select('RIGHT(pengajuan.id_pengajuan,4) as id_pengajuan', FALSE);
		  $this->db->order_by('id_pengajuan','DESC');    
		  $this->db->limit(1);    
		  $query = $this->db->get('pengajuan');  //cek dulu apakah ada sudah ada kode di tabel.    
		  if($query->num_rows() <> 0){      
			   //cek kode jika telah tersedia    
			   $data = $query->row();      
			   $kode = intval($data->id_pengajuan) + 1; 
		  }
		  else{      
			   $kode = 1;  //cek jika kode belum terdapat pada table
		  }
			  $tgl=date('dmY'); 
			  $batas = str_pad($kode, 5, "0", STR_PAD_LEFT);    
			  $kodetampil = "P00".$batas;  //format kode
			  return $kodetampil;  
	}

	public function get_track($id)
	{
		$query = $this->db->query("SELECT * FROM pengajuan as s, lembaga as l, jenis_surat as j, log_surat as g where s.id_lembaga = l.id_lembaga and s.id_jenis_surat = j.id_jenis_surat and s.id_pengajuan = g.id_pengajuan and g.id_pengajuan = '$id' ");
		return $query->row();
	}

	public function get_tracklagi($id)
	{
		$query = $this->db->query("SELECT * FROM pengajuan as s, lembaga as l, jenis_surat as j, log_surat as g where s.id_lembaga = l.id_lembaga and s.id_jenis_surat = j.id_jenis_surat and s.id_pengajuan = g.id_pengajuan and g.id_pengajuan = '$id' ");
		return $query->result();
	}

	public function get_id($id)
	{
		$query = $this->db->query("SELECT * 
			FROM pengajuan p
			LEFT JOIN (SELECT id_pengajuan, id_log, MAX(tgl_log) AS maxdate FROM log_surat GROUP BY id_pengajuan) AS x ON x.id_pengajuan = p.id_pengajuan
			LEFT JOIN log_surat o ON o.id_pengajuan=p.id_pengajuan
			LEFT JOIN jenis_surat j ON p.id_jenis_surat=j.id_jenis_surat
			LEFT JOIN lembaga l ON l.id_lembaga=p.id_lembaga
			WHERE o.tgl_log=x.maxdate and p.id_pengajuan = '$id'; ");

		return $query->row();
	}

}
