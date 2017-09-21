
<?php
//date_default_timezone_set("America/New_York");
require("pr_config.php");
pg_connect($pg_connect);

// ini_set("log_errors", 1);
date_default_timezone_set("America/New_York");

ini_set("display_errors", 0);
ini_set("error_log", "/var/www/html/prgap/logs/php-error.log");

error_log("running map_ajax.php");
die();

//var_dump($_POST);
$mapfile = "/var/www/html/prgap/prgap.map";

$user_x = $_POST['user_x'];
$user_y = $_POST['user_y'];


$win_w = $_POST['winw'];
$win_h = $_POST['winh'];
$click_x =$_POST['clickx'];
$click_y = $_POST['clicky'];
$canvas_x = $_POST['canvas_x'];
$canvas_y = $_POST['canvas_y'];
$extent_raw = $_POST['extent'];
$zoom = $_POST['zoom'];
$mode = $_POST['mode'];
$layer = $_POST['layers'];
$query_layer = $_POST['query_layer'];
$owner_aoi = $_POST['owner_aoi'];
$manage_aoi = $_POST['manage_aoi'];
$muni_aoi = $_POST['muni_aoi'];
$island_aoi = $_POST['island_aoi'];
$zone_aoi = $_POST['life_zone_aoi'];
$wtshd_aoi = $_POST['basin_aoi'];
$subwtshd_aoi = $_POST['sub_basin_aoi'];
$ecosys_aoi = $_POST['ecosys_aoi'];

$job_id = $_POST['job_id'];

$post = print_r($_POST, true);
$logfileptr = fopen("/var/log/weblog/prgap", "a");
fprintf($logfileptr, "\n\n\n%s   %s\nInput\n%s ", date('l dS \of F Y h:i:s A'), __FILE__, $post);
fclose($logfileptr);

//check that script is still running after mapobj creation
$query = "insert into check_mapobj(job_id ) values ( $job_id )";
pg_query($query);

//create map object
$map = ms_newMapObj($mapfile);


//check that script is still running after mapobj creation
$query = "delete from check_mapobj where job_id = $job_id";
pg_query($query);

$logfileptr = fopen("/var/log/weblog/prgap", "a");
fprintf($logfileptr, "\n%s %s \n%s ", date('l dS \of F Y h:i:s A'), __FILE__, "ms_newMapObj command complete");
fclose($logfileptr);

if(!isset($_SESSION['username'])){
	$_SESSION['username'] = "visitor";
}
$user_name = $_SESSION['username'];



//set defaults
if(!isset($mode)) $mode = 'pan';
if(!isset($layer)) $layer = "elevation muni";
if(!isset($query_layer)) $query_layer = 'muni';
if(!isset($zoom))  $zoom=1;

//get cick points supplied by dragging.js
if(($canvas_x >= 1)||($canvas_y >= 1)){
	$click_x = $canvas_x;
	$click_y = $canvas_y;
}
//create click obj
$click_point = ms_newPointObj();
if(!isset($click_x)){
	$click_x=$win_w/2;
}
if(!isset($click_y)){
	$click_y=$win_h/2;
}
$click_point->setXY($click_x, $click_y);

//save extent to rect
$old_extent =  ms_newRectObj();
if(isset($extent_raw)){
	$extent = explode(" ", $extent_raw);
	$old_extent->setextent($extent[0], $extent[1], $extent[2], $extent[3]);
}elseif(isset($user_x)){
	$user_x_min = $user_x - 4500;
	$user_x_max = $user_x + 4500;
	$user_y_min = $user_y - 4500;
	$user_y_max = $user_y + 4500;
	$old_extent->setextent($user_x_min, $user_y_min, $user_x_max, $user_y_max);
}else {
	$old_extent->setextent(39084.500, 205175.500, 328284.500, 277070.500);
}





