
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>shapefile upload</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="StyleSheet" href="styles/popups.css" type="text/css" />
<link rel="stylesheet" href="styles/custom-theme/jquery-ui-1.8.6.custom.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" ></script>
<script type="text/javascript" src="javascript/jquery-ui-1.8.6.custom.min.js" ></script>
<style type="text/css">
/* <![CDATA[ */
.ui-widget {font-size: 11px;}
button {width: 130px;
margin: 20px;}
/* ]]> */
</style>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
$(document).ready(function(evt) {
   $("button").button();
      $("#btn").click(function(evt) {
         evt.preventDefault();
         upload();
      });
   });
function upload(){
   document.forms.fm2.win_w.value = opener.parent.map.document.getElementById('win_w').value;
   document.forms.fm2.win_h.value = opener.parent.map.document.getElementById('win_h').value;
   document.forms.fm2.layers.value = opener.parent.map.document.getElementById('layers').value;
   document.forms.fm2.target = opener.parent.map.name;
   document.forms.fm2.submit();
   window.close();
}
/* ]]> */
</script>
</head>
<body>
<p>Select *.shp, *.shx, and *.prj files to upload shapfile for your AOI. Program will reproject into proper co-ordinates.</p>
<form id="fm2" enctype="multipart/form-data" action="prgap/map2.php" method="post" >
<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
<input type="hidden" name="type" value="uploaded" />
<input type="hidden" name="win_w"  />
<input type="hidden" name="win_h"  />
<input  type="hidden" name="layers"  />
<input  type="hidden" name="mode" value="pan" />
<input id="zoom" type="hidden" name="zoom" value="1" />
<div>
select SHP file: <input name="shp" type="file" size="50"/><br />
select SHX file: <input name="shx" type="file" size="50"/><br />
select PRJ file: <input name="prj" type="file" size="50"/>
</div>
<button id="btn">Upload shapefile</button>
</form>

</body>
</html>

