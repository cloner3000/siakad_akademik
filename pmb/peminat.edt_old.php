<?php
// Author : Emanuel Setio Dewo
// Email  : setio.dewo@gmail.com

session_start();
include_once "../sisfokampus1.php";
HeaderSisfoKampus("Data Aplikan");

// *** Parameters ***

// *** Main ***
$gos = (empty($_REQUEST['gos']))? "fnEdit" : $_REQUEST['gos'];
$gos();

// *** Functions ***
function fnEdit() {
  $Institusi = GetaField('identitas', 'Kode', KodeID, 'Nama');
  
  $gel = sqling($_REQUEST['gel']);
  $md = $_REQUEST['md']+0;
  $id = $_REQUEST['id'];
  
  $g = GetFields('pmbperiod', "PMBPeriodID='$gel' and KodeID", KodeID, "*");
  if (empty($g) || $g['NA'] == 'Y')
    die(ErrorMsg("Error",
    "Gelombang <b>$gelombang</b> tidak ditemukan atau sudah tidak aktif.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='btnClose' value='Tutup' onClick='window.close()' />"));
  
  if ($md == 0) {
    $w = GetFields('aplikan', "AplikanID='$id' and KodeID", KodeID, '*');
    if (empty($w)) die(ErrorMsg('Error',
      "Data aplikan dengan nomer: <b>$id</b> tidak ditemukan.<br />
      Hubungi Sysadmin untuk informasi lebih lanjut.
      <hr size=1 color=silver />
      <input type=button name='btnClose' value='Tutup' onClick='window.close()' />"));
    $jdl = "Edit Data Aplikan - $w[PMBPeriodID]";
    $_id = "<font size=+1>$id</font>";
  }
  elseif ($md == 1) {
    $w = array();
    $w['TanggalLahir'] = date('Y-m-d');
    $w['TanggalBuat'] = date('Y-m-d');
    $w['TanggalEdit'] = date('Y-m-d');
	$w['Kelamin'] = 'P';
	$w['SudahBekerja'] = 'N';
    $jdl = "Tambah Data Aplikan - $gel";
    $_id = "<font color=red>[ Autogenerated ]</font>";
  }
  else die(ErrorMsg("Error",
    "Mode edit <b>$md</b> tidak dikenali oleh sistem.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='btnClose' value='Tutup' onClick='window.close()' />"));
  
  echo <<<ESD
  <SCRIPT LANGUAGE="JavaScript1.2">
  <!--
  function carisekolah(frm){
    lnk = "carisekolah.php?SekolahID="+frm.AsalSekolah.value+"&Cari="+frm.NamaSekolah.value;
	win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    win2.creator = frm;
  }
  function caript(frm){
    lnk = "cariperguruantinggi.php?SekolahID="+frm.AsalSekolah.value+"&Cari="+frm.NamaSekolah.value;
	win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    win2.creator = frm;
  }
  function tambahsekolah(frm) {
	lnk = "pmbasalsek.edit.php?md=1";
	win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    win2.creator = self;
  }
  function tambahpt(frm) {
	lnk = "pmbasalpt.edit.php?md=1";
	win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    win2.creator = self;
  }
  function ChooseThis(name, target)
  {	var count = data.JumlahSumberInfo.value;
	if(document.getElementById(name+target).checked == true)
	{	for(var i = 0; i < count; i++)
		{	document.getElementById(name+i).checked = false;
		}
		document.getElementById(name+target).checked = true;
	}
  }
  -->
  </script>
   <script type='text/javascript'>
			function createRequestObject()
			{
			var ro;
			var browser = navigator.appName;
			if(browser == "Microsoft Internet Explorer")
			{
			ro = new ActiveXObject("Microsoft.XMLHTTP");
			}
			else
			{
			ro = new XMLHttpRequest();
			}
			return ro;
			}

				var xmlhttp = createRequestObject();
				function rubah(pilih)
				{
					var Propinsi = pilih.value;
				
					if (!Propinsi) return;
					xmlhttp.open('get', 'kabupaten.php?idPropinsi='+Propinsi, true);
					xmlhttp.onreadystatechange = function()
					{
					if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
					document.getElementById("kabupaten").innerHTML = xmlhttp.responseText;
					return false;
					}
				
					xmlhttp.send(null);
				}
	

				var xmlhttp = createRequestObject2();
				function kabsel(pilih)
				{
					var Propinsi = pilih.value;
				
					if (!Propinsi) return;
					xmlhttp.open('get', 'kabsel.php?idPropinsi='+Propinsi, true);
					xmlhttp.onreadystatechange = function()
					{
					if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
					document.getElementById("kabsek").innerHTML = xmlhttp.responseText;
					return false;
					}
				
					xmlhttp.send(null);
				}
			
				function createRequestObject2()
					{
					var ro;
					var browser = navigator.appName;
					if(browser == "Microsoft Internet Explorer")
					{
					ro = new ActiveXObject("Microsoft.XMLHTTP");
					}
					else
					{
					ro = new XMLHttpRequest();
					}
					return ro;
					}
			
				var xmlhttp = createRequestObject3();
				function pilsekolah(pilih)
				{
					var kab = pilih.value;
				
					if (!kab) return;
					xmlhttp.open('get', 'pilsekolah.php?idKab='+kab, true);
					xmlhttp.onreadystatechange = function()
					{
					if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
					document.getElementById("idSekolah").innerHTML = xmlhttp.responseText;
					return false;
					}
				
					xmlhttp.send(null);
				}
					var xmlhttp = createRequestObject3();
				function pilPT(pilih)
				{
					var kab = pilih.value;
				
					if (!kab) return;
					xmlhttp.open('get', 'pilpt.php?cariPT='+kab, true);
					xmlhttp.onreadystatechange = function()
					{
					if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
					document.getElementById("idSekolah").innerHTML = xmlhttp.responseText;
					return false;
					}
				
					xmlhttp.send(null);
				}
				
					var xmlhttp = createRequestObject2();
				function pilihkotapt(pilih)
				{
					var kab = pilih.value;
				
					if (!kab) return;
					xmlhttp.open('get', 'kabprop.sel.php?sekolah='+kab, true);
					xmlhttp.onreadystatechange = function()
					{
					if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200))
					document.getElementById("PilihPropID").innerHTML = xmlhttp.responseText;
					return false;
					}
				
					xmlhttp.send(null);
				}

</script>
ESD;

  
  // Parameter
  $NamaSekolah = GetaField('asalsekolah', 'SekolahID', $w['AsalSekolah'], "concat(Nama, ', ', Kota)");
  $asalsekolah = $w['AsalSekolah'];
  $optkelamin = GetRadio("select Kelamin, Nama, concat(Kelamin, ' - ', Nama) as _kel from kelamin where NA='N'",
    'Kelamin', "_kel", 'Kelamin', $w[Kelamin], ', ');
  $TanggalLahir = GetDateOption($w['TanggalLahir'], 'TGL');
  $TanggalDaftar = GetDateOption($w['TanggalEdit'], 'TGLBuat');
  $optagama = GetOption2('agama', "concat(Agama, ' - ', Nama)", 'Agama', $w['Agama'], '', 'Agama');
  $optpendidikan = GetOption2('pendidikanortu', 'Nama', 'Pendidikan', $w['PendidikanAyah'], '', 'Pendidikan');
  $optpekerjaan = GetOption2('pekerjaanortu', 'Nama', 'Nama', $w['PekerjaanAyah'], '', 'Pekerjaan');
  $SumberInformasi = GetSumberInformasi($w['SumberInformasi']);
  $PilihanProgram = GetOption2('program', "concat(ProgramID, ' - ', Nama)", 'ProgramID', $w['ProgramID'], '', 'ProgramID');
  $optpresenter = GetOption2('presenter', "concat(PresenterID, ' - ', Nama)", 'PresenterID', $w['PresenterID'], '', 'PresenterID');
  $sudahbekerjachecked = ($w['SudahBekerja'] == 'Y')? 'checked' : '';
  
  $s8 = "select distinct(PropinsiID) as PropinsiID,NamaPropinsi from propinsi where NA='N' order by NamaPropinsi";
  $r8=_query($s8);
  $optionProp = "<option value='' selected></option>";
  while ($w8 = _fetch_array($r8)) {
  if ($w8['PropinsiID']==$_SESSION['Propinsi']) {
  $optionProp .= "<option value='$_SESSION[Propinsi]' selected>$w8[NamaPropinsi]</option>";
  }
  else{
  $optionProp .=  "<option value='$w8[PropinsiID]'>$w8[NamaPropinsi]</option>";
  }
  }
  
   //Propinsi Sekolah
  $s12 = "select distinct(PropinsiID) as PropinsiID,NamaPropinsi from asalsekolah where PropinsiID != '' and PropinsiID is not Null order by PropinsiID";
  $r12=_query($s12);
  $optionPropSekolah = "<option value='' selected>- Propinsi Asal Sekolah -</option>";
  while ($w12 = _fetch_array($r12)) {
  $optionPropSekolah .=  "<option value='$w12[PropinsiID]'>$w12[NamaPropinsi]</option>";
  }
  
  //Default Sekolah
 $defSek = GetFields('asalsekolah',"SekolahID", $asalsekolah,'SekolahID,Nama');
  $defSekolah =  "<option value='$defSek[SekolahID]'>$defSek[Nama]</option>";
  
  // Tampilkan
  CheckFormScript("PresenterID,Program,Nama,Kelamin,AsalSekolah,TahunLulus,NilaiSekolah,TempatLahir");
  echo <<<ESD
  <table class=bsc cellspacing=1 width=100% />
  <form name='data' action='?' method=POST onSubmit="return CheckForm(this)">
  <input type=hidden name='gos' value='fnSave' />
  <input type=hidden name='md' value='$md' />
  <input type=hidden name='gel' value='$gel' />
  <input type=hidden name='id' value='$id' />
  
  <tr><th class=ttl colspan=2>$jdl</th></tr>
  <tr><td class=inp>Nomer Aplikan :</td>
	  <td class=ul1>$_id</td>
      </tr>
  <tr><td class=inp>Tanggal Datang :</td>
      <td class=ul1>$TanggalDaftar</td>
      </tr>
  <tr><td class=inp>Presenter: </td>
	  <td class=ul1><select name='PresenterID'>$optpresenter</select></td>
	  </tr>
  <tr><td class=inp>Catatan Presenter: </td>
	  <td class=ul1>
      <textarea name='CatatanPresenter' cols=30 rows=3>$w[CatatanPresenter]</textarea>
      </td>
  <tr><td bgcolor=green colspan=2 height=1></td></tr>
  
  <tr><td class=inp>Nama :</td>
      <td class=ul1>
      <input type=text name='Nama' value='$w[Nama]' size=30 maxlength=50 />
      </td>
      </tr>
  <tr><td class=inp>Jenis Kelamin :</td>
      <td class=ul1>$optkelamin</td>
      </tr>
  <tr><td class=inp>Tempat Lahir :</td>
      <td class=ul1>
      <input type=text name='TempatLahir' value='$w[TempatLahir]' size=30 maxlength=50 />
      </td>
      </tr>
  <tr><td class=inp>Tanggal Lahir :</td>
      <td class=ul1>
      $TanggalLahir
      </td>
      </tr>
  <tr><td class=inp>Alamat :</td>
      <td class=ul1>
      <input type=text name='Alamat' value='$w[Alamat]' size=40 maxlength=200 />
      </td>
      </tr>
  <tr><td class=inp>Kota :</td>
      <td class=ul1>
      <input type=text name='Kota' value='$w[Kota]' size=30 maxlength=50 /> 
      </tr>
  <tr><td class=inp>Propinsi/Kabupaten/Kota : </td>
      <td class=ul1><select name='Propinsi' onChange="javascript:rubah(this)" >$optionProp</select> <select name="kabupaten" id="kabupaten">
	  <option value='$_SESSION[kabupaten]'>$_SESSION[namakabupaten]</option>
</select>
      </td>
      </tr>
	    <tr><td class=inp>Kode Pos :</td>
      <td class=ul1>
         <input type=text name='KodePos' value='$_SESSION[KodePos]' size=10 maxlength=50 />
      </tr>
  <tr><td class=inp>RT / RW :</td>
      <td class=ul1>
      <input type=text name='RT' value='$w[RT]' size=5 maxlength=5 /> /
      <input type=text name='RW' value='$w[RW]' size=5 maxlength=5 />
      </td>
      </tr>
  <tr><td class=inp>Telepon / Ponsel :</td>
      <td class=ul1>
      <input type=text name='Telepon' value='$w[Telepon]' size=20 maxlength=50 /> /
      <input type=text name='Handphone' value='$w[Handphone]' size=20 maxlength=50 />
      </td>
      </tr>
  <tr><td class=inp>E-mail :</td>
      <td class=ul1>
      <input type=text name='Email' value='$w[Email]' size=40 maxlength=50 />
      </td>
      </tr>
  
  <tr><th class=ttl colspan=2>Pendidikan Terakhir</th></tr>
   <tr><td class=inp>Pendidikan Terakhir : </td>
      <td class=ul1>
	  <select name='JenisSekolah' onChange="javascript:pilihkotapt(this)" >
	  <option value="" selected="selected">-- pendidikan terakhir --</option>
	  <option value="sma">SMA/SMK/MA/Sederajat</option>
	  <option value="pt">Perguruan Tinggi</option>
	  </select>
      </td>
      </tr>
 <tr><td class=inp>Cari Sekolah : </td>
      <td class=ul1 id="PilihPropID">
      </td>
      </tr>
  <tr><td class=inp>Nama Sekolah :</td>
	  <td class=ul1>
	  <select name="NamaSekolah" id="idSekolah">$defSekolah</select><sup> * harus diisi</sup>
		</div>
		</td></tr>
      </tr>
  <tr><td class=inp>Jurusan Sekolah :</td>
      <td class=ul1>
      <input type=text name='JurusanSekolah' value='$w[JurusanSekolah]' size=40 maxlength=50 />
      </td>
      </tr>
      
  <tr><td class=inp>Tahun Lulus :</td>
      <td class=ul1>
      <input type=text name='TahunLulus' value='$w[TahunLulus]' size=5 maxlength=5 />
      </td>
  <tr><td class=inp>Nilai UAN :</td>
      <td class=ul1>
      <input type=text name='NilaiSekolah' value='$w[NilaiSekolah]' size=5 maxlength=5 />
      </td>
      </tr>
  <tr><td class=inp>Sudah Bekerja?</td>
	  <td class=ul1>
			<input type=checkbox name='SudahBekerja' value='Y' $sudahbekerjachecked>
      </td>
  </tr>
	  
  <tr><th class=ttl colspan=2>Data Orangtua/Wali</th></tr>
  <tr><td class=inp>Nama Orangtua/Wali :</td>
      <td class=ul1>
      <input type=text name='NamaAyah' value='$w[NamaAyah]' size=30 maxlength=50 />
      </td>
      </tr>
  <tr><td class=inp>Pendidikan Terakhir :</td>
      <td class=ul1>
      <select name='PendidikanAyah'>$optpendidikan</select>
      </td>
      </tr>
  <tr><td class=inp>Pekerjaan :</td>
      <td class=ul1>
      <select name='PekerjaanAyah'>$optpekerjaan</select>
      </td>
      </tr>

  <tr><th class=ttl colspan=2>Pilihan Program Studi</th></tr>
ESD;
  
  $s1 = "select DISTINCT(j.JenjangID), j.Nama, j.Keterangan 
			from prodi p left outer join jenjang j on p.JenjangID=j.JenjangID
			where p.KodeID='".KodeID."' and p.NA='N' order by p.JenjangID DESC" ;
  $r1 = _query($s1);
  
  $_Jenjang = "928rlaisfav9sfap";
  while($w1 = _fetch_array($r1))
  {	
	$Pilihan = GetPilihanProdi($w1['JenjangID'], $w['ProdiID']);
	echo "<tr><td class=inp>&bull; $w1[Keterangan] ($w1[Nama]) :</td>
			  <td class=ul1>$Pilihan</td>
			  </tr>";
	
  }

  echo <<<ESD
  <tr><td class=inp>Program : </td>
	  <td class=ul1><select name='Program'>$PilihanProgram</select></td>
	  </tr>
  
  <tr><td class=inp valign=top>
      Atas inisiatif siapa Sdr/i datang ke<br />$Institusi?
      </td>
      <td class=ul1>
      <textarea name='Catatan' cols=30 rows=3>$w[Catatan]</textarea>
      </td>
      </tr>
  
  <tr><td class=inp valign=top>Sumber Informasi :</td>
      <td class=ul1>
      $SumberInformasi
      </td>
      </tr>
  
  <tr><td class=ul1 colspan=2 align=center>
      <input type=submit name='btnSimpan' value='Simpan' />
      <input type=button name='btnBatal' value='Batal'
        onClick="window.close()" />
      </td>
      </tr>
  </form>
  </table>
ESD;
}

