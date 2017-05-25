<?php
function get_custom_aoi($aoi_name, $result_x, $result_y, $result_ext, $size_w, $size_h ){
	$prdbcon = pg_connect("host=localhost dbname=prgap user=postgres");

	//put results into arrays
	$click_x_vals = explode(",", $result_x);
	$click_y_vals = explode(",", $result_y);
	$mapext = explode(" ", $result_ext);

	//convert extent arrays to variables
	$minx = $mapext[0];
	$miny = $mapext[1];
	$maxx = $mapext[2];
	$maxy = $mapext[3];
	$extx = $maxx - $minx;
	$exty = $maxy - $miny;

	//calculate x values of map co-ords
	$i=0;
	foreach($click_x_vals as $click_x_val){
		$x[$i++] = (($click_x_val/$size_w)*$extx+$minx);
	}

	//calculate y values of map co-ords
	$i=0;
	foreach($click_y_vals as $click_y_val){
		$y[$i++] = ((($size_h - $click_y_val)/$size_h)*$exty+$miny);
	}

	//create query to make aoi
	$query_values = "";
	for($i=0; $i<count($x); $i++){
		$query_values = $query_values."$x[$i] $y[$i], ";
	}

	$query_values = $query_values."$x[0] $y[0]";
	//$query = "insert into aoi(wkb_geometry, name) values
	 //(GeometryFromText('MULTIPOLYGON((($query_values)))', 32161), '{$aoi_name}')";
	$query = "insert into aoi(wkb_geometry, name) values
	((select multi(intersection(GeometryFromText('MULTIPOLYGON((($query_values)))', 32161),wkb_geometry)) from pr_bnd where ogc_fid = 1), '{$aoi_name}')";
	//echo $query;
	pg_query($prdbcon, $query);

}

