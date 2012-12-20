<div id="content_wrapper">

<h1> <?php echo $page_info->title; ?> </h1>
<p> <?php echo $page_info->description; ?> </p>

<?php 
foreach ($page_contents as $content)
{
	$content_style = 'left:' . $content->x . 'px; top:' . $content->y . 'px;';
	echo '<div class="content_box" style="' . $content_style . '">';
	echo $content->description;
	echo '</div>';
}
?>
</div>



