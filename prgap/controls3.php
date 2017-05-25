<?php 
require('pr_range_class.php');
session_start();
require('pr_config.php');
pg_connect($pg_connect);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>controls3_php</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="../styles/aqtree3clickable.css" />
<link rel="stylesheet" href="../styles/custom-theme/jquery-ui-1.8.6.custom.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" ></script>
<script type="text/javascript" src="../javascript/jquery-ui-1.8.6.custom.min.js" ></script>
<script type="text/javascript" src="../javascript/aqtree3clickable.js"></script>
<script type="text/javascript" src="../javascript/controls_tab1.js"></script>
<script type="text/javascript" src="../javascript/controls234.js"></script>
<style type="text/css">
/* <![CDATA[ */
body {padding: 0px; margin: 2px;}
#tabs {font-size: 11px; width: 315px;}
#tabs-1 { width: 270px; font-size: 16px;}
#tabs-2{ width: 270px; font-size: 11px;}
#tabs-2 td{
		  font-size: 14px;
		  text-align: center;}
#tabs-3 {overflow: scroll; width: 270px; font-size: 16px;}
button {margin: 10px 0px 0px 100px; width: 100px;}
span.desc {font-size: 16px; line-height: 2;}
h2 {text-align: center;}
/* ]]> */
</style>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
$(document).ready(function(){
   load_selections();		  
   $("#tabs").tabs();
   $("#opentab").click();
   $("button").button();
   var win_h = $(window).height();
   $("#tabs-1,#tabs-2,#tabs-3").height(win_h - 78);
   $("#sub").click(function(evt) {
      document.forms[1].submit();	  
   });
});
/* ]]> */
</script>
</head>
<body>
<div id="tabs">
		  
<ul>
<li><a href="#tabs-1">View Layers</a></li>
<li><a id="opentab" href="#tabs-2">Select Species</a></li>
<li><a id="legendtab" href="#tabs-3">Legends</a></li>
</ul>

<div id="tabs-1">
<form action="map.php" method="post" target="map">
<ul class="aqtree3clickable">
<li class="aq3open"><a href="#" class="no_link">Foreground</a>
<ul>
<li><input type="checkbox" name="muni"  onclick="loadlayers();" checked="checked"/><a>Municipalities</a></li>
<li><input type="checkbox" name="islands"  onclick="loadlayers();" /><a>Islands</a></li>
<li><input type="checkbox" name="wtshds"  onclick="loadlayers();" /><a>Watersheds</a></li>
<li><input type="checkbox" name="subwtshds"  onclick="loadlayers();" /><a>Subwatersheds</a></li>
<li><input type="checkbox" name="roads"  onclick="loadlayers();" /><a>Roads</a></li>
<li><input type="checkbox" name="zones"  onclick="loadlayers();" /><a>Life Zones</a></li>
<li><input type="checkbox" name="hexs"  onclick="loadlayers();" /><a>Hexagons</a></li>
</ul>
</li>
<li><a href="#" class="no_link">Stewardship</a>
<ul>
<li><input type="radio" name="steward" value="gapown"  onclick="loadlayers();" /><a href="#own" onclick="show_lgnd();">Ownership</a></li>
<li><input type="radio" name="steward" value="gapman"  onclick="loadlayers();" /><a href="#manage" onclick="show_lgnd();">Management</a></li>
<li><input type="radio" name="steward" value="gapsta"  onclick="loadlayers();" /><a href="#status" onclick="show_lgnd();" >Status</a></li>
<li><input type="radio" name="steward" value="none" checked="checked" onclick="loadlayers();" /><a>none</a></li>
</ul>
</li>
<li><a href="#" class="no_link">Background</a>
<ul>
<li><input type="radio" name="background" value="landcover"  onclick="loadlayers();" /><a href="#lcov" onclick="show_lgnd();">GAP Land Cover</a></li>
<li><input type="radio" name="background" value="lcov2"  onclick="loadlayers();" /><a href="#lcov_smpl" onclick="show_lgnd();">General Land Cover</a></li>
<li><input type="radio" name="background" value="elevation" checked="checked" onclick="loadlayers();" /><a  href="#elev" onclick="show_lgnd();">Elevation</a></li>
<li><input type="radio" name="background" value="none"  onclick="loadlayers();" /><a>none</a></li>
</ul>
</li>
</ul>
</form>
</div>
<div id="tabs-2">
<?php



$aoi_name = $_POST['aoi_name'];
$type = $_POST['type'];

