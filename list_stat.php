<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Listing Status</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css">
/* <![CDATA[ */
body {font-family: sans-serif;}
.hdr {font-weight: bold; font-size: 1.1em;}
td {width: 200px;}
h3 {text-align: center;}
.pif {display: none;}
/* ]]> */
</style>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
function set_view(){
   var taxclass = document.getElementById('taxclas').value;
   //alert(taxclass);
   if(taxclass != 'AVES'){
      document.getElementById('pif').style.display = 'none';
   }
}
/* ]]> */
</script>
</head>
<body onload="set_view();">
<?php

require("pr_config.php");
pg_connect($pg_connect);

$sppcode = $_POST['sppcode'];
$species = $_POST['species'];

$query = "select grank, usesa, sprot from pr_infospp where sppcode = '{$sppcode}'";

//echo $query;

$result = pg_query($query);
$row = pg_fetch_array($result);
//var_dump($row);

?>
<input type="hidden" id="taxclas" value="<?php echo $row['strtaxclas']; ?>" />
<h3><?php echo $species; ?></h3>
<table>
<tr><td class='hdr' colspan="2" class="hdr">Ranking Information</td></tr>
<tr>
<td>Federal</td>
<td>
<?php 
if(strlen($row['usesa']) == 0) {
   echo "---";
}else{
   echo $row['usesa']; 
} 
?>
</td>
</tr>
<tr>
<td>Puerto Rico</td>
<td>
<?php //echo $row['strsprot']; 
if(strlen($row['sprot']) == 0) {
   echo "---";
}else{
   echo $row['sprot']; 
}

?></td>
</tr>
<tr>
<td>Nserve Global</td>
<td>
<?php //echo $row['strgrank']; 
if(strlen($row['grank']) == 0) {
   echo "---";
}else{
   echo $row['grank']; 
}
?></td>
</tr>

</table>

<div id="pif">
<table>
<tr><td class="hdr" colspan="2">Partners-In-Flight Regions</td></tr>
<tr>
<td>So. Atl. Coastal Plain</td>
<td>
<?php //echo $row['intpif_03']; 
if(strlen($row['intpif_03']) == 0) {
   echo "---";
}else{
   echo $row['intpif_03']; 
}
?></td>
</tr>
<tr>
<td>Mid Atl. Piedmont</td>
<td>
<?php //echo $row['intpif_10']; 
if(strlen($row['intpif_10']) == 0) {
   echo "---";
}else{
   echo $row['intpif_10']; 
}
?></td>
</tr>
<tr>
<td>Southern Piedmont</td>
<td>
<?php //echo $row['intpif_11']; 
if(strlen($row['intpif_11']) == 0) {
   echo "---";
}else{
   echo $row['intpif_11']; 
}
?></td>
</tr>
<tr>
<td>Mid Atl Ridge and Valley</td>
<td>
<?php //echo $row['intpif_12']; 
if(strlen($row['intpif_12']) == 0) {
   echo "---";
}else{
   echo $row['intpif_12']; 
}
?></td>
</tr>
<tr>
<td>So. Blue Ridge</td>
<td>
<?php //echo $row['intpif_23']; 
if(strlen($row['intpif_23']) == 0) {
   echo "---";
}else{
   echo $row['intpif_23']; 
}
?></td>
</tr>
<tr>
<td>Mid Atl. Coastal Plain</td>
<td>
<?php //echo $row['intpif_44'];
if(strlen($row['intpif_44']) == 0) {
   echo "---";
}else{
   echo $row['intpif_44']; 
}
?></td>
</tr>
</table>
</div>

</body>
</html>
