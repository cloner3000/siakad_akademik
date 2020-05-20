<?php
// Kostumisasi oleh Arisal Yanuarafi 16 Maret 2012

session_start();
$_SESSION['Error']='';
include_once "../sisfokampus1.php";

HeaderSisfoKampus("Jadwal Kuliah", 1);

// *** infrastruktur **
echo <<<SCR
  <script src="../$_SESSION[mnux].edit.script.js"></script>
SCR;

// *** Parameters ***
$_uasTahun = GetSetVar('_uasTahun');
$_uasProdi = GetSetVar('_uasProdi');
$_uasProg  = GetSetVar('_uasProg');

// *** Special Parameters ***
$md = $_REQUEST['md']+0;
$id = $_REQUEST['id']+0;
$juasid = $_REQUEST['juasid']+0;
$juasid2 = $_REQUEST['juasid2']+0;
$juasid3 = $_REQUEST['juasid3']+0;
$juasid4 = $_REQUEST['juasid4']+0;
$juasid5 = $_REQUEST['juasid5']+0;

// *** Main ***
$gos = (empty($_REQUEST['gos']))? 'Edit' : $_REQUEST['gos'];
$gos($md, $id, $juasid, $juasid2,$juasid3, $juasid4,$juasid5);

// *** Functions ***
function Edit($md, $id, $juasid, $juasid2,$juasid3, $juasid4,$juasid5) {
  $w = GetFields('jadwal j left outer join kelas kl on kl.KelasID=j.NamaKelas', 'j.JadwalID', $id, 'kl.Nama as NamaKLS, j.*');
  $w['Dosen'] = GetaField('dosen', "KodeID='".KodeID."' and Login", $w['DosenID'], 'Nama');
  $w['_JM'] = substr($w['JamMulai'], 0, 5);
  $w['_JS'] = substr($w['JamSelesai'], 0, 5);
  $prodi = GetFields('prodi', "ProdiID='$_SESSION[_uasProdi]' and KodeID", KodeID, "*");
  $tahun = GetFields('tahun', "TahunID='$_SESSION[_uasTahun]' and ProdiID='$_SESSION[_uasProdi]' and ProgramID='$_SESSION[_uasProg]' and KodeID", KodeID, "*");
  
  if ($md == 0) {
	$jdl = "Edit Jadwal UAS";
	$jadwaluas = GetFields('jadwaluas', 'JadwalUASID', $juasid, '*');
	$jadwaluas2 = GetFields('jadwaluas', 'JadwalUASID', $juasid2, '*');
    $jadwaluas3 = GetFields('jadwaluas', 'JadwalUASID', $juasid3, '*');
	$jadwaluas4 = GetFields('jadwaluas', 'JadwalUASID', $juasid4, '*');
    $jadwaluas5 = GetFields('jadwaluas', 'JadwalUASID', $juasid5, '*');
	$w['UASTanggal'] = $jadwaluas['Tanggal'];
	$w['UASJamMulai'] = substr($jadwaluas['JamMulai'], 0, 5);
	$w['UASJamSelesai'] = substr($jadwaluas['JamSelesai'], 0, 5);
	$w['UASDosenID'] = $jadwaluas['DosenID'];
	$w['UASDosen'] = GetaField('dosen', "Login='$jadwaluas[DosenID]' and KodeID", KodeID, 'Nama');
	$w['UASRuangID'] = $jadwaluas['RuangID'];
	$w['UASKapasitas'] = $jadwaluas['Kapasitas'];
	$w['UASKolomUjian'] = $jadwaluas['KolomUjian'];
	$w['UASBarisUjian'] = ceil($jadwaluas['Kapasitas'] / $jadwaluas['KolomUjian']);
	$w0['UASDosenID'] = $jadwaluas2['DosenID'];
	$w0['UASRuangID'] = $jadwaluas2['RuangID'];
	$w0['UASKapasitas'] = $jadwaluas2['Kapasitas'];
	$w0['UASKolomUjian'] = $jadwaluas2['KolomUjian'];
	$w0['UASDosen'] = GetaField('dosen', "Login='$jadwaluas2[DosenID]' and KodeID", KodeID, 'Nama');
    
    $w3['UASDosenID'] = $jadwaluas3['DosenID'];
	$w3['UASRuangID'] = $jadwaluas3['RuangID'];
	$w3['UASKapasitas'] = $jadwaluas3['Kapasitas'];
	$w3['UASKolomUjian'] = $jadwaluas3['KolomUjian'];
	$w3['UASDosen'] = GetaField('dosen', "Login='$jadwaluas3[DosenID]' and KodeID", KodeID, 'Nama');
    
    $w4['UASDosenID'] = $jadwaluas4['DosenID'];
	$w4['UASRuangID'] = $jadwaluas4['RuangID'];
	$w4['UASKapasitas'] = $jadwaluas4['Kapasitas'];
	$w4['UASKolomUjian'] = $jadwaluas4['KolomUjian'];
	$w4['UASDosen'] = GetaField('dosen', "Login='$jadwaluas4[DosenID]' and KodeID", KodeID, 'Nama');
    
    $w5['UASDosenID'] = $jadwaluas5['DosenID'];
	$w5['UASRuangID'] = $jadwaluas5['RuangID'];
	$w5['UASKapasitas'] = $jadwaluas5['Kapasitas'];
	$w5['UASKolomUjian'] = $jadwaluas5['KolomUjian'];
	$w5['UASDosen'] = GetaField('dosen', "Login='$jadwaluas5[DosenID]' and KodeID", KodeID, 'Nama');
  }
  elseif ($md == 1) {
	$jdl = "Tambah Jadwal UAS";
	$w['UASTanggal'] = $tahun['TglUASMulai'];
	$w['UASJamMulai'] = '09:00';
	$w['UASJamSelesai'] = '09:50';
  }
  else {
	die(ErrorMsg("Error", "Mode tidak dikenali")); 
  }
  // Parameters
  JdwlUASScript();
echo '
<link type="text/css" href="../datepicker2/datePicker.css" rel="stylesheet" />	
<script type="text/javascript" src="../datepicker2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../datepicker2/date-id.js"></script>
<!--[if IE]>
<script type="text/javascript" src="../datepicker2/jquery.bgiframe.js"></script>
<![endif]-->
<script type="text/javascript" src="../datepicker2/jquery.datePicker.js"></script>
';

$s2 = "select date_format(TglUASMulai, '%d')+0 as _fromday, date_format(TglUASMulai, '%m')+0 as _frommonth, date_format(TglUASMulai, '%Y')+0 as _fromyear,
	date_format(TglUASSelesai, '%d')+0 as _today, date_format(TglUASSelesai, '%m')+0 as _tomonth, date_format(TglUASSelesai, '%Y')+0 as _toyear
	from tahun where NA = 'N' and TahunID = '$_SESSION[_uasTahun]' and ProdiID = '$_SESSION[_uasProdi]' and ProgramID = '$_SESSION[_uasProg]'";
$q2 = _query($s2);
$w2 = (_fetch_array($q2));

$start = $w2[_fromyear].",".($w2[_frommonth]).",".$w2[_fromday];
$end = $w2[_toyear].','.($w2[_tomonth]).','.$w2[_today];


echo "
<script>
function setDatePicker(selector,rangeSelector,stat){
	var dt = $('#alt'+selector).val().replace('-',',');
	dt = dt.replace('-',',');
	
	$('#'+selector).datePicker({startDate:'01/01/1990'});
	$('#'+selector).datePicker().val(new Date(dt).asString()).trigger('change');
	$('#'+selector).dpSetPosition($.dpConst.POS_TOP, $.dpConst.POS_RIGHT);
	
	if (rangeSelector != ''){
		var dts = $('#'+rangeSelector).val().replace('-',',');
		dts = dts.replace('-',',');
		
		if (dts) {
			dts = new Date(dts);
			
			if (stat == 'end'){
				$('#'+selector).dpSetEndDate(dts.addDays(-1).asString());
			} else if (stat == 'start'){
				$('#'+selector).dpSetStartDate(dts.addDays(1).asString());
			}
			
		}
		
		// bind to event
		$('#'+rangeSelector).bind(
			'dpClosed',
			function(e, selectedDates)
			{
				var year = selectedDates[0].getFullYear();
				var mon = selectedDates[0].getMonth()+1;
				var day = selectedDates[0].getDate();
				var realvalue = year+'-'+mon+'-'+day;
				$('#alt'+rangeSelector).val(realvalue);
				
				var d = selectedDates[0];
				if (d) {
					d = new Date(d);
					if (stat == 'end'){
						$('#'+selector).dpSetEndDate(d.addDays(-1).asString());
					} else if (stat == 'start'){
						$('#'+selector).dpSetStartDate(d.addDays(1).asString());
					}
				}
			}
		)
	}	
}

$(function()
{
	Date.format = 'dd mmmm yyyy';
	setDatePicker('UASTanggal','UASTanggal','');
		var dts = '".$start."';
		if (dts) {
			dts = new Date(dts);
				$('#UASTanggal').dpSetStartDate(dts.addDays(0).asString());
			}
		var dts = '".$end."';
		if (dts) {
			dts = new Date(dts);
				$('#UASTanggal').dpSetEndDate(dts.addDays(0).asString());
			}
});
</script>";

function GetDateOption3($value,$name){
	$a = "<input type=hidden name=".$name." id=alt".$name." value=".$value." /><input type=text id=".$name." value=".$value." readonly=true />";
	return $a;
}

  //$opttgluas = GetDateOption3($w['UASTanggal'], 'UASTanggal');  
  $opttgluas = GetDateOption($w['UASTanggal'], 'UASTanggal');
  
  // Fungsi melihat jam ujian, bagi prodi yang ada mengatur ini ditampilkan berupa 1 combobox saja
  /*$jmu = _query("SELECT * from jamujian where ProdiID='$_SESSION[_uasProdi]'");
  	$optJam = '';
    while ($JamUjian = _fetch_array($jmu)) {
    	$optJam .= "<option value='$JamUjian[JamUjianID]'>$JamUjian[Urutan]. $JamUjian[JamMulai] - $JamUjian[JamSelesai]</option>";
    }*/
    
  $optJamSelesai = GetTimeOption($w['UASJamSelesai'], 'UASJamSelesai');
  
  $optJamSelesai = GetTimeOption($w['UASJamSelesai'], 'UASJamSelesai');
  $arr = Explode(':', $w['JamUTSMulai']);
  $_hr = GetNumberOption(0, 23, $arr[0]);
  $_mn = GetNumberOption(0, 59, $arr[1]);
  $optJamMulai = "<select name='UASJamMulai_h' onchange=\"javascript:setJamSelesai(this)\">$_hr</select>
    <select name='UASJamMulai_n'>$_mn</select>";
  
  $JamNya = (!empty($optJam))? "<select name='JamUjian'>$optJam</select><br />$optJamMulai &#8594; $optJamSelesai" : "$optJamMulai &#8594; $optJamSelesai";
  $NamaHari = GetaField('hari', 'HariID', $w['HariID'], 'Nama');
  // Tampilkan
  CheckFormScript("UASRuangID");
  
echo '
  <script>
  function setJamSelesai(jam){
		var jamnya = jam.value;
		if (jamnya == "08"){
		frmJadwalUAS.UASJamSelesai_h.value="09";
		frmJadwalUAS.UASJamSelesai_n.value="30";
		}
		else if (jamnya == "10"){
		frmJadwalUAS.UASJamSelesai_h.value="11";
		frmJadwalUAS.UASJamSelesai_n.value="30";
		}
		else if (jamnya == "13"){
		frmJadwalUAS.UASJamSelesai_h.value="14";
		frmJadwalUAS.UASJamSelesai_n.value="30";
		}
		else if (jamnya == "15"){
		frmJadwalUAS.UASJamSelesai_h.value="16";
		frmJadwalUAS.UASJamSelesai_n.value="30";
		}
		else if (jamnya == "14"){
		frmJadwalUAS.UASJamSelesai_h.value="15";
		frmJadwalUAS.UASJamSelesai_n.value="30";
		}
		else if (jamnya == "16"){
		frmJadwalUAS.UASJamSelesai_h.value="17";
		frmJadwalUAS.UASJamSelesai_n.value="30";
		}
	}
  	function cekJdwl(){
	
		var fromHour = document.forms[0].JamMulai_h.value;
		var fromMinutes = document.forms[0].JamMulai_n.value;

		var toHour = document.forms[0].JamSelesai_h.value;
		var toMinutes = document.forms[0].JamSelesai_n.value;
		
		var d4 = new Date();
		d4.setHours(fromHour);
		d4.setMinutes(fromMinutes);
		
		var fromJam = d4.getTime();


		
		var d5 = new Date();
		d5.setHours(toHour);
		d5.setMinutes(toMinutes);
		
		var toJam = d5.getTime();
		
		
		var errmsg = "";
		
		if (fromJam >= toJam){
			errmsg += "Jam ujian mulai harus lebih awal dari jam kuliah selesai\\n"
		}
		if (errmsg != ""){
			alert (errmsg);
			return false;
		}
	}
  </script>';
  
  TampilkanJudul($jdl);
  	  	  
  echo <<<END
  <table class=bsc cellspacing=1 width=100%>
  <form name='frmJadwalUAS' action='../$_SESSION[mnux].edit.php' method=POST onSubmit="return CheckForm(this)">
  <input type=hidden name='gos' value='Simpan' />
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='TahunID' value='$_SESSION[_uasTahun]' />
  <input type=hidden name='ProdiID' value='$_SESSION[_uasProdi]' />
  <input type=hidden name='ProgramID' value='$_SESSION[_uasProg]' />
  <input type=hidden name='id' value='$id' />
  <input type=hidden name='juasid' value='$juasid' />
  <tr><td class=inp>Program Studi:</td>
      <td class=ul1><b>$prodi[Nama]</b> <sup>($_SESSION[_uasProdi])</sup></td>
      <td class=inp>Program:</td>
      <td class=ul1>$w[ProgramID]</td>
      </tr>
  <tr><td class=inp>Tanggal Mulai Kuliah:</td>
      <td class=ul1>$w[KuliahTanggal] <sup>$NamaHari</sup></td>
	  <td class=inp>Jam Kuliah:</td>
      <td class=ul1>
        $w[_JM] &#8594; $w[_JS]
        </td>
      </tr>
  <tr><td class=inp>Ruang:</td>
      <td class=ul1>$w[RuangID]</td>
      <td class=inp>Kapasitas:</td>
      <td class=ul1>$w[Kapasitas]<sub>orang</sub></td>
      </tr>

  <tr><td class=inp>Matakuliah:</td>
      <td class=ul1 colspan=3 nowrap>$w[Nama] <sup>$w[MKKode]</sup></td>
      </tr>
  <tr><td class=inp>Dosen Pengajar:</td>
      <td class=ul1>$w[Dosen] <sup>$w[DosenID]</sup></td>
      <td class=inp>Kelas:</td>
      <td class=ul1>$w[NamaKLS]</td>
      </tr>
  <tr><td colspan=4><hr color=silver size=3></td></tr>
  <tr><td class=inp>Tanggal UAS:</td>
      <td class=ul1 nowrap>$opttgluas</td>
      <td class=inp>Jam UAS:</td>
      <td class=ul1 nowrap>
        $JamNya
      </td></tr>
<tr><td colspan="4"><hr noshade="noshade" style="color:#CCCCCC" /></td></tr>
  <tr><td class=inp>Ruang UAS 1:</td>
      <td class=ul1>
        <input type=text name='UASRuangID' value='$w[UASRuangID]' size=10 maxlength=50 
          onKeyUp="javascript:CariRuang('$_SESSION[_uasProdi]', 'frmJadwalUAS','$w[Kapasitas]','')" />
        &raquo;
      <a href='#'
        onClick="javascript:CariRuang('$_SESSION[_uasProdi]', 'frmJadwalUAS','$w[Kapasitas]','')" />Cari...</a> |
      <a href='#' onClick="javascript:frmJadwalUAS.UASRuangID.value=''">Reset</a>
        </td>
      <td class=inp>Kapasitas:</td>
      <td class=ul1>
        <input type=text name='UASKapasitas' value='$w[UASKapasitas]' size=4 maxlength=5  />
		<input type=hidden name='UASKapasitasH' value='$w[UASKapasitas]' size=4 maxlength=5 />
        <sub>orang</sub>
        </td>
      </tr>
  <tr><td class=inp>Kolom Ujian:</td>
	  <td class=ul1><input type=text name='UASKolomUjian' value='$w[UASKolomUjian]' onChange="HitungBaris('frmJadwalUAS')" size=1 maxlength=2 />
	  <td class=inp>Baris Ujian:</td>
	  <td class=ul1><input type=text name='UASBarisUjian' value='$w[UASBarisUjian]' size=1 maxlength=2  />
  </tr>
  
  <tr><td class=inp>Dosen Pengawas:</td>
      <td class=ul1 colspan=3 nowrap> 
      <input type=text name='UASDosenID' value='$w[UASDosenID]' size=10 maxlength=50 />
      <input type=text name='UASDosen' value='$w[UASDosen]' size=30 maxlength=50 onKeyUp="javascript:CariDosen('$_SESSION[_uasProdi]', 'frmJadwalUAS','')" />
	 <div style='text-align:right'>
      &raquo;
      <a href='#'
        onClick="javascript:CariDosen('$_SESSION[_uasProdi]', 'frmJadwalUAS','')" />Cari...</a> |
      <a href='#' onClick="javascript:frmJadwalUAS.UASDosenID.value='';frmJadwalUAS.UASDosen.value=''">Reset</a>
      </div>
      </td>
      </tr>
	  <tr><td colspan="4"><hr noshade="noshade" style="color:#CCCCCC" /></td></tr>
END;

  echo <<<END1
  <tr><td class=inp>Ruang UAS 2:</td>
      <td class=ul1>
        <input type=text name='UASRuangID2' value='$w0[UASRuangID]' size=10 maxlength=50 
          onKeyUp="javascript:CariRuang('$_SESSION[_uasProdi]', 'frmJadwalUAS', '', '2')" />
        &raquo;
      <a href='#'
        onClick="javascript:CariRuang('$_SESSION[_uasProdi]', 'frmJadwalUAS', '', '2')" />Cari...</a> |
      <a href='#' onClick="javascript:frmJadwalUAS.UASRuangID2.value=''">Reset</a> <sub> *) Kosongkan jika memakai 1 ruang saja</sub>
        </td>
      <td class=inp>Kapasitas:</td>
      <td class=ul1>
        <input type=text name='UASKapasitas2' value='$w0[UASKapasitas]' size=4 maxlength=5 />
		<input type=hidden name='UASKapasitasH2' value='$w0[UASKapasitas]' size=4 maxlength=5 />
        <sub>orang</sub>
        </td>
      </tr>
  <tr><td class=inp>Kolom Ujian:</td>
	  <td class=ul1><input type=text name='UASKolomUjian2' value='$w0[UASKolomUjian]' onChange="HitungBaris('frmJadwalUAS')" size=1 maxlength=2 />
	  <td class=inp>Baris Ujian:</td>
	  <td class=ul1><input type=text name='UASBarisUjian2' value='$w0[UASBarisUjian]' size=1 maxlength=2  />
  </tr>
  
  <tr><td class=inp>Dosen Pengawas:</td>
      <td class=ul1 colspan=3 nowrap>
      <input type=text name='UASDosenID2' value='$w0[UASDosenID]' size=10 maxlength=50 />
      <input type=text name='UASDosen2' value='$w0[UASDosen]' size=30 maxlength=50 onKeyUp="javascript:CariDosen('$_SESSION[_uasProdi]', 'frmJadwalUAS', '2')" />
      <div style='text-align:right'>
      &raquo;
      <a href='#'
        onClick="javascript:CariDosen('$_SESSION[_uasProdi]', 'frmJadwalUAS','2')" />Cari...</a> |
      <a href='#' onClick="javascript:frmJadwalUAS.UASDosenID2.value='';frmJadwalUAS.UASDosen2.value=''">Reset</a>
      </div>
      </td>
      </tr>
      <tr><td colspan="4"><hr noshade="noshade" style="color:#CCCCCC" /></td></tr>
