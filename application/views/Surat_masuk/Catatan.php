<?php 
    // include_once APPPATH . '/third_party/fpdf/wordwrap.php';
    require('application/libraries/force_justify.php');
    
    $pdf = new FPDF('P','mm','A5');
    $pdf->AddFont('bookman','','bookman-old-style.php');
    $pdf->AddFont('bookmanbold','B','bookman-old-style-bold.php');
    $pdf->AddFont('bookatik','B','book-antiqua.php');
    $pdf->AddFont('Aprille','','Aprille Display Caps SSi.php');


    // membuat halaman baru
    $pdf->AddPage();
    // setting jenis font yang akan digunakan
    $pdf->SetFont('Times','B',16);
    // mencetak string
  
    // Logo
    $pdf->Image('assets/p2s2.png',50,2,40);
    // Arial bold 15
    $pdf->SetFont('bookman','',16);
    // Move to the right
    $pdf->Cell(5);
    $pdf->Ln(12);
    // Title
    $pdf->SetFont('Aprille','',18);
    $pdf->Cell(0,6,'PONDOK PESANTREN SALAFIYAH SYAFIIYAH SUKOREJO','0','1','C',false);
    $pdf->SetFont('bookmanbold','B',13);
    $pdf->Cell(0,6,'SUKOREJO SITUBONDO JAWA TIMUR','0','1','C',false);
    $pdf->SetFont('bookman','',10);
    $pdf->Cell(0,5,'SUMBEREJO BANYUPUTIH SITUBONDO JAWA TIMUR','0','1','C',false);

    // garis (margin kiri, margin atas, lebar, kanan)
    $pdf->Line(10,40,140,40);
    $pdf->Line(10,41,140,41);
    $pdf->Line(10,40,140,40);

    $pdf->Ln(6);
    $pdf->SetFont('arial','B',12);
    $pdf->Cell(40,7,'',0,0,'L');
    $pdf->Cell(25,6,'Catatan Pengajuan',0,0,'L');

    $pdf->Ln(5);
    $pdf->SetFont('arial','',11);
    $pdf->Cell(15,7,'Pengirim',0,0,'L');
    $pdf->Cell(5,7,':',0,0,'L');
    $pdf->Cell(50,7,'(Nama Lembaga)',0,0,'L');

    $pdf->Ln(5);
    $pdf->SetFont('arial','',11);
    $pdf->Cell(15,7,'Perihal',0,0,'L');
    $pdf->Cell(5,7,':',0,0,'L');
    $pdf->Cell(50,7,'(Perihal Surat)',0,0,'L');

    $pdf->Ln(6);
    $pdf->SetFont('arial','B',12);
    $pdf->Cell(50,7,'',0,0,'L');
    $pdf->Cell(25,6,'Catatan ',0,0,'L');

    $pdf->Ln(5);
    $pdf->SetFont('arial','',10);
    $pdf->Cell(15,7,'ini adlah isi catatan ini adlah isi catatan ini adlah isi catatan ini adlah isi catatan ini adlah isi catatan',0,0,'L');
   
    
    $pdf->Ln(20);
    $pdf->SetFont('arial','',10);
    $pdf->Cell(70,7,'',0,0,'L');
    $pdf->Cell(50,7,'Sukorejo, 30 Maret 2023',0,0,'L');

    $pdf->Ln(4);
    $pdf->SetFont('arial','',10);
    $pdf->Cell(70,7,'',0,0,'L');
    $pdf->Cell(50,7,'Kabag. Evaluasi dan Pengembangan,',0,0,'L');

    $pdf->Ln(15);
    $pdf->SetFont('arial','',10);
    $pdf->Cell(70,7,'',0,0,'L');
    $pdf->Cell(50,7,'Abd. Mujib',0,0,'L');

    $pdf->ln(2);
  
   
        
    function date_lengkap($date)
    {
        $tgl = date_create($date);
        return date_format($tgl, "d/M/Y");
    }
    
    $pdf->Output();

?>