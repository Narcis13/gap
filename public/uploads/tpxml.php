<?php

$servername = "localhost";
$username = "root";
$password = "q1w2e3r4";
$dbname = "adata";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$month = date("n",strtotime($_GET['ancur']));
$d=date("d.m.Y",strtotime($_GET['ancur']));
$an=date("Y",strtotime($_GET['ancur']));


  function rsc($str) {
  
      // Using str_replace() function 
      // to replace the word 
      $res = str_replace( array( '\'', '"',
      ',' , ';', '/', '.' ,'-'), ' ', $str);
  
      // Returning the result 
      return $res;
      }


$sql = "SELECT ap.id id ,ap.numar nrop, ap.suma valoareop, f.iban fiban, f.denumire fdenumire, f.codfiscal fcodfiscal, replace(ap.detalii,',','') detalii, DATE_FORMAT(ap.data,'%d/%m/%Y') dataop, art.iban iban , dfp.codang codang, dfp.indic indic from detaliiplati dp\n
   inner join antetplati ap on dp.idOP = ap.id\n
   inner join detaliifp dfp on dfp.id = dp.idFP\n
   inner join antetfp afp on afp.id=dfp.idAntet\n
   inner join furnizori f on f.id = afp.idFurnizor\n
   inner join artbugete art on art.id=dfp.idLiniiBuget\n
   where ap.data='".$_GET['ancur']."'\n
   group by nrop";
$result = $conn->query($sql);
$antet="<?xml version=\"1.0\"?><f1129 xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"mfp:anaf:dgti:f1129:declaratie:v1\" xmlns=\"mfp:anaf:dgti:f1129:declaratie:v1\" versiune_pdf=\"A2.0.19\" d_rec=\"0\" suma_control=\"0\" total_opm=\"0\" nr_inregistrari=\"21\" luna_r=\"".$month."\" an=\"2022\" data_document=\"28.04.2022\" nr_document=\"0000000001\" nume_ip=\"UNITATEA MILITARA 02497\" adresa_ip=\"Str NEGRU VODA Nr 47\" cui_ip=\"4318016\" tip_ent=\"1\">";
$text="";
if ($result->num_rows > 0) {
    // output data of each row
	$nrcrt=1;

    while($row = $result->fetch_assoc()) {
      //  $text=$text.($nrcrt.",".$row['nrop'].",".$row['valoareop'].",UM 02497 PITESTI,4318016,STR NEGRU-VODA NR 47,".$row['iban'].",,".$row['fdenumire'].",".$row['fcodfiscal'].",".$row['fiban'].",,,".$row['detalii'].",".$row['dataop'].",,".$row['codang'].",".$row['indic'].",0000000000,OP"."\r\n");
		$text=$text.("<rand_op nr_op=\"".$row['nrop']."\" iban_platitor=\"".$row['iban']."\" den_trezorerie=\"TREZORERIA  STATULUI\" cod_program=\"0000001905\" cod_angajament=\"".$row['codang']."\" ind_angajament=\"".$row['indic']."\" cui_beneficiar=\"".$row['fcodfiscal']."\" den_beneficiar=\"".rsc($row['fdenumire'])."\" iban_beneficiar=\"".$row['fiban']."\" den_banca_trez=\"TREZORERIA  STATULUI\" suma_op=\"".$row['valoareop']."\" explicatii=\"".rsc($row['detalii'])."\" />"."\r\n");
        $nrcrt++;
    }
} else {
    echo "0 results".$sql;
}
$nr=$nrcrt-1;
$antet="<?xml version=\"1.0\"?><f1129 xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"mfp:anaf:dgti:f1129:declaratie:v1\" xmlns=\"mfp:anaf:dgti:f1129:declaratie:v1\" versiune_pdf=\"A2.0.19\" d_rec=\"0\" suma_control=\"0\" total_opm=\"0\" nr_inregistrari=\"".$nr."\" luna_r=\"".$month."\" an=\"".$an."\" data_document=\"".$d."\" nr_document=\"0000000001\" nume_ip=\"UNITATEA MILITARA 02587\" adresa_ip=\"Str. Mircea Vulcanescu Nr. 88 sect. 1\" cui_ip=\"4267028\" tip_ent=\"1\">"."\r\n";

$conn->close();

$filename = 'plati_anaf_'.$_GET['ancur'].'.xml';
$result=$antet.$text."</f1129>";
header("Content-Type: application/xml");
header('Content-Disposition: attachment; filename="'.$filename.'"');
header("Content-Length: " . strlen($result));
echo $result;
exit;