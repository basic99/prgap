<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>zoomcoord_php</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="StyleSheet" href="styles/popups.css" type="text/css" />
<link rel="stylesheet" href="styles/custom-theme/jquery-ui-1.8.6.custom.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" ></script>
<script type="text/javascript" src="javascript/jquery-ui-1.8.6.custom.min.js" ></script>
<style type="text/css">
/* <![CDATA[ */
   .ui-widget {font-size: 11px;}
button {width: 100px;
margin: 20px;} 
/* ]]> */
</style>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
$(function() {
      $("button").button();
      $("#rst").click(function(evt) {
         evt.preventDefault();
			document.forms.fm1.reset();
      });
	$('#sub').click(function (event) {
		event.preventDefault();
		var x = $('#x').val();
		var y = $('#y').val();
		var proj = $('input[name=projection]:radio:checked').val();
		$.ajax({
			type: "POST",
			url: "zoomcoord_ajax.php",
			data: { projection: proj, user_x: x, user_y: y },
			dataType: "json",
			success: function(data){
				$('#planex').val(data.x);
				$('#planey').val(data.y);
				$('#fm2').submit();
				window.close();
			}
		});
	});
});
/* ]]> */
</script>
</head>
<body >

<?php

$win_w = $_POST['win_w'];
$win_h = $_POST['win_h'];
$layer = $_POST['layers'];
?>

<p id="somecontent">To zoom to a particular point on the map viewer enter an x,y coordinate for the State Plane
projection(meters) or longitude, latitude for geographic projection(decimal degrees).</p>

<form id="fm1">
<table>
<tr>
<td>x coordinate or longitude</td>
<td><input id="x" type="text" /></td>
</tr>
<tr>
<td>y coordinate or latitude</td>
<td><input id="y" type="text" /></td>
</tr>
<tr>
<tr>
<td>Projection:</td>
<td><input type="radio" name="projection" value="plane"  checked="checked"/> State Plane </td>
</tr>
<tr>
<td></td>
<td><input type="radio" name="projection" value="geograph" /> Geographic(DD) </td>

</table>
<button id="rst">Reset</button>
<button id="sub">Submit</button>
</form>

<form id="fm2" action="prgap/map.php" target="map" method="POST">
<input type="hidden" name="win_w" value="<?php echo  $win_w; ?>"/>
<input type="hidden" name="win_h" value="<?php echo  $win_h; ?>" />
<input type="hidden" name="layers" value="<?php echo  $layer; ?>" />
<input type="hidden" name="user_x" id="planex" />
<input type="hidden" name="user_y" id="planey" />
</form>

</body>
</html>