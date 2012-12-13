<div id="content_wrapper">

<h1> <?php echo $page_info->title; ?> </h1>
<p> <?php echo $page_info->description; ?> </p>

<?php 
foreach ($page_contents->result() as $content)
{
	echo $content->description . '<br />';
} 
?>

<a id="inline" href="#data">This shows content of element who has id="data"</a>

<div style="display:none"><div id="data">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div></div>
</div>



