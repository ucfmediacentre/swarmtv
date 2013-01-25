<div id="page_info_form_wrap">
	
	
	<form action="#" method="get" enctype="multipart/form-data" class="hidden">
		
		<input type="hidden" value="<?php echo $page_info->id; ?>" name="id" />
		
		<label for="description"> Description: </label>
        <textarea name="description"><?php echo $page_info->description; ?> </textarea>
        
        <label for="keywords"> Keywords: </label>
        <textarea name="keywords"><?php echo $page_info->keywords; ?> </textarea>
        
        <select name="public">
          <option value="1" <?php if($page_info->public == "1") echo 'selected="true"'; ?>>public</option>
          <option value="0" <?php if($page_info->public == "0") echo 'selected="true"'; ?>>private</option>
         </select>
         
         <input type="submit" value="Submit" id="page_info_submit"/>
    </form>

</div>