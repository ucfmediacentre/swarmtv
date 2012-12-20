// import all the libraries
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.fancybox-1.3.4.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript">

// Save the base url as a a javascript variable
var base_url = "<?php echo base_url(); ?>";

$(document).ready(function(){
	
	// Ajax submit for updating page info 
	$('#page_info_submit').click(function(e){
		// Stop the page from navigating away from this page
		e.preventDefault();		
		
		// get the values from the form
		var idVal = $('input[name="id"]').val();
		var descriptionVal = $('textarea[name="description"]').val();
        var keywordsVal = $('textarea[name="keywords"]').val();
        var publicVal = $('select[name="public"]').val();
		
		// Post the values to the pages controller  
		$.post(base_url + "index.php/pages/update", { id: idVal , description: descriptionVal, keywords: keywordsVal, public:publicVal },
   			function(data) {
   			// User feed back
     		alert("Data Loaded: " + data);
   		});
	});
	
	// init fancy box
	$("a#add_content_form_trigger").fancybox({
		'overlayOpacity':0,
		'autoDimensions':true,
		'showCloseButton':false,
	});
	
	// trigger the fancy box on double click
	$('#content_wrapper').dblclick(function(){
		$("a#add_content_form_trigger").trigger('click');
	});
	
	// Ajax for adding content
	$('#submit_content').click(function(e){
		e.preventDefault();
		
		var content_file = $('#content_file').get(0).files[0];
		var content_description = $('#content_description').val();
		var pages_id = $('input[name="pages_id"]').val();
		
		// AJAX to server
		var uri = base_url + "index.php/contents/add";
		var xhr = new XMLHttpRequest();
		var fd = new FormData();
		 
		xhr.open("POST", uri, true);
		
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && xhr.status == 200) {
				// Handle response.
				alert(xhr.responseText); // handle response.
			}
		};
		
		// check to see if a file has been selected 
		if (typeof content_file !== "undefined") fd.append('file', content_file);
		
		fd.append('description', content_description);
		fd.append('pages_id', pages_id);
		// Initiate a multipart/form-data upload
		xhr.send(fd);
        
	});
	
	// update preview is file is selected
	$('#content_file').change(function(){
		
		// check to see if a file has been selected
		var content_file = $('#content_file').get(0).files[0];
		if (typeof content_file !== "undefined") 
		{	
			$('#content_file_info').empty();
			
			var imageType = /image.*/;
     
    		if (content_file.type.match(imageType)) {
				
				// create thumbnail
				var img = document.createElement("img");
				img.classList.add("thumbnail");
				img.file = content_file;
				img.width = 100;
				$('#content_file_info').append(img);
				
				// load in image data 
				var reader = new FileReader();
				reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
				reader.readAsDataURL(content_file);
			}	
			
			var info = '<br />Name: ' + content_file.name + "<br /> Size: " + content_file.size + " bytes";
			$('#content_file_info').append(info); 	
		}
	});
});

</script>