$owner_aoi = $_POST['owner'];
$manage_aoi = $_POST['manage'];
$muni_aoi = $_POST['muni'];
$island_aoi = $_POST['island'];
$zone_aoi = $_POST['zone'];
$wtshd_aoi = $_POST['wtshd'];
$subwtshd_aoi = $_POST['subwtshd'];

//$rclass_ser = $_POST['rclass'];

if (!isset($_SESSION["range".$aoi_name]) ){

	$_SESSION["range".$aoi_name] = new pr_range_class($aoi_name);
}
$rclass = $_SESSION["range".$aoi_name];
?>

<form action="controls4.php" method="post" target="controls" id="fm2" >
<input  type="hidden" name="aoi_name" value="<?php echo $aoi_name; ?>" /> 
<table style="border-collapse:collapse;" id="cntrls3">

<tr>
<th></th><th style="width: 80px;">Number of Species</th><th colspan="2">Select Category</th>
</tr>

<tr>
<td style="width:15px;"><input type="radio" name="species" value="all" checked="checked" /></td>
<td style="border: solid black 1px; border-right: white;"><?php echo $rclass->num_species['all_species']; ?></td>
<td colspan="2" style="border: solid black 1px; border-left: white;" >all species in selection area</td>
</tr>

<tr><td colspan="4" style="height: 5px; border-right:  solid 1px white; "></td></tr>

<tr>
<td></td>
<td style="border: solid black 1px; border-right: white; border-bottom: white"><?php echo $rclass->num_species['fed_species']; ?></td>
<td style="border-top: solid black 1px;"><input type="checkbox" name="fed" onclick="categories();" /></td>
<td style="border: solid black 1px; border-bottom: white; border-left: white;"> federally listed species</td>
</tr>

<tr>
<td></td>
<td style="border-left: solid black 1px;"><?php echo $rclass->num_species['state_species']; ?></td>
<td><input type="checkbox" name="state" onclick="categories();"/></td>
<td style="border-right: solid black 1px;"> puerto rico listed species</td>
</tr>



<tr>
<td><input type="radio" name="species" value="prot" /></td>
<td style="border-left: solid black 1px;"><?php echo $rclass->num_species['ns_global_species']; ?></td>
<td style="border-bottom:solid black 1px;"><input type="checkbox"  name="nsglobal" onclick="categories();"/></td>
<td style="border-right: solid black 1px; border-bottom:solid black 1px;"> natureserve global priority species</td>
</tr>

<tr>
<td></td>
<td style="border-left: solid black 1px;"><input type="radio" name="sel" value="and" /> </td>
<td colspan="2" style="border-right: solid black 1px;">AND Select only species in all checked categories</td>
</tr>

<tr>
<td ></td>
<td style="border: solid black 1px; border-top: white; border-right: white;"><input type="radio" name="sel" value="or" checked="checked" /></td>
<td colspan="2" style="border-bottom: solid black 1px; border-right: solid black 1px;"> OR Select species in any checked category </td>
</tr>

</table>
<button id="sub">Submit</button>
</form>
</div>
<div id="tabs-3">

<h4><a href="#lcov_smpl">General Land Cover</a></h4>
<h4><a href="#lcov">GAP Land Cover</a></h4>
<h4><a href="#own">Ownership (Stewardship)</a></h4>
<h4><a href="#manage">Management (Stewardship)</a></h4>
<h4><a href="#status">GAP Status (Stewardship)</a></h4>


<a name="elev"></a><br />
<h4>Elevation (meters)</h4>
<img alt="legend" src="/graphics/prgap/pr_elev_legend.png" />
<br />

<a name="lcov_smpl"></a><br />
<h4>General Land Cover</h4>
<img alt="legend" src="/graphics/prgap/pr_simple_lcov.png" />
<br />

<a name="lcov"></a><br />
<h4>GAP Land Cover</h4>
<img alt="landcover legend" src="/graphics/prgap/pr_lcov_1_25.png" />
<img alt="landcover legend" src="/graphics/prgap/pr_lcov_26_50.png" />
<img alt="landcover legend" src="/graphics/prgap/pr_lcov_51_70.png" />
<br />

<a name="own"></a><br />
<h4>Ownership (Stewardship)</h4>
<img alt="legend" src="/graphics/prgap/pr_owner_ms.png" />
<br />

<a name="manage"></a><br />
<h4>Management (Stewardship)</h4>
<img alt="legend" src="/graphics/prgap/pr_manage_ms.png" />
<br />

<a name="status"></a><br />
<h4>GAP Status (Stewardship)</h4>
<img alt="legend" src="/graphics/prgap/pr_status_ms.png" />
<br />
</div>

</div>
</body>
</html>
