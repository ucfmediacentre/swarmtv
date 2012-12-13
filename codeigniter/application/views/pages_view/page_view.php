<div id="content_wrapper">

<h1> <?php echo $page_info->title; ?> </h1>
<p> <?php echo $page_info->description; ?> </p>

<?php 
foreach ($page_contents->result() as $content)
{
	echo $content->description . '<br />';
} 
?>
</div>


