<div id="content_wrapper">

<h1> <?php echo $page_info->title; ?> </h1>
<p> <?php echo $page_info->description; ?> </p>

<?php 

//Create a div for each piece of text on the page as old SwarmTV used to do;
$listview = '';
$temp_x =200;
$temp_y = 100;
foreach ($page_contents->result() as $row)
{
	$listview = $listview . '<div id="div' . $row->id . 'type="text" class="element" style="position: absolute; opacity: 1; color: rgb(192, 192, 192); background: url(&quot;transparentpixel.gif&quot;) repeat scroll 0% 0% transparent; font-size: 15px; width: 554px; height: 188px; left: ' . $temp_x . 'px; top: ' . $temp_y . 'px; z-index: 5437; overflow: auto; margin: 1px; padding: 15px; cursor: auto; border: medium none;" onmouseover="this.style.border=\'1px dashed #888\'; this.style.margin=\'0px\';" onmouseout="this.style.border=\'none\'; this.style.margin=\'1px\';" ondblclick="openEditor(\'div' . $row->id . ');">' . $row->contents . '</div>';
	$temp_y = $temp_y+200;
}
echo $listview;
?>
</div>



