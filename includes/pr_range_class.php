<?php
$prdbcon = pg_connect("host=localhost dbname=prgap user=postgres");

class pr_range_class
{
	private $range;
	private $sppcodes;
	public $num_species;
	private $tot_class;
	private $query;

	function __construct($aoi_name)
	{
		//import connection from top of file and use named connections in queries
		global $prdbcon;

		$query = "select aoi_data from aoi where name = '{$aoi_name}'";
		$result = pg_query($prdbcon, $query);
		$row = pg_fetch_array($result);
		$aoi_predefined = unserialize($row['aoi_data']);

		$key_gapown = explode(":", $aoi_predefined['owner_aoi']);
		$key_gapman = explode(":", $aoi_predefined['manage_aoi']);
		$key_muni = explode(":", $aoi_predefined['muni_aoi']);
		$key_island = explode(":", $aoi_predefined['island_aoi']);
		$key_zone = explode(":", $aoi_predefined['zone_aoi']);
		$key_wtshd = explode(":", $aoi_predefined['wtshd']);
		$key_subwtshd = explode(":", $aoi_predefined['subwtshd']);

		if (strlen($key_gapown[0] == 0)) unset($key_gapown);
		if (strlen($key_gapman[0] == 0)) unset($key_gapman);
		if (strlen($key_muni[0] == 0)) unset($key_muni);
		if (strlen($key_island[0] == 0)) unset($key_island);
		if (strlen($key_zone[0] == 0)) unset($key_zone);
		if (strlen($key_wtshd[0] == 0)) unset($key_wtshd);
		if (strlen($key_subwtshd[0] == 0)) unset($key_subwtshd);

		//calcuate ranges from tables for predefined aoi
		if (isset($key_gapown) || isset($key_gapman) || isset($key_muni) || isset($key_island) || isset($key_zone) || isset($key_wtshd) || isset($key_subwtshd)){
			//if (false) {
			$j=0;
			for ($i=0; $i<sizeof($key_gapown); $i++){
				$query = "select pr_species_hex_ogc_fid from range_from_aoi where pr_owner_ogc_fid  = {$key_gapown[$i]}";
				$results = pg_query($prdbcon, $query);
				while($row = pg_fetch_array($results)){
					$range[$j++] = $row['pr_species_hex_ogc_fid'];
				}
			}
			for ($i=0; $i<sizeof($key_gapman); $i++){
				$query = "select pr_species_hex_ogc_fid from range_from_aoi where pr_manage_ogc_fid  = {$key_gapman[$i]}";
				$results = pg_query($prdbcon, $query);
				while($row = pg_fetch_array($results)){
					$range[$j++] = $row['pr_species_hex_ogc_fid'];
				}
			}
			for ($i=0; $i<sizeof($key_muni); $i++){
				$query = "select pr_species_hex_ogc_fid from range_from_aoi where pr_muni_ogc_fid  = {$key_muni[$i]}";
				$results = pg_query($prdbcon, $query);
				while($row = pg_fetch_array($results)){
					$range[$j++] = $row['pr_species_hex_ogc_fid'];
				}
			}
			for ($i=0; $i<sizeof($key_island); $i++){
				$query = "select pr_species_hex_ogc_fid from range_from_aoi where pr_coast_ogc_fid  = {$key_island[$i]}";
				$results = pg_query($prdbcon, $query);
				while($row = pg_fetch_array($results)){
					$range[$j++] = $row['pr_species_hex_ogc_fid'];
				}
			}
			for ($i=0; $i<sizeof($key_zone); $i++){
				$query = "select pr_species_hex_ogc_fid from range_from_aoi where pr_life_zones_ogc_fid  = {$key_zone[$i]}";
				$results = pg_query($prdbcon, $query);
				while($row = pg_fetch_array($results)){
					$range[$j++] = $row['pr_species_hex_ogc_fid'];
				}
			}

			for ($i=0; $i<sizeof($key_wtshd); $i++){
				$query = "select pr_species_hex_ogc_fid from range_from_aoi where pr_wtshds_ogc_fid  = {$key_wtshd[$i]}";
				$results = pg_query($prdbcon, $query);
				while($row = pg_fetch_array($results)){
					$range[$j++] = $row['pr_species_hex_ogc_fid'];
				}
			}
			for ($i=0; $i<sizeof($key_subwtshd); $i++){
				$query = "select pr_species_hex_ogc_fid from range_from_aoi where pr_subwtshds_ogc_fid  = {$key_subwtshd[$i]}";
				$results = pg_query($prdbcon, $query);
				while($row = pg_fetch_array($results)){
					$range[$j++] = $row['pr_species_hex_ogc_fid'];
				}
			}
			//else calculate from geometry for custom aoi
		}else{
			$query2 = "select ogc_fid from pr_species_hex where intersects((select wkb_geometry from aoi where name = '{$aoi_name}'), pr_species_hex.wkb_geometry)";
			$result=pg_query($prdbcon, $query2);
			$i=0;
			while(($row = pg_fetch_array($result)) !== FALSE){
				$range[$i++] = $row[0];
			}
		}

		//get strsppcodes and store as key in associative array with key as strsppcode and value 0
		//loop through strsppcodes and ranges to find species in aoi and store in array
		$query = "select sppcode from pr_infospp";
		$result = pg_query($prdbcon, $query);
		while(($row = pg_fetch_array($result)) !== FALSE){
			$sppcodes[$row[0]] = 0;
		}
		$keys = array_keys($sppcodes);
		for($i=0; $i<count($keys); $i++){
			for($j=0; $j<sizeof($range); $j++){
				$query = "select {$keys[$i]} from pr_species_hex where ogc_fid = {$range[$j]}";
				$result = pg_query($prdbcon, $query);
				$row = pg_fetch_array($result);
				if(($row[0]!=0) && ($row[0]!=5) && ($row[0]!=6)){
					$sppcodes[$keys[$i]]=1;
					break;
				}
			}
		}

		//loop through strsppcodes to calculate numbers of species for protection status in range
		$all_species =$fed_species =$state_species =$gap_species =$ns_global_species=
		$ns_state_species =$pif_species = 0;
		$query = "select sppcode, usesa2, sprot2, grank2 from pr_infospp";
		$result = pg_query($prdbcon, $query);
		while($row = pg_fetch_array($result)){
			if ($sppcodes[$row['sppcode']] == 1){
				$all_species++;
				if ($row['usesa2'] !== NULL) $fed_species++;
				if ($row['sprot2'] !== NULL) $state_species++;
				//if ($row['gap_p_all2'] !== NULL) $gap_species++;
				if ($row['grank2'] !== NULL) $ns_global_species++;
				//if ($row['strsrank2'] !== NULL) $ns_state_species++;
				//if ($row['intpif'] != 0) $pif_species++;
			}
		}

		//assign class variable from preceeding calculations
		$this->range = $range;
		$this->strsppcodes = $sppcodes;
		$this->num_species['fed_species'] = $fed_species;
		$this->num_species['state_species'] = $state_species;
		$this->num_species['ns_global_species'] = $ns_global_species;
		$this->num_species['all_species'] = $all_species;
		//var_dump($this->num_species);

	}
	////////////////////////////////////////////////////////////////////////////////
	////////////end constructor
	//////////////////////////////////////////////////////////////////////////////////

