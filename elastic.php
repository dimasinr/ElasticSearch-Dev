<?php

require 'vendor/autoload.php';
include 'config.php';

$fn = fopen("uwsgi.log","r");
 
 while(! feof($fn)) {
	 $result = fgets($fn);
	 $data = [];
	 $parsing = preg_match_all("/^(\S+\ \S+\ \S+\ \d+\ \w+\/\d+\w+\}) (\D+\w+ \w+\/\w+\}) (\S+ \w+\|\w+\: +\d+\S+\ \w+\/\w+\])/",$result,$data);
	 $address_space_usage = $data[1][0] ?? "";
	 $rss_usage = $data[2][0] ?? "";
	 $pid = $data[3][0] ?? "";

	$client = Elasticsearch\ClientBuilder::create()->build();

	$params = [
		'index' => 'my_index',
		'id'    => 'sud',
		'body'  => ['address_space_usage' => '$address_space_usage',
					'rss_usage' => '$rss_usage',
					'pid' => '$pid',
		]
	];
	
	$response = $client->index($params);
	print_r($response);

}

   fclose($fn);
?>

