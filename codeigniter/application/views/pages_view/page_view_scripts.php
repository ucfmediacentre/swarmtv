<!-- import all the libraries -->
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.fancybox-1.3.4.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fineuploader.jquery-3.0/jquery.fineuploader-3.0.min.js"></script>

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
	$("a#content_info_form_trigger").fancybox({
		'overlayOpacity':0,
		'autoDimensions':true,
		'showCloseButton':false,
	});
	
	$("a#page_info_form_trigger").fancybox({
		'overlayOpacity':0,
		'autoDimensions':true,
		'showCloseButton':false,
	});
	
	// trigger the fancy box on double click
	$('.element').dblclick(function(){
		//Distinguish between a doubleclick on the title and a doubleclick on an element
		if($(this).attr('type')=="title"){
			$("a#page_info_form_trigger").trigger('click');
		} else {
			$("a#content_info_form_trigger").trigger('click');
		}
		
	});
	
	// init FineUploader 
	// http://fineuploader.com/fine-uploader-basic-demo.html
	// *** IMAGE ***
	
	var imageUploader = new qq.FineUploader({
      	element: $('#image_uploader')[0],
      	request: {
        	endpoint: base_url + 'index.php/pages/upload_image', // UPDATE THIS LINK
        	params: {'name': 'Alcwyn'}
      	},
      	validation: {
        	allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
        	sizeLimit: 10204800 // 200 kB = 200 * 1024 bytes
      	},
      	autoUpload: false,
      	text: {
        	uploadButton: '<i class="icon-plus icon-white"></i> Select Files'
      	},
      	callbacks: {
        	onSubmit: function(id, fileName) {
          		$messages.append('<div id="file-' + id + '" class="alert" style="margin: 20px 0 0"></div>');
        	},
        	onComplete: function(id, fileName, responseJSON) {
          		if (responseJSON.success) {
            		console.log("Image upload success: " + responseJSON.name);
          		} else {
            		console.log("Image upload failed");
          		}
        	}
        },
        debug: true
    });
 
    $('#trigger_image_uploader').click(function() {
      imageUploader.uploadStoredFiles();
    });
});

</script>