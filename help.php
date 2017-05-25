<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Help</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style type="text/css">
/* <![CDATA[ */
p,a {font-family: sans-serif; font-size: 1.1em;}
/* ]]> */
</style>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */

/* ]]> */
</script>
</head>
<body>
<ol>
<li><a href="#hlp1">The program doesn't look right in my browser, what can I do?</a></li>
<li><a href="#hlp2">How do I navigate the main map? </a></li>
<li><a href="#hlp3">How do I change the layers visible on the map?</a></li>
<li><a href="#hlp4">How do I create a custom AOI?</a></li>
<li><a href="#hlp5">How do I create an AOI from predefined areas?</a></li>
<li><a href="#hlp6">I have created an AOI, now what?</a></li>
<li><a href="#hlp7">How do I use single species mode?</a></li>
<li><a href="#hlp8">How do I use multiple species mode?</a></li>
<li><a href="#hlp9">How do I view a legend?</a></li>


</ol>
<p><strong><a name="hlp1">The program doesn't look right in my browser, what can I do?</a></strong> The program was written to work best at a resolution of 1024x768 or greater. It was tested and works with the Firefox and Internet Explorer browsers, and may not work with other browsers. If the size of the map frame changes, for example by adding tabs or viewing full screen, the map will not draw to the correct size, as evidenced by the scale bar not being placed in the lower left corner. In this case use the button with the red up arrow to reload the correct map size.</p>
<p><strong><a name="hlp2">How do I navigate the main map? </a></strong>The toolbar on the main map has several buttons that will vary depending on whether the user has defined an AOI. On the first map(before an AOI is defined) there are 5 buttons on the first row which define the current mode. The selected mode can be seen as a down or selected button. In zoom in mode a click on the map will zoom in at that point 2x. By clicking and dragging you can create a zoombox. In zoom out mode a click on the map will zoom out 2x at the click point. In pan mode a click on the map will recenter the map and a click and drag will drag the map. In draw custom mode a click on the map will  draw a line for a custom AOI. Usually the user will start in this mode from the define AOI tab. In query mode a click on the map will display the query result of the layer selected to the right in the box on the toolbar. </p>
<p><strong><a name="hlp3">How do I change the layers visible on the map?</a></strong> The frame to the left of the map has 3 tabs. Under the view layers tab there is an expandable tree menu that allows you to select the layers to display. Select a checkbox in the forground menu to view that layer. In the stewardship menu you can select either management, ownership, GAP status, or none. The background menu allows you to select either the landcover or elevation map as a background raster map.</p>
<p><strong><a name="hlp4">How do I create a custom AOI?</a></strong> First, select the define AOI tab. Under this tab click the custom button. Move the cursor over the map. The cursor should change to a cross-hair. If you need to zoom in more before defining the AOI, then click the zoom in button, zoom in and then click the draw custom button(pencil) on the map to reset to draw mode. Click on the map to locate the starting point. Move the cursor to the second point and click again. Continue in this fashion until the polygon describes the AOI. If you change your mind or make a mistake click reset and start over. To create an AOI of the polygon that is drawn click submit.</p>
<p><strong><a name="hlp5">How do I create an AOI from predefined areas?</a></strong> First, select the define AOI tab. Click the desired category to expand the tree menu. By selecting either the show this layer checkbox or making a selection that layer will be displayed, and it will also be made the queryable layer. By selecting query mode on the main map the user can get help selecting the correct areas. When an area is selected, then it will appear with a red cross-hatch pattern. You can change selections either by unchecking or clicking the reset button. When the selected areas define the desired AOI, then click the submit button to create the AOI.</p>
<p><strong><a name="hlp6">I have created an AOI, now what?</a></strong> When you click the submit AOI button, the server will create an entry in a database table defining your AOI and will also import a mask of the AOI into the GRASS program. This could take several minutes, especially for AOI that cover most of the ecosystem. When the calculations are complete you will see the AOI created outlined in blue in the map page. The frame to the left now has an AOI info tab. Clicking one of the buttons here will create a GRASS report for the AOI that has been defined. Click the select species button, and the the select species tab will display. To continue analysis using all listed species in the AOI simply click submit. To choose from listing categories, choose the second radio button on the left, and then the listing status of interest. If more than one status is checked then check and  or or as appropriate. Click submit.</p>
<p><strong><a name="hlp7">How do I use single species mode?</a></strong> This is the default mode for the main AOI page. In the bottom left frame is a list of species. Select one and submit and it will appear in the text box in the next frame and the predicted distribution map for the species will appear. There are 6 different maps to select, two of which will show the entire ecosystem(predicted distribution and hexagonal range maps), but the other 4 maps are calculated, and to save computing time will only display for an area around the AOI. The calculate buttons beside the four calculated maps will show reports of the selected species for the AOI.</p>
<p><strong><a name="hlp8">How do I use multiple species mode?</a></strong> Click the multiple species radio button. Select several species from the frame on the lower left, either singly or using the ctrl key and click submit. Don't worry about selecting species twice as the program will only add a particular species one time. Click the richness map button and the map will appear. The map will automatically select a white to red legend that uses the maximum calculated range.</p>
<p><strong><a name="hlp9">How do I view a legend?</a></strong> Click the links on any of the maps on the single species page or the layers tab of the left frame</p>
</body>
</html>
