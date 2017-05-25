<?php
require('pr_config.php');
pg_connect($pg_connect);

require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Cache');
try{
   $frontendOptions = array(
      'lifetime' => 604800, // cache lifetime forever
      'automatic_serialization' => true
   );
   $backendOptions = array(
       'cache_dir' => '../../temp/' // Directory where to put the cache files
   );
   // getting a Zend_Cache_Core object
   $cache = Zend_Cache::factory('Output',
                                'File',
                                $frontendOptions,
                                $backendOptions);
} catch(Exception $e) {
  echo $e->getMessage();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>controls_php</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="../styles/aqtree3clickable.css" />
<link rel="stylesheet" href="../styles/custom-theme/jquery-ui-1.8.6.custom.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" ></script>
<script type="text/javascript" src="../javascript/jquery-ui-1.8.6.custom.min.js" ></script>
<script type="text/javascript" src="../javascript/aqtree3clickable.js"></script>
<script type="text/javascript" src="../javascript/controls_tab1.js"></script>
<script type="text/javascript" src="../javascript/set_tabs.js"></script>
<script type="text/javascript" src="../javascript/controls1.js"></script>
<style type="text/css">
/* <![CDATA[ */
body {padding: 0px; margin: 2px;}
#tabs {font-size: 11px; width: 315px;}
#tabs-1, #tabs-3{overflow: scroll;  width: 270px; font-size: 16px;}
#tabs-2 {overflow: scroll;  width: 298px; font-size: 16px; } 
#tabs-2cont {padding-bottom: 0px;}

#pre_btns {font-size: 11px; padding-bottom: 20px;}
#cust  {font-size: 11px; }

#tabs-2 ul {width: 550px;  padding-right: 20px;}
p {font-size: 16px;}
button {width: 90px;}

/* ]]> */
</style>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
$(function() {
   $( "#tabs" ).tabs();
   $("button").button();
   
   var win_h = $(window).height();
   $("#tabs-1,#tabs-3").height(win_h - 78);
   $("#tabs-2").height(win_h - 104);
   
   $("#aoi_reset").click(function(evt) {
      evt.preventDefault();
      pre_reset();
   });
   $("#aoi_submit").click(function(evt) {
      evt.preventDefault();
      aoi_pre_sub();
   });
   $("#aoi_custom").click(function(evt) {
      evt.preventDefault();
      document.getElementById('cont2').style.display = 'none';
      document.getElementById('cust').style.display = 'block';
      cust_start();
   });
   $("#cust_rst").click(function(evt) {
      evt.preventDefault();
      cust_reset();
   });
   $("#cust_sbmt").click(function(evt) {
      evt.preventDefault();
      aoi_cust_sub();
   });
   $("#predef").click(function(evt) {
      evt.preventDefault();
      document.getElementById('cont2').style.display = 'block';
      document.getElementById('cust').style.display = 'none';
      //set_tab2();
      pre_start();
      
   });
});
/* ]]> */
</script>
</head>
<body>
<div id="tabs">
<ul>
<li><a href="#tabs-1">View Layers</a></li>
<li><a href="#tabs-2cont">Define AOI</a></li>
<li><a id="legendtab" href="#tabs-3">Legends</a></li>
</ul>
<form action="">
<div id="tabs-1">
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
<li><input type="radio" name="steward" value="gapown"  onclick="loadlayers();" /><a href="#owner" onclick="show_lgnd();">Ownership</a></li>
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
</div>
<div id="tabs-2cont">
<div id="cont2">
   
<div id="pre_btns" >
<button id="aoi_reset">&nbsp;Reset&nbsp;&nbsp;</button>
<button id="aoi_submit">Submit</button>
<button id="aoi_custom">Custom</button>
</div><!-- end pre_btns -->



<div id="tabs-2">
   
<input type="checkbox"  name="ecosys" style="margin-left:4px;" onclick="add_ecosys()"/><span>full extent</span>

<ul class="aqtree3clickable">

<li><a href="#" class="no_link">ownership</a>
<ul>
<li><input type="checkbox"  name="owner_tab2" onclick="show_owner();"  />
<a class="lnk1">Show this layer</a></li>
<?php
$query = "select o_class_co, land_owner, ogc_fid  from pr_owner order by o_class_co";
$result = pg_query($query);
while ($row = pg_fetch_array($result)){
	echo "<li><input type='checkbox'  name='owner_aoi' value=\"{$row['ogc_fid']}\" onclick='add_owner();' class='lnk2'/><a>{$row['land_owner']}</a></li>";
}
?>
</ul>
</li>

<li><a href="#" class="no_link">management</a>
<ul>
<li><input type="checkbox" name="manage_tab2"  onclick="show_manage();" />
<a class="lnk1">Show this layer</a></li>
<?php
$query = "select m_class_co, mgmt_name, ogc_fid from pr_manage order by m_class_co";
$result = pg_query($query);
while ($row = pg_fetch_array($result)){
	echo "<li><input type='checkbox' name='manage_aoi' value=\"{$row['ogc_fid']}\" onclick='add_manage();' class='lnk2'/><a>{$row['mgmt_name']}</a></li>";
}
?>
</ul>
</li>

<li><a href="#" class="no_link">municipality</a>
<ul>
<li><input type="checkbox" name="muni_tab2"  onclick="show_muni();" />
<a class="lnk1" >Show this layer</a></li>
<?php
$query = "select municipio, ogc_fid from pr_muni order by municipio";
$result = pg_query($query);
while ($row = pg_fetch_array($result)){
	echo "<li><input type='checkbox' name='muni_aoi' value=\"{$row['ogc_fid']}\" onclick='add_muni();' class='lnk2'/><a>{$row['municipio']}</a></li>";
}
?>
</ul>
</li>

<li><a href="#" class="no_link">island</a>
<ul>
<li><input type="checkbox" name="island_tab2"  onclick="show_island();" />
<a class="lnk1" >Show this layer</a></li>
<?php
$query = "select name, ogc_fid from pr_coast order by name";
$result = pg_query($query);
while ($row = pg_fetch_array($result)){
	echo "<li><input type='checkbox' name='island_aoi' value=\"{$row['ogc_fid']}\" onclick='add_island();'  class='lnk2'/><a>{$row['name']}</a></li>";
}
?>
</ul>
</li>

<li><a href="#" class="no_link">watershed</a>
<ul>
<li><input type="checkbox" name="wtshd_tab2"  onclick="show_wtshd();" />
<a class="lnk1" >Show this layer</a></li>
<?php
$query = "select cuenca_opa, ogc_fid from pr_wtshds order by cuenca_opa";
$result = pg_query($query);
while ($row = pg_fetch_array($result)){
	echo "<li><input type='checkbox' name='wtshd_aoi' value=\"{$row['ogc_fid']}\" onclick='add_wtshd();'  class='lnk2'/><a>{$row['cuenca_opa']}</a></li>";
}
?>
</ul>
</li>

<li><a href="#" class="no_link">subwatershed</a>
<ul>
<li><input type="checkbox" name="subwtshd_tab2"  onclick="show_subwtshd();" />
<a class="lnk1" >Show this layer</a></li>
<?php
$query = "select subcuenca, ogc_fid from pr_subwtshds order by subcuenca";
$result = pg_query($query);
while ($row = pg_fetch_array($result)){
	echo "<li><input type='checkbox' name='subwtshd_aoi' value=\"{$row['ogc_fid']}\" onclick='add_subwtshd();'  class='lnk2'/><a>{$row['subcuenca']}</a></li>";
}
?>
</ul>
</li>

<li><a href="#" class="no_link">life zone</a>
<ul>
<li><input type="checkbox" name="life_zone_tab2"  onclick="show_life_zone();" />
<a class="lnk1" >Show this layer</a></li>
<?php
$query = "select zone_desc, ogc_fid from pr_life_zones order by zone_desc";
$result = pg_query($query);
while ($row = pg_fetch_array($result)){
	echo "<li><input type='checkbox' name='zone_aoi' value=\"{$row['ogc_fid']}\" onclick='add_zone();'  class='lnk2'/><a>{$row['zone_desc']}</a></li>";
}
?>
</ul>
</li>



</ul>
</div><!-- end tabs-2 -->

</div><!-- end cont2 -->

<div id="cust" style="display: none;">
   <button id="cust_rst">Reset</button>
   <button id="cust_sbmt">Submit</button>
   <button id="predef">Predefined</button>


<p>Click on the map to locate the starting point. Move the cursor to the second point and click again. 
Continue in this fashion until the polygon describes the AOI. To start over click reset, or to submit AOI click submit. </p>

<p>Create an AOI by <a href="javascript:upload();">uploading</a> a user Shapefile.</p>
</div>
</div><!-- end tabs-2cont -->

</form>

<div id="tabs-3">
   
<h4><a href="#lcov_smpl">General Land Cover</a></h4>
<h4><a href="#lcov">GAP Land Cover</a></h4>
<h4><a href="#owner">Ownership (Stewardship)</a></h4>
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

<a name="owner"></a><br />
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
