<!-- import all the libraries -->
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.fancybox-1.3.4.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fineuploader.jquery-3.0/jquery.fineuploader-3.0.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="<?php echo base_url(); ?>js/vendor/jquery.ui.touch-punch.min.js"></script>

<script type="text/javascript">

// Save the base url as a javascript variable
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
		//Distinguish between doubleclicks on different elements
		switch ($(this).attr('type')=="title") {
			case "title":
				$("a#page_info_form_trigger").trigger('click');
				break;
			case "text":
				$("a#content_info_form_trigger").trigger('click');
				break;
			case "image":
				break;
			case "audio":
				break;
			case "movie":
				break;
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

var initDiagonal;
var initFontSize;

$('.element').each(function(){
	//alert($(this).attr('type'));
    switch ($(this).attr('type')) {
    	case "title":
    		break;
		case "text":
			$(this).draggable().resizable();
			
			/*$(this).draggable().resizable({
				create: function(event, ui) {
					initDiagonal = getContentDiagonal(this);
					initFontSize = parseInt($(this).css("font-size"));
				},
				resize: function(e, ui) {
					var newDiagonal = getContentDiagonal(this);
					var ratio = newDiagonal / initDiagonal;
					
					$(this).css("font-size", initFontSize + ratio * 3);
				}
			});
			
			function getContentDiagonal(this) {
				var contentWidth = $(this).width();
				var contentHeight = $(this).height();
				return Math.sqrt(contentWidth * contentWidth + contentHeight * contentHeight);
			}*/
			break;
    case "image":
    	$(this).draggable().resizable({ alsoResize: $(this).children() });
    	break;
    }
    /*case "audio":
    	break;
    case "movie:
    	break;
    }*/
});
                                                                                                                                      
var initDiagonal;
var initFontSize;

$(function() {
    $("#resizable").resizable({
        create: function(event, ui) {
            initDiagonal = getContentDiagonal();
            initFontSize = parseInt($("#resizable").css("font-size"));
        },
        resize: function(e, ui) {
            var newDiagonal = getContentDiagonal();
            var ratio = newDiagonal / initDiagonal;
            
            $("#resizable").css({"font-size" : initFontSize*ratio*0.75});
        }
    });
});

function getContentDiagonal() {
    var contentWidth = $("#resizable").width();
    var contentHeight = $("#resizable").height();
    return Math.sqrt(contentWidth * contentWidth + contentHeight * contentHeight);
}

</script>