<?php 
/**
 * Submitted from curr_user_aois.php, updates table aoi.
 * 
 * @package swgap
 */
session_start();
require("pr_config.php");
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>User page</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="StyleSheet" href="styles/popups.css" type="text/css" />
<link rel="stylesheet" href="styles/custom-theme/jquery-ui-1.8.6.custom.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" ></script>
<script type="text/javascript" src="javascript/jquery-ui-1.8.6.custom.min.js" ></script>
<style type="text/css">
/* <![CDATA[ */
.bld {font-weight: bold;}
.ui-widget {font-size: 11px;}
button {width: 100px;
margin: 10px;}
/* ]]> */
</style>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
$(document).ready(function() {
	$("button").button();
	$("#sv").click(function(evt) {
		evt.preventDefault();
		save_aoi();
		});
	$("#dl").click(function(evt) {
		evt.preventDefault();
		delete_aoi();
		});
	$("#can").click(function(evt) {
		evt.preventDefault();
		go_back();
		});
});
function ajax_req(){
	var aoiname = $("#aoiname").val();
	var del = $("#del").val();
	var desc = $("#desc").val();
	//alert(aoiname + del + desc);
	$.ajax({
		type: "POST",
		url: "save_aoi_ajax.php",
		data: { aoiname: aoiname,  del: del, desc: desc },
		dataType: "json",
		success: function(data){
			if(data.success){
				if(opener.document.forms.fm4){
					opener.document.forms.fm4.submit();
				} else {
					$(window).attr("location", "curr_user_aois.php");
				}
			}
		}
	});
}

function save_aoi(){
	if(document.forms[0].desc.value.length == 0){
		alert('must enter a description')
	} else{
		document.forms[0].del.value = '';
		ajax_req();
	}

}

function delete_aoi(){
	document.forms[0].del.value = 'delete';
	ajax_req();
}
/*
function go_back(){
	opener.document.forms.fm4.submit();
}*/

function go_back(){
	if(opener.document.forms.fm4){
		opener.document.forms.fm4.submit();
	} else {
		$(window).attr("location", "curr_user_aois.php");
	}
}
/* ]]> */
</script>
</head>
<body >

<?php

pg_connect($pg_connect);

$user = $_SESSION['username'];

$aoi_name = $_POST['aoi_name'];
$type = $_POST['type'];
$aoi_name_saved = $_POST['aoi_name_saved'];

if (isset($_POST['aoi_name_saved_cb'])){
	$aoi_name_saved = $_POST['aoi_name_saved_cb'];
}
if ($type == 'selected') {
	$aoi_name = $aoi_name_saved;
}

$query = "select description from aoi where name = '{$aoi_name}'";
$result = pg_query($query);
$row = pg_fetch_array($result);

?>
<form method="post" action="save_aoi.php" target="_self">
<input  type="hidden" name="aoi_name" id="aoiname" value="<?php echo $aoi_name; ?>" />
<input  type="hidden" name="del" id="del" />
<div>
AOI name:<input type="text" size="25" maxlength="50" name="desc" id="desc" value="<?php echo $row[0]; ?>"/>
</div>
<button id="sv">Save</button>
<button id="dl">Delete</button>
<button id="can">Cancel</button>

</form>


</body>
</html>