END1;

echo <<<END3
  <tr><td class=inp>Ruang UAS 3:</td>
      <td class=ul1>
        <input type=text name='UASRuangID3' value='$w3[UASRuangID]' size=10 maxlength=50 
          onKeyUp="javascript:CariRuang('$_SESSION[_uasProdi]', 'frmJadwalUAS', '', '3')" />
        &raquo;
      <a href='#'
        onClick="javascript:CariRuang('$_SESSION[_uasProdi]', 'frmJadwalUAS', '', '3')" />Cari...</a> |
      <a href='#' onClick="javascript:frmJadwalUAS.UASRuangID3.value=''">Reset</a> 
        </td>
      <td class=inp>Kapasitas:</td>
      <td class=ul1>
        <input type=text name='UASKapasitas3' value='$w3[UASKapasitas]' size=4 maxlength=5 />
		<input type=hidden name='UASKapasitasH3' value='$w3[UASKapasitas]' size=4 maxlength=5 />
        <sub>orang</sub>
        </td>
      </tr>
  <tr><td class=inp>Kolom Ujian:</td>
	  <td class=ul1><input type=text name='UASKolomUjian3' value='$w3[UASKolomUjian]' onChange="HitungBaris('frmJadwalUAS')" size=1 maxlength=2 />
	  <td class=inp>Baris Ujian:</td>
	  <td class=ul1><input type=text name='UASBarisUjian3' value='$w3[UASBarisUjian]' size=1 maxlength=2  />
  </tr>
  
  <tr><td class=inp>Dosen Pengawas:</td>
      <td class=ul1 colspan=3 nowrap>
      <input type=text name='UASDosenID3' value='$w3[UASDosenID]' size=10 maxlength=50 />
      <input type=text name='UASDosen3' value='$w3[UASDosen]' size=30 maxlength=50 onKeyUp="javascript:CariDosen('$_SESSION[_uasProdi]', 'frmJadwalUAS', '3')" />
      <div style='text-align:right'>
      &raquo;
      <a href='#'
        onClick="javascript:CariDosen('$_SESSION[_uasProdi]', 'frmJadwalUAS','3')" />Cari...</a> |
      <a href='#' onClick="javascript:frmJadwalUAS.UASDosenID3.value='';frmJadwalUAS.UASDosen3.value=''">Reset</a>
      </div>
      </td>
      </tr>
      <tr><td colspan="4"><hr noshade="noshade" style="color:#CCCCCC" /></td></tr>
