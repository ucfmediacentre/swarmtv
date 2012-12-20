<a class="hidden" id="add_content_form_trigger" href="#add_content_form"> HACK FOR FANCYBOX IMPLEMENTATION </a>

<!-- omni-content box: will detect exactly what the user wants to do instead of forcing them to make so many choices -->
<div class="hidden">
	<div id="add_content_form_wrapper">
		<form id="add_content_form" >
			<h2> Add Content </h2>	
			<p id="content_file_info"> </p>
			<label for="content_file">Choose a file:</label>
			<input type="file" name="content_file" id="content_file" /><br />
			
			<label for="content_description">Add some text:</label><br />
			<textarea id="content_description" > </textarea> <br />
			
			<input type="submit" id="submit_content" value="Submit" /> 
			
			<!-- hidden values -->
			<input type="hidden" name="pages_id" value="<?php echo $page_info->id; ?>"/>
			<input type="hidden" name="x" value="400"/>
			<input type="hidden" name="y" value="400"/>
		</form>
	</div>
</div>
