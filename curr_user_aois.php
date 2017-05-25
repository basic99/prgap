<?php 
session_start();
require("pr_config.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Saved AOIs</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" ></script>
<link rel="StyleSheet" href="styles/popups.css" type="text/css" />
<link rel="stylesheet" href="styles/custom-theme/jquery-ui-1.8.6.custom.css" />
<script type="text/javascript" src="javascript/jquery-ui-1.8.6.custom.min.js" ></script>
<style type="text/css">
/* <![CDATA[ */
#cont1, #cont2 {width: 345px;}
.cls {float: right; margin-top: 50px;}
.ui-widget {font-size: 11px;}
button {width: 100px;
margin: 10px;}
/* ]]> */
</style>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
$(function(){
   $("button").button();
   $("#aoisub").click(function(evt) {
		 evt.preventDefault();
		 aoi_submit();
   });
	$("#aoisave").click(function(evt) {
		 evt.preventDefault();
		 aoi_save();
   });
	$(".aoiedit").click(function(evt) {
		 evt.preventDefault();
		 aoi_edit();
   });
	$(".shpdnld").click(function(evt) {
		 evt.preventDefault();
		 shp_dnload();
   });
	$(".cls").click(function(evt) {
		 evt.preventDefault();
		 window.close();
   });
	if(opener.location.pathname.indexOf("map2.php") != -1){
		document.getElementById('cont1').style.display = 'none';
		document.getElementById('cont2').style.display = 'block';

	}else{
		document.getElementById('cont2').style.display = 'none';
		document.getElementById('cont1').style.display = 'block';
	}
});

function aoi_submit(){

	if (document.forms[0].aoi_name_saved){
		var rad_len = document.forms[0].aoi_name_saved.length;
		for(var i = 0; i < rad_len; i++){
			if(document.forms[0].aoi_name_saved[i].checked == true){
				opener.document.forms.fm1.aoi_name_saved.value = document.forms[0].aoi_name_saved[i].value;
			}
		}
	} else if (document.forms[0].aoi_name_saved_cb){
		opener.document.forms.fm1.aoi_name_saved.value = document.forms[0].aoi_name_saved_cb.value;
	}else{
		//alert('no AOI selected');
	}
	if (opener.document.forms.fm1.aoi_name_saved.value.length == 0){
		alert('no AOI selected');
	}else{
		opener.document.forms.fm1.type.value = 'saved_aoi';
		opener.document.forms.fm1.action = 'map2.php';
		opener.document.getElementById('zoom').value = '1';
		opener.document.getElementById('mode').value = "pan";
		opener.document.forms.fm1.submit();
	}
}


function set_state(){
	if(opener.location.pathname.indexOf("map2.php") != -1){
		document.getElementById('cont1').style.display = 'none';
		document.getElementById('cont2').style.display = 'block';
	}else{
		document.getElementById('cont2').style.display = 'none';
		document.getElementById('cont1').style.display = 'block';
	}
}
function aoi_save(){
	document.forms[0].type.value = "current";
	document.forms[0].submit();
}


function aoi_edit(){
	if (document.forms[0].aoi_name_saved){
		var rad_len = document.forms[0].aoi_name_saved.length;
		var aoisltd = false;
		for(var i = 0; i < rad_len; i++){
			if(document.forms[0].aoi_name_saved[i].checked == true){
				aoisltd = true;
			}
		}
	} else if (document.forms[0].aoi_name_saved_cb){
		if (document.forms[0].aoi_name_saved_cb.checked == true){
			aoisltd = true;
		}
	} else {
		alert('no AOI selected');
		return;
	}
	if(aoisltd){
		document.forms[0].type.value = "selected";
		//document.forms[0].action = "shp_dnload.php";
		document.forms[0].submit();
	} else {
		alert('no AOI selected');
	}
}

function shp_dnload(){
	if (document.forms[0].aoi_name_saved){
		var rad_len = document.forms[0].aoi_name_saved.length;
		var aoisltd = false;
		for(var i = 0; i < rad_len; i++){
			if(document.forms[0].aoi_name_saved[i].checked == true){
				aoisltd = true;
			}
		}
	} else if (document.forms[0].aoi_name_saved_cb){
		if (document.forms[0].aoi_name_saved_cb.checked == true){
			aoisltd = true;
		}
	} else {
		alert('no AOI selected');
		return;
	}
	if(aoisltd){
		var aoi_name_saved = $('(input[name=aoi_name_saved], input[name=aoi_name_saved_cb]):checked').val();
		//alert(aoi_name_saved);
		$.ajax({
			type: "POST",
			url: "shp_dnload.php",
			data: {  aoi_name_saved: aoi_name_saved },
			dataType: "json",
			success: function(data){
				if(data.success){
					var zipfile = "/server_temp/" + data.filename + ".zip";
					//alert(zipfile);
					document.forms[1].action = zipfile;
					document.forms[1].submit();
				}
			}
		});
	} else {
		alert('no AOI selected');
	}
}
/* ]]> */
</script>
</head>
<body onload="set_state()">

<?php
if(!isset($_SESSION['username']) || $_SESSION['username'] == "visitor"){
	echo "<h2>User not logged in.</h2>";
	echo "<h4>Please log in to use this feature.</h4>";
	die();
}
$user = trim($_SESSION['username']);
$aoi_name = $_POST['aoi_name'];
//echo $aoi_name;
pg_connect($pg_connect);
$query = "select description from aoi where name = '{$aoi_name}'";
$result = pg_query($query);
$row = pg_fetch_array($result);

?>

<form method="post" action="save_aoi.php" target="_self">
<input  type="hidden" name="aoi_name" value="<?php echo $aoi_name; ?>" />
<input  type="hidden" name="type"  />
<table>
<tr>
<td class="bld">Username:&nbsp;&nbsp;&nbsp; </td>
<td><?php echo $user; ?></td>
</tr>
<tr>
<td class="bld">Current AOI:&nbsp;&nbsp;&nbsp;</td>
<td id="aoi_desc"><?php  echo $row[0];?></td>
</tr>
<tr>
<td colspan="2" class="bld" style=" padding:20px;"><center>Saved AOIs</center></td>
</tr>
<?php
$query = "select distinct name, description from aoi where username = '{$user}'";
$result = pg_query($query);
if (pg_numrows($result) == 1){
	$row = pg_fetch_array($result);
	echo "<tr><td align='center'><input type='checkbox' checked='checked' name='aoi_name_saved_cb' value='"
	.trim($row['name'])."' /></td><td>"
	.htmlentities($row['description'])."</td></tr>";
}else{
	while($row = pg_fetch_array($result)){
		echo "<tr><td align='center'><input type='radio' name='aoi_name_saved' value='"
		.trim($row['name'])."' /></td><td>"
		.htmlentities($row['description'])."</td></tr>";
	}
}
?>
</table>
</form>
<br />

<form target="_self" method="GET">
</form>

<!-- this div is shown when opened from from map.php  -->
<div id="cont1">
<h4>Select an AOI and choose an action</h4>
<div>
<button id="aoisub">Use</button>
<button class="aoiedit">Edit</button>
<button class="shpdnld">Download</button>
</div>
<button class="cls">Close</button>
</div>


<!-- this div is shown when opened from from map2.php  -->
<div id="cont2">
<h4>Select an AOI and choose an action</h4>
<div>
<button id="aoisave">Save</button>
<button class="aoiedit">Edit</button>
<button class="shpdnld">Download</button>
</div>
<button class="cls">Close</button>
</div>

</body>
</html>
