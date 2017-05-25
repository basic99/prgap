<?php
require("pr_config.php");
$report_name = "ncgap".rand(100000, 999999).".xls";

$fp = fopen("{$mspath}{$report_name}", "w");

$content = $_POST['content'];
$content_lns = explode("\n", $content);

//parse out text report to spreadsheet
foreach ($content_lns as $v1){
	$v2 = explode("|", $v1);
	if (preg_match("/#|^ *[0-9]+|TOTAL/", $v2[1])) {
		if ($pos1 = strpos($v2[2], ".")) {
			$v2[2] = substr($v2[2], 0, $pos1);
		}
		$v2[2] = html_entity_decode($v2[2]);
		foreach ($v2 as &$v3){
			while ($pos2 = strpos($v3, ",")) {
				$v3 = substr($v3, 0, $pos2).substr($v3, $pos2 + 1);
			}
		}
		if (!preg_match("/TOTAL/", $v2[1])) {
			array_shift($v2);
		}
		$out = implode("\t", $v2);
		fwrite($fp, $out."\n");
	}
}
fclose($fp);
echo json_encode(array("ssreport"=>$report_name));

?>