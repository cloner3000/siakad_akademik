<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com
// Start  : 30 Agustus 2008

session_start();

  include_once "../dwo.lib.php";
  include_once "../db.mysql.php";
  include_once "../connectdb.php";
  include_once "../parameter.php";
  include_once "../cekparam.php";
  include_once "../header_pdf.php";

// Init
$pdf = new PDF('P', 'mm', 'A4');
$pdf->SetTitle("Pengumuman Penilaian - $TahunID");

// *** Parameters ***
$JadwalID = $_REQUEST['JadwalID']+0;
$ProdiID = GetSetVar('ProdiID');

// *** Main ***
if ($JadwalID == 0) {
  // Maka cetak semua
  $s = "select JadwalID
    from jadwal
    where KodeID = '".KodeID."'
      and ProdiID = '$_jdwlProdi'
    order by HariID, JamMulai";
  $r = _query($s);
  while ($w = _fetch_array($r)) {
    CetakNilai($w['JadwalID'], $pdf);
  }
}
else CetakNilai($id, $pdf);


$pdf->Output();
// *** Functions ***
function CetakNilai($jid, $p) {
  $JadwalID = GetSetVar('JadwalID');
  $jdwl = GetFields("jadwal j
    left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
    left outer join prodi prd on prd.ProdiID = j.ProdiID and prd.KodeID = '".KodeID."'
    left outer join program prg on prg.ProgramID = j.ProgramID and prg.KodeID = '".KodeID."'
    left outer join mk mk on mk.MKID = j.MKID
    left outer join hari huas on huas.HariID = date_format(j.UASTanggal, '%w') 
	LEFT OUTER JOIN kelas k ON k.KelasID = j.NamaKelas
    ",
    "j.JadwalID", $JadwalID,
    "j.*, concat(d.Nama, ', ', d.Gelar) as DSN, d.NIDN,
    prd.Nama as _PRD, prg.Nama as _PRG,
    mk.Sesi,
    date_format(j.UASTanggal, '%d-%m-%Y') as _UASTanggal,
    date_format(j.UASTanggal, '%w') as _UASHari,
    huas.Nama as HRUAS,
    LEFT(j.UASJamMulai, 5) as _UASJamMulai, LEFT(j.UASJamSelesai, 5) as _UASJamSelesai, k.Nama AS namaKelas
    ");
  $TahunID = $jdwl['TahunID'];
  $thn = GetFields('tahun', "KodeID = '".KodeID."' and ProdiID = '$jdwl[ProdiID]' and ProgramID = '$jdwl[ProgramID]' and TahunID", $TahunID, "*");
  // Buat Header
  BuatHeader($jdwl, $thn, $p);
  BuatIsinya($jdwl, $p);
  BuatFooter($jdwl, $p);
}
function BuatFooter($jdwl, $p) {
  global $arrID;
  $t = 5;
  $p->Ln(4);
  $p->SetFont('Helvetica', 'B', 8);
  $p->Cell(180, $t, "*) Kehadiran Kurang Dari Persyaratan, Dosen Berwenang Memberi Nilai E.", 0 , 'L');
  $p->Ln(4);
  $p->SetFont('Helvetica', '', 7);
  $p->Cell(180, $t, "Catatan: Bagi mahasiswa yang namanya tidak terproses, silakan konfirmasi ke BIAAKSI", 0 , 'L');
  $p->Ln(6);
  $p->Cell(41);
  $p->SetFont('Helvetica', '', 9);
  $p->Cell(41, $t, "Nama", 0 , 'L');
  $p->Cell(56, $t, "Tanda Tangan", 0 , 'L');
  $p->Cell(60, $t, $arrID['Kota'] . ", " . date('d M Y'), 0, 1);
  $p->Cell(138);
  $p->Cell(60, $t, "Dosen Pengasuh,", 0 , 1);
  $p->Ln(13);

  
  $p->Cell(28, $t, "Pengawas:", 0 , 'L');
  $p->Cell(46, $t, "1. ____________________", 0 , 'L');
  $p->Cell(64, $t, "_______________________", 0 , 'L');
  $p->SetFont('Helvetica', 'B', 9);
  $p->Cell(60, $t, $jdwl['DSN'], 0, 1);
  $p->Cell(138);
  $p->SetFont('Helvetica', '', 9);
  $p->Cell(60, $t, 'NIDN: ' . $jdwl['NIDN'], 0, 1);
  $p->Cell(28);
  $p->Cell(46, $t, "2. ____________________", 0 , 'L');
  $p->Cell(60, $t, "_______________________", 0 , 'L');
  $p->Ln(8);
  $p->Cell(1);
  $p->Cell(190, 0, "", 1, 1);
  $p->SetFont('Helvetica', 'B', 8);
  $p->Cell(180, $t, "*/ No. BP Mahasiswa Sudah diverifikasi untuk Pelaporan EPSBED-DIKTI.", 0 , 'L');

}
function BuatIsinya($jdwl, $p) {
  $t =  12;
  BuatHeaderTabel($p);
  $s = "select k.*, UPPER(m.Nama) as NamaMhsw
    from krsmanual k
      left outer join mhsw m on k.MhswID = m.MhswID and m.KodeID = 'ITP'
    where k.JadwalID = '$jdwl[JadwalID]'
    order by m.MhswID";
  $r = _query($s);
  $n = 0;
  $p->SetFont('Helvetica', '', 9);
  while ($w = _fetch_array($r)) {
    $n++;
    $p->Cell(2);
    $p->Cell(10, $t, $n, 'LTBR', 0,'C');
    $p->Cell(32, $t, $w['MhswID'], 'TBR', 0);
    $p->Cell(55, $t, $w['NamaMhsw'], 'TBR', 0);
    $p->Cell(15, $t, '', 'TBR', 0, 'C');
    $p->Cell(20, $t, '', 'TBR', 0, 'C');
    $p->Cell(35, $t, $n, 'TBR', 0, 'L');
    $p->Ln($t);
  }
}

function BuatHeaderTabel($p) {
  $t = 4;
  $p->SetFont('Helvetica', 'B', 9);
  $p->Cell(2);
  $p->Cell(10, $t, 'No.', 'LTR', 0, 'C');
  $p->Cell(32, $t, 'Nomer BP', 'TR', 0);
  $p->Cell(55, $t, 'Nama Mahasiswa', 'TR', 0);
  $p->Cell(15, $t, 'Nilai', 'TR', 0, 'C');
  $p->Cell(20, $t, 'Jumlah', 'TR', 0, 'C');
  $p->Cell(35, $t, 'Tanda Tangan', 'TR', 0, 'C');
  $p->Ln($t);
  $p->Cell(2);
  $p->Cell(10, $t, ' ', 'LBR', 0);
  $p->Cell(32, $t, '', 'BR', 0);
  $p->Cell(55, $t, '', 'BR', 0);
  $p->Cell(15, $t, '(Angka)', 'BR', 0, 'C');
  $p->Cell(20, $t, 'Kehadiran', 'BR', 0, 'C');
  $p->Cell(35, $t, ' ', 'BR', 0, 'C');
  $p->Ln($t);
}
function BuatHeader($jdwl, $thn, $p) {
  $t = 5; $lbr = 190;
  $p->AddPage('P');

  $arr = array();
  $arr[] = array('Mata Kuliah', ':', $jdwl['MKKode'] . '   ' . $jdwl['Nama'], 
    'Dosen Pengasuh', ':', $jdwl['DSN']);
  $arr[] = array('Kelas / Thn Akd', ':', $jdwl['namaKelas'] . ' / ' . $thn['Nama'],
    'Program Studi', ':', $jdwl['_PRD'] . ' ('. $jdwl['_PRG'].')');
  $arr[] = array('Semester / SKS', ':', $jdwl['Sesi'] . ' / ' . $jdwl['SKS'],
    'Hari / Tgl Ujian', ':', $jdwl['HRUAS'] . 
    ' / ' . $jdwl['_UASTanggal'] .
    ' / ' . $jdwl['_UASJamMulai'] . ' - ' . $jdwl['_UASJamSelesai']);
  // Tampilkan
  $p->SetFont('Helvetica', 'B', 10);
  $p->Cell($lbr, 8, 'Daftar Hadir dan Nilai Ujian Akhir Semester', 0, 1, 'C');
  $p->SetFont('Helvetica', '', 7);
  foreach ($arr as $a) {
    // Kolom 1
    $p->SetFont('Helvetica', 'I', 7);
    $p->Cell(25, $t, $a[0], 0, 0);
    $p->Cell(4, $t, $a[1], 0, 0, 'C');
    $p->SetFont('Helvetica', 'B', 7);
    $p->Cell(70, $t, $a[2], 0, 0);
    // Kolom 2
    $p->SetFont('Helvetica', 'I', 7);
    $p->Cell(25, $t, $a[3], 0, 0);
    $p->Cell(4, $t, $a[4], 0, 0, 'C');
    $p->SetFont('Helvetica', 'B', 7);
    $p->Cell(70, $t, $a[5], 0, 0);
    $p->Ln($t);
  }
  $p->Ln(4);
}

