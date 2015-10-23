<?php
include 'init.lib.php';
include 'nakl.lib.php';
include 'NovaPoshtaApi2.php';
connect_to_mysql();
session_start();

if (strpos($_SESSION[BASE.'usersetup'],$_SESSION[BASE.'base'].'can_shop')>0){
}else{
  echo "access denied for this user";
  exit();
}
//==================================SETUP=MENU==========================================
if (!session_verify($_SERVER["PHP_SELF"],"none")){
  exit();
}

// Header information
header('Content-Type: text/html; charset=utf-8');
// Require class file

// Set key
$key = '2881767a867276465606e8e1df1ac390';
// Create instance
$np = new NovaPoshtaApi2($key);
// Get Track Info
// $result = $np->documentsTracking('20290013270857');
// Get cities by name
// $result = $np->getCities(0, 'Андреевка');
// Get region by name
// $result = $np->getArea('Чернігівська', '');
// Get city by name and region
// $result = $np->getCity('Андреевка', 'Харьков');
// Get method from Common Model of NovaPoshta
// $result = $np->getDocumentStatuses();
/*
$result = $np->model('counterparty')->save(array(
	'CounterpartyProperty' => 'Recipient',
	'CityRef' => 'f4890a83-8344-11df-884b-000c290fbeaa',
	'CounterpartyType' => 'PrivatePerson',
	'FirstName' => 'Иван',
	'MiddleName' => 'Иванович',
	'LastName' => 'Иванов',
	'Phone' => '380501112233',
));
*/
/*
$result = $np->model('counterparty')->update(array(
	'Ref' => '3f9c9486-6cd6-11e4-acce-0050568002cf',
	'CounterpartyProperty' => 'Recipient',
	'CityRef' => 'f4890a83-8344-11df-884b-000c290fbeaa',
	'CounterpartyType' => 'PrivatePerson',
	'FirstName' => 'Иван1',
	'MiddleName' => 'Иванович1',
	'LastName' => 'Иванов1',
	'Phone' => '380501112234',
));
*/
// $result = $np->model('counterparty')->delete(array('Ref' => '3f9c94b0-6cd6-11e4-acce-0050568002cf'));
/*
$result = $np->model('ContactPerson')->save(array(
	'CounterpartyRef' => '3f9c94c1-6cd6-11e4-acce-0050568002cf',
	'FirstName' => 'Иван2-1',
	'MiddleName' => 'Иванович2-1',
	'LastName' => 'Иванов2-1',
	'Phone' => '0501112255',
));
*/
/*
$result = $np->model('ContactPerson')->update(array(
	'Ref' => '29a5c4e8-6d43-11e4-acce-0050568002cf',
	'CounterpartyRef' => '3f9c94c1-6cd6-11e4-acce-0050568002cf',
	'FirstName' => 'Иван3',
	'MiddleName' => 'Иванович3',
	'LastName' => 'Иванов3',
	'Phone' => '0501112266',
	'Email' => 'some@mail.ru'
));
*/
/*
$result = $np->model('counterparty')->save(array(
	'CounterpartyProperty' => 'Recipient',
	'CityRef' => 'f4890a83-8344-11df-884b-000c290fbeaa',
	'CounterpartyType' => 'Organization',
	'FirstName' => 'ПАО КБ ПриватБанк',
	'MiddleName' => '',
	'LastName' => '',
	'OwnershipForm' => '361b83db-886e-11e1-a146-0026b97ed48a',
	'EDRPOU' => '14360570',
));
*/
// $result = $np->model('ContactPerson')->delete(array('Ref' => '29a5c4e8-6d43-11e4-acce-0050568002cf'));
// $result = $np->getCounterpartyContactPersons('94122e79-6e72-11e4-acce-0050568002cf');
// $result = $np->getCounterpartyOptions('94122e79-6e72-11e4-acce-0050568002cf');
// $result = $np->cloneLoyaltyCounterpartySender('f4890a83-8344-11df-884b-000c290fbeaa');
// $result = $np->getCounterparties(null, 1, '', '');
// $result = $np->getCounterpartyByEDRPOU('12345678', 'f4890a83-8344-11df-884b-000c290fbeaa');

// Get result

/*
$ver = mysql_query("SET NAMES utf8");
  $tQuery = "SELECT 
		`klienti_id`,
		`klienti_name_1`,
		`klienti_email`,
		`klienti_pass`
		FROM `tbl_klienti` 
		WHERE `klienti_pass`<>''
		ORDER BY `klienti_id` ASC
		";
$ver = mysql_query($tQuery);

$count=0;
while($count<mysql_num_rows($ver)){
  
$ins = mysql_query("SET NAMES utf8");
  $tQuery = "UPDATE `tbl_klienti` SET
	    `klienti_pass`='".md5(mysql_result($ver,$count,"klienti_pass"))."'
	    WHERE `klienti_id`='".mysql_result($ver,$count,"klienti_id")."'
		";
$ins = mysql_query($tQuery);
  
$count++;
}
*/


$pass = $_GET['pass'];

if($pass == md5('test')) echo "OK";
echo md5('test');

/*
echo $result['data'][0]['Barcode'];

echo "<br><br>";
echo "<pre>";
var_export($result);
echo "</pre>";
*/

/* $file_name = "tmp/test.xml";
 //$xml = fopen($file_name,"r");
 $xml = file_get_contents($file_name); 
echo $xml;
 // fwrite($fp,$excel);
// fclose($fp);

<filter>Львов</filter>
 */
 // ver 2 === 2881767a867276465606e8e1df1ac390
 
 //$np = new NovaPoshtaApi2('2881767a867276465606e8e1df1ac390');
 
 
 /*
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<file>
<auth>af85354bf7a0d367d707c087d8e20852</auth>
<city/>
</file>
";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,'https://api.novaposhta.ua/v1.0/xml/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
curl_setopt($ch, CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_POSTFIELDS,$xml);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
$response = curl_exec($ch);
curl_close($ch);

$p = xml_parser_create();
xml_parse_into_struct($p,$response,$vals,$index);
xml_parser_free($p);
print_r($vals[5]);
echo "<br>";
//print_r($vals['RESPONSECODE']);
ECHO $vals[5];


//echo $response->getElementByTagName('city')->item(0)->textContent;
//echo $response['cities']['city'][0]['Ref'];
//var_dump($response);
//echo $response->result->cities->city[0]->nameRu;

//echo "<pre>",$xml,"</pre>";
//print_r($response);*/

?>
