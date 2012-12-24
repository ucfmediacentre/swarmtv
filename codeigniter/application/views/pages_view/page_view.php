<h1> <?php echo $page_info->title; ?> </h1>
<p> <?php echo $page_info->description; ?> </p>

<?php

foreach ($page_elements as $element)
{
	echo '<div id="' . $element['id'] . '" class="element" type="' . $element['type'] . '" />OOOO</div>';
}

?>



