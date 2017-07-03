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




// $query = 'START n = node(*)  
// MATCH (n)-[p1]-(r)-[p2]-(m)
// WHERE n.station_Name = "大手町駅"  
// RETURN m.station_No as S_No, m.station_Name as S_Name, m.station_English as S_En, m.line_Name as L_Name, m.line_English as L_En, m.latitude as lat, m.longitade as lon';  

$query = $_GET['query'];

$result = $client->sendCypherQuery($query)->getRows();

$count = count($result['S_No']);  

for($i = 0; $i < $count; $i++)
{
	$result_data[$i]['S_No'] = $result['S_No'][$i];  
	$result_data[$i]['S_Name'] = $result['S_Name'][$i];  
	$result_data[$i]['S_En'] = $result['S_En'][$i];  
	$result_data[$i]['L_Name'] = $result['L_Name'][$i];  
	$result_data[$i]['L_En'] = $result['L_En'][$i];  
	$result_data[$i]['lat'] = $result['lat'][$i];  
	$result_data[$i]['lon'] = $result['lon'][$i];  
}

$resultJson = json_encode($result_data);  
echo $resultJson;  
?>  
