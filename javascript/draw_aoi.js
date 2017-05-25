var posix = new Array();
var posiy = new Array();

function func1(evt){
	var lngth = posix.length;
	var clickx = evt.pageX;
	var clicky = evt.pageY;
	var docux = document.getElementById('dragMap').offsetLeft;
	var docuy = document.getElementById('dragMap').offsetTop;
	posix[lngth] = clickx - docux;
	posiy[lngth] = clicky - docuy;
	if (lngth > 0) drw()
	else drw_cross();
}
function drw_cross(){
	var jg = window.jg_box;
	jg.clear();
	jg.setStroke(2);
	jg.setColor("#ff0000");
	jg.drawLine((posix[0]-5), posiy[0], (posix[0]+5), posiy[0]);
	jg.drawLine(posix[0], (posiy[0]+5), posix[0], (posiy[0]-5));
	jg.paint();
}
function drw(){
	var jg = window.jg_box;
	jg.clear();
	jg.setStroke(2);
	jg.setColor("#ff0000"); // red
	jg.drawPolygon(posix, posiy);
	jg.paint();
}