	//given selections of controls3.php calculate numbers of each class for that selection
	//by constructing query, save query as class variable to get list of species
	//returns associative array
	function num_class($species, $sel, $fed, $state, $nsglobal){

		//import connection from top of file and use named connections in queries
		global $prdbcon;

		$query = "select  taxclass, sppcode, primcomnameenglish, gname, itis, primcomnamespanish from pr_infospp";
		$i=0;

		//modify query for and selections
		if ( $species ==='prot' && $sel === 'and'){

			//case fed selected
			if($fed == 'on'){
				$query = $query." where usesa2 is not null";
				$i++;
			}

			//case state selected
			if($state == 'on'){
				if($i==0) {
					$query = $query." where sprot2 is not null";
					$i++;
				}else{
					$query = $query." and sprot2 is not null";
				}
			}
			//case nsglobal selected
			if($nsglobal == 'on'){
				if($i==0) {
					$query = $query." where (grank2 is not null";
					$i++;
				}else{
					$query = $query." and grank2 is not null";
				}
			}
		}

		//modify query for or selections
		if ( $species ==='prot' && $sel === 'or'){
			//case fed selected
			if($fed == 'on'){
				$query = $query." where (usesa2 is not null";
				$i++;
			}

			//case state selected
			if($state == 'on'){
				if($i==0) {
					$query = $query." where (sprot2 is not null";
					$i++;
				}else{
					$query = $query." or sprot2 is not null";
				}
			}


			//case nsglobal selected
			if($nsglobal == 'on'){
				if($i==0) {
					$query = $query." where (grank2 is not null";
					$i++;
				}else{
					$query = $query." or grank2 is not null";
				}
			}
			if($i>0)$query .=")";
		}

		$avian = $mammal = $rept = $amph =  0;

		//get numbers for avian, mammal, rept and amph for all species
		$result = pg_query($prdbcon, $query) or die('unable to execute database query');
		while ($row = pg_fetch_array($result)){
			if ($this->strsppcodes[$row['sppcode']] == 1){
				if($row['taxclass'] == 'Amphibians') $amph++;
				if($row['taxclass'] == 'Aves') $avian++;
				if($row['taxclass'] == 'Mammalia') $mammal++;
				if($row['taxclass'] == 'Reptilia') $rept++;

			}
		}
		// assign to class variable and return values as associative array
		$this->query = $query;
		$this->tot_class['amph'] = $amph;
		$this->tot_class['avian'] = $avian;
		$this->tot_class['mammal'] = $mammal;
		$this->tot_class['rept'] = $rept;
		//var_dump($this->tot_class);
		return $this->tot_class;
	}
	/////////////////////////////////////////////////////////////////////////////////
	// end function num_class
	////////////////////////////////////////////////////////////////////////////////

