<div id="page_info_form_wrap">

	<form action="#" method="get" enctype="multipart/form-data">
		
		<input type="hidden" value="<?php echo $page_info->id; ?>" name="id" />
		
		<label for="description"> Description: </label>
        <textarea name="description"> </textarea>
        
        <label for="keywords"> Keywords: </label>
        <textarea name="keywords"> </textarea>
        
        <select name="public">
          <option value="1">public</option>
          <option value="0">private</option>
         </select>
         
         <input type="submit" value="Submit" id="page_info_submit"/>
    </form>

</div>