END3;

echo <<<END4
  <tr><td class=inp>Ruang UAS 4:</td>
      <td class=ul1>
        <input type=text name='UASRuangID4' value='$w4[UASRuangID]' size=10 maxlength=50 
          onKeyUp="javascript:CariRuang('$_SESSION[_uasProdi]', 'frmJadwalUAS', '', '4')" />
        &raquo;
      <a href='#'
        onClick="javascript:CariRuang('$_SESSION[_uasProdi]', 'frmJadwalUAS', '', '4')" />Cari...</a> |
      <a href='#' onClick="javascript:frmJadwalUAS.UASRuangID4.value=''">Reset</a> 
        </td>
      <td class=inp>Kapasitas:</td>
      <td class=ul1>
        <input type=text name='UASKapasitas4' value='$w4[UASKapasitas]' size=4 maxlength=5 />
		<input type=hidden name='UASKapasitasH4' value='$w4[UASKapasitas]' size=4 maxlength=5 />
        <sub>orang</sub>
        </td>
      </tr>
  <tr><td class=inp>Kolom Ujian:</td>
	  <td class=ul1><input type=text name='UASKolomUjian4' value='$w4[UASKolomUjian]' onChange="HitungBaris('frmJadwalUAS')" size=1 maxlength=2 />
	  <td class=inp>Baris Ujian:</td>
	  <td class=ul1><input type=text name='UASBarisUjian4' value='$w4[UASBarisUjian]' size=1 maxlength=2  />
  </tr>
  
  <tr><td class=inp>Dosen Pengawas:</td>
      <td class=ul1 colspan=3 nowrap>
      <input type=text name='UASDosenID4' value='$w4[UASDosenID]' size=10 maxlength=50 />
      <input type=text name='UASDosen4' value='$w4[UASDosen]' size=30 maxlength=50 onKeyUp="javascript:CariDosen('$_SESSION[_uasProdi]', 'frmJadwalUAS', '4')" />
      <div style='text-align:right'>
      &raquo;
      <a href='#'
        onClick="javascript:CariDosen('$_SESSION[_uasProdi]', 'frmJadwalUAS','4')" />Cari...</a> |
      <a href='#' onClick="javascript:frmJadwalUAS.UASDosenID4.value='';frmJadwalUAS.UASDosen4.value=''">Reset</a>
      </div>
      </td>
      </tr>
      <tr><td colspan="4"><hr noshade="noshade" style="color:#CCCCCC" /></td></tr>
