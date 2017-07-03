<?php  
require_once 'vendor/autoload.php';  
use Neoxygen\NeoClient\ClientBuilder;  
  
$client = ClientBuilder::create()  
	->addConnection('default','http','192.168.99.100',7474,true,'neo4j','123')  
	->setAutoFormatResponse(true)  
	->setDefaultTimeout(200)  
	->build();

// $version = $client->getNeo4jVersion();  
// echo $version;  

$line_name = $_GET['LineName'];  



$query = 'START n = node(*) WHERE n.line_English = "'.$line_name.'"RETURN n.station_No, n.station_Name, n.station_English, n.line_Name, n.line_English, n.latitude, n.longitade';  

$result = $client->sendCypherQuery($query)->getRows();  
$count = count($result['n.station_No']);  

for($i = 0; $i < $count; $i++)
{
	$result_data[$i]['n.station_No'] = $result['n.station_No'][$i];  
	$result_data[$i]['n.station_Name'] = $result['n.station_Name'][$i];  
	$result_data[$i]['n.station_English'] = $result['n.station_English'][$i];  
	$result_data[$i]['n.line_Name'] = $result['n.line_Name'][$i];  
	$result_data[$i]['n.line_English'] = $result['n.line_English'][$i];  
	$result_data[$i]['n.latitude'] = $result['n.latitude'][$i];  
	$result_data[$i]['n.longitade'] = $result['n.longitade'][$i];  
}

$resultJson = json_encode($result_data);  
echo $resultJson;  
?>  
