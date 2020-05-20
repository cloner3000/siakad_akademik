<?php
session_start();

  include_once "../dwo.lib.php";
  include_once "../db.mysql.php";
  include_once "../connectdb.php";
  include_once "../parameter.php";
  include_once "../cekparam.php";
  include_once "../fpdf.php";

// *** Parameters ***
	$JadwalID = sqling($_REQUEST['JadwalID']);
	$SKS = sqling($_REQUEST['SKS']);
	
	$jdwl = GetFields("jadwal j
		left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
		left outer join prodi prd on prd.ProdiID = j.ProdiID and prd.KodeID = '".KodeID."'
		left outer join program prg on prg.ProgramID = j.ProgramID and prg.KodeID = '".KodeID."'
		left outer join mk mk on mk.MKID = j.MKID
		left outer join kelas k on k.KelasID = j.NamaKelas
		left outer join hari hr on hr.HariID=j.HariID
		left outer join jadwaluts jut on jut.JadwalID = j.JadwalID
		left outer join jadwaluas jua on jua.JadwalID = j.JadwalID
			left outer join hari huts on huts.HariID = date_format(jut.Tanggal, '%w')
		  left outer join hari huas on huas.HariID = date_format(jua.Tanggal, '%w')
		",
		"j.JadwalID", $JadwalID,
		"k.Nama as _NamaKelas, j.*, concat(d.Gelar1, ' ', d.Nama, ', ', d.Gelar) as DSN, d.NIDN,
		prd.Nama as _PRD, prg.Nama as _PRG, mk.Sesi, mk.PerSKS, prd.FakultasID,
		date_format(jua.Tanggal, '%d-%m-%Y') as _UASTanggal,
		date_format(jut.Tanggal, '%d-%m-%Y') as _UTSTanggal,
		date_format(jut.Tanggal, '%w') as _UTSHari,
		date_format(jua.Tanggal, '%w') as _UASHari,
		huts.Nama as HRUTS,
		huas.Nama as HRUAS, mk.MKKode,
		hr.Nama as HariKuliah,
		j.JamMulai, j.JamSelesai,
		LEFT(jut.JamMulai, 5) as _UTSJamMulai, LEFT(jut.JamSelesai, 5) as _UTSJamSelesai,
		LEFT(jua.JamMulai, 5) as _UASJamMulai, LEFT(jua.JamSelesai, 5) as _UASJamSelesai
		");

if (empty($jdwl))
  die(ErrorMsg("Error",
    "Data jadwal tidak ditemukan.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='Tutup' value='Tutup'
      onClick=\"window.close()\" />"));

// *** Changable Parameters ***//
/*
if($SKS == 2) $Kolom = 14;
else if($SKS == 3) $Kolom = 16;
else if($SKS == 4) $Kolom = 14;
else $Kolom = 14;
*/
$Kolom = GetaField('jadwal', "KodeID='".KodeID."' and JadwalID", $_REQUEST['JadwalID'], 'RencanaKehadiran');
$lbr = 280;
// *** Functions ***//
$pdf = new FPDF();
$pdf->SetTitle("Daftar Presensi Mahasiswa");


	$s = "select h.Sesi,k.MhswID, upper(m.Nama) as Nama,m.StatusAwalID, m.TahunID as ThnMasuk, h.TahunID, p.JenjangID,m.ProgramID,h.Biaya,h.Potongan,h.Bayar,h.Tarik
    from khs h, krs k
      left outer join mhsw m on m.MhswID = k.MhswID and m.KodeID = '".KodeID."'
	  left outer join prodi p on p.ProdiID = m.ProdiID
    where k.JadwalID = '$jdwl[JadwalID]'
	AND h.MhswID=k.MhswID
	AND h.TahunID=k.TahunID
	Group By k.MhswID
    order by k.MhswID";

$r = _query($s);
$n = _num_rows($r);

$no =0;
$maxentryperpage = 20;
$maxentryoflastpage = 10;
$pages = floor($n/$maxentryperpage);
$lastpageentry = $n%$maxentryperpage;
if($lastpageentry == 0)
{	$pages -= 1;
    $lastpageentry = $maxentryperpage;
}
$totalpage = $pages;
if($lastpageentry > $maxentryoflastpage) $totalpage += 2;
else $totalpage += 1;

// Buat semua halaman tanpa footer
for($i = 0; $i< $pages; $i++)
{ 	$start = $i*$maxentryperpage;
	
	$s1 = "select h.Sesi,k.MhswID, upper(m.Nama) as Nama,m.StatusAwalID, m.TahunID as ThnMasuk, h.TahunID, p.JenjangID,m.ProgramID,h.Biaya,h.Potongan,h.Bayar,h.Tarik
    from khs h, krs k
      left outer join mhsw m on m.MhswID = k.MhswID and m.KodeID = '".KodeID."'
	  left outer join prodi p on p.ProdiID = m.ProdiID
    where k.JadwalID = '$jdwl[JadwalID]'
	AND h.MhswID=k.MhswID
	AND h.TahunID=k.TahunID
	Group By k.MhswID
    order by k.MhswID
	limit $start, $maxentryperpage";
	
	$r1 = _query($s1);
	
	$pdf->AddPage('L');
	$pdf->SetAutoPageBreak(true, 3);
	// Buat Header Logo
	HeaderLogo("DAFTAR PRESENSI MAHASISWA", $pdf, 'L');
	// Buat header dulu
	BuatHeader($jdwl, $Kolom, $pdf);
	// Tampilkan datanya
	AmbilDetail($jdwl, $r1, $start, $Kolom, $pdf);
	// Buat footer
	BuatFooter($jdwl, ($i+1), $totalpage, $pdf);
}

//Buat halaman terakhir dengan footer
$start = $i*$maxentryperpage;

if($lastpageentry > $maxentryoflastpage)
{	
	
	$s1 = "select h.Sesi,k.MhswID, upper(m.Nama) as Nama,m.StatusAwalID, m.TahunID as ThnMasuk, h.TahunID, p.JenjangID,m.ProgramID,h.Biaya,h.Potongan,h.Bayar,h.Tarik
    from khs h, krs k
      left outer join mhsw m on m.MhswID = k.MhswID and m.KodeID = '".KodeID."'
	  left outer join prodi p on p.ProdiID = m.ProdiID
    where k.JadwalID = '$jdwl[JadwalID]'
	AND h.MhswID=k.MhswID
	AND h.TahunID=k.TahunID
	Group By k.MhswID
    order by k.MhswID
	limit $start, $maxentryperpage";
	
	$r1 = _query($s1);
	$pdf->AddPage('L');
	$pdf->SetAutoPageBreak(true, 5);
	// Buat Header Logo
	HeaderLogo("DAFTAR PRESENSI MAHASISWA", $pdf, 'L');
	// Buat header dulu
	BuatHeader($jdwl, $Kolom, $pdf);
	// Tampilkan datanya
	AmbilDetail($jdwl, $r1, $start, $Kolom, $pdf);
	// Buat footer
	BuatFooter($jdwl, ($i+1), $totalpage, $pdf);
	
	$pdf->AddPage('L');
	$pdf->SetAutoPageBreak(true, 4);
	// Buat Header Logo
	HeaderLogo("DAFTAR PRESENSI MAHASISWA", $pdf, 'L');
	// Buat header dulu
	BuatHeader($jdwl, $Kolom, $pdf);
	// Buat rekap kehadiran dan tanda tangan
	BuatEnding($jdwl, $Kolom, $pdf);
	// Buat footer
	BuatFooter($jdwl, $i+2, $totalpage, $pdf);
}
else
{	
	$s1 = "select h.Sesi,k.MhswID, upper(m.Nama) as Nama, m.StatusAwalID, m.TahunID as ThnMasuk, h.TahunID, p.JenjangID,m.ProgramID,h.Biaya,h.Potongan,h.Bayar,h.Tarik
    from khs h, krs k
      left outer join mhsw m on m.MhswID = k.MhswID and m.KodeID = '".KodeID."'
	  left outer join prodi p on p.ProdiID = m.ProdiID
    where k.JadwalID = '$jdwl[JadwalID]'
	AND h.MhswID=k.MhswID
	AND h.TahunID=k.TahunID
	Group By k.MhswID
    order by k.MhswID
	limit $start, $maxentryperpage";
	
	$r1 = _query($s1);
	
	$pdf->AddPage('L');
	$pdf->SetAutoPageBreak(true, 5);
	// Buat Header Logo
	HeaderLogo("DAFTAR PRESENSI MAHASISWA", $pdf, 'L');
	// Buat header dulu
	BuatHeader($jdwl, $Kolom, $pdf);
	// Tampilkan datanya
	AmbilDetail($jdwl, $r1, $start, $Kolom, $pdf);
	// Buat rekap kehadiran dan tanda tangan
	BuatEnding($jdwl, $Kolom, $pdf);
	// Buat footer
	BuatFooter($jdwl, ($i+1), $totalpage, $pdf);
}

$pdf->Output();

// *** Functions ***
function BuatEnding($jdwl, $Kolom, $p) {
  global $arrID;
  
  $lbrkolom = 12; $t = 8;
  // Footer
  $p->Cell(78, $t, 'Jumlah Mahasiswa Hadir :', 'LBR', 0, 'R');
  $p->Cell($lbrkolom, $t, '', 'BR', 0);
  for($i = 0; $i < $Kolom; $i++) $p->Cell($lbrkolom, $t, '', 'BR', 0);
  $p->Ln($t);
  
  $p->Cell(78, $t, 'Paraf Dosen :', 'LBR', 0, 'R');
  $p->Cell($lbrkolom, $t, '', 'BR', 0);
  for($i = 0; $i < $Kolom; $i++) $p->Cell($lbrkolom, $t, '', 'BR', 0);
  $p->Ln($t);
  
  $t = 4.5;
  $p->Ln(5);
  $p->SetFont('Helvetica', 'I', 7);
  $p->Cell(200, $t, "* Harap tidak Menambah Daftar Hadir, Jika Nama/NPM tidak ada dalam Daftar Presensi, Silakan Konfirmasi ke Prodi masing-masing.", 0 , 0);
  $p->SetFont('Helvetica', '', 9);
  $p->Cell(60, $t, $arrID['Kota'] . ", ___________________", 0, 1);  
  $p->SetFont('Helvetica', 'I', 7);
  $p->Cell(200, $t, "* Bagi Nama/NPM yang tidak Terdaftar pada Absen, Tidak dibenarkan Mengikuti Perkuliahan. Untuk itu harap konfirmasi/menyelesaikan Administrasi Akademik.", 0 , 0);
  $p->SetFont('Helvetica', '', 9);
  $p->Cell(60, $t, "Dosen Pengasuh,", 0 , 1);
  $p->SetFont('Helvetica', 'I', 7);

$p->Ln(15);

  $p->Cell(200);
  $p->SetFont('Helvetica', 'B', 9);
  $p->Cell(60, $t, $jdwl['DSN'], 0, 1);
  $p->Cell(200);
  $p->SetFont('Helvetica', '', 9);
  $p->Cell(60, $t, 'NIDN: ' . $jdwl['NIDN'], 0, 1);
}
function AmbilDetail($jdwl, $r, $start, $Kolom, $p) {
  $lbrkolom = 12;
  global $no;
  $t = 6; 
  $p->SetFont('Helvetica', '', 7);
  while ($w = _fetch_array($r)) {
  	$totByrMhs = GetaField("khs", "TahunID='$w[TahunID]' And MhswID", $w['MhswID'],'SetujuPA');
    //if ($totByrMhs == 'Y') {
					$no++;
    				$p->Cell(8, $t, $no, 'LBR', 0, 'C');
    				$p->Cell(20, $t, $w['MhswID'], 'BR', 0);
				    $p->Cell(50, $t, $w['Nama'], 'BR', 0);
					for($i = 0; $i < $Kolom; $i++) $p->Cell($lbrkolom, $t, '', 'BR', 0);
					$p->Cell($lbrkolom, $t, '', 'BR', 0);
					$p->Ln($t);
	//}
	
  }
}
function BuatHeaderTabel($Kolom, $p) {
  $t = 6;
  $s = 12;
  
  $p->SetFont('Helvetica', 'B', 9);
  // Baris 1
  $p->Cell(8, $t, '', 'LTR', 0, 'C');
  $p->Cell(20, $t, '', 'TR', 0);
  $p->Cell(50, $t, '', 'TR', 0, 'C');
  $p->Cell($s*$Kolom, $t, 'TANGGAL & PERTEMUAN', 'TR', 0,'C');
  $p->Cell($s, $t, '', 'TR', 0, 'C');
  $p->Ln($t);
  
  // Baris 2 
  $p->Cell(8, $t, 'No.', 'BLR', 0, 'C');
  $p->Cell(20, $t, 'N P M', 'BR', 0, 'C');
  $p->Cell(50, $t, 'NAMA MAHASISWA', 'BR', 0, C);
  for($i = 0; $i < $Kolom; $i++) $p->Cell($s, $t, $i+1, 1, 0, C);
  $p->Cell($s, $t, 'JML', 'BR', 0, 'C');
  $p->Ln($t);
  
  // Baris 3 
  //$p->Cell(8, $t, '', 'LBR', 0, 'C');
  //$p->Cell(20, $t, '', 'BR', 0);
  //$p->Cell(50, $t, '', 'BR', 0, 'C');
  //for($i = 0; $i < $Kolom; $i++) $p->Cell($s, $t, '', 'BR', 0, C);
  //$p->Cell($s, $t, '', 'BR', 0,' C');
  //$p->Ln($t);
}
function BuatHeader($jdwl, $Kolom, $p) {
  $NamaTahun = GetaField('tahun', "KodeID='".KodeID."' and TahunID='$jdwl[TahunID]' and ProdiID",
    $jdwl['ProdiID'], 'Nama');
  $t = 6; $lbr = 200;

  $arr = array();
  $arr[] = array('Mata Kuliah', ':', $jdwl['MKKode'] . ' - ' . $jdwl['Nama'] . ' / ' . $NamaTahun);
  $arr[] = array('Kelas', ':', $jdwl['_NamaKelas'] . ' - ( Ruang: ' . $jdwl['RuangID'] . ' ) ', 
    'Dosen Pengasuh', ':', $jdwl['DSN'].GetaField('jadwaldosen j left outer join dosen d on d.Login=j.DosenID ', "JadwalID", $jdwl['JadwalID'], "concat(' / ', d.Gelar1,' ', d.Nama, ', ',d.Gelar)"));
  $arr[] = array('Semester / SKS', ':', $jdwl['Sesi'] . ' / ' . $jdwl['SKS']. ' | '.$jdwl['HariKuliah'].', '.$jdwl['JamMulai'].' - '.$jdwl['JamSelesai'],
    'Hari / Tgl UTS', ':', $jdwl['HRUTS'] . 
    ' / ' . $jdwl['_UTSTanggal'] .
    ' / ' . $jdwl['_UTSJamMulai'] . ' - ' . $jdwl['_UTSJamSelesai']);
  $arr[] = array('Program Studi', ':', $jdwl['_PRD'] . ' ('. $jdwl['_PRG'].')',
	'Hari / Tgl UAS', ':', $jdwl['HRUAS'] . 
    ' / ' . $jdwl['_UASTanggal'] .
    ' / ' . $jdwl['_UASJamMulai'] . ' - ' . $jdwl['_UASJamSelesai']);
  // Tampilkan
  $p->SetFont('Helvetica', '', 9);
  foreach ($arr as $a) {
    // Kolom 1
    $p->SetFont('Helvetica', 'I', 9);
    $p->Cell(25, $t, $a[0], 0, 0);
    $p->Cell(4, $t, $a[1], 0, 0, 'C');
    $p->SetFont('Helvetica', 'B', 9);
    $p->Cell(100, $t, $a[2], 0, 0);
    // Kolom 2
    $p->SetFont('Helvetica', 'I', 9);
    $p->Cell(25, $t, $a[3], 0, 0);
    $p->Cell(4, $t, $a[4], 0, 0, 'C');
    $p->SetFont('Helvetica', 'B', 9);
    $p->Cell(25, $t, $a[5], 0, 0);
    $p->Ln($t);
  }
  $p->Ln(4);
  BuatHeaderTabel($Kolom, $p);
}

function HeaderLogo($jdl, $p, $orientation='P')
{	$pjg = 110;
	$logo = (file_exists("../img/logo.jpg"))? "../img/logo.jpg" : "img/logo.jpg";
    $identitas = GetFields('identitas', 'Kode', KodeID, 'Nama, Alamat1, Telepon, Fax');
	$p->Image($logo, 12, 8, 18);
	$p->SetY(5);
    $p->SetFont("Helvetica", '', 8);
    $p->Cell($pjg, 5, $identitas['Yayasan'], 0, 1, 'C');
    $p->SetFont("Helvetica", 'B', 10);
    $p->Cell($pjg, 7, $identitas['Nama'], 0, 0, 'C');
    
	//Judul
	if($orientation == 'L')
	{
		$p->SetFont("Helvetica", 'B', 16);
		$p->Cell(20, 7, '', 0, 0);
		$p->Cell($pjg, 7, $jdl, 0, 1, 'C');
	}
	else
	{	$p->SetFont("Helvetica", 'B', 12);
		$p->Cell(80, 7, $jdl, 0, 1, 'R');
	}
	
    $p->SetFont("Helvetica", 'I', 6);
	$p->Cell($pjg, 3,
      $identitas['Alamat1'], 0, 1, 'C');
    $p->Cell($pjg, 3,
      "Telp. ".$identitas['Telepon'].", Fax. ".$identitas['Fax'], 0, 1, 'C');
    $p->Ln(3);
	if($orientation == 'L') $length = 275;
	else $length = 190;
    $p->Cell($length, 0, '', 1, 1);
    $p->Ln(2);
}

function BuatFooter($jdwl, $page, $totalpage, $p)
{	$t = 6;
    $p->SetFont("Helvetica", '', 10);
	$p->Ln(4);
	$p->Cell(10, $t, '', 'T', 0); 
	$p->Cell($length, $t, 'Halaman: '.$page.' / '.$totalpage, 'T', 0);
}
?>
