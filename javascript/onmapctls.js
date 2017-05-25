function setmapctls(){	
	$('#ctls > div').each(function(){
		  $(this).click(function(e){
            e.stopPropagation();
            switch ($(e.currentTarget).attr("id")) {
               case "zoommax":
                  fullview();
                  break;
               case "panup":
                  panup();
                  break;
               case "panleft":
                  panleft();
                  break;
               case "panright":
                  panright();
                  break;
               case "pandown":
                  pandown();
                  break;
               case "zoomin":
                  onmapzoomin();
                  break;
               case "zoomout":
                  onmapzoomout();
                  break;               
            }         
		  })
	});
};

function pandown() {
   var wide =  $('#winw_ajax').val();
	var high =  $('#winh_ajax').val();
   document.getElementById('clkx_ajax').value = wide/2;
	document.getElementById('clky_ajax').value = 3*high/4;
   document.getElementById('zoom_ajax').value = 1;
	send_ajax();   
};
function panup() {
   var wide =  $('#winw_ajax').val();
	var high =  $('#winh_ajax').val();
   document.getElementById('clkx_ajax').value = wide/2;
	document.getElementById('clky_ajax').value = high/4;
   document.getElementById('zoom_ajax').value = 1;
	send_ajax();   
};
function panright() {
   var wide =  $('#winw_ajax').val();
	var high =  $('#winh_ajax').val();
   document.getElementById('clkx_ajax').value = 3*wide/4;
	document.getElementById('clky_ajax').value = high/2;
   document.getElementById('zoom_ajax').value = 1;
	send_ajax();
};
function panleft() {
   var wide =  $('#winw_ajax').val();
	var high =  $('#winh_ajax').val();
   document.getElementById('clkx_ajax').value = wide/4;
	document.getElementById('clky_ajax').value = high/2;
   document.getElementById('zoom_ajax').value = 1;
	send_ajax();
};
function onmapzoomin() {
   var wide =  $('#winw_ajax').val();
	var high =  $('#winh_ajax').val();
   document.getElementById('clkx_ajax').value = wide/2;
	document.getElementById('clky_ajax').value = high/2;
   document.getElementById('zoom_ajax').value = 2;
	send_ajax();
};
function onmapzoomout() {
   var wide =  $('#winw_ajax').val();
	var high =  $('#winh_ajax').val();
   document.getElementById('clkx_ajax').value = wide/2;
	document.getElementById('clky_ajax').value = high/2;
   document.getElementById('zoom_ajax').value = -2;
	send_ajax();
};
