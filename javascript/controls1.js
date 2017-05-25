//these functions make layer visible and queryable when creating aoi
function show_owner(){
	if(document.forms[0].owner_tab2.checked){
		document.forms[0].steward[0].checked = true;
		parent.map.document.forms[0].query_layer.value = 'land_owner';
	}else{
		document.forms[0].steward[0].checked = false;
	}
	loadlayers();
}
function show_manage(){
	if(document.forms[0].manage_tab2.checked){
		document.forms[0].steward[1].checked = true;
		parent.map.document.forms[0].query_layer.value = 'mgmt_name';
	}else{
		document.forms[0].steward[1].checked = false;
	}
	loadlayers();
}
function show_muni(){
	if(document.forms[0].muni_tab2.checked){
		document.forms[0].muni.checked = true;
		parent.map.document.forms[0].query_layer.value = 'muni';
	}else{
		document.forms[0].muni.checked = false;
	}
	loadlayers();
}
function show_island(){
	if(document.forms[0].island_tab2.checked){
		document.forms[0].islands.checked = true;
		parent.map.document.forms[0].query_layer.value = 'island';
	}else{
		document.forms[0].islands.checked = false;
	}
	loadlayers();
}
function show_life_zone(){
	if(document.forms[0].life_zone_tab2.checked){
		document.forms[0].zones.checked = true;
		parent.map.document.forms[0].query_layer.value = 'zone';
	}else{
		document.forms[0].zones.checked = false;
	}
	loadlayers();
}
function show_wtshd(){
	if(document.forms[0].wtshd_tab2.checked){
		document.forms[0].wtshds.checked = true;
		parent.map.document.forms[0].query_layer.value = 'wtshds';
	}else{
		document.forms[0].wtshds.checked = false;
	}
	loadlayers();
}
function show_subwtshd(){
	if(document.forms[0].subwtshd_tab2.checked){
		document.forms[0].subwtshds.checked = true;
		parent.map.document.forms[0].query_layer.value = 'subwaters';
	}else{
		document.forms[0].subwtshds.checked = false;
	}
	loadlayers();
}
//these functions create red hatch on map for aoi definition
function add_owner(){
	document.forms[0].owner_tab2.checked = true;
	document.forms[0].steward[0].checked = true;
	parent.map.document.forms[0].query_layer.value = 'land_owner';
	var length = document.forms[0].owner_aoi.length;
	var previous = "";
	for (var i=0;  i<length; i++){
		if(document.forms[0].owner_aoi[i].checked){
			var selected = document.forms[0].owner_aoi[i].value;
			if (previous.length == 0){
				previous = selected;
			}else{
				previous = previous + ":" + selected;
			}
		}
	}
	parent.map.document.getElementById('query_item').value = 'owner_desc';
	parent.map.document.getElementById('owner_ajax').value = previous;
	parent.map.document.getElementById('owner_aoi').value = previous;
	parent.map.document.getElementById('owner_pdf').value = previous;
	loadlayers();
}
function add_manage(){
	document.forms[0].manage_tab2.checked = true;
	document.forms[0].steward[1].checked = true;
	parent.map.document.forms[0].query_layer.value = 'mgmt_name';
	var length = document.forms[0].manage_aoi.length;
	var previous = "";
	for (var i=0;  i<length; i++){
		if(document.forms[0].manage_aoi[i].checked){
			var selected = document.forms[0].manage_aoi[i].value;
			if (previous.length == 0){
				previous = selected;
			}else{
				previous = previous + ":" + selected;
			}
		}
	}
	parent.map.document.getElementById('query_item').value = 'manage_desc';
	parent.map.document.getElementById('manage_ajax').value = previous;
	parent.map.document.getElementById('manage_aoi').value = previous;
	parent.map.document.getElementById('manage_pdf').value = previous;
	loadlayers();
}
function add_muni(){
	document.forms[0].muni_tab2.checked = true;
	document.forms[0].muni.checked = true;
	parent.map.document.forms[0].query_layer.value = 'muni';
	var length = document.forms[0].muni_aoi.length;
	var previous = "";
	for (var i=0;  i<length; i++){
		if(document.forms[0].muni_aoi[i].checked){
			var selected = document.forms[0].muni_aoi[i].value;
			if (previous.length == 0){
				previous = selected;
			}else{
				previous = previous + ":" + selected;
			}
		}
	}
	parent.map.document.getElementById('query_item').value = 'muni_desc';
	parent.map.document.getElementById('muni_ajax').value = previous;
	parent.map.document.getElementById('muni_aoi').value = previous;
	parent.map.document.getElementById('muni_pdf').value = previous;
	loadlayers();
}
function add_island(){
	document.forms[0].island_tab2.checked = true;
	document.forms[0].islands.checked = true;
	parent.map.document.forms[0].query_layer.value = 'island';
	var length = document.forms[0].island_aoi.length;
	var previous = "";
	for (var i=0;  i<length; i++){
		if(document.forms[0].island_aoi[i].checked){
			var selected = document.forms[0].island_aoi[i].value;
			if (previous.length == 0){
				previous = selected;
			}else{
				previous = previous + ":" + selected;
			}
		}
	}
	parent.map.document.getElementById('query_item').value = 'island_desc';
	parent.map.document.getElementById('island_ajax').value = previous;
	parent.map.document.getElementById('island_aoi').value = previous;
	parent.map.document.getElementById('island_pdf').value = previous;
	loadlayers();
}
function add_zone(){
	document.forms[0].life_zone_tab2.checked = true;
	document.forms[0].zones.checked = true;
	parent.map.document.forms[0].query_layer.value = 'zone';
	var length = document.forms[0].zone_aoi.length;
	var previous = "";
	for (var i=0;  i<length; i++){
		if(document.forms[0].zone_aoi[i].checked){
			var selected = document.forms[0].zone_aoi[i].value;
			if (previous.length == 0){
				previous = selected;
			}else{
				previous = previous + ":" + selected;
			}
		}
	}
	parent.map.document.getElementById('query_item').value = 'island_desc';
	parent.map.document.getElementById('life_zone_ajax').value = previous;
	parent.map.document.getElementById('life_zone_aoi').value = previous;
	parent.map.document.getElementById('life_zone_pdf').value = previous;
	loadlayers();
}
function add_wtshd(){
	document.forms[0].wtshd_tab2.checked = true;
	document.forms[0].wtshds.checked = true;
	parent.map.document.forms[0].query_layer.value = 'wtshds';
	var length = document.forms[0].wtshd_aoi.length;
	var previous = "";
	for (var i=0;  i<length; i++){
		if(document.forms[0].wtshd_aoi[i].checked){
			var selected = document.forms[0].wtshd_aoi[i].value;
			if (previous.length == 0){
				previous = selected;
			}else{
				previous = previous + ":" + selected;
			}
		}
	}
	parent.map.document.getElementById('query_item').value = 'basin_desc';
	parent.map.document.getElementById('basin_ajax').value = previous;
	parent.map.document.getElementById('basin_aoi').value = previous;
	parent.map.document.getElementById('basin_pdf').value = previous;
	loadlayers();
}
function add_subwtshd(){
	document.forms[0].subwtshd_tab2.checked = true;
	document.forms[0].subwtshds.checked = true;
	parent.map.document.forms[0].query_layer.value = 'subwaters';
	var length = document.forms[0].subwtshd_aoi.length;
	var previous = "";
	for (var i=0;  i<length; i++){
		if(document.forms[0].subwtshd_aoi[i].checked){
			var selected = document.forms[0].subwtshd_aoi[i].value;
			if (previous.length == 0){
				previous = selected;
			}else{
				previous = previous + ":" + selected;
			}
		}
	}
	parent.map.document.getElementById('query_item').value = 'sub_basin_desc';
	parent.map.document.getElementById('sub_basin_ajax').value = previous;
	parent.map.document.getElementById('sub_basin_aoi').value = previous;
	parent.map.document.getElementById('sub_basin_pdf').value = previous;
	loadlayers();
}

