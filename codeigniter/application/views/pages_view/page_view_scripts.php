
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.fancybox-1.3.4.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fineuploader.jquery-3.0/jquery.fineuploader-3.0.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="<?php echo base_url(); ?>js/vendor/jquery.ui.touch-punch.min.js"></script>
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
	$("a#add_element_form_trigger").fancybox({
		'overlayOpacity':0,
		'autoDimensions':true,
		'showCloseButton':false,
	});
	
	// trigger the fancy box on double click
	$('#element_wrapper').dblclick(function(e){
		$("a#add_element_form_trigger").trigger('click');
		
		$('input[name="x"]').val(e.pageX);
		$('input[name="y"]').val(e.pageY);
	});
	
	// Ajax for adding element
	$('#submit_element').click(function(e){
		e.preventDefault();
		
		var element_file = $('#element_file').get(0).files[0];
		var element_description = $('#element_description').val();
		var pages_id = $('input[name="pages_id"]').val();
		var x = $('input[name="x"]').val();
		var y = $('input[name="y"]').val();
		
		// AJAX to server
		var uri = base_url + "index.php/elements/add";
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
		if (typeof element_file !== "undefined") fd.append('file', element_file);
		
		fd.append('description', element_description);
		fd.append('pages_id', pages_id);
		fd.append('x', x);
		fd.append('y', y);
		// Initiate a multipart/form-data upload
		xhr.send(fd);
        
	});
	
	// update preview is file is selected
	$('#element_file').change(function(){
		
		// check to see if a file has been selected
		var element_file = $('#element_file').get(0).files[0];
		if (typeof element_file !== "undefined") 
		{	
			$('#element_file_info').empty();
			
			var imageType = /image.*/;
     
    		if (element_file.type.match(imageType)) {
				
				// create thumbnail
				var img = document.createElement("img");
				img.classList.add("thumbnail");
				img.file = element_file;
				img.width = 100;
				$('#element_file_info').append(img);
				
				// load in image data 
				var reader = new FileReader();
				reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
				reader.readAsDataURL(element_file);
			}	
			
			var info = '<br />Name: ' + element_file.name + "<br /> Size: " + element_file.size + " bytes";
			$('#element_file_info').append(info); 	
		}
	});
});

</script>