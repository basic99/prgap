
function beginDragBox(event) {
	//var elementToDrag = event.currentTarget;
	var elemPos = $('#dragMap').position();
	var jg = window.jg_box;
	var deltaX = event.pageX - elemPos.left;
	var deltaY = event.pageY - elemPos.top;
	var orig_y = event.pageY;
	var orig_x = event.pageX;
	var move_x = 0;
	var move_y = 0;
	var box_w = 0;
	var box_h = 0;
	var box_top = 0;
	var box_left = 0;
	jg.setColor("#ff0000"); // red
	$(document).mousemove( moveHandler );
	$(document).mouseup( upHandler );
	$(document).click( zoom2 );
	event.preventDefault();
	event.stopPropagation();

	//draw box
	function moveHandler(e) {
		$(document).unbind('click', zoom2);
		//calculate box coordinates for mouse move in all four quadrants
		move_x  =  e.pageX - orig_x;
		move_y  =  e.pageY - orig_y;
		if ((move_x >= 0) && (move_y >= 0) ){
			box_w = move_x;
			box_h = move_y;
			box_top = deltaY;
			box_left = deltaX;
		} else if ((move_x <= 0) && (move_y <= 0) ){
			box_w = -move_x;
			box_h = -move_y;
			box_top = deltaY + move_y;
			box_left = deltaX + move_x;
		} else if ((move_x <= 0) && (move_y >= 0) ){
			box_w = -move_x;
			box_left = deltaX + move_x;
			box_h = move_y;
			box_top = deltaY;
		} else if ((move_x >= 0) && (move_y <= 0) ){
			box_w = move_x;
			box_left = deltaX;
			box_h = -move_y;
			box_top = deltaY + move_y;
		}
		//draw box
		jg.clear();
		jg.drawRect(box_left, box_top, box_w, box_h);
		jg.paint();
		event.stopPropagation();
	}

	function upHandler(e) {
		if((move_y != 0) || (move_x != 0)){
			document.getElementById('clkx_ajax').value = box_left + box_w/2;
			document.getElementById('clky_ajax').value = box_top +box_h/2 ;
			var win_w = $('#winw_ajax').val();
			var win_h = $('#winh_ajax').val();
			var zoom_w =  win_w/box_w;
			var zoom_h = win_h/box_h;
			var zoom = Math.min(zoom_w, zoom_h);
			zoom = Math.floor(zoom);
			$('#zoom_ajax').val(zoom);
			jg.clear();
			send_ajax();
		}
		$(document).unbind('mouseup', upHandler);
		$(document).unbind('mousemove', moveHandler);
		event.stopPropagation();

	}
	//for click only zoom 2x
	function zoom2(e){
		var deltaX = e.pageX - elemPos.left;
		var deltaY = e.pageY - elemPos.top;
		document.getElementById('clkx_ajax').value = deltaX;
		document.getElementById('clky_ajax').value = deltaY;
		document.getElementById('zoom_ajax').value = 2;
		send_ajax();
		$(document).unbind('click', zoom2);
		event.stopPropagation();
	}
}

function zoom2out(e){
	var elemPos = $('#dragMap').position();
	var deltaX = e.pageX - elemPos.left;
	var deltaY = e.pageY - elemPos.top;
	document.getElementById('clkx_ajax').value = deltaX;
	document.getElementById('clky_ajax').value = deltaY;
	document.getElementById('zoom_ajax').value = -2;
	send_ajax();
}