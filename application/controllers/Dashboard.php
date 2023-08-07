<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Scurity_model');
	}

	public function index()
	{
		// Mengambil data dari basis data dengan query SQL
        $query = "SELECT DATE_FORMAT(pengajuan.tgl_masuk, '%M') AS bulan,
                         jenis_surat,
                         COUNT(id_pengajuan) AS jml
                  FROM pengajuan, jenis_surat
                  WHERE pengajuan.id_jenis_surat = jenis_surat.id_jenis_surat
                        AND pengajuan.tgl_masuk >= '2023-03-01'
                        AND pengajuan.tgl_masuk <= '2023-05-31'
                  GROUP BY jenis_surat, bulan";

        $result = $this->db->query($query)->result_array();

        // Membuat array untuk data grafik
        $chartData = array();
        foreach ($result as $row) {
            $jenisSurat = $row['jenis_surat'];
            $bulan = $row['bulan'];
            $jml = $row['jml'];

            if (!isset($chartData[$jenisSurat])) {
                $chartData[$jenisSurat] = array();
            }

            $chartData[$jenisSurat][] = array(
                'bulan' => $bulan,
                'jml' => $jml
            );
        }

// Mengirim data grafik ke tampilan
$isi['chartData'] = json_encode($chartData);
		$isi['content'] = 'Dashboard/Dashboard';
		$isi['ajax'] = 'Dashboard/Ajax';
		$this->load->view('Template',$isi);
	}

	public function list_chart()
	{
		$list = $this->db->query("
			SELECT DATE_FORMAT(pengajuan.tgl_masuk, '%M') AS bulan,
       		jenis_surat,
       		COUNT(id_pengajuan) AS jml
			FROM pengajuan, jenis_surat
			WHERE pengajuan.id_jenis_surat = jenis_surat.id_jenis_surat
				AND pengajuan.tgl_masuk >= '2023-03-01'
				AND pengajuan.tgl_masuk <= '2023-05-31'
			GROUP BY bulan, pengajuan.id_jenis_surat
		")->result();
		
		$bulan = array();
		$jumlah = array();
		$jenis_surat = array();
		foreach ($list as $datanya) {
			
			$bulan[] = $datanya->bulan;
			$jumlah[] 	= $datanya->jml;
			$jenis_surat[] = $datanya->jenis_surat;
		}
		$output = array(
			'title' => 'DATA REKAPITULASI PENGAJUAN ',
			'subtitle' => '',
			'kategori' => 'ini kategori',
			'bulan' => $bulan,
			'jumlah' => $jumlah,
			'jenis_surat' => $jenis_surat,
		);
		echo json_encode($output);
	}

	

}
?>