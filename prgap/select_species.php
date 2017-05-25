<?php 
require('pr_range_class.php');
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>select species</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="../styles/custom-theme/jquery-ui-1.8.6.custom.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" ></script>
<script type="text/javascript" src="../javascript/jquery-ui-1.8.6.custom.min.js" ></script>
<style type="text/css">
/* <![CDATA[ */
/*
#btns {position:relative; padding-top: 5;}
#select {width: 100%;}
img { margin: 0px; padding: 0px;}
body { margin: 0px; padding-left: 5px;}*/
#btns {/*position:relative; padding-top: 5;*/
		  position: absolute;
		  bottom: 16px;
		  height: 25px;
		  margin-left: 15px;
		  font-size: 10px;}
#select {
		  width: 100%;}
img {
		  margin: 0px;
		  padding: 0px;}
body {
		  margin: 0px;
		  padding-left: 5px;}
#cont2 {
		  font-size: 11px;
}
#cont2 p {font-size: 16px;}
#cont2 button {margin: 6px;}
select {font-size: 12px;}
#msg {color: red;
		  font-size: 16px;}
</style>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */

$(document).ready(function(){
	// Your code here
	  $("button").button();
	$("#cont2").hide();
	$("#srch").click(function(event){
		event.preventDefault();
		$("#cont1").hide();
		$("#cont2").show();
	});
	$("#slct").click(function(event){
		event.preventDefault();
		var action = parent.functions.location.pathname;
      document.forms.fm2.action = action;
      document.forms.fm2.submit();
	});
	$("#svlist").click(function(event){
		event.preventDefault();
		if(navigator.appName.indexOf('Microsoft') != -1){
		  document.forms.f3.target = "_blank";
      }
      document.forms.f3.submit();
	});
	$("#rst").click(function(event){
		event.preventDefault();
		$("#search").val('');
      $("#fm1").submit();
	});
	$("#srchcncl").click(function(event){
		event.preventDefault();
		document.getElementById('search').value='';
		document.forms.fm1.submit();
	});
	$("#srchsbmt").click(function(event){
		event.preventDefault();
		document.forms.search_form.submit();
	});
	$('input:radio').click(function(event){
		$("#fm1").submit();
	});
	var lang = $("#lang").val();
	if(lang == "primcomnamespanish"){
		$('input:radio:eq(0)').attr("checked","checked");
	}
	if(lang == "primcomnameenglish"){
		//alert(lang);
		$('input:radio:eq(1)').attr("checked","checked");
	}
	if(lang == "gname"){
		//alert(lang);
		$('input:radio:eq(2)').attr("checked","checked");
	}

	if($('#select option').size() == 0){
		$("#cont1").hide();
		$("#cont2").show();
		$('#msg').html("No search results returned")
	}

});

function form_submit(){
	//alert('testing');
	var action = parent.functions.location.pathname;
	document.forms.fm2.action = action;
	document.forms.fm2.submit();
}
function form_reset(){
	$("#search").val('');
	$("#fm1").submit();
}
function get_list(){
	if(navigator.appName.indexOf('Microsoft') != -1){
		document.forms.f3.target = "_blank";
	}
	document.forms.f3.submit();
}
/* ]]> */
</script>
</head>
<body>
<?php

require('pr_config.php');
pg_connect($pg_connect);

$avian = $_POST['avian'];
$mammal = $_POST['mammal'];
$reptile = $_POST['reptile'];
$amphibian = $_POST['amphibian'];
$language = $_POST['language'];
$aoi_name = $_POST['aoi_name'];
$search = $_POST['search'];

//var_dump($_POST);

if(!isset($_POST['language'])){
	$language="gname";
}
$rclass = $_SESSION["range".$aoi_name];

?>
<div id="cont1">
<form method="post" action="select_species.php" target="_self" id="fm1">
<input type="hidden" name="avian" value="<?php echo $avian; ?>" />
<input type="hidden" name="mammal" value="<?php echo $mammal; ?>" />
<input type="hidden" name="reptile" value="<?php echo $reptile; ?>" />
<input type="hidden" name="amphibian" value="<?php echo $amphibian; ?>" />
<input type="hidden" name="aoi_name" value="<?php echo $aoi_name; ?>" />
<input type="hidden" name="language" id="lang" value="<?php echo $language; ?>" />
<input type="hidden" name="search" id="search" value="<?php echo $search; ?>" />
<table>
<tr>

<td><input type="radio" name="language"  value="primcomnamespanish"/></td>
<td> Spanish</td>

<td><input type="radio" name="language"  value="primcomnameenglish"/></td>
<td> English</td>

<td><input type="radio" name="language"  value="gname"/></td>
<td> Scientific</td>
</tr>

</table>
</form>

