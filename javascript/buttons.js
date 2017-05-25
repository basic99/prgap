
//these functions set values for toolbar buttons
function zoom_in(){
	$('#dragMap').mousedown( beginDragBox );
	$('#dragMap').unbind('mousedown', beginDrag);
	$('#dragMap').unbind('click', zoom2out);
	$('#dragMap').unbind('click', func1);
	$('#myMap').unbind('click', runquery);

	document.getElementById('myMap').style.display = 'block';
	document.getElementById('dragMap').style.display = 'block';
	
	document.getElementById('dragMap').style.cursor = 'pointer';
	document.getElementById('zmin').src = "/graphics/ncgap/mag_plus_dn.png";
	document.getElementById('zmout').src = "/graphics/ncgap/mag_minus_up.png";
	document.getElementById('pn').src = "/graphics/ncgap/pan_up.png";
	if(document.getElementById('qry')){
		document.getElementById('qry').src = "/graphics/ncgap/info_up.png";
	}
	if(document.getElementById('draw')){
		document.getElementById('draw').src = "/graphics/ncgap/draw_up.png";
	}
	//alert('hello world');
}

function zoom_out(){
	$('#dragMap').click( zoom2out );
	$('#dragMap').unbind('mousedown', beginDrag);
	$('#dragMap').unbind('mousedown', beginDragBox);
	$('#dragMap').unbind('click', func1);
	$('#myMap').unbind('click', runquery);

	document.getElementById('myMap').style.display = 'none';
	
	document.getElementById('dragMap').style.cursor = 'pointer';
	document.getElementById('dragMap').style.display = 'block';
	document.getElementById('zmin').src = "/graphics/ncgap/mag_plus_up.png";
	document.getElementById('zmout').src = "/graphics/ncgap/mag_minus_dn.png";
	document.getElementById('pn').src = "/graphics/ncgap/pan_up.png";
	if(document.getElementById('qry')){
		document.getElementById('qry').src = "/graphics/ncgap/info_up.png";
	}
	if(document.getElementById('draw')){
		document.getElementById('draw').src = "/graphics/ncgap/draw_up.png";
	}
}

function pan(){
	
	$('#dragMap').mousedown( beginDrag );
	$('#dragMap').unbind('click', zoom2out);
	$('#dragMap').unbind('click', func1);
	$('#dragMap').unbind('mousedown', beginDragBox);
	$('#myMap').unbind('click', runquery);


	document.getElementById('dragMap').style.cursor = 'pointer';
	document.getElementById('dragMap').style.display = 'block';
	document.getElementById('myMap').style.display = 'none';
	document.getElementById('zmin').src = "/graphics/ncgap/mag_plus_up.png";
	document.getElementById('zmout').src = "/graphics/ncgap/mag_minus_up.png";
	document.getElementById('pn').src = "/graphics/ncgap/pan_dn.png";
	if(document.getElementById('qry')){
		document.getElementById('qry').src = "/graphics/ncgap/info_up.png";
	}
	if(document.getElementById('draw')){
		document.getElementById('draw').src = "/graphics/ncgap/draw_up.png";
	}
}

function draw(){
	$('#dragMap').click( func1 );
	$('#dragMap').unbind("click", zoom2out );
	$('#dragMap').unbind('mousedown', beginDrag);
	$('#dragMap').unbind('mousedown', beginDragBox);
	$('#myMap').unbind('click', runquery);

	document.getElementById('zmin').src = "/graphics/ncgap/mag_plus_up.png";
	document.getElementById('draw').src = "/graphics/ncgap/draw_dn.png";
	document.getElementById('zmout').src = "/graphics/ncgap/mag_minus_up.png";
	document.getElementById('pn').src = "/graphics/ncgap/pan_up.png";
	if(document.getElementById('qry')){
		document.getElementById('qry').src = "/graphics/ncgap/info_up.png";
	}
	document.getElementById('myMap').style.display = "block";
	document.getElementById('dragMap').style.display = "block";
	document.getElementById('dragMap').style.cursor = 'crosshair';
	
}

function query(){
	$('#myMap').click( runquery ); //dragging.js
	document.getElementById('myMap').style.display = 'block';
	document.getElementById('dragMap').style.display = 'none';

	document.getElementById('zmin').src = "/graphics/ncgap/mag_plus_up.png";
	document.getElementById('zmout').src = "/graphics/ncgap/mag_minus_up.png";
	document.getElementById('pn').src = "/graphics/ncgap/pan_up.png";
	if(document.getElementById('qry')){
		document.getElementById('qry').src = "/graphics/ncgap/info_dn.png";
	}
	if(document.getElementById('draw')){
		document.getElementById('draw').src = "/graphics/ncgap/draw_up.png";
	}
}

//set to full zoom out
function fullview(){
	document.getElementById('extent_ajax').value = "39084 205175 328284 277070";
	document.getElementById('zoom_ajax').value=1;
	send_ajax();
	pan();
}
/*
function clkcntr(){
	//var clky = ($('#winh_ajax').val())/2;
	//var clkx = ($('#winw_ajax').val())/2;
	//$('#clkx_ajax').val(clkx);
	//$('#clky_ajax').val(clky);
}
*/
function find_height(){
	var win_h = $(window).height();
	return win_h;
}

function find_width(){
	var win_w = $(window).width();
	return  win_w;

}
function resize(){
	var win_h = find_height() - 69;
	var win_w = find_width();
	if (win_h%2 == 1) win_h = win_h -1;
	if (win_w%2 == 1) win_w = win_w -1;
	$('#clkx_ajax').val(win_w/2);
	$('#clky_ajax').val(win_h/2);
	$('#winw_ajax').val(win_w);
	$('#winh_ajax').val(win_h);
	$('#loader_gif').css("top", win_h/2 + 60);
	$('#loader_gif').css("left", win_w/2 -110);
	send_ajax();
}

function new_aoi(){
	//parent.location = parent.location;  //bs but it works
	parent.data.location = 'dummy.html';
	parent.functions.location = 'dummy.html';
	window.location = "map.php";
	parent.controls.location = "controls.php";
	
	}
function export_pdf(){
	window.open("../export_info.php","","menubar=no,scrollbars,width=550,height=250");
}