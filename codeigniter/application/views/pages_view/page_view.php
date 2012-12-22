<h1> <?php echo $page_info->title; ?> </h1>
<p> <?php echo $page_info->description; ?> </p>

<?php

foreach ($page_elements->result() as $element)
	{

	echo '<div id="' . $element->id . '" class="element" class="' . $element->type . '" /></div>';
	/*switch ($element->type){
		case 'text':		
			echo htmlspecialchars_decode($element->description);
			break;
		case 'image':
			echo "<img width=\"$element->width\" src=\"" . base_url() . "assets/image/$element->filename\" />";
			break;
		case 'audio':
			break;
		case 'movie':
			break;
	}
	echo '</div>';*/
}
?>