//set layers
if(preg_match("/elevation/", $layer)){
	$this_layer = $map->getLayerByName('elevation');
	$this_layer->set('status', MS_ON);
}else{
	$this_layer = $map->getLayerByName('elevation');
	$this_layer->set('status', MS_OFF);
}
if(preg_match("/landcover/", $layer)){
	$this_layer = $map->getLayerByName('landcover');
	$this_layer->set('status', MS_ON);
}else{
	$this_layer = $map->getLayerByName('landcover');
	$this_layer->set('status', MS_OFF);
}
if(preg_match("/lcov2/", $layer)){
	$this_layer = $map->getLayerByName('landcover2');
	$this_layer->set('status', MS_ON);
}else{
	$this_layer = $map->getLayerByName('landcover2');
	$this_layer->set('status', MS_OFF);
}
if(preg_match("/muni/", $layer)){
	$this_layer = $map->getLayerByName('muni');
	$this_layer->set('status', MS_ON);
}else{
	$this_layer = $map->getLayerByName('muni');
	$this_layer->set('status', MS_OFF);
}
if(preg_match("/island/", $layer)){
	$this_layer = $map->getLayerByName('islands');
	$this_layer->set('status', MS_ON);
}else{
	$this_layer = $map->getLayerByName('islands');
	$this_layer->set('status', MS_OFF);
}
if(preg_match("/wtshds/", $layer)){
	$this_layer = $map->getLayerByName('wtshds');
	$this_layer->set('status', MS_ON);
}else{
	$this_layer = $map->getLayerByName('wtshds');
	$this_layer->set('status', MS_OFF);
}
if(preg_match("/subwaters/", $layer)){
	$this_layer = $map->getLayerByName('subwtshds');
	$this_layer->set('status', MS_ON);
}else{
	$this_layer = $map->getLayerByName('subwtshds');
	$this_layer->set('status', MS_OFF);
}
if(preg_match("/roads/", $layer)){
	$this_layer = $map->getLayerByName('roads');
	$this_layer->set('status', MS_ON);
}else{
	$this_layer = $map->getLayerByName('roads');
	$this_layer->set('status', MS_OFF);
}
if(preg_match("/zones/", $layer)){
	$this_layer = $map->getLayerByName('zones');
	$this_layer->set('status', MS_ON);
}else{
	$this_layer = $map->getLayerByName('zones');
	$this_layer->set('status', MS_OFF);
}
if(preg_match("/hexs/", $layer)){
	$this_layer = $map->getLayerByName('hexagons');
	$this_layer->set('status', MS_ON);
}else{
	$this_layer = $map->getLayerByName('hexagons');
	$this_layer->set('status', MS_OFF);
}
if(preg_match("/ownership/", $layer)){
	$this_layer = $map->getLayerByName('gapown');
	$this_layer->set('status', MS_ON);
}else{
	$this_layer = $map->getLayerByName('gapown');
	$this_layer->set('status', MS_OFF);
}
if(preg_match("/management/", $layer)){
	$this_layer = $map->getLayerByName('gapman');
	$this_layer->set('status', MS_ON);
}else{
	$this_layer = $map->getLayerByName('gapman');
	$this_layer->set('status', MS_OFF);
}
if(preg_match("/status/", $layer)){
	$this_layer = $map->getLayerByName('gapsta');
	$this_layer->set('status', MS_ON);
}else{
	$this_layer = $map->getLayerByName('gapsta');
	$this_layer->set('status', MS_OFF);
}

