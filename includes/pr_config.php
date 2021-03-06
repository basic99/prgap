<?php
//copy file to pr_config.php and make changes as necessary

//location of GRASS raster data for webserver
$grass_raster = "/pub/grass/puerto_rico/webserv/cellhd/";

//location of GRASS raster data for permanent
$grass_raster_perm = "/pub/grass/puerto_rico/PERMANENT/cellhd/";

$GISBASE = "/usr/local/grass-6.4.6";

// copy .grassrc6 from /home/webserv
$GISRC = "/var/www/html/prgap/grassrc";

$PATH = "/usr/local/grass-6.4.6/bin:/usr/local/grass-6.4.6/scripts:/usr/local/bin:/usr/bin:/bin";

//location of base directory, ie where map file is located
$base_dir = "/var/www/html/prgap";

$mspath = "/pub/server_temp/";

$pg_connect = "host=localhost dbname=prgap user=postgres";

$pdo_dsn = "pgsql:dbname=prgap;host=127.0.0.1";

ini_set("log_errors", 1);
?>