<div id="page_info_form_wrap">

	<form action="#" method="get" enctype="multipart/form-data">
		
		<input type="hidden" value="<?php echo $page_info->id; ?>" name="id" />
		<input type="hidden" value="200" name="x" />
		<input type="hidden" value="100" name="y" />
		<label for="content"> Content: </label>
        <textarea name="content"><?php echo $content_info->content; ?> </textarea>
        
        <input type="submit" value="Submit" id="content_submit"/>
    </form>

</div>