function GetSumberInformasi($nilai) {
  $arrnilai = explode(',', $nilai);
  $ret = '';
  $s = "select * from sumberinfo where KodeID='".KodeID."' and NA='N' order by InfoID";
  $r = _query($s);
  $n = 0;
  while ($w = _fetch_array($r)) {
    $ck = (array_search($w['InfoID'], $arrnilai) === false)? '' : 'checked';
    $ret .= "<input type=checkbox id='sumberinfo$n' name='sumberinfo[]' value='$w[InfoID]' $ck> $w[Nama]<br />";
	$n++;
  }
  $ret .="<input type=hidden name='JumlahSumberInfo' value='$n'>";
  return $ret;
}

function GetPilihanProdi($jen, $pil) {
  $arr = explode(',', $pil);
  $ret = array();
  $s = "select ProdiID, Nama from prodi where KodeID='".KodeID."' and JenjangID='$jen' and NA='N' order by ProdiID";
  $r = _query($s);
  while ($w = _fetch_array($r)) {
    $ck = (array_search($w['ProdiID'], $arr) === false)? '' : 'checked';
    $ret[] = "<input type=checkbox name='Pilihan[]' value='$w[ProdiID]' $ck> $w[Nama]";
  }
  $_ret = implode('&nbsp;&nbsp;', $ret);
  return $_ret;
}

