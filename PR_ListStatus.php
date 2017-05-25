<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Listing Status</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="StyleSheet" href="styles/popups.css" type="text/css" />
<style type="text/css">
/* <![CDATA[ */
body {font-family: sans-serif;}
td {width: 200px;}
h3 {text-align: left;}
/* ]]> */
</style>

</head>
<body >
<?php

require("pr_config.php");
pg_connect($pg_connect);

$sppcode = $_POST['sppcode'];
$species = $_POST['species'];

$query = "select * from pr_infospp where sppcode = '{$sppcode}'";

//echo $query;

$result = pg_query($query);
$row = pg_fetch_array($result);
//var_dump($row);

?>
<h3>
<?php
echo  $row['primcomnameenglish']
?>
<br />
<?php
echo  $row['primcomnamespanish']
?>
<br /><i>
<?php
echo  $row['gname']
?>
</i></h3>
<hr />
<table>
<tr>
<td><a href="/listcodes/FederalStatusCodes.html" target="fedcodes" onclick="window.open('', 'fedcodes', 'menubar=no,height=200,width=520')"><b>Federal Status</b></a></td>
<td>
<?php 
if(strlen($row['usesa']) == 0) {
   echo "---";
}else{
   echo $row['usesa']; 
} 
?>
</td>
<tr>
<td><a href="/listcodes/PRStateStatusCodes.html" target="statecodes" onclick="window.open('', 'statecodes', 'menubar=no,height=200,width=520')"><b>PR Status</b></a></td>
<td>
<?php //echo $row['strsprot']; 
if(strlen($row['sprot']) == 0) {
   echo "---";
}else{
   echo $row['sprot']; 
}

?>
</td>
</tr>

<tr>
<td colspan="2"><a href="http://www.natureserve.org/explorer/ranking.htm" target="nserv" onclick="window.open('', 'nserv', 'menubar=no,scrollbars=yes,width=800')"><b>Nature Serve Rank</b></a></td>
</tr>
<tr>
<td>&nbsp;&nbsp;Global Rank</td>
<td>
<?php //echo $row['strgrank']; 
if(strlen($row['grank']) == 0) {
   echo "---";
}else{
   echo $row['grank']; 
}
?>
</td>
</tr>

</table>
</div>

</body>
</html>