	//get list of selected species for select box
	function get_species($avian, $mammal, $reptile, $amphibian, $language){

		//import connection from top of file and use named connections in queries
		global $prdbcon;

		$query = $this->query;
		$sppcodes = $this->strsppcodes;
		$result = pg_query($prdbcon, $query);
		while (($row = pg_fetch_array($result))!==FALSE){
			if ($sppcodes[$row['sppcode']] == 1){
				switch ($language){
					case "primcomnamespanish":
						$display = strtolower($row[$language]);
						break;
					case "primcomnameenglish":
						$display = strtolower($row[$language]);
						break;
					case "gname":
						$display = ucfirst($row[$language]);
						break;
				}
				if($row['taxclass'] == 'Amphibians' && $amphibian == 'on') {
					echo "<option value=\"".$row['sppcode']."\">".$display."</option>";
				}
				if($row['taxclass'] == 'Aves' && $avian == 'on'){
					echo "<option value=\"".$row['sppcode']."\">".$display."</option>";
				}
				if($row['taxclass'] == 'Mammalia' && $mammal == 'on') {
					echo "<option value=\"".$row['sppcode']."\">".$display."</option>";
				}
				if($row['taxclass'] == 'Reptilia' && $reptile) {
					echo "<option value=\"".$row['sppcode']."\">".$display."</option>";
				}
			}
		}
	}

	//get list of selected species for select box
	function get_species_search($avian, $mammal, $reptile, $amphibian, $language, $search){

		//import connection from top of file and use named connections in queries
		global $prdbcon;

		$query = $this->query;
		if(strpos($query, "where") === false){
			$query .= " where primcomnameenglish ilike '%{$search}%'";
			$query .= " or primcomnamespanish ilike '%{$search}%'" ;
			$query .= " or gname ilike '%{$search}%'" ;
		} else {
			$query .= " and (primcomnameenglish ilike '%{$search}%'";
			$query .= " or primcomnamespanish ilike '%{$search}%'" ;
			$query .= " or gname ilike '%{$search}%')" ;
		}

		//echo $query; die();
		$sppcodes = $this->strsppcodes;
		$result = pg_query($prdbcon, $query);
		while (($row = pg_fetch_array($result))!==FALSE){
			if ($sppcodes[$row['sppcode']] == 1){
				switch ($language){
					case "primcomnamespanish":
						$display = strtolower($row[$language]);
						break;
					case "primcomnameenglish":
						$display = strtolower($row[$language]);
						break;
					case "gname":
						$display = ucfirst($row[$language]);
						break;
				}
				if($row['taxclass'] == 'Amphibians' && $amphibian == 'on') {
					echo "<option value=\"".$row['sppcode']."\">".$display."</option>";
				}
				if($row['taxclass'] == 'Aves' && $avian == 'on'){
					echo "<option value=\"".$row['sppcode']."\">".$display."</option>";
				}
				if($row['taxclass'] == 'Mammalia' && $mammal == 'on') {
					echo "<option value=\"".$row['sppcode']."\">".$display."</option>";
				}
				if($row['taxclass'] == 'Reptilia' && $reptile) {
					echo "<option value=\"".$row['sppcode']."\">".$display."</option>";
				}
			}
		}
	}

