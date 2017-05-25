<?php
require("pr_config.php");
pg_connect($pg_connect);

$aoi_name_saved = $_POST['aoi_name_saved'];

$query = "select name, username, description from aoi where name = '{$aoi_name_saved}'";
$result = pg_query($query);
$row = pg_fetch_array($result);
$desc_arr = str_split($row['description']);
$file_name = "";
foreach ($desc_arr as $k){
	if (ctype_space($k)) {
		$k = '_';
	}elseif (!ctype_alnum($k)){
		$k = '';
	}
	$file_name = $file_name.$k;
}
$file_name_ln = 15;
$file_name = substr($file_name, 0, $file_name_ln);
while(preg_match("/.*_$/", $file_name)){
	$file_name = substr($file_name, 0, --$file_name_ln);
}

$gdal_cmd = "/usr/local/bin/ogr2ogr -f 'ESRI Shapefile' -nln {$file_name} -where \"name='{$aoi_name_saved}'\"  {$mspath}{$file_name} PG:'dbname=prgap user=postgres host=localhost' aoi &>/dev/null";
exec($gdal_cmd);
$zip_cmd = "zip -r -j {$mspath}{$file_name} {$mspath}{$file_name} &>/dev/null";
exec($zip_cmd);

echo json_encode(array("success"=>true, "filename"=>$file_name));die();
?>