function add_ecosys(){
	if(document.forms[0].ecosys.checked){
		parent.map.document.getElementById('ecosys_ajax').value = "1";
		parent.map.document.getElementById('ecosys_aoi').value = "1";
	}else{
		parent.map.document.getElementById('ecosys_ajax').value = "";
		parent.map.document.getElementById('ecosys_aoi').value = "";
	}
	loadlayers();
}

//submit defined aoi for analysis
function aoi_pre_sub(){
	if(parent.map.document.forms.fm1.owner.value == '' &&
	parent.map.document.forms.fm1.manage.value == '' &&
	parent.map.document.forms.fm1.subwtshd.value == '' &&
	parent.map.document.forms.fm1.wtshd.value == '' &&
	parent.map.document.forms.fm1.zone.value == '' &&
	parent.map.document.forms.fm1.island.value == '' &&
	parent.map.document.forms.fm1.ecosys.value == '' &&
	parent.map.document.forms.fm1.muni.value == ''){
		alert('must select AOI before submitting')
	} else {
		parent.map.document.getElementById('aoi_type').value = 'predefined';
		parent.map.document.getElementById('click_val_x').value = parent.map.posix;
		parent.map.document.getElementById('click_val_y').value = parent.map.posiy;
		parent.map.document.getElementById('fm1').action = "map2.php";
		parent.map.document.getElementById('fm1').target = "map";
		parent.map.document.getElementById('zoom').value = '1';
		parent.map.document.getElementById('mode').value = "pan";
		parent.map.document.getElementById('fm1').submit();
		//  alert('hello');
	}
}