//show selected predefined AOI as red hatch
if (isset($owner_aoi) && !empty($owner_aoi)){
	$key_gap = explode(":", $owner_aoi);
	$filter = "(ogc_fid = {$key_gap[0]})";
	for($i=1; $i<count($key_gap); $i++){
		$filter .= " or (ogc_fid = {$key_gap[$i]})";
	}
	//echo $filter;
	$this_layer = $map->getLayerByName('owner_select');
	$this_layer->setFilter($filter);
	$this_layer->set('status', MS_ON);
}
if (isset($manage_aoi) && !empty($manage_aoi)){
	$key_gap = explode(":", $manage_aoi);
	$filter = "(ogc_fid = {$key_gap[0]})";
	for($i=1; $i<count($key_gap); $i++){
		$filter .= " or (ogc_fid = {$key_gap[$i]})";
	}
	//echo $filter;
	$this_layer = $map->getLayerByName('manage_select');
	$this_layer->setFilter($filter);
	$this_layer->set('status', MS_ON);
}
if (isset($muni_aoi) && !empty($muni_aoi)){
	$key_gap = explode(":", $muni_aoi);
	$filter = "(ogc_fid = {$key_gap[0]})";
	for($i=1; $i<count($key_gap); $i++){
		$filter .= " or (ogc_fid = {$key_gap[$i]})";
	}
	//echo $filter;
	$this_layer = $map->getLayerByName('muni_select');
	$this_layer->setFilter($filter);
	$this_layer->set('status', MS_ON);
}
if (isset($island_aoi) && !empty($island_aoi)){
	$key_gap = explode(":", $island_aoi);
	$filter = "(ogc_fid = {$key_gap[0]})";
	for($i=1; $i<count($key_gap); $i++){
		$filter .= " or (ogc_fid = {$key_gap[$i]})";
	}
	//echo $filter;
	$this_layer = $map->getLayerByName('island_select');
	$this_layer->setFilter($filter);
	$this_layer->set('status', MS_ON);
}
if (isset($zone_aoi) && !empty($zone_aoi)){
	$key_gap = explode(":", $zone_aoi);
	$filter = "(ogc_fid = {$key_gap[0]})";
	for($i=1; $i<count($key_gap); $i++){
		$filter .= " or (ogc_fid = {$key_gap[$i]})";
	}
	//echo $filter;
	$this_layer = $map->getLayerByName('zone_select');
	$this_layer->setFilter($filter);
	$this_layer->set('status', MS_ON);
}
if (isset($wtshd_aoi) && !empty($wtshd_aoi)){
	$key_gap = explode(":", $wtshd_aoi);
	$filter = "(ogc_fid = {$key_gap[0]})";
	for($i=1; $i<count($key_gap); $i++){
		$filter .= " or (ogc_fid = {$key_gap[$i]})";
	}
	//echo $filter;
	$this_layer = $map->getLayerByName('wtshd_select');
	$this_layer->setFilter($filter);
	$this_layer->set('status', MS_ON);
}
if (isset($subwtshd_aoi) && !empty($subwtshd_aoi)){
	$key_gap = explode(":", $subwtshd_aoi);
	$filter = "(ogc_fid = {$key_gap[0]})";
	for($i=1; $i<count($key_gap); $i++){
		$filter .= " or (ogc_fid = {$key_gap[$i]})";
	}
	//echo $filter;
	$this_layer = $map->getLayerByName('subwtshd_select');
	$this_layer->setFilter($filter);
	$this_layer->set('status', MS_ON);
}
if (isset($ecosys_aoi) && !empty($ecosys_aoi)){
	$this_layer = $map->getLayerByName('ecosys_select');
	$this_layer->set('status', MS_ON);
}
//creating main map
$mapname = "map".rand(0,9999999).".png";
$maploc = "{$mspath}{$mapname}";
$map->setSize($win_w, $win_h);
$map->zoompoint($zoom, $click_point, $win_w, $win_h, $old_extent);
$mapimage = $map->draw();
$mapimage->saveImage($maploc);

//create ref map
$refname="refmap".rand(0,9999999).".png";
$refurl="/server_temp/".$refname;
$refname = $mspath.$refname;
$refimage = $map->drawReferenceMap();
$refimage->saveImage($refname);

//get new extent
$new_extent = 	sprintf("%3.6f",$map->extent->minx)." ".
sprintf("%3.6f",$map->extent->miny)." ".
sprintf("%3.6f",$map->extent->maxx)." ".
sprintf("%3.6f",$map->extent->maxy);

$ret =  json_encode(array("mapname"=>$mapname,"extent"=>$new_extent, "refname"=>$refurl));

$logfileptr = fopen("/var/log/weblog/prgap", "a");
fprintf($logfileptr, "%s   %s\nOutput\n%s\n", date('l dS \of F Y h:i:s A'), __FILE__,  $ret);
fclose($logfileptr);

echo $ret;

?>