<form action="single.php" method="post" name="fm2" target="functions">
<input type="hidden" name="prev_sel" value="" />

<div style=" position: absolute; top: 25px; width: 95%; display: block;">
<select size="7" name="species[]" id="select" multiple="multiple">
<?php  
$rclass->get_species_search($avian, $mammal, $reptile, $amphibian, $language, $search);
$report_name = $rclass->get_species_ss($avian, $mammal, $reptile, $amphibian, $search);
?>
</select>
</div>
<!--
<div style="position: absolute; bottom: 3px; height: 25px;  margin-left: 15px;">
<img src="/graphics/prgap/select_65x25_up.png" id="btn11" alt="button" onclick="form_submit();"
onmousedown="document.getElementById('btn11').src = '/graphics/prgap/select_65x25_dn.png';"
onmouseup="document.getElementById('btn11').src = '/graphics/prgap/select_65x25_up.png';"/>

<img src="/graphics/prgap/save_65x25_up.png" id="btn20" alt="button" onclick="get_list();"
onmousedown="document.getElementById('btn20').src = '/graphics/prgap/save_65x25_dn.png';"
onmouseup="document.getElementById('btn20').src = '/graphics/prgap/save_65x25_up.png';"/>

<img src="/graphics/prgap/search_65x25_up.png" id="btn31"  alt="button" 
onmousedown="document.getElementById('btn31').src = '/graphics/prgap/search_65x25_dn.png';"
onmouseup="document.getElementById('btn31').src = '/graphics/prgap/search_65x25_up.png';"/>

<img src="/graphics/prgap/reset_65x25_up.png" id="btn32"  alt="button" onclick="form_reset();"
onmousedown="document.getElementById('btn32').src = '/graphics/prgap/reset_65x25_dn.png';"
onmouseup="document.getElementById('btn32').src = '/graphics/prgap/reset_65x25_up.png';"/>
</div>-->
<div id="btns" >

<button id="slct">Select</button>
<button id="svlist">Save list</button>
<button id="srch">Search</button>
<button id="rst">Reset</button>
	
</div>
</form>

</div>

<div id="cont2">
<form method="post" action="select_species.php" target="_self" name="search_form">
<input type="hidden" name="avian" value="<?php echo $avian; ?>" />
<input type="hidden" name="mammal" value="<?php echo $mammal; ?>" />
<input type="hidden" name="reptile" value="<?php echo $reptile; ?>" />
<input type="hidden" name="amphibian" value="<?php echo $amphibian; ?>" />
<input type="hidden" name="aoi_name" value="<?php echo $aoi_name; ?>" />
<input type="hidden" name="language" value="<?php echo $language; ?>" />
<!--
<table>
<tr>
<td colspan="2">Enter full or partial common name or scientific name:</td>
</tr>
<tr>
<td colspan="2"><input type="text"  name="search" size="30" /></td>
</tr>
<tr>

<td><img src="/graphics/prgap/cancel_65x25_up.png" id="btn34"  alt="button" 
onclick="document.getElementById('search').value='';document.forms.fm1.submit();"
onmousedown="document.getElementById('btn34').src = '/graphics/prgap/cancel_65x25_dn.png';"
onmouseup="document.getElementById('btn34').src = '/graphics/prgap/cancel_65x25_up.png';"/>
</td>

<td><img src="/graphics/prgap/submit_65x25_up.png" id="btn35"  alt="button" onclick="document.forms.search_form.submit();"
onmousedown="document.getElementById('btn35').src = '/graphics/prgap/submit_65x25_dn.png';"
onmouseup="document.getElementById('btn35').src = '/graphics/prgap/submit_65x25_up.png';"/>
</td>

</tr>
</table>
</form>
<div id="msg"></div>-->
<div>
<p>Enter full or partial common name or scientific name:</p>
<input type="text"  name="search" size="30" />
</div>

<button id="srchcncl">Cancel</button>
<button id="srchsbmt">Submit</button>

</form>
<div id="msg"></div>
</div>

<form action="<?php echo '/server_temp/'.$report_name; ?>" target="_self" method="post" name="f3">

</form>


<form action="../data_download.php" method="post" name="fm4" target="_blank" >
<input type="hidden" name="avian" value="<?php echo $avian; ?>" />
<input type="hidden" name="mammal" value="<?php echo $mammal; ?>" />
<input type="hidden" name="reptile" value="<?php echo $reptile; ?>" />
<input type="hidden" name="amphibian" value="<?php echo $amphibian; ?>" />
<input type="hidden" name="aoi_name" value="<?php echo $aoi_name; ?>" />
<input type="hidden" name="search"  value="<?php echo $search; ?>" />
<input type="hidden" name="spp"  />
<input type="hidden" name="richness_map"  />
<input type="hidden" name="richness_species"  />
</form>

</body>
</html>
