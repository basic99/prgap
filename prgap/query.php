
<?php
//set mapfile and load mapscript if not already loaded
$mapfile = "../prgap.map";
require('pr_config.php');
pg_connect($pg_connect);

//function to convert clickpoint to map co-ords
function img2map($width, $height, $point, $ext){
	if ($point->x && $point->y){
		$dpp_x = ($ext->maxx -$ext->minx)/$width;
		$dpp_y = ($ext->maxy -$ext->miny)/$height;
		$p[0] = $ext->minx + $dpp_x*$point->x;
		$p[1] = $ext->maxy - $dpp_y*$point->y;
	}
	return $p;
}

//get form variables
$win_w = $_POST['win_w'];
$win_h = $_POST['win_h'];
//$size = $_POST['win_size'];
$click_x =$_POST['img_x'];
$click_y = $_POST['img_y'] - 68;
$extent_raw = $_POST['extent'];
$zoom = $_POST['zoom'];
$layer = $_POST['layers'];
$mode = $_POST['mode'];
$query_layer = $_POST['query_layer'];

//create click obj
$click_point = ms_newPointObj();
$click_point->setXY($click_x, $click_y);

//var_dump($_POST);

//save extent to object
$extent = explode(" ", $extent_raw);
$old_extent =  ms_newRectObj();
$old_extent->setextent($extent[0], $extent[1], $extent[2], $extent[3]);

//create map object
$map = ms_newMapObj($mapfile);
$map->setSize($win_w, $win_h);
list($qx, $qy) = img2map($map->width, $map->height, $click_point, $old_extent);
$qpoint = ms_newPointObj();
$qpoint->setXY($qx,$qy);

if(preg_match("/stew_area|mgmt_name|mgmt_unit|land_owner|gap_status/", $query_layer)){
	$qlayer = $map->getLayerByName('manage_q');
	$qlayer->queryByPoint($qpoint, MS_SINGLE, 0);
	$result = $qlayer->getResult(0);
	$result = $result->shapeindex;
	$query = "select {$query_layer} from pr_stewardship where ogc_fid = '{$result}'";
	if($result2 = pg_query($query)){
		$row = pg_fetch_array($result2);
		$msg = $row[0];
	} else {
		$msg = '';
	}
}
if(preg_match("/zone/", $query_layer)){
	$qlayer = $map->getLayerByName('zones');
	$qlayer->queryByPoint($qpoint, MS_SINGLE, 0);
	$result = $qlayer->getResult(0);
	$result = $result->shapeindex;
	$query = "select zone_desc from pr_life_zones where ogc_fid = '{$result}'";
	if($result2 = pg_query($query)){
		$row = pg_fetch_array($result2);
		$msg = $row[0];
	} else {
		$msg = '';
	}
}
if(preg_match("/muni/", $query_layer)){
	$qlayer = $map->getLayerByName('muni');
	$qlayer->queryByPoint($qpoint, MS_SINGLE, 0);
	$result = $qlayer->getResult(0);
	$result = $result->shapeindex;
	$query = "select municipio from pr_muni where ogc_fid = '{$result}'";
	if($result2 = pg_query($query)){
		$row = pg_fetch_array($result2);
		$msg = $row[0];
	} else {
		$msg = '';
	}
}
if(preg_match("/hexs/", $query_layer)){
	$qlayer = $map->getLayerByName('hexagons');
	$qlayer->queryByPoint($qpoint, MS_SINGLE, 0);
	$result = $qlayer->getResult(0);
	$result = $result->shapeindex;
	$query = "select hex_id  from pr_species_hex where ogc_fid = '{$result}'";
	if($result2 = pg_query($query)){
		$row = pg_fetch_array($result2);
		$msg = $row[0];
	} else {
		$msg = '';
	}
}

if(preg_match("/island/", $query_layer)){
	$qlayer = $map->getLayerByName('islands');
	$qlayer->queryByPoint($qpoint, MS_SINGLE, 0);
	$result = $qlayer->getResult(0);
	$result = $result->shapeindex;
	$query = "select name  from pr_coast where ogc_fid = '{$result}'";
	if($result2 = pg_query($query)){
		$row = pg_fetch_array($result2);
		$msg = $row[0];
	} else {
		$msg = '';
	}
}
if(preg_match("/wtshds/", $query_layer)){
	$qlayer = $map->getLayerByName('wtshds');
	$qlayer->queryByPoint($qpoint, MS_SINGLE, 0);
	$result = $qlayer->getResult(0);
	$result = $result->shapeindex;
	$query = "select cuenca_opa  from pr_wtshds where ogc_fid = '{$result}'";
	if($result2 = pg_query($query)){
		$row = pg_fetch_array($result2);
		$msg = $row[0];
	} else {
		$msg = '';
	}
}
if(preg_match("/subwaters/", $query_layer)){
	$qlayer = $map->getLayerByName('subwtshds');
	$qlayer->queryByPoint($qpoint, MS_SINGLE, 0);
	$result = $qlayer->getResult(0);
	$result = $result->shapeindex;
	$query = "select subcuenca  from pr_subwtshds where ogc_fid = '{$result}'";
	if($result2 = pg_query($query)){
		$row = pg_fetch_array($result2);
		$msg = $row[0];
	} else {
		$msg = '';
	}
}
if(preg_match("/landcover/", $query_layer)){
	$qlayer = $map->getLayerByName('landcover');
	$qlayer->queryByPoint($qpoint, MS_SINGLE, 0);
	$qlayer->open();
	$items = $qlayer->getItems(); //not required, use with var_dump($items);
	$shape = $qlayer->getShape(0, 0);
	$x = $shape->values['value_0'];
	$qlayer->close();
	$query = "select lcov  from pr_lcov_cats where cat_num = {$x}";
	if($result2 = pg_query($query)){
		$row = pg_fetch_array($result2);
		$msg = $row[0];
	} else {
		$msg = '';
	}
	//$msg = $x;
}
if(preg_match("/lcov2/", $query_layer)){
	$qlayer = $map->getLayerByName('landcover2');
	$qlayer->queryByPoint($qpoint, MS_SINGLE, 0);
	$qlayer->open();
	$items = $qlayer->getItems(); //not required, use with var_dump($items);
	$shape = $qlayer->getShape(0, 0);
	$x = $shape->values['value_0'];
	$qlayer->close();
	$query = "select lcov  from pr_lcovsimp_cats where cat_num = {$x}";
	if($result2 = pg_query($query)){
		$row = pg_fetch_array($result2);
		$msg = $row[0];
	} else {
		$msg = '';
	}
	//$msg = $x;
}

echo json_encode(array("result"=>$msg)); 
?>

