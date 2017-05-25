<?php
require('pr_aoi_class.php');
session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>GRASS Report</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
/* <![CDATA[ */
 @media print{
  .prn {display: none; }
  }
  
  body {font-family: sans-serif;}
/* ]]> */
</style>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
function spreadsheet(){
	var pretag = document.getElementsByTagName("pre");
	var content = pretag[0].innerHTML;
	document.forms[0].content.value = content;
	document.forms[0].submit();
}
/* ]]> */
</script>
</head>
<body>

<?php
$report_type = $_POST['report'];
$sppcode = $_POST['sppcode'];
$species = $_POST['species'];
$species2 = $_POST['species2'];


$aoi_name = $_POST['aoi_name'];
$a = $_SESSION[$aoi_name];


if ($report_type == 'landcover'){
	$report =  "<h1>AOI Land Cover Report</h1>";
	$report .= "<pre>".$a->aoi_landcover()."</pre>";
	echo $report;
}

if ($report_type == 'management') {
	$report =  "<h1>AOI Management Report</h1>";
	$report  .= "<pre>".$a->aoi_management()."</pre>";
	echo $report;
}

if ($report_type == 'owner') {
	 $report = "<h1>AOI Ownership Report</h1>";
	$report .= "<pre>".$a->aoi_ownership()."</pre>";
	echo $report;
}

if ($report_type == 'status') {
	$report = "<h1>AOI GAP Status Report</h1>";
	$report .= "<pre>".$a->aoi_status()."</pre>";
	echo $report;
}

if ($report_type == 'status_sp') {
	$report =  "<h1>Species GAP Status Report</h1>";
	$report .= "<h3>{$species}</h3>";
	$report .= "<pre>".$a->species_status($sppcode)."</pre>";
	echo $report;
}

if ($report_type == 'landcover_sp') {
	$report =  "<h1>Species Land Cover Report</h1>";
	$report .= "<h3>{$species}</h3>";
	$report .= "<pre>".$a->species_landcover($sppcode)."</pre>";
	echo $report;
}

if ($report_type == 'management_sp') {
	$report = "<h1>Species Management Report</h1>";
	$report .= "<h3>{$species}</h3>";
	$report .= "<pre>".$a->species_management($sppcode)."</pre>";
	echo $report;
}

if ($report_type == 'owner_sp') {
	$report = "<h1>Species Ownership Report</h1>";
	$report .= "<h3>{$species}</h3>";
	$report .= "<pre>".$a->species_ownership($sppcode)."</pre>";
	echo $report;
}
if ($report_type == 'predicted') {
	$report = "<h1>Predicted Distribution Report</h1>";
	$report .= "<h3>{$species}</h3>";
	$report .= "<pre>".$a->predicted($sppcode)."</pre>";
	echo $report;
}
if ($report_type == 'richness_report') {
	$report = "<h1>Richness Report</h1>";
	$report .= "<h3>{$species2}</h3>";
	$report .= "<pre>".$a->richnessreport($species)."</pre>";
	echo $report;
}

?>



<img src="/graphics/segap/b21_up.png" alt="b21" id="b21" class="prn" onclick="window.print();" 
   onmousedown="document.getElementById('b21').src='/graphics/segap/b21_dn.png';"
   onmouseup="document.getElementById('b21').src='/graphics/segap/b21_up.png';"/>

<img src="/graphics/segap/b22_up.png" alt="b22" id="b22" class="prn" onclick="spreadsheet();" 
   onmousedown="document.getElementById('b22').src='/graphics/segap/b22_dn.png';"
   onmouseup="document.getElementById('b22').src='/graphics/segap/b22_up.png';"/>

<form action="aoi_report_ss.php" target="_self" method="post">
<input id="aoi_name" type="hidden" name="aoi_name" value="<?php echo $aoi_name; ?>" />
<input type="hidden" name="report" value="<?php echo $report; ?>" />
<input type="hidden" name="itiscode" value="<?php echo $sppcode ?>" />
<input type="hidden" name="content"  />
</form>

</body>
</html>
