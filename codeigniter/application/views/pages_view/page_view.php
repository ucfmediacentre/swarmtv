<div id="content_wrapper">

<h1> <?php echo $page_info->title; ?> </h1>
<p> <?php echo $page_info->description; ?> </p>

<?php 
foreach ($page_contents as $content)
{
	$content_style = 'left:' . $content->x . 'px; top:' . $content->y . 'px;';
	echo '<div class="content_box" style="' . $content_style . '">';
	switch ($content->type){
		case 'text':		
			echo htmlspecialchars_decode($content->description);
			break;
		case 'image':
			echo "<img width=\"$content->width\" src=\"" . base_url() . "assets/image/$content->filename\" />";
			break;
		case 'audio':
			break;
		case 'movie':
			break;
	}
	echo '</div>';
}
?>
</div>