function pre_reset(){
	for( var i = 0; i < document.forms[0].owner_aoi.length; i++) document.forms[0].owner_aoi[i].checked = false;
	for( var i = 0; i < document.forms[0].manage_aoi.length; i++) document.forms[0].manage_aoi[i].checked = false;
	for( var i = 0; i < document.forms[0].muni_aoi.length; i++) document.forms[0].muni_aoi[i].checked = false;
	for( var i = 0; i < document.forms[0].island_aoi.length; i++) document.forms[0].island_aoi[i].checked = false;
	for( var i = 0; i < document.forms[0].wtshd_aoi.length; i++) document.forms[0].wtshd_aoi[i].checked = false;
	for( var i = 0; i < document.forms[0].subwtshd_aoi.length; i++) document.forms[0].subwtshd_aoi[i].checked = false;
	for( var i = 0; i < document.forms[0].zone_aoi.length; i++) document.forms[0].zone_aoi[i].checked = false;
	parent.map.clear_aois();

}

//functions to draw and submit custom aoi
function cust_start(){
	parent.map.draw();
}
function aoi_cust_sub(){
	if((parent.map.posix.length < 3)){
		alert('must select AOI before submitting')
	} else {
		parent.map.document.getElementById('aoi_type').value = 'custom';
		parent.map.document.getElementById('click_val_x').value = parent.map.posix;
		parent.map.document.getElementById('click_val_y').value = parent.map.posiy;
		parent.map.document.getElementById('fm1').action = "map2.php";
		parent.map.document.getElementById('fm1').target = "map";
		parent.map.document.getElementById('zoom').value = '1';
		parent.map.document.getElementById('mode').value = "pan";
		parent.map.document.getElementById('fm1').submit();
		//  alert('hello');
	}
}
function cust_reset(){
	parent.map.posix.length = 0;
	parent.map.posiy.length = 0;
	parent.map.jg_box.clear();
}
function upload(){
	window.open("../upload.php","", "height=300,width=600")

}