function fnSave() {
  include_once "statusaplikan.lib.php";
  
  $md = $_REQUEST['md']+0;
  $id = $_REQUEST['id'];
  $gel = sqling($_REQUEST['gel']);
  
  $AplikanID = $id;
  
  if($md==1)
	{ 
	  $AplikanID = GetNextAplikanID($gel);
  
	  $ada = GetaField('aplikan', "KodeID='".KodeID."' and AplikanID", $AplikanID, 'AplikanID');
     
	  if (!empty($ada)) 
	  {
        die(ErrorMsg('Error',
          "Nomer Aplikan sudah ada.<br />
          Anda harus memasukkan nomer Aplikan yang lain.<br />
          Hubungi Sysadmin untuk informasi lebih lanjut.
          <hr size=1 color=silver />
          Opsi: <input type=button name='Kembali' value='Kembali'
            onClick=\"javascript:history.go(-1)\" />
            <input type=button name='Tutup' value='Tutup'
            onClick=\"window.close()\" />"));
      }	  
	}
  $PresenterID = $_REQUEST['PresenterID'];
  $CatatanPresenter = sqling($_REQUEST['CatatanPresenter']);
  $Nama = mysql_escape_string(sqling($_REQUEST['Nama']));
  $Kelamin = sqling($_REQUEST['Kelamin']);
  $TempatLahir = sqling($_REQUEST['TempatLahir']);
  $TanggalLahir = "$_REQUEST[TGL_y]-$_REQUEST[TGL_m]-$_REQUEST[TGL_d]";
  $TGLBuat = "$_REQUEST[TGLBuat_y]-$_REQUEST[TGLBuat_m]-$_REQUEST[TGLBuat_d]";
  $Agama = sqling($_REQUEST['Agama']);
  $Alamat = sqling($_REQUEST['Alamat']);
  $Kota = sqling($_REQUEST['Kota']);
  $Propinsi = sqling($_REQUEST['Propinsi']);
  $KodePos = sqling($_REQUEST['KodePos']);
  $RT = sqling($_REQUEST['RT']);
  $RW = sqling($_REQUEST['RW']);
  $Telepon = sqling($_REQUEST['Telepon']);
  $Handphone = sqling($_REQUEST['Handphone']);
  $Email = sqling($_REQUEST['Handphone']);
  $AsalSekolah = sqling($_REQUEST['SavAsalSekolah']);
  $AlamatSekolah = $_REQUEST['AlamatSekolah'];
  $JurusanSekolah = sqling($_REQUEST['JurusanSekolah']);
  $TahunLulus = sqling($_REQUEST['TahunLulus']);
  $SudahBekerja = (!empty($_REQUEST['SudahBekerja']))? 'Y' : 'N';
  $NilaiSekolah = $_REQUEST['NilaiSekolah'];
  $NamaAyah = sqling($_REQUEST['NamaAyah']);
  $PendidikanAyah = sqling($_REQUEST['PendidikanAyah']);
  $PekerjaanAyah = sqling($_REQUEST['PekerjaanAyah']);
  $Program = $_REQUEST['Program'];
  $Catatan = sqling($_REQUEST['Catatan']);
  $SumberInfo = implode(',', $_REQUEST['sumberinfo']);
  // Simpan
  $Pilihan = $_REQUEST['Pilihan'];
  $ProdiID = "";
  foreach($Pilihan as $prodi)
  {	$ProdiID .= (empty($ProdiID))? $prodi : ','.$prodi;
  }
  
  if ($md == 0) {
    $s = "update aplikan
      set PresenterID = '$PresenterID', CatatanPresenter = '$CatatanPresenter', AplikanID = '$AplikanID', 
		  Nama = '$Nama', Kelamin = '$Kelamin', TempatLahir = '$TempatLahir', TanggalLahir = '$TanggalLahir',
          Agama = '$Agama', Alamat = '$Alamat', Kota = '$Kota', Propinsi = '$Propinsi', KodePos = '$KodePos', RT = '$RT', RW = '$RW',
          Telepon = '$Telepon', Handphone = '$Handphone', Email = '$Email',
          AsalSekolah = '$AsalSekolah', AlamatSekolah='$AlamatSekolah', JurusanSekolah = '$JurusanSekolah', TahunLulus = '$TahunLulus', 
		  SudahBekerja = '$SudahBekerja', NilaiSekolah = '$NilaiSekolah',
          NamaAyah = '$NamaAyah', PendidikanAyah = '$PendidikanAyah', PekerjaanAyah = '$PekerjaanAyah',
          ProdiID='$ProdiID', ProgramID = '$Program', 
          Catatan = '$Catatan', SumberInformasi = '$SumberInfo',
          LoginEdit = '$_SESSION[_Login]', TanggalEdit = '$TGLBuat'
      where AplikanID = '$id' ";
    $r = _query($s);
	
	SetStatusAplikan('APL', $AplikanID, $gel);
	
    TutupScript();
  }
  elseif ($md == 1) {
	
	$s = "insert into aplikan
      (KodeID, PMBPeriodID,  PresenterID, AplikanID, CatatanPresenter, 
      Nama, Kelamin, TempatLahir, TanggalLahir,
      Agama, Alamat, Kota, Propinsi, KodePos, RT, RW,
      Telepon, Handphone, Email,
      AsalSekolah, AlamatSekolah, JurusanSekolah, TahunLulus, 
	  SudahBekerja, NilaiSekolah, 
      NamaAyah, PendidikanAyah, PekerjaanAyah,
      ProdiID, ProgramID, 
      Catatan, SumberInformasi,
	  Login, Password, 
      LoginBuat, TanggalBuat, TanggalEdit)
      values
      ('".KodeID."', '$gel', '$PresenterID', '$AplikanID', '$CatatanPresenter', 
      '$Nama', '$Kelamin', '$TempatLahir', '$TanggalLahir',
      '$Agama', '$Alamat', '$Kota', '$Propinsi', '$KodePos', '$RT', '$RW',
      '$Telepon', '$Handphone', '$Email',
      '$AsalSekolah', '$AlamatSekolah', '$JurusanSekolah', '$TahunLulus', 
	  '$SudahBekerja', '$NilaiSekolah',
      '$NamaAyah', '$PendidikanAyah', '$PekerjaanAyah',
      '$ProdiID', '$Program',
      '$Catatan', '$SumberInfo',
	  '$AplikanID', PASSWORD('$TanggalLahir'),
      '$_SESSION[_Login]', now(), '$TGLBuat')";
    $r = _query($s);
	
	SetStatusAplikan('APL', $AplikanID, $gel);
    
	TutupScript();
  }
  else die(ErrorMsg('Error',
    "Mode edit <b>$md</b> tidak dikenali sistem.<br />
    Hubungi Sysadmin untuk informasi lebih lanjut.
    <hr size=1 color=silver />
    <input type=button name='btnClose' value='Tutup' onClick='window.close()' />"));
	
}
function TutupScript() {
echo <<<SCR
<SCRIPT>
  function ttutup() {
    opener.location='../index.php?mnux=$_SESSION[mnux]&_apliPage=0';
    self.close();
    return false;
  }
  ttutup();
</SCRIPT>
SCR;
}

function GetNextAplikanID($gel) {
  $gelombang = GetFields('pmbperiod', "PMBPeriodID='$gel' and KodeID", KodeID, "FormatNoAplikan, DigitNoAplikan");
  // Buat nomer baru
  $nomer = str_pad('', $gelombang['DigitNoAplikan'], '_', STR_PAD_LEFT);
  $nomer = $gelombang['FormatNoAplikan'].$nomer;
  $akhir = GetaField('aplikan',
    "AplikanID like '$nomer' and KodeID", KodeID, "max(AplikanID)");
  $nmr = str_replace($gelombang['FormatNoAplikan'], '', $akhir);
  $nmr++;
  $baru = str_pad($nmr, $gelombang['DigitNoAplikan'], '0', STR_PAD_LEFT);
  $baru = $gelombang['FormatNoAplikan'].$baru;
  return $baru;
}
?>

</body>
</html>
