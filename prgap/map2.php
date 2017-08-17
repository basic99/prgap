<?php
/**
 * Second of 2 pages that displays map in frame map. This page used when AOI is defined.
 *
 * Gather $_POST data from map.php or $_FILES from upload.php.
 * If $_FILES is set then check that uploaded files contain .shp, .shx and .prj if not then throw an  exception.
 * Move uploaded files from temp space.
 * Test $type variable to determine which AOI definition function to call, which will create an entry in table aoi.
 * Create map object, and turn on layers from layers string. Use setFilter to display AOI boundary from table aoi.
 * To display species range map (layer range in mapfile) use set('classitem', strtolower($sppcode)) to select column in
 * table nc_range that corresponds to species.
 * To display GRASS calculated maps select layer mapcalc and use set('data', $grass_raster.$map_species) to tell it the location of
 * the calculated GRASS map.
 * To display predicted distribution maps select layer mapcalc and use set('data', $grass_raster_perm.$raster)  to tell it the location
 * of the predicted distribution GRASS map
 *
 *
 * @package ncgap
 *
 */
session_start();
if(!isset($_SESSION['username'])){
	$_SESSION['username'] = "visitor";
}
$user_name = $_SESSION['username'];
require('pr_config.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>map2 page</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="imagetoolbar" content="no" />
<link rel="StyleSheet" href="../styles/map.css" type="text/css" />

<style type="text/css">
/* <![CDATA[ */

/* ]]> */
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" ></script>
<script type="text/javascript" src="../javascript/wz_jsgraphics.js"></script>
<script type="text/javascript" src="../javascript/dragging.js"></script>
<script type="text/javascript" src="../javascript/drag_box.js"></script>
<script type="text/javascript" src="../javascript/buttons.js"></script>
<script type="text/javascript" src="../javascript/draw_aoi.js"></script>
<script type="text/javascript" src="../javascript/onmapctls.js"></script>

<script language="javascript" type="text/javascript">
/* <![CDATA[ */

var curr_user_win;
var aoi_alert = true;
var win_w;
var win_h;
var click_x;
var click_y;
var permlink_flag = true;

$(function() {
   initwin();
	document.getElementById('dragMap').ondragstart = function() {return false;};
	setmapctls();
	pan();
   //this bit of code is for permalink
   <?php
	if(!isset($_GET['species'])){
		echo "parent.controls.location = \"controls2.php\";";
	}
	?>
	var shape_error = $('#shape_error').val();
	if(shape_error == 'none'){
		send_ajax();
	} else {
		$('.mapimage').html(shape_error);
	}
	$(window).unload( function () {
		if(curr_user_win){
			curr_user_win.close();
		}
	} );
});

function initwin(){
	win_h = $(window).height() - 69;
	win_w = $(window).width();
	if (win_h%2 == 1) win_h = win_h -1;
	if (win_w%2 == 1) win_w = win_w -1;
	click_x = win_w/2;
	click_y = win_h/2;
	$('#winh_ajax').val(win_h);
	$('#winw_ajax').val(win_w);
	$('#clkx_ajax').val(click_x);
	$('#clky_ajax').val(click_y);
}


function send_ajax(){
	var job_id = Math.floor(Math.random() * 1000000000);
	//$('#tmpimg').remove();
	var extent = $('#extent_ajax').val();
	var zoom = $('#zoom_ajax').val();
	var zoom_aoi = $('#zoomaoi_ajax').val();
	var layers = $('#layers_ajax').val();
	var win_h = $('#winh_ajax').val();
	var win_w = $('#winw_ajax').val();
	var click_x = $('#clkx_ajax').val();
	var click_y = $('#clky_ajax').val();
	var posix = $('#posix_ajax').val();
	var posiy = $('#posiy_ajax').val();
	var typ = $('#type_ajax').val();
	var aoiname = $('#aoiname_ajax').val();
	var aoinamesaved = $('#aoinamesaved_ajax').val();
	var pred_transp = $("#pred_transp_ajax").val();
	var range_transp = $("#range_transp_ajax").val();


	var manage = $('#manage_ajax').val();
	var owner = $('#owner_ajax').val();
	var basin = $('#basin_ajax').val();
	var sub_basin = $('#sub_basin_ajax').val();
	var muni = $('#muni_ajax').val();
	var island = $('#island_ajax').val();
	var zone = $('#zone_ajax').val();
	var ecosys= $('#ecosys_ajax').val();
	var shapefile = $('#shape_shp').val();

	//species code to use to generate map
	var sppcode = $('#sppcode_ajax').val();
	//type of map, eg predicted, ownership etc
	var species_layer = $('#species_layer_ajax').val();
	//type of map, eg predicted, ownership etc of previous selection, if same can reuse map
	var species_layer_prev = $('#species_layer_prev_ajax').val();
	$('#species_layer_prev_ajax').val(species_layer);
	//name of map created previously to reuse if possible
	var map_species = $('#map_species_ajax').val();
	//string of names of species to create richness map
	var richness_species = $('#richness_species_ajax').val();
	//id number for job to check if create map object fails
	//var job_id = Math.floor(Math.random() * 1000000000);

	$('.mapimage').hide();
	$('#loader_gif').show();
	$.ajax({
		type: "POST",
		url: "map2_ajax.php",
		data: {
			extent: extent,
			zoom: zoom,
			zoomaoi: zoom_aoi,
			layers: layers,
			winw: win_w,
			winh: win_h,
			clickx: click_x,
			clicky: click_y,
			posi_x: posix,
			posi_y: posiy,
			type: typ,
			aoi_name: aoiname,
			aoi_name_saved: aoinamesaved,
			manage_aoi: manage,
			owner_aoi: owner,
			basin_aoi: basin,
			sub_basin_aoi: sub_basin,
			muni_aoi: muni,
			island_aoi: island,
			zone_aoi: zone,
			ecosys_aoi: ecosys,
			sppcode: sppcode,
			species_layer: species_layer,
			species_layer_prev: species_layer_prev,
			map_species: map_species,
			richness_species: richness_species,
			shapefile: shapefile,
		   pred_transp: pred_transp,
			range_transp: range_transp,
			job_id: job_id
		},
		dataType: "json",
		success: function(data){
			console.log("testing");
			console.log(data);
			$(parent.refmap.document.images[0]).attr("src", data.refname);
			$('#extent_ajax').val(data.extent);
			$('#extent_pdf').val(data.extent);
			$('.aoi_name').val(data.aoiname);
			$("#zoomaoi_ajax").val(0); //reset flag to prevent zoom to aoi
			//$('#loadingarea').html("<img id='tmpimg' src=/server_temp/" + data.mapname + " />");
			$('.mapimage').attr("src", "/server_temp/" + data.mapname);
			$('.mapimage').load(function(){
				$('.mapimage').fadeIn(300);
				$('#loader_gif').fadeOut(300);
				$('#aoi_msg').fadeOut(300);
			});
			$('#dragMap').css("top", "69px");
			$('#dragMap').css("left", "0px");
			$('#clkx_ajax').val(win_w/2);
			$('#clky_ajax').val(win_h/2);
			$('#map_species_ajax').val(data.mapspecies);
			$('#map_species_pdf').val(data.mapspecies);

         // if(permlink_flag){
			<?php
			// if(isset($_GET['species'])){
			// 	$species = $_GET['species'];
			// 	echo "parent.controls.location = \"controls4.php?species={$species}&aoiname=\" + data.aoiname;";
			// }
			?>
			// permlink_flag = false;
			// }

		}

	});
	//check for mapobj create failure after 2 seconds and if fail resubmit ajax

	setTimeout(function(){
		$.ajax({
			type: "POST",
			url: "chk_mapobj.php",
			data: {job_id: job_id},
			dataType: "json",
			success: function(data){
				if(data.check == "failure"){
					send_ajax();
				}
			}
		});
	}, 2000);

}


//zoom to aoi
function zoom_aoi(){
	$("#zoomaoi_ajax").val(1);
	$("#zoom_ajax").val(1);
	send_ajax();
}

function help(){
	window.open("/howto/","h1","");
}



function clip_zip(){
	if (parent.data.document.forms.fm4){
		if(parent.functions.location.pathname.indexOf("single.php") != -1 ){
			parent.data.document.forms.fm4.richness_map.value = '';
			parent.data.document.forms.fm4.richness_species.value = '';
			var sppcode = parent.functions.document.forms.fm1.sppcode.value;
			parent.data.document.forms.fm4.spp.value = sppcode;
		} else {

			parent.data.document.forms.fm4.spp.value = null;
			var species = document.forms.ajaxform.richness_species.value;
			var map_name = document.forms.ajaxform.map_species.value;
			parent.data.document.forms.fm4.richness_map.value = map_name;
			parent.data.document.forms.fm4.richness_species.value = species;
		}
		parent.data.document.forms.fm4.submit();
	} else {
		document.forms.fm6.submit();
	}
}




function save_aoi(){
	curr_user_win = window.open("","w2","menubar=no,scrollbars,width=400,height=600");  //parent.
	document.forms.fm4.submit();

}

function login2(){
	curr_user_win = window.open("/ncgap/login/login.php","w2","menubar=no,scrollbars,width=600,height=300,top=150"); //parent.

}
/* ]]> */
</script>

</head>

<body >
<img id="loader_gif" alt="loading icon" style="position: absolute; top: 100px; left: 100px; display: block; z-index: 8;" src="/graphics/ncgap/ajax-loader.gif" />
<p style="position: absolute;" id="aoi_msg">Please wait while we make some calculations for your AOI<br />(this could take several minutes)</p>

<script type="text/javascript">
document.getElementById('loader_gif').style.top = (find_height()/2 + 24) +"px";
document.getElementById('loader_gif').style.left = (find_width()/2 -110) +"px";
document.getElementById('aoi_msg').style.top = (find_height()/2 - 50) +"px";
document.getElementById('aoi_msg').style.left = (find_width()/2 - 200) +"px";
</script>



<div id="toolbar" style="display: block;" >

<table>
<tr>
<td><input type="image" src="/graphics/ncgap/mag_plus_up.png" id="zmin" title="Zoom In" onclick="zoom_in()" /></td>
<td><input type="image" src="/graphics/ncgap/mag_minus_up.png" id="zmout" title="Zoom Out" onclick="zoom_out()" /></td>
<td><input type="image" src="/graphics/ncgap/pan_up.png" id="pn" title="Pan" onclick="pan()" /></td>
<td><input type="image" src="/graphics/ncgap/draw_up.png" style="visibility:hidden;"/> </td>
<td><input  type="image" src="/graphics/ncgap/info_up.png"  style="visibility:hidden;"/> </td>
<td><input  type="image" src="/graphics/ncgap/info_up.png"  style="visibility:hidden;"/> </td>
<td><input  type="image" src="/graphics/ncgap/info_up.png"  style="visibility:hidden;"/> </td>
<td><input  type="image" src="/graphics/ncgap/info_up.png"  style="visibility:hidden;"/> </td>
<td><input type="image" src="/graphics/ncgap/edit_user_up.png" title="login" onclick="login2();" /> </td>
<td >User: </td>
<td ><input type="text" size="20" id="visitor" value="<?php  echo $user_name; ?>" readonly='readonly' /></td>
</tr>

<tr>
<td><input type="image" src="/graphics/prgap/pr_full_up.png" title="Zoom to Full Extent" onclick="fullview()" /> </td>
<td><input type="image" src="/graphics/ncgap/zoom_aoi_up.png" title="zoom to AOI" onclick="zoom_aoi()" /></td>
<td><input type="image" src="/graphics/ncgap/new_aoi_up.png" title="create different AOI" onclick="new_aoi();" /></td>
<td><input type="image" src="/graphics/ncgap/resize_up.png" title="reload frame size of map" onclick="resize();" /></td>
<td><input type="image" src="/graphics/ncgap/aoi_up.png" title="Use AOI" onclick="save_aoi();" /> </td>
<td><input type="image" src="/graphics/ncgap/pdf_up.png" title="export pdf" onclick="export_pdf();" /> </td>
<td><input type="image" src="/graphics/ncgap/data_dnld.png" title="data download as zip file" onclick="clip_zip()" /> </td>
<td><input type="image" src="/graphics/ncgap/help_up.png" title="Help" onclick="help();" /></td>
</tr>
</table>
</div>

<?php
//require('nc_config.php');
$error_html = 'none'; //set to default, changed if shapefile upload error

 //use this for permalink
if(isset($_GET['species'])){
	$link_species = $_GET['species'];
	$link_predicted = "predicted range";
	$mode = 'pan';
	$layer = 'elevation states';
	$type = 'predefined';
	$zoom = 1;
	//$win_w = 948;
	//$win_h = 524;
	$extent = '39084 205175 328284 277070';
   $ecosys_aoi = "1";
} else {

   $posix = $_POST['posix'];
   $posiy = $_POST['posiy'];
   //aoi name for saved aoi
   $aoi_name_saved = $_POST['aoi_name_saved'];

   //$extent = $_POST['extent'];
   $win_w = $_POST['win_w'];
   $win_h = $_POST['win_h'];

   $layer = $_POST['layers'];

   //ogc_fid for predefined aoi
   $owner_aoi = $_POST['owner'];
   $manage_aoi = $_POST['manage'];
   $basin_aoi = $_POST['wtshd'];
   $sub_basin_aoi = $_POST['subwtshd'];
   $muni_aoi = $_POST['muni'];
   $zone_aoi = $_POST['zone'];
   $island_aoi = $_POST['island'];
   $ecosys_aoi = $_POST['ecosys'];

   $extent = $_POST['extent'];
   $zoom_aoi = $_POST['zoom_aoi'];
   $zoom = $_POST['zoom'];
   $mode = $_POST['mode'];
   $sppcode = $_POST['sppcode'];
   $species_layer = $_POST['species_layer'];
   $species_layer_prev = $_POST['species_layer_prev'];
   $map_species = $_POST['map_species'];
   $richness_species = stripslashes($_POST['richness_species']);
   $type = $_POST['type'];
   //var_dump($_POST);

   //create click obj
   $click_x=$win_w/2;
   $click_y=$win_h/2;

}
//create custom exception to catch aoi upload errors
class aoi_upload_exception extends exception {}

try {
	//process uploaded file
	if(isset($_FILES['shp']['tmp_name'])){
		$tmp_shp = $_FILES['shp']['tmp_name'];
		$name_shp = strtolower($_FILES['shp']['name']);
		$tmp_shx = $_FILES['shx']['tmp_name'];
		$name_shx = strtolower($_FILES['shx']['name']);
		$tmp_prj = $_FILES['prj']['tmp_name'];
		$name_prj = strtolower(($_FILES['prj']['name']));

		if (!preg_match("/.*\.shp$/", $name_shp)) {
			throw new aoi_upload_exception("no file selected for .SHP or does not end in .shp");
		}
		if (!preg_match("/.*\.shx$/", $name_shx)) {
			throw new aoi_upload_exception("no file selected for .SHX or does not end in .shx");
		}
		if (!preg_match("/.*\.prj$/", $name_prj)) {
			throw new aoi_upload_exception("no file selected for .PRJ or does not end in .prj");
		}
		if (basename($name_shp, ".shp") != basename($name_shx, ".shx")) {
			throw new aoi_upload_exception(".SHP file has different base name from .SHX file");
		}
		if (basename($name_shp, ".shp") != basename($name_prj, ".prj")) {
			throw new aoi_upload_exception(".SHP file has different base name from .PRJ file");
		}
		$shapefile = $mspath."shapefile".rand(0,100000);
		$file_shp = $shapefile.".shp";
		$file_shx = $shapefile.".shx";
		$file_prj = $shapefile.".prj";
		if(!move_uploaded_file($tmp_shp, $file_shp) || !move_uploaded_file($tmp_shx, $file_shx) || !move_uploaded_file($tmp_prj, $file_prj)){
			throw new aoi_upload_exception("Server error, please try again");
		}
	}

}catch (aoi_upload_exception $e){
	$message = $e->getMessage();
	$error_html =  "<br /><br /><br /><br /><br />";
	$error_html .= "<h4>".$message."</h4>";
	$error_html .="<h4>Please click the red X button and create the AOI again.</h4>";
}

?>

<!--
<div id="loadingarea" style="display: none;">
</div>
-->

<div id="myMap" style="position:absolute; top:68px; left:0px;">
<img alt="map"  class="mapimage" src="" />
</div>

<div id="dragMap"   style="position:absolute; top:68px; left:0px;">
<img alt="map"  class="mapimage" src="" />
</div>

<div id="ctls" style="position:absolute; top:69px; left:0px; z-index: 999;" >

<div id="panup" style="position:absolute; left: 13px; top: 4px; width: 18px; height: 18px; z-index: 1004;">
<img src="/graphics/openlayers/north-mini.png" />
</div>

<div id="panleft" style="position:absolute; left: 4px; top: 22px; width: 18px; height: 18px;">
<img src="/graphics/openlayers/west-mini.png" />
</div>

<div id="panright" style="position:absolute; left: 22px; top: 22px; width: 18px; height: 18px;">
<img src="/graphics/openlayers/east-mini.png" />
</div>

<div id="pandown" style="position:absolute; left: 13px; top: 40px; width: 18px; height: 18px;">
<img src="/graphics/openlayers/south-mini.png" />
</div>

<div id="zoomin" style="position:absolute; left: 13px; top: 63px; width: 18px; height: 18px;">
<img src="/graphics/openlayers/zoom-plus-mini.png" />
</div>

<div id="zoommax" style="position:absolute; left: 13px; top: 81px; width: 18px; height: 18px;">
<img src="/graphics/openlayers/zoom-world-mini.png" />
</div>

<div id="zoomout" style="position:absolute; left: 13px; top: 99px; width: 18px; height: 18px;">
<img src="/graphics/openlayers/zoom-minus-mini.png" />
</div>

</div>



<!-- form fm2 used to generate aoi wide reports -->
<form action="../aoi_report3.php" name="fm2" method="post">
<input  type="hidden" name="aoi_name" id="aoiname_report" class="aoi_name" />
<input type="hidden" name="report" value="" />
<input type="hidden" name="sppcode" value="" />
<input type="hidden" name="species" value="" />
</form>

<!-- form fm3 used to open controls3.php -->
<form name='fm3' action="controls3.php" method="post" target="controls">
<input  type="hidden" name="aoi_name" id="aoi_name_fm3" class="aoi_name"/>
<input  type="hidden" name="type" value="<?php echo $type; ?>" />
<input type="hidden" name="manage"  value="<?php  echo $manage_aoi; ?>" />
<input type="hidden" name="owner"  value="<?php  echo $owner_aoi; ?>" />
<input type="hidden" name="basin"  value="<?php  echo $basin_aoi; ?>" />
<input type="hidden" name="sub_basin"  value="<?php  echo $sub_basin_aoi; ?>" />
</form>

<form name='fm4' method="post" target="w2" action="../curr_user_aois.php">
<input  type="hidden" name="aoi_name" id="aoi_name_fm4" class="aoi_name"/>
</form>

<form name="fm5" action="../pdf_report.php" target="_blank" method="post">
<input type="hidden" name="layers2" id="layers_pdf" value='<?php  echo $layer; ?>' />
<input type="hidden" name="extent" id="extent_pdf"  />
<input type="hidden" name="win_w"  value="<?php  echo $win_w; ?>" />
<input type="hidden" name="win_h"  value="<?php  echo $win_h; ?>" />
<input type="hidden" name="dpi"  />
<input type="hidden" name="desc"  />
<input  type="hidden" name="species_layer"  />
<input  type="hidden" name="sppcode"  />
<input  type="hidden" name="map_species" id="map_species_pdf" />
<input  type="hidden" name="aoi_name" class="aoi_name"  />
</form>

<form action="../data_download.php" method="post" name="fm6" target="_blank" >
<input type="hidden" name="aoi_name" class="aoi_name" />
</form>

<form action="" name="ajaxform">
<input type="hidden" id="username" value="<?php echo $user_name; ?>"  />
<input type="hidden" id="aoiname_ajax" class="aoi_name" />
<input type="hidden" id="aoinamesaved_ajax" value="<?php echo $aoi_name_saved; ?>" />
<input type="hidden" id="clkx_ajax"  value="<?php echo $click_x; ?>" />
<input type="hidden" id="clky_ajax" value="<?php echo $click_y; ?>" />
<input type="hidden" id="posix_ajax" value="<?php echo $posix; ?>" />
<input type="hidden" id="posiy_ajax" value="<?php echo $posiy; ?>" />
<input type="hidden" id="extent_ajax" value='<?php  echo $extent; ?>' />
<input type="hidden" id="zoom_ajax" value='1'/>
<input type="hidden" id="layers_ajax" value='<?php  echo $layer; ?>' />
<input type="hidden" id="winh_ajax" value='<?php  echo $win_h; ?>' />
<input type="hidden" id="winw_ajax" value='<?php  echo $win_w; ?>' />
<input type="hidden" id="type_ajax" value='<?php  echo $type; ?>' />
<input type="hidden" id="zoomaoi_ajax" value="0" />
<input type="hidden" id="shape_shp" value="<?php echo $file_shp; ?>" />
<input type="hidden" id="shape_error" value="<?php echo $error_html; ?>" />
<input type="hidden" id="pred_transp_ajax" value="50" />
<input type="hidden" id="range_transp_ajax" value="50" />


<input type="hidden" class="aoikeys" id="manage_ajax" value="<?php  echo $manage_aoi; ?>" />
<input type="hidden" class="aoikeys" id="owner_ajax" value="<?php  echo $owner_aoi; ?>" />
<input type="hidden" class="aoikeys" id="basin_ajax"  value="<?php  echo $basin_aoi; ?>" />
<input type="hidden" class="aoikeys" id="sub_basin_ajax" value="<?php  echo $sub_basin_aoi; ?>" />
<input type="hidden" class="aoikeys" id="muni_ajax" value="<?php  echo $muni_aoi; ?>" />
<input type="hidden" class="aoikeys" id="island_ajax" value="<?php  echo $island_aoi; ?>" />
<input type="hidden" class="aoikeys" id="zone_ajax" value="<?php  echo $zone_aoi; ?>" />
<input type="hidden" class="aoikeys" id="ecosys_ajax" value="<?php  echo $ecosys_aoi; ?>" />

<input  type="hidden" name="sppcode" id="sppcode_ajax" value="<?php echo $link_species; ?>" />
<input  type="hidden" name="species_layer" id="species_layer_ajax"  value="<?php echo $link_predicted; ?>" />
<input  type="hidden" name="species_layer_prev" id="species_layer_prev_ajax" />
<input  type="hidden" id="map_species_ajax" name="map_species" />
<input  type="hidden" id="richness_species_ajax" name="richness_species"  />
</form>

<script type="text/javascript">
var jg_box = new jsGraphics("dragMap");
</script>

</body>
</html>
