<a class="hidden" id="content_info_form_trigger" href="#content_info_form"> HACK FOR FANCYBOX IMPLEMENTATION </a>

<div class="hidden">
	<div id="content_info_form">
		<div class="tabs">
			<div class="tab">
			   <input type="radio" id="tab_1" name="tab_group_1" checked>
			   <label for="tab_1">Text</label>
			   
			   <!--<div class="content">
				   <textarea id="content_text"></textarea>
			   </div>-->
			   <div class="content">
				   <textarea id="content_text" name="content_text"></textarea>
				   <input type="hidden" value="<?php echo $page_info->id; ?>" name="page_id" />
				   <input type="hidden" value="<?php echo $page_info->id; ?>" name="content_id" />
			       <input type="button" value="Update" id="content_text_submit"/>
			   </div>
		   </div>
			
		   <div class="tab">
			   <input type="radio" id="tab_2" name="tab_group_1">
			   <label for="tab_2">Images</label>
			   
			   <div class="content">
				   <div id="image_uploader"></div>
						<div id="trigger_image_uploader" class="btn btn-primary" style="margin-top: 10px;">
						  	<i class="icon-upload icon-white"></i> Upload now
						</div>
			   </div> 
		   </div>
			
			<div class="tab">
			   <input type="radio" id="tab_3" name="tab_group_1">
			   <label for="tab_3">Audio</label>
			 
			   <div class="content">
				   stuff
			   </div> 
		   </div>
		   
		   <div class="tab">
			   <input type="radio" id="tab_4" name="tab_group_1">
			   <label for="tab_4">Movies</label>
			 
			   <div class="content">
				   stuff
			   </div> 
		   </div>
	
			<div class="tab">
			   <input type="radio" id="tab_5" name="tab_group_1">
			   <label for="tab_5">Upload</label>
			 
			   <div class="content">
				   stuff
			   </div> 
		   </div>
		</div>
	</div>
</div>
