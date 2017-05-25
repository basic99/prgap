<?php
/**
 * Basically plain html form.
 * 
 * @package ncgap
 */

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>create PDF</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="StyleSheet" href="styles/popups.css" type="text/css" />
<link rel="stylesheet" href="styles/custom-theme/jquery-ui-1.8.6.custom.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" ></script>
<script type="text/javascript" src="javascript/jquery-ui-1.8.6.custom.min.js" ></script>
<style type="text/css">
/* <![CDATA[ */

#btndiv {font-size: 11px;}
button {margin: 10px;
width: 80px;}
/* ]]> */
</style>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
$(document).ready(function() {
	$("button").button();
	$("#rst").click(function(evt) {
		evt.preventDefault();
		document.forms[0].reset();
		});
	$("#sbmt").click(function(evt) {
		evt.preventDefault();
		get_pdf();
		});
});

function get_pdf(){
	if(document.forms[0].dpi[0].checked){
		opener.document.forms.fm5.dpi.value = 72;
	} else {
		opener.document.forms.fm5.dpi.value = 300;
	}
	opener.document.forms.fm5.desc.value = document.forms[0].desc.value;
	opener.document.forms.fm5.submit();
	window.close();
}
/*
function get_pdf(){
	if(document.forms[0].dpi[0].checked){
		opener.document.forms.fm5.dpi.value = 72;
	} else {
		opener.document.forms.fm5.dpi.value = 300;
	}
	opener.document.forms.fm5.desc.value = document.forms[0].desc.value;
	opener.document.forms.fm5.submit();
	window.close();
}*/
/* ]]> */
</script>
</head>
<body>


<form action="pdf_report.php" target="_self" method="post">
<h3>Enter a description</h3>
<input type="text" size="60" maxlength="50" name="desc"/>
<h4>Select the desired resolution</h4>
<input id="dpi72" type="radio" name="dpi" value="72" checked="checked" /><label for="dpi72">low (72 dpi)</label>
<input id="dpi300" type="radio" name="dpi" value="300" /><label for="dpi300">high (300 dpi)</label>

<div id="btndiv">
<button id="rst">Reset</button>
<button id="sbmt">Submit</button>
</div>
</form>

</body>
</html>
