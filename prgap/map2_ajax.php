<?php
require('pr_aoi_class.php');
require('pr_config.php');
require('pr_define_aoi.php');

session_start();

// ini_set("display_errors", 0);
ini_set("error_log", "/var/www/html/prgap/logs/php-error.log");

error_log("running map_ajax2.php");
date_default_timezone_set("America/New_York");
pg_connect($pg_connect);

//click points set by javascript
$canvas_x = $_POST['canvas_x'];
$canvas_y = $_POST['canvas_y'];
//click points set by POST form
$point_x = $_POST['clickx'];
$point_y = $_POST['clicky'];
//click points and extent for custom aoi
$clickx = $_POST['posi_x'];
$clicky = $_POST['posi_y'];

//aoi name for saved aoi
$aoi_name_saved = $_POST['aoi_name_saved'];

$extent = $_POST['extent'];
$win_w = $_POST['winw'];
$win_h = $_POST['winh'];
$layer = $_POST['layers'];

//ogc_fid for predefined aoi
$owner_aoi = $_POST['owner_aoi'];
$manage_aoi = $_POST['manage_aoi'];
$muni_aoi = $_POST['muni_aoi'];
$island_aoi = $_POST['island_aoi'];
$zone_aoi = $_POST['zone_aoi'];
$wtshd_aoi = $_POST['basin_aoi'];
$subwtshd_aoi = $_POST['sub_basin_aoi'];
$ecosys_aoi = $_POST['ecosys_aoi'];
$file_shp = $_POST['shapefile'];
$pred_transp = $_POST['pred_transp'];
$range_transp = $_POST['range_transp'];
$range_transp_prev = $_POST['range_transp_prev'];

$zoom_aoi = $_POST['zoomaoi'];
$zoom = $_POST['zoom'];
$mode = $_POST['mode'];
$aoi_name = $_POST['aoi_name'];
$sppcode = $_POST['sppcode'];
//echo $sppcode;
$species_layer = $_POST['species_layer'];
$species_layer_prev = $_POST['species_layer_prev'];
$map_species = $_POST['map_species'];
$richness_species = ($_POST['richness_species']);
$type = $_POST['type'];
$job_id = $_POST['job_id'];

$user_name = $_SESSION['username'];
error_log($user_name);

$post = print_r($_POST, true);
$logfileptr = fopen("/var/log/weblog/prgap", "a");
fprintf($logfileptr, "\n\n\n%s   %s\nInput\n%s ", date('l dS \of F Y h:i:s A'), __FILE__, $post);
fclose($logfileptr);

if (!isset($point_x) || !isset($point_y) || empty($point_x) || empty($point_y)) {
	$point_x = $win_w/2;
	$point_y =$win_h/2;
}
if(isset($canvas_x) && !empty($canvas_x) && isset($canvas_y) && !empty($canvas_y)){
	$point_x = $canvas_x;
	$point_y = $canvas_y;
}
//create click obj
$click_point = ms_newPointObj();
$click_point->setXY($point_x, $point_y);


//if AOI is undefined then create it in postgis and create new AOI object else get aoi from form variable
if (strlen($aoi_name) ==0){
        //create aoi name
        $now = localtime(time(),1);
        $aoi_name = "aoi".$now['tm_yday'].rand(0,9999999);
        if ($type == 'custom'){
                get_custom_aoi($aoi_name, $clickx, $clicky, $extent, $win_w, $win_h );
        }elseif($type == 'predefined'){
                $aoi_predefined['owner_aoi'] = $owner_aoi;
                $aoi_predefined['manage_aoi'] = $manage_aoi;
                $aoi_predefined['muni_aoi'] = $muni_aoi;
                $aoi_predefined['island_aoi'] = $island_aoi;
                $aoi_predefined['zone_aoi'] = $zone_aoi;
                $aoi_predefined['wtshd'] = $wtshd_aoi;
                $aoi_predefined['subwtshd'] = $subwtshd_aoi;
                $aoi_predefined['ecosys'] = $ecosys_aoi;
                if ($type == "predefined") {
                        $aoi_predef_save = pg_escape_string(serialize($aoi_predefined));
                        $query = "update aoi set aoi_data = '{$aoi_predef_save}' where name = '{$aoi_name}'";
                }
                get_predefined_aoi($aoi_name, $owner_aoi, $manage_aoi, $muni_aoi, $island_aoi, $zone_aoi, $wtshd_aoi, $subwtshd_aoi, $ecosys_aoi);
                pg_query($query);
        }elseif($type == 'uploaded') {
                get_uploaded_aoi($aoi_name, $file_shp);
        }elseif ($type == 'saved_aoi'){
                $aoi_name = $aoi_name_saved;
                $query = "select description from aoi where name = '{$aoi_name}'";
                $result = pg_query($query);
                $row = pg_fetch_array($result);
                $aoi_desc = $row['description'];
        }
        $new_page = true;
        $_SESSION[$aoi_name] = new pr_aoi_class($aoi_name);
}else{
        $new_page = false;
}

$pr_aoi_class = $_SESSION[$aoi_name];
//$aoi_area = $pr_aoi_class->get_area();

//create mapobj
$mapfile = "/var/www/html/prgap/prgap.map";