	function get_species_ss($avian, $mammal, $reptile, $amphibian, $search){
		$report_name = "report".rand(0,999999).".xls";

		//import connection from top of file and use named connections in queries
		global $prdbcon;

		//open file for writing and write column headers
		$handle = fopen("/pub/server_temp/{$report_name}", "w+");
		$somecontent = "sppcode \t itiscode \t scientific name \t commom name english \t commom name spanish\n";
		fwrite($handle, $somecontent);

		//run query and write data to file
		$query = $this->query;
		if(strpos($query, "where") === false){
			$query .= " where primcomnameenglish ilike '%{$search}%'";
			$query .= " or primcomnamespanish ilike '%{$search}%'" ;
			$query .= " or gname ilike '%{$search}%'" ;
		} else {
			$query .= " and (primcomnameenglish ilike '%{$search}%'";
			$query .= " or primcomnamespanish ilike '%{$search}%'" ;
			$query .= " or gname ilike '%{$search}%')" ;
		}
		//$query .= " where primcomnameenglish ilike '%{$search}%'";
		//$query .= " or primcomnamespanish ilike '%{$search}%'" ;
		//$query .= " or gname ilike '%{$search}%'" ;

		$sppcodes = $this->strsppcodes;
		$result = pg_query($prdbcon, $query);

		while (($row = pg_fetch_array($result))!==FALSE){
			if ($sppcodes[$row['sppcode']] == 1){
				if($row['taxclass'] == 'Amphibians' && $amphibian == 'on') {
					$somecontent = $row['sppcode']."\t".$row['itis']."\t".$row['gname']."\t".$row['primcomnameenglish']."\t".$row['primcomnamespanish']."\n";
					fwrite($handle, $somecontent);
				}
				if($row['taxclass'] == 'Aves' && $avian == 'on'){
					$somecontent = $row['sppcode']."\t".$row['itis']."\t".$row['gname']."\t".$row['primcomnameenglish']."\t".$row['primcomnamespanish']."\n";
					fwrite($handle, $somecontent);
				}
				if($row['taxclass'] == 'Mammalia' && $mammal == 'on') {
					$somecontent = $row['sppcode']."\t".$row['itis']."\t".$row['gname']."\t".$row['primcomnameenglish']."\t".$row['primcomnamespanish']."\n";
					fwrite($handle, $somecontent);
				}
				if($row['taxclass'] == 'Reptilia' && $reptile) {
					$somecontent = $row['sppcode']."\t".$row['itis']."\t".$row['gname']."\t".$row['primcomnameenglish']."\t".$row['primcomnamespanish']."\n";
					fwrite($handle, $somecontent);
				}
			}
		}
		fclose($handle);
		return $report_name;
	}

	//get list of selected species for select box
	function get_species_dnld($avian, $mammal, $reptile, $amphibian, $search){

		global $prdbcon;
		//run query and write data to file
		$query = $this->query;
		if(strpos($query, "where") === false){
			$query .= " where primcomnameenglish ilike '%{$search}%'";
			$query .= " or primcomnamespanish ilike '%{$search}%'" ;
			$query .= " or gname ilike '%{$search}%'" ;
		} else {
			$query .= " and (primcomnameenglish ilike '%{$search}%'";
			$query .= " or primcomnamespanish ilike '%{$search}%'" ;
			$query .= " or gname ilike '%{$search}%')" ;
		}
		$sppcodes = $this->strsppcodes;
		//var_dump($sppcodes);
		$result = pg_query($prdbcon, $query);

		while (($row = pg_fetch_array($result))!==FALSE){
			//var_dump($row);
			if ($sppcodes[$row['sppcode']] == 1){
				if(strtoupper($row['taxclass']) == 'AMPHIBIANS' && $amphibian == 'on') {

					echo "<tr><td><input type='checkbox' onclick='poll();' name='pds' value='".$row['sppcode']."' /></td><td>".$row['primcomnamespanish']."</td></tr>";
				}
				if(strtoupper($row['taxclass']) == 'AVES' && $avian == 'on'){
					echo "<tr><td><input type='checkbox' onclick='poll();' name='pds' value='".$row['sppcode']."'/></td><td>".$row['primcomnamespanish']."</td></tr>";
				}
				if(strtoupper($row['taxclass']) == 'MAMMALIA' && $mammal == 'on') {
					echo "<tr><td><input type='checkbox' onclick='poll();' name='pds' value='".$row['sppcode']."'/></td><td>".$row['primcomnamespanish']."</td></tr>";
				}
				if(strtoupper($row['taxclass']) == 'REPTILIA' && $reptile) {
					echo "<tr><td><input type='checkbox' onclick='poll();' name='pds' value='".$row['sppcode']."'/></td><td>".$row['primcomnamespanish']."</td></tr>";
				}
			}
		}
	}

	//tester function
	function test1(){
		var_dump($this->range);
		var_dump($this->strsppcodes);
		var_dump($this->num_species);
	}
}

?>