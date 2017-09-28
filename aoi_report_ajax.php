<?php
require('pr_aoi_class.php');
session_start();

$report_type = $_POST['report'];
$sppcode = $_POST['sppcode'];
$species = $_POST['species'];
$species2 = $_POST['species2'];
$aoi_name = $_POST['aoiname'];
$a = $_SESSION[$aoi_name];

ini_set("error_log", "/var/www/html/prgap/logs/php-error.log");
ini_set("log_errors", 1);

error_log("aoi_report_ajax");
error_log($aoi_name);


//echo json_encode(array("time"=>$report_type, "status"=>$aoi_name, "rep"=>"$sppcode"));die();




if ($report_type == 'landcover'){
	$report =  "<h1>AOI Land Cover Report</h1>";
	$report .= "<pre>".$a->aoi_landcover()."</pre>";
}

if ($report_type == 'management') {
	$report =  "<h1>AOI Management Report</h1>";
	$report  .= "<pre>".$a->aoi_management()."</pre>";
}

if ($report_type == 'owner') {
	 $report = "<h1>AOI Ownership Report</h1>";
	$report .= "<pre>".$a->aoi_ownership()."</pre>";
}

if ($report_type == 'status') {
	$report = "<h1>AOI GAP Status Report</h1>";
	$report .= "<pre>".$a->aoi_status()."</pre>";
}

if ($report_type == 'status_sp') {
	$report =  "<h1>Species GAP Status Report</h1>";
	$report .= "<h3>{$species}</h3>";
	$report .= "<pre>".$a->species_status($sppcode)."</pre>";
}

if ($report_type == 'landcover_sp') {
	$report =  "<h1>Species Land Cover Report</h1>";
	$report .= "<h3>{$species}</h3>";
	$report .= "<pre>".$a->species_landcover($sppcode)."</pre>";
}

if ($report_type == 'management_sp') {
	$report = "<h1>Species Management Report</h1>";
	$report .= "<h3>{$species}</h3>";
	$report .= "<pre>".$a->species_management($sppcode)."</pre>";
}

if ($report_type == 'owner_sp') {
	$report = "<h1>Species Ownership Report</h1>";
	$report .= "<h3>{$species}</h3>";
	$report .= "<pre>".$a->species_ownership($sppcode)."</pre>";
}
if ($report_type == 'predicted') {
	$report = "<h1>Predicted Distribution Report</h1>";
	$report .= "<h3>{$species}</h3>";
	$report .= "<pre>".$a->predicted($sppcode)."</pre>";
}
if ($report_type == 'richness_report') {
	$report = "<h1>Richness Report</h1>";
	$report .= "<h3>{$species2}</h3>";
	$report .= "<pre>".$a->richnessreport($species)."</pre>";
}

// error_log($report);

echo json_encode(array("rep"=>$report));die();
?>