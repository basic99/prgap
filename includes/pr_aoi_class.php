<?php
include("pr_config.php");
$prdbcon = pg_connect($pg_connect);

ini_set("error_log", "/var/www/html/prgap/logs/php-error.log");


//////////////////////////////////////////////////////////////////////////////////
// this class has a constructor that takes as a parameter an AOI name
// the constructor calculates the bounding box and imports a mask into GRASS
// various functions that depend on the AOI can then be called
///////////////////////////////////////////////////////////////////////////////


putenv("GISBASE={$GISBASE}");
putenv("GISRC={$GISRC}");
putenv("PATH={$PATH}");

class pr_aoi_class{

	private $aoi_name;
	private $mask_name;
	private $min_x;
	private $min_y;
	private $max_x;
	private $max_y;
	private $area;
	public $base_dir;
	//public  $max_area_exception;


	public function __construct($a) {
		global $prdbcon;

		//assign parameter class variable
		$this->aoi_name = $a;

		//set base directory so GRASS can locate
		global $base_dir;
		$this->base_dir = $base_dir;

		//get max extents of aoi
		$query_fid = "select ogc_fid from aoi where name='{$this->aoi_name}'";
		$result_i = pg_query($prdbcon, $query_fid);
		$min_x = $min_y = 9999999;
		$max_x = $max_y =  -9999999;
		$row_i = pg_fetch_array($result_i);

		$query_minx = "select x(pointn(exteriorring(envelope(wkb_geometry)), 1)) from aoi where ogc_fid={$row_i[0]}";
		$query_miny = "select y(pointn(exteriorring(envelope(wkb_geometry)), 1)) from aoi where ogc_fid={$row_i[0]}";
		$query_maxx = "select x(pointn(exteriorring(envelope(wkb_geometry)), 3)) from aoi where ogc_fid={$row_i[0]}";
		$query_maxy = "select y(pointn(exteriorring(envelope(wkb_geometry)), 3)) from aoi where ogc_fid={$row_i[0]}";


		$result = pg_query($prdbcon, $query_minx);
		$row = pg_fetch_array($result);
		$this->min_x = min($row[0], $min_x);
		// $min_x = $row[0]-10000;
		$min_x = $this->min_x;

		$result = pg_query($prdbcon, $query_miny);
		$row = pg_fetch_array($result);
		$this->min_y = min($row[0], $min_y);
		// $min_y = $row[0] - 10000;
		$min_y = $this->min_y;

		$result = pg_query($prdbcon, $query_maxx);
		$row = pg_fetch_array($result);
		$this->max_x = max($row[0], $max_x);
		//$max_x = $row[0] + 10000;
		$max_x = $this->max_x;

		$result = pg_query($prdbcon, $query_maxy);
		$row = pg_fetch_array($result);
		$this->max_y = max($row[0], $max_y);
		// $max_y = $row[0] + 10000;
		$max_y =  $this->max_y;
		$this->area = ($max_x - $min_x) * ($max_y - $min_y);
		//if ($this->area > $max_aoi_area) {
		//throw new Exception($this->area);
		//}

		//check if can use mask already in GRASS
		$query = "select ogc_fid, aoi_data from aoi where name='{$this->aoi_name}'";
		$result = pg_query($prdbcon, $query);
		$row = pg_fetch_array($result);
		if (!empty($row['aoi_data'])) {
			$aoi_data = unserialize($row['aoi_data']);

			if ($aoi_data['ecosys'] == 1) {
				$this->mask_name = 'ecosys';
				return;
			}
		}

		//create name for mask
		$blank_file = aoi.rand(0,9999999);
		$blank = "/pub/server_temp/".$blank_file;
		$this->mask_name = $blank_file;

		//copy blank file to rectangle of AOI
		$gdal_cmd1 = "/usr/local/bin/gdal_translate -of GTiff -projwin {$min_x} {$max_y} {$max_x} {$min_y} /var/www/html/data/prgap/pr_blank {$blank} 1>/dev/null";
		//echo $gdal_cmd1;ob_flush();flush();
		system($gdal_cmd1);

		//burn aoi into blank file
		$gdal_cmd = "/usr/local/bin/gdal_rasterize -burn 1 -sql \"SELECT AsText(wkb_geometry) FROM  aoi  where aoi.name='{$this->aoi_name}' \"   PG:\"host=localhost port=5432 dbname=prgap user=postgres\"  {$blank} 1>/dev/null";
		//echo $gdal_cmd; ob_flush();flush();
		system($gdal_cmd);

		//import mask into GRASS
		$grass_cmd=<<<GRASS_SCRIPT
g.region -d
r.in.gdal input={$blank} output={$blank_file}a
cat {$this->base_dir}/grass/mask_recl | r.reclass input={$blank_file}a output={$blank_file}
GRASS_SCRIPT;
error_log($grass_cmd);
		//echo $grass_cmd."<br>";ob_flush();flush();
		system($grass_cmd);
		//system('whoami');

	}
	public function get_area(){
		return $this->area;
	}
	// function for testing only
	public function show_vars(){
		echo $this->aoi_name."<br>";
		echo $this->mask_name."<br>";
		echo $this->min_x."<br>";
		echo $this->min_y."<br>";
		echo $this->max_y."<br>";
		echo $this->max_x."<br>";

	}