function get_predefined_aoi($aoi_name, $owner_aoi, $manage_aoi, $muni_aoi, $island_aoi, $zone_aoi, $wtshd_aoi, $subwtshd_aoi, $ecosys_aoi){
	$prdbcon = pg_connect("host=localhost dbname=prgap user=postgres");

	$key_gapown = explode(":", $owner_aoi);
	$key_gapman = explode(":", $manage_aoi);
	$key_muni = explode(":", $muni_aoi);
	$key_island = explode(":", $island_aoi);
	$key_zone = explode(":", $zone_aoi);
	$key_wtshd = explode(":", $wtshd_aoi);
	$key_subwtshd = explode(":", $subwtshd_aoi);
	
	if ($ecosys_aoi == 1) {
		$island_aoi = "7:3:8:6:5:2:4";
		$key_island = explode(":", $island_aoi);
	}

	$feature_count = 0;
	$query = "insert into aoi(name) values ('{$aoi_name}')";
	pg_query($prdbcon, $query);
	if(strlen($key_muni[0]) != 0){
		for ($i = 0; $i < count($key_muni); $i++){
			if ($feature_count == 0) {
				$query2 = "update aoi set wkb_geometry =
            (select multi(wkb_geometry) from pr_muni where ogc_fid = '{$key_muni[$i]}')
	         where name = '{$aoi_name}'";
				// echo $query2."\n";
				pg_query($prdbcon, $query2);
			}else {
				$query3 = "update aoi set wkb_geometry =
            (select multi(geomunion(aoi.wkb_geometry, pr_muni.wkb_geometry)) from aoi, pr_muni  
            where aoi.name = '{$aoi_name}' and pr_muni.ogc_fid = '{$key_muni[$i]}')
	         where aoi.name = '{$aoi_name}'";
				//echo $query3."\n";
				pg_query($prdbcon, $query3);
			}
			$feature_count++;
		}
	}
	if(strlen($key_wtshd[0]) != 0){
		for ($i = 0; $i < count($key_wtshd); $i++){
			if ($feature_count == 0) {
				$query2 = "update aoi set wkb_geometry =
            (select multi(wkb_geometry) from pr_wtshds where ogc_fid = '{$key_wtshd[$i]}')
	         where name = '{$aoi_name}'";
				// echo $query2."\n";
				pg_query($prdbcon, $query2);
			}else {
				$query3 = "update aoi set wkb_geometry =
            (select multi(geomunion(aoi.wkb_geometry, pr_wtshds.wkb_geometry)) from aoi, pr_wtshds  
            where aoi.name = '{$aoi_name}' and pr_wtshds.ogc_fid = '{$key_wtshd[$i]}')
	         where aoi.name = '{$aoi_name}'";
				//echo $query3."\n";
				pg_query($prdbcon, $query3);
			}
			$feature_count++;
		}
	}
	if(strlen($key_subwtshd[0]) != 0){
		for ($i = 0; $i < count($key_subwtshd); $i++){
			if ($feature_count == 0) {
				$query2 = "update aoi set wkb_geometry =
            (select multi(wkb_geometry) from pr_subwtshds where ogc_fid = '{$key_subwtshd[$i]}')
	         where name = '{$aoi_name}'";
				// echo $query2."\n";
				pg_query($prdbcon, $query2);
			}else {
				$query3 = "update aoi set wkb_geometry =
            (select multi(geomunion(aoi.wkb_geometry, pr_subwtshds.wkb_geometry)) from aoi, pr_subwtshds  
            where aoi.name = '{$aoi_name}' and pr_subwtshds.ogc_fid = '{$key_subwtshd[$i]}')
	         where aoi.name = '{$aoi_name}'";
				//echo $query3."\n";
				pg_query($prdbcon, $query3);
			}
			$feature_count++;
		}
	}
	if(strlen($key_island[0]) != 0){
		for ($i = 0; $i < count($key_island); $i++){
			if ($feature_count == 0) {
				$query2 = "update aoi set wkb_geometry =
            (select multi(wkb_geometry) from pr_coast where ogc_fid = '{$key_island[$i]}')
	         where name = '{$aoi_name}'";
				// echo $query2."\n";
				pg_query($prdbcon, $query2);
			}else {
				$query3 = "update aoi set wkb_geometry =
            (select multi(geomunion(aoi.wkb_geometry, pr_coast.wkb_geometry)) from aoi, pr_coast  
            where aoi.name = '{$aoi_name}' and pr_coast.ogc_fid = '{$key_island[$i]}')
	         where aoi.name = '{$aoi_name}'";
				//echo $query3."\n";
				pg_query($prdbcon, $query3);
			}
			$feature_count++;
		}
	}
	if(strlen($key_zone[0]) != 0){
		for ($i = 0; $i < count($key_zone); $i++){
			if ($feature_count == 0) {
				$query2 = "update aoi set wkb_geometry =
            (select multi(wkb_geometry) from pr_life_zones where ogc_fid = '{$key_zone[$i]}')
	         where name = '{$aoi_name}'";
				// echo $query2."\n";
				pg_query($prdbcon, $query2);
			}else {
				$query3 = "update aoi set wkb_geometry =
            (select multi(geomunion(aoi.wkb_geometry, pr_life_zones.wkb_geometry)) from aoi, pr_life_zones 
            where aoi.name = '{$aoi_name}' and pr_life_zones.ogc_fid = '{$key_zone[$i]}')
	         where aoi.name = '{$aoi_name}'";
				//echo $query3."\n";
				pg_query($prdbcon, $query3);
			}
			$feature_count++;
		}
	}
	if(strlen($key_gapown[0]) != 0){
		for ($i = 0; $i < count($key_gapown); $i++){
			if ($feature_count == 0) {
				$query2 = "update aoi set wkb_geometry =
            (select multi(wkb_geometry) from pr_owner where ogc_fid = '{$key_gapown[$i]}')
	         where name = '{$aoi_name}'";
				// echo $query2."\n";
				pg_query($prdbcon, $query2);
			}else {
				$query3 = "update aoi set wkb_geometry =
            (select multi(geomunion(aoi.wkb_geometry, pr_owner.wkb_geometry)) from aoi, pr_owner 
            where aoi.name = '{$aoi_name}' and pr_owner.ogc_fid = '{$key_gapown[$i]}')
	         where aoi.name = '{$aoi_name}'";
				//echo $query3."\n";
				pg_query($prdbcon, $query3);
			}
			$feature_count++;
		}
	}
	if(strlen($key_gapman[0]) != 0){
		for ($i = 0; $i < count($key_gapman); $i++){
			if ($feature_count == 0) {
				$query2 = "update aoi set wkb_geometry =
            (select multi(wkb_geometry) from pr_manage where ogc_fid = '{$key_gapman[$i]}')
	         where name = '{$aoi_name}'";
				// echo $query2."\n";
				pg_query($prdbcon, $query2);
			}else {
				$query3 = "update aoi set wkb_geometry =
            (select multi(geomunion(aoi.wkb_geometry, pr_manage.wkb_geometry)) from aoi, pr_manage 
            where aoi.name = '{$aoi_name}' and pr_manage.ogc_fid = '{$key_gapman[$i]}')
	         where aoi.name = '{$aoi_name}'";
				//echo $query3."\n";
				pg_query($prdbcon, $query3);
			}
			$feature_count++;
		}
	}



	return "<p>created aoi named ".$aoi_name."</p>";
}