END4;

echo <<<END5
  <tr><td class=inp>Ruang UAS 5:</td>
      <td class=ul1>
        <input type=text name='UASRuangID5' value='$w5[UASRuangID]' size=10 maxlength=50 
          onKeyUp="javascript:CariRuang('$_SESSION[_uasProdi]', 'frmJadwalUAS', '', '5')" />
        &raquo;
      <a href='#'
        onClick="javascript:CariRuang('$_SESSION[_uasProdi]', 'frmJadwalUAS', '', '5')" />Cari...</a> |
      <a href='#' onClick="javascript:frmJadwalUAS.UASRuangID5.value=''">Reset</a> 
        </td>
      <td class=inp>Kapasitas:</td>
      <td class=ul1>
        <input type=text name='UASKapasitas5' value='$w5[UASKapasitas]' size=4 maxlength=5 />
		<input type=hidden name='UASKapasitasH5' value='$w5[UASKapasitas]' size=4 maxlength=5 />
        <sub>orang</sub>
        </td>
      </tr>
  <tr><td class=inp>Kolom Ujian:</td>
	  <td class=ul1><input type=text name='UASKolomUjian5' value='$w5[UASKolomUjian]' onChange="HitungBaris('frmJadwalUAS')" size=1 maxlength=2 />
	  <td class=inp>Baris Ujian:</td>
	  <td class=ul1><input type=text name='UASBarisUjian5' value='$w5[UASBarisUjian]' size=1 maxlength=2  />
  </tr>
  
  <tr><td class=inp>Dosen Pengawas:</td>
      <td class=ul1 colspan=3 nowrap>
      <input type=text name='UASDosenID5' value='$w5[UASDosenID]' size=10 maxlength=50 />
      <input type=text name='UASDosen5' value='$w5[UASDosen]' size=30 maxlength=50 onKeyUp="javascript:CariDosen('$_SESSION[_uasProdi]', 'frmJadwalUAS', '5')" />
      <div style='text-align:right'>
      &raquo;
      <a href='#'
        onClick="javascript:CariDosen('$_SESSION[_uasProdi]', 'frmJadwalUAS','5')" />Cari...</a> |
      <a href='#' onClick="javascript:frmJadwalUAS.UASDosenID5.value='';frmJadwalUAS.UASDosen5.value=''">Reset</a>
      </div>
      </td>
      </tr>
      <tr><td colspan="4"><hr noshade="noshade" style="color:#CCCCCC" /></td></tr>
