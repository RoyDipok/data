<?php

include_once('routeros_api.class.php');
include_once('formatbytesbites.php');
include_once('config.php');

if(!isset($_REQUEST['session'])){
  print_r("failed");
}else{
  

$session = "IT-ZONE";
$iphost = explode('!', $data[$session][1])[1];
$userhost = explode('@|@', $data[$session][2])[1];
$passwdhost = explode('#|#', $data[$session][3])[1];
$hotspotname = explode('%', $data[$session][4])[1];
$dnsname = explode('^', $data[$session][5])[1];
$currency = explode('&', $data[$session][6])[1];
$areload = explode('*', $data[$session][7])[1];
$iface = explode('(', $data[$session][8])[1];
$infolp = explode(')', $data[$session][9])[1];
$idleto = explode('=', $data[$session][10])[1];
$livereport = explode('@!@', $data[$session][11])[1];

//connect
$API = new RouterosAPI();
$API->debug = false;
$API->connect($iphost, $userhost, decrypt($passwdhost));


//time
$getclock = $API->comm("/system/clock/print");
$clock = $getclock[0];








// get system resource MikroTik
$getresource = $API->comm("/system/resource/print");
$resource = $getresource[0];
$cpu_loaded = $resource['cpu-load'];
$free_memory = formatBytes($resource['free-memory']);
$free_hdd_space =  formatBytes($resource['free-hdd-space']);

// get routeboard info
$getrouterboard = $API->comm("/system/routerboard/print");
$routerboard = $getrouterboard[0];

//hotspot count
$counthotspotactive = $API->comm("/ip/hotspot/active/print", array("count-only" => ""));
if ($counthotspotactive < 2) {
  $hunit = "item";
} elseif ($counthotspotactive > 1) {
  $hunit = "items";
}
//count users
$countallusers = $API->comm("/ip/hotspot/user/print", array("count-only" => ""));
if ($countallusers < 2) {
  $uunit = "item";
} elseif ($countallusers > 1) {
  $uunit = "items";
}









$response['hotspot'] = $counthotspotactive;
$response['active_user'] = $countallusers;
$response['cpu'] = $cpu_loaded;
$response['free_memory'] = $free_memory;
$response['hdd_space'] = $free_hdd_space;
$response['resource'] = $resource;
$response['getrouterboard'] = $getrouterboard[0];
$response['date'] = $clock;
print_r(json_encode($response));



}

?>