//check that script is still running after mapobj creation
$query = "insert into check_mapobj(job_id ) values ( $job_id )";
pg_query($query);

$map = ms_newMapObj($mapfile);

//check that script is still running after mapobj creation
$query = "delete from check_mapobj where job_id = $job_id";
pg_query($query);

$mapname = "map".rand(0,9999999).".png";
$maploc = "{$mspath}{$mapname}";

//get calculated maps for single species or richness from aoi_class, but first test to see if we can use previous map
if (preg_match("/habitat/", $species_layer) && !preg_match("/habitat/", $species_layer_prev)) {
      $map_species = $pr_aoi_class->landcover_map($sppcode);
}
if (preg_match("/ownership/", $species_layer) && !preg_match("/ownership/", $species_layer_prev)) {
		$map_species = $pr_aoi_class->ownership_map($sppcode);
}
if (preg_match("/status/", $species_layer) && !preg_match("/status/", $species_layer_prev)) {
		$map_species = $pr_aoi_class->protection_map($sppcode);
}
if (preg_match("/manage/", $species_layer) && !preg_match("/manage/", $species_layer_prev)) {
      $map_species = $pr_aoi_class->management_map($sppcode);
}
if (preg_match("/richness/", $species_layer) && !preg_match("/richness/", $species_layer_prev)) {
     $map_species = $pr_aoi_class->richness($richness_species);
}

//convert sppcode to raster name
$raster = "pd_".$sppcode;

//set layers from controls
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


//set raster to display species maps
if(preg_match("/range/", $species_layer)){
        $this_layer = $map->getLayerByName('range');
        $this_layer->set('classitem', strtolower($sppcode));
        $this_layer->set('status', MS_ON);
       $this_layer->set('opacity', $range_transp);
		  //set layers from controls
		  if(preg_match("/landcover/", $layer)){
			  $this_layer = $map->getLayerByName('landcover');
			  $this_layer->set('status', MS_ON);
		  }else{
			  $this_layer = $map->getLayerByName('landcover');
			  $this_layer->set('status', MS_OFF);
		  }
		  if(preg_match("/elevation/", $layer)){
			  $this_layer = $map->getLayerByName('elevation');
			  $this_layer->set('status', MS_ON);
		  }else{
			  $this_layer = $map->getLayerByName('elevation');
			  $this_layer->set('status', MS_OFF);
		  }
}

if(preg_match("/habitat|ownership|status|manage|richness/", $species_layer)){
        $this_layer = $map->getLayerByName('mapcalc');
        //echo ($grass_raster.$map_species);
        $this_layer->set('data', $grass_raster.$map_species);
        $this_layer->set('status', MS_ON);
        //turn off other rasters
        $this_layer = $map->getLayerByName('elevation');
        $this_layer->set('status', MS_OFF);
        $this_layer = $map->getLayerByName('landcover');
        $this_layer->set('status', MS_OFF);
}


if(preg_match("/predicted/", $species_layer)){
        $this_layer = $map->getLayerByName('mapcalc');
        $this_layer->set('data', $grass_raster_perm.$raster);
        $this_layer->set('status', MS_ON);
     $this_layer->set('opacity', $pred_transp);
		  //set layers from controls
		  if(preg_match("/landcover/", $layer)){
			  $this_layer = $map->getLayerByName('landcover');
			  $this_layer->set('status', MS_ON);
		  }else{
			  $this_layer = $map->getLayerByName('landcover');
			  $this_layer->set('status', MS_OFF);
		  }
		  if(preg_match("/elevation/", $layer)){
			  $this_layer = $map->getLayerByName('elevation');
			  $this_layer->set('status', MS_ON);
		  }else{
			  $this_layer = $map->getLayerByName('elevation');
			  $this_layer->set('status', MS_OFF);
		  }

}

$filter = "(name = '{$aoi_name}')";
$this_layer = $map->getLayerByName('aoi');
$this_layer->setFilter($filter);
$this_layer->set('status', MS_ON);

//calculate extent from class variables the first time or zoom to aoi, else use previous extent
$extent_obj =  ms_newRectObj();

if ($new_page  || $zoom_aoi) {
        $min_x = $pr_aoi_class->get_minx();
        $min_y = $pr_aoi_class->get_miny();
        $max_x = $pr_aoi_class->get_maxx();
        $max_y = $pr_aoi_class->get_maxy();
        $x_adj = ($max_x - $min_x)*0.1;
        $y_adj = ($max_y - $min_y)*0.1;
        $extent_obj->setExtent($min_x-$x_adj, $min_y-$y_adj, $max_x+$x_adj, $max_y+$y_adj);
}else {
        $mapext = explode(" ", $extent);
        $minx = $mapext[0];
        $miny = $mapext[1];
        $maxx = $mapext[2];
        $maxy = $mapext[3];
        $extent_obj->setExtent($minx, $miny, $maxx, $maxy);
}
$map->setSize($win_w, $win_h);
$map->zoompoint($zoom, $click_point, $win_w, $win_h, $extent_obj);
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

$ret = json_encode(array("mapname"=>$mapname,"extent"=>$new_extent, "refname"=>$refurl, "aoiname"=>$aoi_name, "mapspecies"=>$map_species));

echo $ret;

?>

