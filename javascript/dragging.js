
function beginDrag( event){
	$('#zoom_ajax').val(1);
	var elemPos = $('#dragMap').position();
	var deltaX = event.pageX - elemPos.left;
	var deltaY = event.pageY - elemPos.top;
	var start_left = elemPos.left;
	var start_top = elemPos.top;
	$('#dragMap').mousemove( moveHandler );
	$('#dragMap').mouseup( upHandler );
	$('#dragMap').click( pan_img );
	event.preventDefault();
	event.stopPropagation();

	function moveHandler(e) {
		$('#dragMap').css("cursor", "move");
		$('#dragMap').unbind('click', pan_img);
		$('#dragMap').css("left", e.pageX - deltaX);
		$('#dragMap').css("top", e.pageY - deltaY);
		e.stopPropagation();
	}

	function upHandler(e) {
		$('#dragMap').css("cursor", "pointer");
		$('#dragMap').unbind('mouseup', upHandler);
		$('#dragMap').unbind('mousemove', moveHandler);
		var move_y = start_top - (e.pageY - deltaY);
		var move_x = start_left - (e.pageX - deltaX);
		if((move_y != 0) || (move_x != 0)){
			var wide =  $('#winw_ajax').val();
			var high =  $('#winh_ajax').val();
			var click_x = wide/2 + move_x;
			var click_y = high/2 + move_y;
			document.getElementById('clkx_ajax').value = click_x;
			document.getElementById('clky_ajax').value = click_y;
			send_ajax();
		}
		e.stopPropagation();
	}

	function pan_img(e){
		$('#dragMap').unbind('mouseup', upHandler);
		$('#dragMap').unbind('mousemove', moveHandler);
		$('#dragMap').unbind('click', pan_img);
		var deltaX = e.pageX - elemPos.left;
		var deltaY = e.pageY - elemPos.top;
		document.getElementById('clkx_ajax').value = deltaX;
		document.getElementById('clky_ajax').value = deltaY;
		send_ajax();

	}
}

function runquery(event){
	var clickx = event.pageX;
	var clicky = event.pageY;
	var ext = $('#extent_ajax').val();
	var winh = $('#winh_ajax').val();
	var winw = $('#winw_ajax').val();
	var query = $('#query_item > *:selected').val();

	$.ajax({
		type: "POST",
		url: "query.php",
		data: {
			win_w: winw,
			win_h:  winh,
			img_x: clickx,
			img_y: clicky,
			extent: ext,
			query_layer: query
		},
		dataType: "json",
		success: function(data){
			$('#message1').val(data.result);
			
		}
	});
}