function get_uploaded_aoi($aoi_name, $file_shp){
	$prdbcon = pg_connect("host=localhost dbname=prgap user=postgres");

	//clean temp table
	$query = "delete from aoi_upload where name is null";
	pg_query($prdbcon, $query);

	//upload file to temp table and give all rows aoi name
	$gdal_cmd = "/usr/local/bin/ogr2ogr -update -append  -f PostgreSQL  PG:'dbname=prgap user=postgres host=localhost'  {$file_shp} -t_srs 'EPSG:32161'  -nln aoi_upload -nlt MULTIPOLYGON";
	//echo $gdal_cmd;
	exec($gdal_cmd);
	$query2 = "update aoi_upload set name = '{$aoi_name}' where name is null";
	//echo $query2;
	pg_query($prdbcon, $query2);

	//create union of temp rows  into aoi table
	$feature_count = $row_count = 0;
	$query = "insert into aoi(name) values ('{$aoi_name}')";
	pg_query($prdbcon, $query);
	$query = "select ogc_fid from aoi_upload where name = '{$aoi_name}'";
	$result =  pg_query($prdbcon, $query);
	while($row = pg_fetch_array($result)){
		$key_upload[$row_count++] = $row[0];
	}
	//var_dump($key_upload);

	for ($i = 0; $i < count($key_upload); $i++){
		if ($feature_count == 0) {
			$query2 = "update aoi set wkb_geometry =
            (select multi(wkb_geometry) from aoi_upload where ogc_fid = '{$key_upload[$i]}')
	         where name = '{$aoi_name}'";
			//echo $query2."\n";
			pg_query($prdbcon, $query2);
		}else {
			$query3 = "update aoi set wkb_geometry =
            (select multi(geomunion(aoi.wkb_geometry, aoi_upload.wkb_geometry)) from aoi,  aoi_upload 
            where aoi.name = '{$aoi_name}' and aoi_upload.ogc_fid = '{$key_upload[$i]}')
	         where aoi.name = '{$aoi_name}'";
			//echo $query3."\n";
			pg_query($prdbcon, $query3);
		}
		$feature_count++;
	}
	//cut to ecosystem boundary
	$query = "update aoi set wkb_geometry = (select multi(intersection(aoi.wkb_geometry, se_bnd.wkb_geometry))
	    from se_bnd, aoi where se_bnd.ogc_fid = 2 and aoi.name = '{$aoi_name}') where aoi.name = '{$aoi_name}'";
	//pg_query($query);


	//cleanup temp table
	$query = "delete from aoi_upload where name = '{$aoi_name}'";
	pg_query($prdbcon, $query);


}

?>