<div class="element" type="title"><h1> <?php echo $page_info->title; ?> </h1></div>

<?php 

//Create a div for each piece of text on the page as old SwarmTV used to do;
$listview = '';
foreach ($page_contents->result() as $row)
{
	$elementType = $row->type;
	
	switch ($elementType) {
    case "text":
        $listview = $listview . '<div id="' . $row->id . '" type="' . $elementType . '" class="element" style="position: absolute; opacity: ' . $row->opacity . '; ' . $row->color . '; background: url(&quot;transparentpixel.gif&quot;) repeat scroll 0% 0% ' . $row->backgroundColor . '; font-family: ' . $row->fontFamily . '; font-size: ' . $row->fontSize . 'px; text-align: ' . $row->textAlign . '; width: ' . $row->width . 'px; height: ' . $row->height . 'px; left: ' . $row->x . 'px; top: ' . $row->y . 'px; z-index: ' . $row->z . '; overflow: auto; margin: 1px; padding: 15px; cursor: auto; border: medium none;" onmouseover="this.style.border=\'1px dashed #888\'; this.style.margin=\'0px\';" onmouseout="this.style.border=\'none\'; this.style.margin=\'1px\';" ondblclick="openEditor(\'div' . $row->id . ');">' . $row->content . '</div>';
        break;
    case "image":
        
    	$listview = $listview . '<div id="' . $row->id . '" type="' . $elementType . '" class="element" style="position: absolute; opacity: ' . $row->opacity . '; width: ' . $row->width . 'px; height: ' . $row->height . 'px; left: ' . $row->x . 'px; top: ' . $row->y . 'px; z-index: ' . $row->z . '; margin: 1px; padding: 15px; cursor: auto; border: medium none;" onmouseover="this.style.border=\'1px dashed #888\'; this.style.margin=\'0px\';" onmouseout="this.style.border=\'none\'; this.style.margin=\'1px\';" ondblclick="openEditor(\'div' . $row->id . ');"><img width="' . $row->width . '" height="' . $row->height . '" alt="' . $row->description . '" style="z-index:' . ($row->z-1) . ';" name="' . $row->filename . '" src="../../../../public/img/' . $row->filename . '"></div>';

        break;
    case "audio":
        echo "stuff";
        break;
    case "movie":
        echo "stuff";
        break;
	}
	
}
echo $listview;
?>




