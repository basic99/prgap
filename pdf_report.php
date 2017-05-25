<?php
require('fpdf.php');
require('pr_config.php');

set_time_limit(300);

//set mapfile and load mapscript if not already loaded
$mapfile = "prgap.map";

pg_connect($pg_connect);
//var_dump($_POST);
//get form variables
$win_w = $_POST['win_w'];
$win_h = $_POST['win_h'];
$extent_raw = $_POST['extent'];
$mode = $_POST['mode'];
$layer = $_POST['layers2'];

//ogc_fid for predefined aoi
$owner_aoi = $_POST['owner'];
$manage_aoi = $_POST['manage'];
$muni_aoi = $_POST['muni'];
$island_aoi = $_POST['island'];
$zone_aoi = $_POST['zone'];
$wtshd_aoi = $_POST['wtshd'];
$subwtshd_aoi = $_POST['subwtshd'];


$desc = $_POST['desc'];
$dpi = $_POST['dpi'];
$species_layer = $_POST['species_layer'];
$map_species = $_POST['map_species'];
$aoi_name = $_POST['aoi_name'];
$sppcode = $_POST['sppcode'];

//create click obj for zoom
$click_point = ms_newPointObj();
$click_x=$win_w/2;
$click_y=$win_h/2;
$click_point->setXY($click_x, $click_y);

//save extent to rect and create rectobj for zoom
$extent = explode(" ", $extent_raw);
$old_extent =  ms_newRectObj();
$old_extent->setextent($extent[0], $extent[1], $extent[2], $extent[3]);


//create map object
$map = ms_newMapObj($mapfile);



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


//convert sppcode to raster name
$raster = "pd_".$sppcode;
//set raster to display species maps
if(preg_match("/range/", $species_layer)){
	$this_layer = $map->getLayerByName('range');
	$this_layer->set('classitem', strtolower($sppcode));
	$this_layer->set('status', MS_ON);
	$this_layer->set('opacity', 50);	
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
	$this_layer->set('opacity', 50);
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
//draw AOI outline
$filter = "(name = '{$aoi_name}')";
$this_layer = $map->getLayerByName('aoi');
$this_layer->setFilter($filter);
$this_layer->set('status', MS_ON);
//create map for pdf

$pdfmapname = "map".rand(0,9999999).".png";

$pdfmaploc = "{$mspath}{$pdfmapname}";
if ($dpi == 300) {
	$map->setSize(3000, 1800);
	$map->scalebar->set("width", 400);
	$map->scalebar->set("height", 8);
	$map->scalebar->label->set("size", 32);
	$map->getLayerByName('roads')->getClass(0)->label->set("size", 24);
} else {
	$map->setSize(720, 432);
}
$map->zoompoint(1, $click_point, $win_w, $win_h, $old_extent);
$pdfmapimage = $map->draw();
$pdfmapimage->saveImage($pdfmaploc);

//////////////////////////////////////////////////////////////



class PDF extends FPDF
{
	function Footer()
	{
		$this->Image('/var/www/html/graphics/prgap/USGS_GAP_BaSIC_PDF_Logo_PR.png',0.5,7.5,0,0.5);
	}
}

//Instanciation of inherited class
$pdf=new PDF('L','in', 'Letter');
$pdf->SetFont('Arial','B',24);
$pdf->SetMargins(0.5,0.5);
$pdf->AddPage();


//print title
//$pdf->Cell(3);
$pdf->Cell(0,0,$desc,0,0);

//output map
$pdf->Image($mspath.$pdfmapname,0.5,1.25,10,6);

//add legends page

if((preg_match("/landcover/", $layer)   && (strlen($species_layer) == 0)) ||  preg_match("/habitat/", $species_layer))
{
	$pdf->AddPage();
	$pdf->Cell(0,0,'GAP Land Cover',0,0);
	$pdf->Image('/var/www/html/graphics/prgap/pr_lcov.png',0.5,1.00,10,0);
}
if(preg_match("/lcov2/", $layer)   && (strlen($species_layer) == 0))
{
	$pdf->AddPage();
	$pdf->Cell(0,0,'General Land Cover',0,0);
	$pdf->Image('/var/www/html/graphics/prgap/pr_simple_lcov.png',0.5,1.25,2.5,0);
	
}

if(preg_match("/management/", $layer) || preg_match("/manage/", $species_layer) ){
	$pdf->AddPage();
	$pdf->Cell(5,0,'Management (Stewardship)',0,0);
	$pdf->Image('/var/www/html/graphics/prgap/pr_manage_ms.png',0.5,1,0,6);
}
if(preg_match("/ownership/", $layer) || preg_match("/ownership/", $species_layer)){
	$pdf->AddPage();
	$pdf->Cell(5,0,'Ownership (Stewardship)',0,0);
	$pdf->Image('/var/www/html/graphics/prgap/pr_owner_ms.png',0.5,1,0,6);
}
if(preg_match("/status/", $layer)  || preg_match("/status/", $species_layer)){
	$pdf->AddPage();
	$pdf->Cell(5,0,'GAP Status (Stewardship)',0,0);
	$pdf->Image('/var/www/html/graphics/prgap/pr_status_ms.png',0.5,1,4,0);
}


if(preg_match("/elevation/", $layer) && (strlen($species_layer) == 0)){
$pdf->AddPage();
$pdf->Cell(5,0,'Elevation (meters)',0,0);
$pdf->Image('/var/www/html/graphics/prgap/pr_elev_legend.png',0.5,1.25,0,5);
}


if(preg_match("/range/", $species_layer)){
	$pdf->AddPage();
	$pdf->Cell(0,0,'Known range',0,0);
	$pdf->Image('/var/www/html/graphics/prgap/pr_range_legend.png',0.5,1.25,1.8,0);}
	$file_name = "prgap".rand(1,1000).".pdf";
	$pdf->Output($file_name, I);
?>
