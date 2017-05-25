<?php
$species = $_GET['species'];

//$_SESSION["range_linkedspecies"] = new se_range_class('linkedspecies');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- index.html -->
<head>
<title>Puerto Rico Online GAP Data Explorer Tool</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script language="javascript" type="text/javascript">
/* <![CDATA[ */


/* ]]> */
</script>
</head>

<frameset rows="*,200,40">

<frameset cols="325,*">
<frame name="controls" src="" noresize="noresize" scrolling="no" />
<frame name="map" src="map2.php?species=<?php echo $species; ?>" noresize="noresize" scrolling="no" />
</frameset>

<frameset cols="325,*,150">
<frame name="data"  noresize="noresize" src="dummy.html" scrolling="no" />
<frame name="functions"  noresize="noresize" src="single.php?species=<?php echo $species; ?>"  frameborder="0" scrolling="no" />
<frame name="refmap" src="refmap.php" noresize="noresize" scrolling="no" />
</frameset>

<frame name="logos" noresize="noresize" src="logos.html" scrolling="no" />

</frameset>

</html>