END5;

  echo "<tr><td class=ul1 colspan=4 align=center>
      <input type=submit name='Simpan' value='Simpan' onclick='return cekJdwl()' />
      <input type=button name='Batal' value='Batal' onClick=window.close() />
      </td></tr>
  </form>
  </table>
  
  <div class='box0' id='cariruang'></div>
  <div class='box0' id='caridosen'></div>
";
}

function Simpan($md, $id, $juasid) {
  $w['UASTanggal'] = "$_REQUEST[UASTanggal_y]-$_REQUEST[UASTanggal_m]-$_REQUEST[UASTanggal_d]";
  
  if (!empty($_REQUEST['JamUjian'])) {
  	$JJ = GetFields('jamujian', "JamUjianID='$_REQUEST[JamUjian]' AND ProdiID", $_SESSION['_uasProdi'], "left(JamMulai,5) as JamMulai, left(JamSelesai,5) as JamSelesai");
    
    $w['UASJamMulai'] = "$JJ[JamMulai]";
  	$w['UASJamSelesai'] = "$JJ[JamSelesai]";
  }
  else {
  $w['UASJamMulai'] = "$_REQUEST[UASJamMulai_h]:$_REQUEST[UASJamMulai_n]";
  $w['UASJamSelesai'] = "$_REQUEST[UASJamSelesai_h]:$_REQUEST[UASJamSelesai_n]";
  }
  
  $w['UASRuangID'] = $_REQUEST['UASRuangID'];
  $w['UASKapasitas'] = $_REQUEST['UASKapasitas'];
  $w['UASKolomUjian'] = $_REQUEST['UASKolomUjian'];
  $w['UASDosenID'] = $_REQUEST['UASDosenID'];
  
  $w['UASRuangID2'] = $_REQUEST['UASRuangID2'];
  $w['UASKapasitas2'] = $_REQUEST['UASKapasitas2'];
  $w['UASKolomUjian2'] = $_REQUEST['UASKolomUjian2'];
  $w['UASDosenID2'] = $_REQUEST['UASDosenID2'];
  
  $w['UASRuangID3'] = $_REQUEST['UASRuangID3'];
  $w['UASKapasitas3'] = $_REQUEST['UASKapasitas3'];
  $w['UASKolomUjian3'] = $_REQUEST['UASKolomUjian3'];
  $w['UASDosenID3'] = $_REQUEST['UASDosenID3'];

  $w['UASRuangID4'] = $_REQUEST['UASRuangID4'];
  $w['UASKapasitas4'] = $_REQUEST['UASKapasitas4'];
  $w['UASKolomUjian4'] = $_REQUEST['UASKolomUjian4'];
  $w['UASDosenID4'] = $_REQUEST['UASDosenID4'];
  
  $w['UASRuangID5'] = $_REQUEST['UASRuangID5'];
  $w['UASKapasitas5'] = $_REQUEST['UASKapasitas5'];
  $w['UASKolomUjian5'] = $_REQUEST['UASKolomUjian5'];
  $w['UASDosenID5'] = $_REQUEST['UASDosenID5'];
  
  $w['JadwalID'] = $id;
  $w['TahunID'] = $_REQUEST['TahunID'];
  $w['ProdiID'] = $_REQUEST['ProdiID'];
  $w['ProgramID'] = $_REQUEST['ProgramID'];
  
  // *** Cek semuanya dulu ***
   $cekada = GetFields('tahun', "NA='N' and TahunID='$w[TahunID]' 
            and ProdiID = '$w[ProdiID]' 
            and left(TglUASMulai,10)<= '$w[UASTanggal]' and 
            left(TglUASSelesai,10)>= '$w[UASTanggal]' and ProgramID","$w[ProgramID]",
            "*");
  $cek = GetFields('tahun', "NA='N' and TahunID='$w[TahunID]' 
            and ProdiID = '$w[ProdiID]' and ProgramID","$w[ProgramID]",
            "date_format(TglUASMulai,'%d %M %Y') as Mulai, date_format(TglUASSelesai,'%d %M %Y') as Selesai");
  
  if(empty($cekada)){
    die(ErrorMsg('Kesalahan Tanggal', 
      "Tanggal yang anda setting tidak sesuai dengan Tanggal penjadwalan UAS,<br/>
       yaitu dari Tanggal : <b>$cek[Mulai]</b> sampai dengan <b>$cek[Selesai]</b>.
      <hr size=1 color=silver />
      <p align=center>
      <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />
      </p>"));
  }
  
  $oke = '';
  if (!empty($w['UASRuangID'])) $oke .= '';
  //$oke .= CekTanggal($w, $juasid);
  
  // Ambil data MK
  $mk = GetFields('mk', "MKID", $w['MKID'], "Nama,MKKode,KurikulumID,SKS,Sesi");
  // Jika semuanya baik2 saja
  if (empty($oke)) {
    // Jika mode=edit
    if ($md == 0) {
      /*if (!empty($w[UASRuangID])) 
	  {
      $s = "update jadwaluas
        set RuangID = '$w[UASRuangID]',
			DosenID = '$w[UASDosenID]',
            Tanggal = '$w[UASTanggal]',
			Kapasitas = '$w[UASKapasitas]',
            JamMulai = '$w[UASJamMulai]', JamSelesai = '$w[UASJamSelesai]',
			KolomUjian = '$w[UASKolomUjian]',
			TanggalEdit = now(),
            LoginEdit = '$_SESSION[_Login]'
        where JadwalUASID = '$juasid' ";
      $r = _query($s);
	   $s3 = "delete from uasmhsw
        where JadwalUASID = '$juasid' ";
      $r3 = _query($s3);
	  DaftarkanMhswKeRuangUAS($w, $juasid);
	  }
	  if (!empty($w[UASRuangID2])) 
	  {
	  if (empty($juasid2)) {
	  $s = "insert into jadwaluas
        (KodeID, TahunID, JadwalID, DosenID, 
        Tanggal, JamMulai, JamSelesai,
		Kapasitas, RuangID, KolomUjian, TanggalBuat, LoginBuat)
        values
        ('".KodeID."', '$w[TahunID]', '$w[JadwalID]', '$w[UASDosenID2]',
        '$w[UASTanggal]', '$w[UASJamMulai]', '$w[UASJamSelesai]',
		'$w[UASKapasitas2]', '$w[UASRuangID2]', '$w[UASKolomUjian2]', now(), '$_SESSION[_Login]')";
      $r = _query($s);
	  
	  $JadwalUASID2 = mysql_insert_id();
	  DaftarkanMhswKeRuangUAS2($w, $JadwalUASID2);
	  }
	  else {
	  $s2 = "update jadwaluas
        set RuangID = '$w[UASRuangID2]',
			DosenID = '$w[UASDosenID2]',
            Tanggal = '$w[UASTanggal]',
			Kapasitas = '$w[UASKapasitas2]',
            JamMulai = '$w[UASJamMulai]', JamSelesai = '$w[UASJamSelesai]',
			KolomUjian = '$w[UASKolomUjian2]',
			TanggalEdit = now(),
            LoginEdit = '$_SESSION[_Login]'
        where JadwalUASID = '$juasid2' ";
      $r2 = _query($s2);
	  
	   $s4 = "delete from uasmhsw
        where JadwalUASID = '$juasid2' ";
      $r4 = _query($s4);
	  DaftarkanMhswKeRuangUAS2($w, $juasid2);
	  }
	  }

	  TutupScript();*/
	  
    }
    elseif ($md == 1) {
	if ($w[UASRuangID] !='') {
      $s = "insert into jadwaluas
        (KodeID, TahunID, JadwalID, DosenID, 
        Tanggal, JamMulai, JamSelesai,
		Kapasitas, RuangID, KolomUjian, TanggalBuat, LoginBuat)
        values
        ('".KodeID."', '$w[TahunID]', '$w[JadwalID]', '$w[UASDosenID]',
        '$w[UASTanggal]', '$w[UASJamMulai]', '$w[UASJamSelesai]',
		'$w[UASKapasitas]', '$w[UASRuangID]', '$w[UASKolomUjian]', now(), '$_SESSION[_Login]')";
      $r = _query($s);
	  
	  $JadwalUASID = mysql_insert_id();
	  DaftarkanMhswKeRuangUAS($w, $JadwalUASID, '1');
	  }
	 if ($w[UASRuangID2] !='') {
	  $s = "insert into jadwaluas
        (KodeID, TahunID, JadwalID, DosenID, 
        Tanggal, JamMulai, JamSelesai,
		Kapasitas, RuangID, KolomUjian, TanggalBuat, LoginBuat)
        values
        ('".KodeID."', '$w[TahunID]', '$w[JadwalID]', '$w[UASDosenID2]',
        '$w[UASTanggal]', '$w[UASJamMulai]', '$w[UASJamSelesai]',
		'$w[UASKapasitas2]', '$w[UASRuangID2]', '$w[UASKolomUjian2]', now(), '$_SESSION[_Login]')";
      $r = _query($s);
	  
	  $JadwalUASID = mysql_insert_id();
	  DaftarkanMhswKeRuangUAS($w, $JadwalUASID,'2');
	  }
      if ($w[UASRuangID3] !='') {
	  $s = "insert into jadwaluas
        (KodeID, TahunID, JadwalID, DosenID, 
        Tanggal, JamMulai, JamSelesai,
		Kapasitas, RuangID, KolomUjian, TanggalBuat, LoginBuat)
        values
        ('".KodeID."', '$w[TahunID]', '$w[JadwalID]', '$w[UASDosenID3]',
        '$w[UASTanggal]', '$w[UASJamMulai]', '$w[UASJamSelesai]',
		'$w[UASKapasitas3]', '$w[UASRuangID3]', '$w[UASKolomUjian3]', now(), '$_SESSION[_Login]')";
      $r = _query($s);
	  
	  $JadwalUASID = mysql_insert_id();
	  DaftarkanMhswKeRuangUAS($w, $JadwalUASID,'3');
	  }
      if ($w[UASRuangID4] !='') {
	  $s = "insert into jadwaluas
        (KodeID, TahunID, JadwalID, DosenID, 
        Tanggal, JamMulai, JamSelesai,
		Kapasitas, RuangID, KolomUjian, TanggalBuat, LoginBuat)
        values
        ('".KodeID."', '$w[TahunID]', '$w[JadwalID]', '$w[UASDosenID4]',
        '$w[UASTanggal]', '$w[UASJamMulai]', '$w[UASJamSelesai]',
		'$w[UASKapasitas4]', '$w[UASRuangID4]', '$w[UASKolomUjian4]', now(), '$_SESSION[_Login]')";
      $r = _query($s);
	  
	  $JadwalUASID = mysql_insert_id();
	  DaftarkanMhswKeRuangUAS($w, $JadwalUASID,'4');
	  }
      if ($w[UASRuangID5] !='') {
	  $s = "insert into jadwaluas
        (KodeID, TahunID, JadwalID, DosenID, 
        Tanggal, JamMulai, JamSelesai,
		Kapasitas, RuangID, KolomUjian, TanggalBuat, LoginBuat)
        values
        ('".KodeID."', '$w[TahunID]', '$w[JadwalID]', '$w[UASDosenID5]',
        '$w[UASTanggal]', '$w[UASJamMulai]', '$w[UASJamSelesai]',
		'$w[UASKapasitas5]', '$w[UASRuangID5]', '$w[UASKolomUjian5]', now(), '$_SESSION[_Login]')";
      $r = _query($s);
	  
	  $JadwalUASID = mysql_insert_id();
	  DaftarkanMhswKeRuangUAS($w, $JadwalUASID,'5');
	  }
	  
	  
      TutupScript();
    }
  }
  // Jika ada yg salah
  else {
	die(ErrorMsg('Ada Kesalahan', 
      "Berikut adalah pesan kesalahannya: 
      <ol>$oke</ol>
      <hr size=1 color=silver />
      <p align=center>
      
	  <input type=button name='Tutup' value='Tutup' onClick=\"window.close()\" />
      </p>"));
  }
}
function CekRuang($w, $JadwalUASID) {
  
  $s = "select j.JadwalUASID, j.JamMulai, j.JamSelesai, j.DosenID, 
    d.Nama as NamaDosen, j.JadwalID, j2.Nama, j2.MKKode, j2.ProdiID, j2.ProgramID, 
	p.Nama as _PRG, pr.Nama as _PRD, date_format(j.Tanggal, '%d-%m-%y') as _Tanggal 
    from jadwaluas j
      left outer join dosen d on d.Login = j.DosenID and d.KodeID = '".KodeID."'
      left outer join jadwal j2 on j.JadwalID = j2.JadwalID and j2.KodeID = '".KodeID."'
	  left outer join program p on p.ProgramID = j2.ProgramID and p.KodeID = '".KodeID."'
      left outer join prodi pr on pr.ProdiID = j2.ProdiID and pr.KodeID = '".KodeID."'
    where j.TahunID = '$w[TahunID]'
      and j.RuangID = '$w[UASRuangID]'
      and j.Tanggal = '$w[UASTanggal]'
      and (('$w[UASJamMulai]:00' <= j.JamMulai and j.JamMulai <= '$w[UASJamSelesai]:59')
      or  ('$w[UASJamMulai]:00' <= j.JamSelesai and j.JamSelesai <= '$w[UASJamSelesai]:59'))
      and j.NA = 'N'
	  and j.KodeID='".KodeID."'
      and j.JadwalUASID <> '$JadwalUASID' ";
  //die("<pre>$s</pre>");
  $r = _query($s);
  $a = '';
  while ($w = _fetch_array($r)) {
    $a .= "<li>
      <b>Jadwal UAS bentrok dengan</b>:<br />
      <table class=bsc width=400>
      <tr><td class=inp width=100>Matakuliah:</td>
          <td class=ul1>$w[Nama] <sup>($w[MKKode])</td>
          </tr>
	  <tr><td class=inp>Tanggal:</td>
          <td class=ul1>$w[_Tanggal]</td>
          </tr>
      <tr><td class=inp>Jam:</td>
          <td class=ul1>$w[JamMulai] &minus; $w[JamSelesai]</td>
          </tr>
      <tr><td class=inp>Dosen:</td>
          <td class=ul1>$w[NamaDosen]</td>
          </tr>
      <tr><td class=inp>Program Studi:</td>
          <td class=ul1>$w[_PRD] <sup>($w[ProdiID])</sup></td>
          </tr>
      <tr><td class=inp>Prg Pendidikan:</td>
          <td class=ul1>$w[_PRG] <sup>($w[ProgramID])</sup></td>
          </tr>
      </table>
      </li>";
  }
  return $a;
}

function CekTanggal($w, $juasid)
{ $uas = GetFields('tahun', "TahunID='$w[TahunID]' and ProgramID='$w[ProgramID]' and ProdiID='$w[ProdiID]' and KodeID", KodeID, "LEFT(TglUASMulai, 10) as _TglUASMulai, LEFT(TglUASSelesai, 10) as _TglUASSelesai");

  $a = '';
  if ($uas['_TglUASMulai'] > $w['UASTanggal'] or $w['UASTanggal'] > $uas['_TglUASSelesai'])
  {
    $a .= "<li>
      <b>Tanggal UAS ".$w[UASTanggal]." berada di luar Tanggal UAS yang direncanakan</b>:<br />
		 Rentang waktu yang disediakan: ".$uas['_TglUASMulai']." s/d ".$uas['_TglUASSelesai']."<br />
      ";
  }
  return $a;
}
function DaftarkanMhswKeRuangUAS2($w, $JadwalUASID2)
{	//echo "JADWALUASID=$JadwalUASID1 ,JADWALID=$w[JadwalID]<br>";
	if($JadwalUASID2+0 != 0)
    {   $limit2= $w['UASKapasitas2']+0;
		$batas = $w['UASKapasitas']+0;
		$s4 = "select k.MhswID from krs k,khs h
      left outer join mhsw m on m.MhswID = k.MhswID and m.KodeID = '".KodeID."'
	  left outer join prodi p on p.ProdiID = m.ProdiID
    where k.JadwalID = '$w[JadwalID]'
	AND h.MhswID=k.MhswID
	AND h.TahunID=k.TahunID
	Group By k.MhswID
    order by k.MhswID limit $batas, $limit2";
		$r4 = _query($s4);
		$n4 = _num_rows($r4);
		echo "JUMLAH: $n2<br>";
		while($w4 = _fetch_array($r4))
		{   //Cek apakah mahasiswa sudah terdaftar
			//$ada = GetaField('uasmhsw', "MhswID='$w4[MhswID]' and JadwalUASID='$JadwalUASID2' and KodeID", KodeID, "UASMhswID");
			if(empty($ada))
			{	$s3 = "insert into uasmhsw
				(KodeID, MhswID, JadwalUASID, TahunID, RuangID, TanggalBuat, LoginBuat)
				values
				('".KodeID."', '$w4[MhswID]', '$JadwalUASID2', '$w[TahunID]', '$w[UASRuangID2]', now(), '$_SESSION[_Login]')";
				$r3 = _query($s3);
			}
		}
	}
}

function DaftarkanMhswKeRuangUAS($w, $JadwalUASID1, $Ruang)
{	//echo "JADWALUASID=$JadwalUASID1 ,JADWALID=$w[JadwalID]<br>";
	if($JadwalUASID1+0 != 0)
    {   
    	if ($Ruang == 1) { $start=0; $limit = $w['UASKapasitas']+0; }
        if ($Ruang == 2) { $start=$w['UASKapasitas']; $limit = $w['UASKapasitas2']+0; }
        if ($Ruang == 3) { $start=$w['UASKapasitas']+$w['UASKapasitas2']; $limit = $w['UASKapasitas3']+0; }
        if ($Ruang == 4) { $start=$w['UASKapasitas']+$w['UASKapasitas2']+$w['UASKapasitas3']; $limit = $w['UASKapasitas4']+0; }
        if ($Ruang == 5) { $start=$w['UASKapasitas']+$w['UASKapasitas2']+$w['UASKapasitas3']+$w['UASKapasitas4']; 
        					$limit = $w['UASKapasitas5']+0; }
		
        $s6 = "select k.MhswID,m.StatusAwalID,k.MKKode,k.TahunID,h.Sesi,h.ProgramID from khs h, krs k
              left outer join mhsw m on m.MhswID = k.MhswID and m.KodeID = '".KodeID."'
              left outer join prodi p on p.ProdiID = m.ProdiID
            where k.JadwalID = '$w[JadwalID]'
            AND h.MhswID=k.MhswID
            AND h.TahunID=k.TahunID
            Group By k.MhswID
            order by k.MhswID limit $start,$limit";
		$r2 = _query($s6);
		$n2 = _num_rows($r2);
		echo "JUMLAH: $n2<br>";
		while($w2 = _fetch_array($r2))
		{   //Cek apakah mahasiswa sudah terdaftar
			//$ada = GetaField('uasmhsw', "MhswID='$w2[MhswID]' and JadwalUASID='$JadwalUASID1' and KodeID", KodeID, "UASMhswID");
			//$totByrMhs = GetaField("khs", "TahunID='$w2[TahunID]' And MhswID", $w2['MhswID'],'Bayar')+0;
			//echo $w2['MhswID'].'-'.$totByrMhs.'<br>';
			if(empty($ada))
			{	$s1 = "insert into uasmhsw
				(KodeID, MhswID, JadwalUASID, TahunID, RuangID, TanggalBuat, LoginBuat)
				values
				('".KodeID."', '$w2[MhswID]', '$JadwalUASID1', '$w[TahunID]', '$w[UASRuangID]', now(), '$_SESSION[_Login]')";
				$r1 = _query($s1);
			}else{
				$err .= "$w2[MhswID]<br>";
			}
		}
        $JumlahMhsw = GetaField('uasmhsw', "JadwalUASID='$JadwalUASID1' and KodeID", KodeID, 'count(UASMhswID)');
		$s = _query("update jadwaluas set JumlahMhsw = '$JumlahMhsw' where JadwalUASID='$JadwalUASID1'");
		if ($err!='') $_SESSION['Error'] = 'Ada mahasiswa yg tidak bisa masuk dalam jadwal:<br>'.$err.'<br>';
	}
	else
	die(ErrorMsg("Error", "Tidak ditemukan Jadwal UAS yang dimaksud. Harap menghubungi yang berwenang."));
}

function GetDateOption2($dt, $nm='dt',$loc='') {
  $arr = Explode('-', $dt);
  $_dy = GetNumberOption(1, 31, $arr[2]);
  $_mo = GetMonthOption($arr[1]);
  $_yr = GetNumberOption(1930, Date('Y')+2, $arr[0]);
  return "<select name='".$nm."_d' onChange=\"$loc\">$_dy</select>
    <select name='".$nm."_m' onChange=\"$loc\">$_mo</select>
    <select name='".$nm."_y' onChange=\"$loc\">$_yr</select>";
}
function TutupScript() {
$sclose = (!empty($_SESSION['Error'])) ? "":"self.close();";

echo <<<SCR
$_SESSION[Error]
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?mnux=$_SESSION[mnux]&gos=';
    $sclose
    return false;
  }
  ttutup();
</SCRIPT>
SCR;
}
function JdwlUASScript() {
  echo <<<SCR
  <script>
  function toggleBox(szDivID, iState) // 1 visible, 0 hidden
  {
    if(document.layers)	   //NN4+
    {
       document.layers[szDivID].visibility = iState ? "show" : "hide";
    }
    else if(document.getElementById)	  //gecko(NN6) + IE 5+
    {
        var obj = document.getElementById(szDivID);
        obj.style.visibility = iState ? "visible" : "hidden";
    }
    else if(document.all)	// IE 4
    {
        document.all[szDivID].style.visibility = iState ? "visible" : "hidden";
    }
  }
  function CariRuang(ProdiID, frm, kapasitasR,Ke) {
    if (eval(frm + ".UASRuangID" + Ke + ".value != ''")) {
      eval(frm + ".UASRuangID" + Ke + ".focus()");
      showRuang(ProdiID, frm, eval(frm +".UASRuangID" + Ke + ".value"), 'cariruang', kapasitasR, eval("frmJadwalUAS.UASTanggal_y.value"),eval("frmJadwalUAS.UASTanggal_m.value"),eval("frmJadwalUAS.UASTanggal_d.value"),eval("frmJadwalUAS.UASJamMulai_h.value"),eval("frmJadwalUAS.UASJamMulai_n.value"), eval("frmJadwalUAS.UASJamSelesai_h.value"),eval("frmJadwalUAS.UASJamSelesai_n.value"), eval(frm +".TahunID.value"),Ke);
      toggleBox('cariruang', 1);
    }
  }
  
    function CariRuang2(ProdiID, frm, kapasitasR) {
	if (eval(frm + ".UASRuangID2.value != ''")) {
      eval(frm + ".UASRuangID2.focus()");
      showRuang(ProdiID, frm, eval(frm +".UASRuangID2.value"), 'cariruang', kapasitasR, eval(frm +".UASTanggal_y.value"),eval(frm 		+".UASTanggal_m.value"),eval(frm +".UASTanggal_d.value"),eval(frm +".UASJamMulai_h.value"),eval(frm +".UASJamMulai_n.value"), eval(frm +".UASJamSelesai_h.value"),eval(frm +".UASJamSelesai_n.value"), eval(frm +".TahunID.value"));
      toggleBox('cariruang', 1);
    }
  }
  
  function CariDosen(ProdiID, frm, Ke) {
    if (eval(frm + ".UASDosen" + Ke + ".value != ''")) {
      eval(frm + ".UASDosen" + Ke + ".focus()");
      showDosen(ProdiID, frm, eval(frm + ".UASDosen" + Ke + ".value"), 'caridosen', eval(frm +".UASTanggal_y.value"),eval(frm 		+".UASTanggal_m.value"),eval(frm +".UASTanggal_d.value"),eval(frm +".UASJamMulai_h.value"),eval(frm +".UASJamMulai_n.value"), eval(frm +".UASJamSelesai_h.value"),eval(frm +".UASJamSelesai_n.value"), eval(frm +".TahunID.value"), Ke);
      toggleBox('caridosen', 1);
    }
  }
    function CariDosen2(ProdiID, frm, kapasitasR) {
    if (eval(frm + ".UASDosen2.value != ''")) {
      eval(frm + ".UASDosen2.focus()");
      showDosen(ProdiID, frm, eval(frm +".UASDosen2.value"), 'caridosen', eval(frm +".UASTanggal_y.value"),eval(frm 		+".UASTanggal_m.value"),eval(frm +".UASTanggal_d.value"),eval(frm +".UASJamMulai_h.value"),eval(frm +".UASJamMulai_n.value"), eval(frm +".UASJamSelesai_h.value"),eval(frm +".UASJamSelesai_n.value"), eval(frm +".TahunID.value"));
      toggleBox('caridosen', 1);
    }
  }
  function HitungBaris(frm)
  {  var kapasitas, kolom;
	 if(eval(frm + ".UASKapasitas.value == ''")) kapasitas = 0;
	 else kapasitas = parseInt(eval(frm + ".UASKapasitas.value"));
	 if(eval(frm + ".UASKolomUjian.value != ''") && eval(frm + ".UASKolomUjian.value != 0")) 
	 {	kolom = parseInt(eval(frm + ".UASKolomUjian.value"));
		if(kolom != 0)
		{	baris = Math.ceil(kapasitas/kolom);
			eval(frm + ".UASBarisUjian.value = " + baris);
		}
	 }	 
	 else 
	 {	eval(frm + ".UASKolomUjian.value = 1");
		eval(frm + ".UASBarisUjian.value = " + kapasitas);
	 } 
  }
  </script>
SCR;
}
?>

</BODY>
</HTML>
