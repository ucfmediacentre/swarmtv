
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.fancybox-1.3.4.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fineuploader.jquery-3.0/jquery.fineuploader-3.0.min.js"></script>
<script type="text/javascript">

// Save the base url as a a javascript variable
var base_url = "<?php echo base_url(); ?>";
var initDiagonal;
var initFontSize;

$(document).ready(function(){
	
	initElements();
	
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
	$('#background').dblclick(function(e){
	
		console.log("boom");
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

// CREATE ELEMENTS ON THE PAGE
// output the elements as a json array
var page_elements_json = <?php echo json_encode($page_elements); ?>;
var page_elements = new Array();

function initElements()
{
	// Loop through all of the elements in the json array
	for (var i = 0; i < page_elements_json.length; i++)
	{
		// create the style object
		var style = { 
						'background-color'	:		page_elements_json[i].backgroundColor,
						'color'				:		page_elements_json[i].color,				
						'font-family'		: 		page_elements_json[i].fontFamily,
						'height'			:		page_elements_json[i].height+'px',
						'opacity'			:		page_elements_json[i].opacity,
						'text-align'		:		page_elements_json[i].textAlign,
						'width'				:		page_elements_json[i].width+'px',
						'left'				:		page_elements_json[i].x+'px',
						'top'				:		page_elements_json[i].y+'px',
						'z-index'  			:		page_elements_json[i].z
					}
		// create the div to contain the elements content
		var elm = $('<div>');
		
		// add the id
		$(elm).attr('id', page_elements_json[i].id);
		
		// add some sneaky data to the container
		$(elm).data('page_id', page_elements_json[i].pages_id);
		$(elm).data('license', page_elements_json[i].license);
		
		//add the style to the element and the generic class 
		$(elm).css(style);
		$(elm).addClass('element');
		
		// customise the element depending on its content type
		switch (page_elements_json[i].type)
		{
			case 'text':
				initText(elm, i);
				break;
			case 'image':
				initImage(elm, i);
				break;
			case 'audio':
				initAudio(elm, i);
				break;
			case 'video':
				initVideo(elm, i);
				break;
		}
		
		// MAKE DRAGGABLE
		$(elm).draggable({
			stop: function(event, ui) {
				updateElement();
			}
		});
		
		// *** GLOBAL VARIABLES CAUSING HAVOC WITH THIS FUNCTION
		// if the file type is not audio then add resize 
		if (page_elements_json[i].type !== 'audio')
		{
			$(elm).resizable({
				create: function(event, ui) {
					initDiagonal = getContentDiagonal($(this).attr('id'));
					initFontSize = parseInt($(this).css("font-size"));
				},
				start: function(e, ui) {
					initDiagonal = getContentDiagonal($(ui.element).attr('id'));
					initFontSize = parseInt($(ui.element).css("font-size"));
				},
				resize: function(e, ui) {

					var newDiagonal = getContentDiagonal($(ui.element).attr('id'));
					
					var ratio = newDiagonal / initDiagonal;
					$(this).css({"font-size" : initFontSize*ratio});
				},
				stop: function(event, ui) {
					updateElement($(ui.element).attr('id'));
				}
			});
		}		
		
		// add new element to the array
		page_elements.push(elm);
	}
	
	// add all the elements in the array to the page.
	$('body').append(page_elements);
}

function getContentDiagonal(elementId) {
	
    var contentWidth = $("#"+elementId).width()-10;
    var contentHeight = $("#"+elementId).height()-10;
    return Math.sqrt((contentWidth * contentWidth) + (contentHeight * contentHeight));
}


// ----------------------------------------------- TEXT
function initText(elm, index)
{
	$(elm).html( unescape(page_elements_json[index].contents)); 
}

// ----------------------------------------------- IMAGE
function initImage(elm, index)
{
	$(elm).html('<img width="100%" height="100%" src="' + base_url + 'assets/image/' + page_elements_json[index].filename + '" />');
}

// ----------------------------------------------- AUDIO
function initAudio(elm, index)
{
	var audio_element = $('<audio controls><source src="' + base_url + 'assets/audio/' + page_elements_json[index].filename + '" type="audio/mpeg"></audio>');
	$(elm).append(audio_element);
}
// ----------------------------------------------- MOVIE
function initVideo(elm, index)
{
	var video_element = $('<video width="100%" height="100%" controls><source src="' + base_url + 'assets/video/' + page_elements_json[index].filename + '" type="video/mp4"></video>');
	$(elm).append(video_element);
}


function updateElement(elementId){
  	console.log("boom");
}


</script>