	// getter functions for max extent of AOI
	public function get_minx(){
		return $this->min_x;
	}

	public function get_maxx(){
		return $this->max_x;
	}

	public function get_miny(){
		return $this->min_y;
	}

	public function get_maxy(){
		return $this->max_y;
	}

	/////////////////////////////////////////////////////////////////////////
	//functions that print reports for all AOI, not dependant on species
	//////////////////////////////////////////////////////////////////////////


	public function aoi_landcover(){
		$report_name = "report".rand(0,999999);
		$str=<<<GRASS_SCRIPT
g.region n={$this->max_y} s={$this->min_y} w={$this->min_x} e={$this->max_x}
r.mapcalc {$this->mask_name}calc_lc = '{$this->mask_name}  * pr_lcov' 1>/dev/null 2>/dev/null
cat {$this->base_dir}/grass/pr_lcov_recl | r.reclass input={$this->mask_name}calc_lc output={$this->mask_name}recl_lc
r.report -n map={$this->mask_name}recl_lc units=a,h,p
GRASS_SCRIPT;
		return `$str`;
}

public function aoi_management(){
	$report_name = "report".rand(0,999999);
	$str=<<<GRASS_SCRIPT
g.region n={$this->max_y} s={$this->min_y} w={$this->min_x} e={$this->max_x}
r.mapcalc {$this->mask_name}calc_man = '{$this->mask_name}  * pr_manage' 1>/dev/null 2>/dev/null
cat {$this->base_dir}/grass/pr_manage_recl | r.reclass input={$this->mask_name}calc_man output={$this->mask_name}recl_man
r.report -n map={$this->mask_name}recl_man units=a,h,p
GRASS_SCRIPT;
	return `$str`;
}

public function aoi_ownership(){
	$report_name = "report".rand(0,999999);
	$str=<<<GRASS_SCRIPT
g.region n={$this->max_y} s={$this->min_y} w={$this->min_x} e={$this->max_x}
r.mapcalc {$this->mask_name}calc_own = '{$this->mask_name}  * pr_owner' 1>/dev/null 2>/dev/null
cat {$this->base_dir}/grass/pr_own_recl | r.reclass input={$this->mask_name}calc_own output={$this->mask_name}recl_own
r.report -n map={$this->mask_name}recl_own units=a,h,p
GRASS_SCRIPT;
	return `$str`;
}

public function aoi_status(){
	$report_name = "report".rand(0,999999);
	$str=<<<GRASS_SCRIPT
g.region n={$this->max_y} s={$this->min_y} w={$this->min_x} e={$this->max_x}
r.mapcalc {$this->mask_name}calc_stat = '{$this->mask_name}  * pr_status' 1>/dev/null 2>/dev/null
cat {$this->base_dir}/grass/pr_status_recl | r.reclass input={$this->mask_name}calc_stat output={$this->mask_name}recl_stat
r.report -n map={$this->mask_name}recl_stat units=a,h,p
GRASS_SCRIPT;
	return `$str`;
}

/////////////////////////////////////////////////////////////////////////////
//functions that print reports for  AOI, are dependant on species
////////////////////////////////////////////////////////////////////////////

public function predicted($a){
	$report_name = "report".rand(0,999999);

	//convert strelcode to raster name
	$raster = "pd_".$a;
	$str=<<<GRASS_SCRIPT

g.region n={$this->max_y} s={$this->min_y} w={$this->min_x} e={$this->max_x}
r.mapcalc {$this->mask_name}calc_pred = '{$this->mask_name}  *{$raster}' 1>/dev/null 2>/dev/null
cat {$this->base_dir}/grass/pr_pred_recl | r.reclass input={$this->mask_name}calc_pred output={$this->mask_name}recl_pred
r.report -n map={$this->mask_name}recl_pred units=a,h,p
GRASS_SCRIPT;
	return `$str`;
}

public function species_status($a){
	$report_name = "report".rand(0,999999);

	//convert strelcode to raster name
	$raster = "pd_".$a;
	$str=<<<GRASS_SCRIPT
g.region n={$this->max_y} s={$this->min_y} w={$this->min_x} e={$this->max_x}
r.mapcalc {$this->mask_name}calc_stat_sp = '{$this->mask_name}  *{$raster}* pr_status' 1>/dev/null 2>/dev/null
cat {$this->base_dir}/grass/pr_status_recl | r.reclass input={$this->mask_name}calc_stat_sp output={$this->mask_name}recl_stat_sp
r.report -n map={$this->mask_name}recl_stat_sp units=a,h,p
GRASS_SCRIPT;
	return `$str`;
}

public function species_ownership($a){
	$report_name = "report".rand(0,999999);

	//convert strelcode to raster name
	$raster = "pd_".$a;
	$str=<<<GRASS_SCRIPT
g.region n={$this->max_y} s={$this->min_y} w={$this->min_x} e={$this->max_x}
r.mapcalc {$this->mask_name}calc_own_sp = '{$this->mask_name}  *{$raster}* pr_owner' 1>/dev/null 2>/dev/null
cat {$this->base_dir}/grass/pr_own_recl | r.reclass input={$this->mask_name}calc_own_sp output={$this->mask_name}recl_own_sp
r.report -n map={$this->mask_name}recl_own_sp units=a,h,p
GRASS_SCRIPT;
	return `$str`;
}

public function species_management($a){
	$report_name = "report".rand(0,999999);

	//convert strelcode to raster name
	$raster = "pd_".$a;
	$str=<<<GRASS_SCRIPT

g.region n={$this->max_y} s={$this->min_y} w={$this->min_x} e={$this->max_x}
r.mapcalc {$this->mask_name}calc_man_sp = '{$this->mask_name}  *{$raster}*  pr_manage' 1>/dev/null 2>/dev/null
cat {$this->base_dir}/grass/pr_manage_recl | r.reclass input={$this->mask_name}calc_man_sp output={$this->mask_name}recl_man_sp
r.report -n map={$this->mask_name}recl_man_sp units=a,h,p
GRASS_SCRIPT;
	return `$str`;
}

public function species_landcover($a){
	$report_name = "report".rand(0,999999);

	//convert strelcode to raster name
	$raster = "pd_".$a;
	$str=<<<GRASS_SCRIPT

g.region n={$this->max_y} s={$this->min_y} w={$this->min_x} e={$this->max_x}
r.mapcalc {$this->mask_name}calc_lc_sp = '{$this->mask_name}  *{$raster}*  pr_lcov' 1>/dev/null 2>/dev/null
cat {$this->base_dir}/grass/pr_lcov_recl | r.reclass input={$this->mask_name}calc_lc_sp output={$this->mask_name}recl_lc_sp
r.report -n map={$this->mask_name}recl_lc_sp units=a,h,p
GRASS_SCRIPT;
	return `$str`;
}

//////////////////////////////////////////////////////////////////////////////////////
//functions that return handle to map created for single species
//////////////////////////////////////////////////////////////////////////////////////

public function landcover_map($a){

	//convert strelcode to raster name
	$raster = "pd_".$a;

	//calculate 10% padding
	$x_pad = ($this->max_x - $this->min_x) * 0.1;
	$y_pad = ($this->max_y - $this->min_y) * 0.1;
	$max_x = $this->max_x + $x_pad;
	$min_x = $this->min_x - $x_pad;
	$max_y = $this->max_y + $y_pad;
	$min_y = $this->min_y - $y_pad;

	//create map name
	$map = "map".rand(0,999999);

	$str=<<<GRASS_SCRIPT
g.region n={$max_y} s={$min_y} w={$min_x} e={$max_x}
r.mapcalc {$map} = '{$raster} *  pr_lcov' 1>/dev/null 2>/dev/null
cat {$this->base_dir}/grass/pr_lcov_colors | r.colors map={$map} color=rules 1>/dev/null 2>/dev/null
GRASS_SCRIPT;
	system($str);
	return $map;
}

public function ownership_map($a){

	//convert strelcode to raster name
	$raster = "pd_".$a;

	//calculate 10% padding
	$x_pad = ($this->max_x - $this->min_x) * 0.1;
	$y_pad = ($this->max_y - $this->min_y) * 0.1;
	$max_x = $this->max_x + $x_pad;
	$min_x = $this->min_x - $x_pad;
	$max_y = $this->max_y + $y_pad;
	$min_y = $this->min_y - $y_pad;

	//create map name
	$map = "map".rand(0,999999);

	$str=<<<GRASS_SCRIPT
g.region n={$max_y} s={$min_y} w={$min_x} e={$max_x}
r.mapcalc {$map} = '{$raster} *  pr_owner_256' 1>/dev/null 2>/dev/null
cat {$this->base_dir}/grass/pr_owner_colors | r.colors map={$map} color=rules 1>/dev/null 2>/dev/null
GRASS_SCRIPT;
	system($str);
	return $map;
}

public function protection_map($a){

	//convert strelcode to raster name
	$raster = "pd_".$a;

	//calculate 10% padding
	$x_pad = ($this->max_x - $this->min_x) * 0.1;
	$y_pad = ($this->max_y - $this->min_y) * 0.1;
	$max_x = $this->max_x + $x_pad;
	$min_x = $this->min_x - $x_pad;
	$max_y = $this->max_y + $y_pad;
	$min_y = $this->min_y - $y_pad;

	//create map name
	$map = "map".rand(0,999999);

	$str=<<<GRASS_SCRIPT
g.region n={$max_y} s={$min_y} w={$min_x} e={$max_x}
r.mapcalc {$map} = '{$raster} *  pr_status' 1>/dev/null 2>/dev/null
cat {$this->base_dir}/grass/pr_sta_color | r.colors map={$map} color=rules 1>/dev/null 2>/dev/null
GRASS_SCRIPT;
	system($str);
	return $map;
}

public function management_map($a){

	//convert strelcode to raster name
	$raster = "pd_".$a;

	//calculate 10% padding
	$x_pad = ($this->max_x - $this->min_x) * 0.1;
	$y_pad = ($this->max_y - $this->min_y) * 0.1;
	$max_x = $this->max_x + $x_pad;
	$min_x = $this->min_x - $x_pad;
	$max_y = $this->max_y + $y_pad;
	$min_y = $this->min_y - $y_pad;

	//create map name
	$map = "map".rand(0,999999);

	$str=<<<GRASS_SCRIPT
g.region n={$max_y} s={$min_y} w={$min_x} e={$max_x}
r.mapcalc {$map} = '{$raster} *  pr_manage_256' 1>/dev/null 2>/dev/null
cat {$this->base_dir}/grass/pr_manage_colors  | r.colors map={$map} color=rules 1>/dev/null 2>/dev/null
GRASS_SCRIPT;
	system($str);
	return $map;
}

//function that returns handle to map created for richness and function to make report for same
//accepts as parameter colon delimted species list

public function richness($a){
	$species = explode(":", $a);
	for ($i=0; $i<sizeof($species); $i++){
		$layers[$i] = "pd_".$species[$i];
	}
	$layer_str = implode(" + ", $layers);
	$rules_file = "{$this->base_dir}/grass/richness_rule";

	//calculate 10% padding
	$x_pad = ($this->max_x - $this->min_x) * 0.1;
	$y_pad = ($this->max_y - $this->min_y) * 0.1;
	$max_x = $this->max_x + $x_pad;
	$min_x = $this->min_x - $x_pad;
	$max_y = $this->max_y + $y_pad;
	$min_y = $this->min_y - $y_pad;

	//create map name
	$map = "map".rand(0,999999);
	$str=<<<GRASS_SCRIPT
g.region n={$max_y} s={$min_y} w={$min_x} e={$max_x}
r.mapcalc  {$map} = '{$layer_str}' 1>/dev/null 2>/dev/null
cat {$rules_file} | r.colors map={$map} color=rules 1>/dev/null 2>/dev/null
GRASS_SCRIPT;
	system($str);
	return $map;
}

public function richnessreport($a){
	$species = explode(":", $a);
	for ($i=0; $i<sizeof($species); $i++){
		$layers[$i] = "pd_".$species[$i];
	}
	$layer_str = implode(" + ", $layers);
	$str=<<<GRASS_SCRIPT
g.region n={$this->max_y} s={$this->min_y} w={$this->min_x} e={$this->max_x} &>/dev/null
r.mapcalc {$this->mask_name}richness_report = '{$this->mask_name}  *({$layer_str})' &>/dev/null
r.report -n map={$this->mask_name}richness_report units=a,h,p 2>/dev/null
GRASS_SCRIPT;

	return `$str`;

}

public function richnessexport($a){
	$map = "richness".rand(0,9999999).".tif";
	$str=<<<GRASS_SCRIPT
r.out.gdal input={$a} format=GTiff type=Byte output=/pub/richness_export/{$map}  &>/dev/null
GRASS_SCRIPT;
	//echo $str;
	system($str);
	return $map;

}


}

?>