
//this function puts checks in boxes of selected layers for controls2,3,4
//is called from set_state() in map.php
function load_selections(){
	var layers = parent.map.document.getElementById('layers_ajax').value;
	//alert(layers);
	if (layers.indexOf('muni') != -1){document.forms[0].muni.checked = true;}
	if (layers.indexOf('zones') != -1){document.forms[0].zones.checked = true;}
	if (layers.indexOf('island') != -1){document.forms[0].islands.checked = true;}
	if (layers.indexOf('roads') != -1){document.forms[0].roads.checked = true;}
	if (layers.indexOf('hexs') != -1){document.forms[0].hexs.checked = true;}
	if (layers.indexOf('wtshds') != -1){document.forms[0].wtshds.checked = true;}
	if (layers.indexOf('subwaters') != -1){document.forms[0].subwtshds.checked = true;}

	if (layers.indexOf('ownership') != -1){
		document.forms[0].steward[0].checked = true;
	}else  if (layers.indexOf('management') != -1){
		document.forms[0].steward[1].checked = true;
	}else if (layers.indexOf('status') != -1){
		document.forms[0].steward[2].checked = true;
	}else{
		document.forms[0].steward[3].checked = true;
	}

	if (layers.indexOf('landcover') != -1){
		document.forms[0].background[0].checked = true;
	}else if (layers.indexOf('lcov2') != -1){
		document.forms[0].background[1].checked = true;
	}else if (layers.indexOf('elevation') != -1){
		document.forms[0].background[2].checked = true;
	}else{
		document.forms[0].background[3].checked = true;
	}

}

//this function puts checks in boxes of selected layers for controls
//is called from set_state() in map.php
function load_selections_1(){
	var layers = parent.map.document.getElementById('layers').value;
	if (layers.indexOf('muni') != -1){
		document.forms[0].muni.checked = true;
		document.forms[0].muni_tab2.checked = true;
	}else{
		document.forms[0].muni.checked = false;
		document.forms[0].muni_tab2.checked = false;
	}
	if (layers.indexOf('zones') != -1){
		document.forms[0].zones.checked = true;
		document.forms[0].life_zone_tab2.checked = true;
	}else{
		document.forms[0].zones.checked = false;
		document.forms[0].life_zone_tab2.checked = false;
	}
	if (layers.indexOf('island') != -1){
		document.forms[0].islands.checked = true;
		document.forms[0].island_tab2.checked = true;

	}else{
		document.forms[0].islands.checked = false;
		document.forms[0].island_tab2.checked = false;
	}
	if (layers.indexOf('wtshds') != -1){
		document.forms[0].wtshds.checked = true;
		document.forms[0].wtshd_tab2.checked = true;

	}else{
		document.forms[0].wtshds.checked = false;
		document.forms[0].wtshd_tab2.checked = false;
	}
	if (layers.indexOf('subwaters') != -1){
		document.forms[0].subwtshds.checked = true;
		document.forms[0].subwtshd_tab2.checked = true;

	}else{
		document.forms[0].subwtshds.checked = false;
		document.forms[0].subwtshd_tab2.checked = false;
	}

	if (layers.indexOf('roads') != -1){document.forms[0].roads.checked = true;}
	if (layers.indexOf('hexs') != -1){document.forms[0].hexs.checked = true;}
	if (layers.indexOf('ownership') != -1){
		document.forms[0].steward[0].checked = true;
		document.forms[0].owner_tab2.checked = true;
		document.forms[0].manage_tab2.checked = false;
	}else  if (layers.indexOf('management') != -1){
		document.forms[0].steward[1].checked = true;
		document.forms[0].manage_tab2.checked = true;
		document.forms[0].owner_tab2.checked = false;
	}else if (layers.indexOf('status') != -1){
		document.forms[0].steward[2].checked = true;
	}else{
		document.forms[0].steward[3].checked = true;
		document.forms[0].owner_tab2.checked = false;
		document.forms[0].manage_tab2.checked = false;
	}
	if (layers.indexOf('landcover') != -1){
		document.forms[0].background[0].checked = true;
	}else if (layers.indexOf('lcov2') != -1){
		document.forms[0].background[1].checked = true;
	}else if (layers.indexOf('elevation') != -1){
		document.forms[0].background[2].checked = true;
	}else{
		document.forms[0].background[3].checked = true;
	}

}


function categories(){
	//alert('hello');
	if(document.forms.fm2.fed.checked || document.forms.fm2.state.checked  || document.forms.fm2.nsglobal.checked ){
		//alert('hello');
		document.forms.fm2.species[1].checked = true;
	}else{
		document.forms.fm2.species[0].checked = true;
	}
}

//function polls checked layer selections, puts result on parent.map and submits
function change_categories(){
	parent.data.location = 'dummy.html';
	parent.functions.location = 'dummy.html';
	document.forms[1].action = 'controls3.php';
	document.forms[1].target = 'controls';
	document.forms[1].submit();
	parent.map.document.forms.fm1.species_layer.value = '';
	parent.map.document.forms.fm1.zoom.value = '1';
	parent.map.document.forms.fm1.submit();
}

function functions_action(){
	var pathname = window.location.pathname;
	if(document.forms[1].mode[0].checked){
		parent.functions.location = pathname.replace("controls4.php", "single.php");
	}
	if(document.forms[1].mode[1].checked){
		parent.functions.location = pathname.replace("controls4.php", "multiple.php");
	}
}

function lc_report(){
   // window.open("","report","toolbar=no, menubar=no, scrollbars");
   window.open("","report","toolbar=no, menubar=no, scrollbars");
   parent.map.document.forms.fm2.target = 'report';
   parent.map.document.forms.fm2.report.value = 'landcover';
   parent.map.document.forms.fm2.submit();
}

function manage_report(){
   window.open("","report","toolbar=no, menubar=no, scrollbars");
   parent.map.document.forms.fm2.target = 'report';
   parent.map.document.forms.fm2.report.value = 'management';
   parent.map.document.forms.fm2.submit();
}

function owner_report(){
   window.open("","report","toolbar=no, menubar=no, scrollbars");
   parent.map.document.forms.fm2.target = 'report';
   parent.map.document.forms.fm2.report.value = 'owner';
   parent.map.document.forms.fm2.submit();
}

function status_report(){
   window.open("","report","toolbar=no, menubar=no, scrollbars");
   parent.map.document.forms.fm2.target = 'report';
   parent.map.document.forms.fm2.report.value = 'status';
   parent.map.document.forms.fm2.submit();
}

function close_gears(){
	var but_on = parent.gears.document.getElementById("cont3").style.display;
	if (but_on == 'none'){
		parent.gears.close();
	}
}