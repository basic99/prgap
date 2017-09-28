<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>GRASS Report</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="StyleSheet" href="styles/popups.css" type="text/css" />
<link rel="stylesheet" href="styles/custom-theme/jquery-ui-1.8.6.custom.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" ></script>
<script type="text/javascript" src="javascript/jquery-ui-1.8.6.custom.min.js" ></script>
<style type="text/css">
/* <![CDATA[ */
@media print {
  #btncont {display: none; }
}

.ui-widget {
  font-size: 11px;}
button {
  width: 100px;
  margin: 20px;}
/* ]]> */
</style>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */

$(function() {
  $("button").button();
  $("#prnrep").click(function(evt) {
         evt.preventDefault();
			window.print();
      });
  $("#sprdsht").click(function(evt) {
         evt.preventDefault();
			spreadsheet();
      });
  $("#cls").click(function(evt) {
         evt.preventDefault();
			window.close();
      });

	var aoiname = $('#aoiname').val();
	var report = $('#report').val();
	var species = $('#species').val();
	var species2 = $('#species2').val();
	var sppcode = $('#sppcode').val();

    var data = { aoiname: aoiname, report: report, species: species, species2: species2, sppcode: sppcode};
    console.log(data);

	$.ajax({
		type: "POST",
		url: "aoi_report_ajax.php",
		data: data,
		dataType: "text",
		success: function(data){ //alert(data);
            console.log(data);
			$('#somecontent').hide().html(data.rep).show("normal");
		}
	});

});

function spreadsheet(){
	var pretag = document.getElementsByTagName("pre");
	var content = pretag[0].innerHTML;
	$.ajax({
		type: "POST",
		url: "aoi_report_ss.php",
		data: { content: content },
		dataType: "json",
		success: function(data){
			document.forms[0].action = "/server_temp/" + data.ssreport;
			document.forms[0].submit();
		}
	});


}


/* ]]> */
</script>
</head>
<body>

<?php
$report = $_POST['report'];
$sppcode = $_POST['sppcode'];
$species = $_POST['species'];
$species2 = $_POST['species2'];
$aoi_name = $_POST['aoi_name'];

//var_dump($_POST);
?>

<div id="somecontent">

<h1>Generating report</h1>

<img  alt="loading icon" style=" margin: 25px; " src="/graphics/prgap/ajax-loader.gif" />
<br>

</div>

<div id="btncont">
<button id="prnrep" >Print report</button>
<button id="sprdsht">Spreadsheet</button>
<button id="cls">Close</button>
</div>

<form target="_blank" method="GET">
</form>

<form >
<input id="aoiname" type="hidden" name="aoi_name" value="<?php echo $aoi_name; ?>" />
<input type="hidden" id="report" value="<?php echo $report; ?>" />
<input type="hidden" id="sppcode" value="<?php echo $sppcode ?>" />
<input type="hidden" id="species" value="<?php echo $species ?>" />
<input type="hidden" id="species2" value="<?php echo $species2 ?>" />
<input type="hidden" id="content" name="content" />
</form>

</body>
</html>
