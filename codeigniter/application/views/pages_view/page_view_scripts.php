
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
	
	
	// ------------------------------------------------------------------------- ELEMENTS LOOP
	//iterate through divs on the page and instantiate them
	$('.element').each(function() {
		//sort out which function needs to fill the elements
		switch ($(this).attr('type')){
    		case "text":
    			//ask for div id
    			var $id = $(this).attr('id');
    			//var initFontSize = $(this).css("font-size");
    			for (var i=0;i<page_elements.length;i++)
				{
					if(page_elements[i].id == $id){
						injectTextElements(i,$id);
					}
				}
				//make each text element draggable and linked to the database
				$(this).draggable({
					stop: function(event, ui) {
						updateElementProperties($(this).attr('id'));
					}
				});
				
				//make each text element resizable and linked to the database
				$(this).resizable({
					create: function(event, ui) {
						initDiagonal = getContentDiagonal($(this).attr('id'));
						initFontSize = parseInt($(this).css("font-size"));
					},
					resize: function(e, ui) {
						var newDiagonal = getContentDiagonal($(this).attr('id'));
						var ratio = newDiagonal / initDiagonal;
						$(this).css({"font-size" : initFontSize*ratio});
					},
					stop: function(event, ui) {
						updateElementProperties($(this).attr('id'));
					}
				});
				
				
				//var newDiagonal = getContentDiagonal(this);
				//var ratio = newDiagonal / initDiagonal;
				//$(this).css("font-size", initFontSize + ratio * 3);

				
    			break;
    		case "image":
    			var $id = $(this).attr('id');
    			for (var i=0;i<page_elements.length;i++)
				{
					if(page_elements[i].id == $id){
						injectImageElements(i,$id);
					}
				}
				
				//make each image element draggable and linked to the database
				$(this).draggable({
					stop: function(event, ui) {
						updateElementProperties($(this).attr('id'));
					}
				});
				
				//make each image element resizable and linked to the database
				$(this).resizable({
					alsoResize: $(this).children(),
					
					stop: function(event, ui) {
						updateElementProperties($(this).attr('id'));
					}
				});
				
    			break;
			case "audio":
				break;
			case "movie":
				break;
    	}
 	}); 
});

// output the elements as a json array
var page_elements_json = <?php echo json_encode($page_elements); ?>;
var page_elements = new Array();

function initElements()
{
	for (var i = 0; i < page_elements_json.length; i++)
	{
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

		var elm = $('<div>');
		
		$(elm).attr('id', page_elements_json[i].id);
		
		$(elm).data('page_id', page_elements_json[i].pages_id);
		$(elm).data('license', page_elements_json[i].license);
		
		$(elm).css(style);
		$(elm).addClass('element');
		
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
				updateElementProperties($(this).attr('id'));
			}
		});
		
		// if the file type is not audio then add resize 
		if (page_elements_json[i].type !== 'audio')
		{
			$(elm).resizable({
				create: function(event, ui) {
					initDiagonal = getContentDiagonal($(this).attr('id'));
					initFontSize = parseInt($(this).css("font-size"));
				},
				resize: function(e, ui) {
					var newDiagonal = getContentDiagonal($(this).attr('id'));
					var ratio = newDiagonal / initDiagonal;
					$(this).css({"font-size" : initFontSize*ratio});
				},
				stop: function(event, ui) {
					//updateElementProperties($(this).attr('id'));
				}
			});
		}		
		
		page_elements.push(elm);
	}
	
	$('body').append(page_elements);
}

function getContentDiagonal(elementId) {
	console.log("diagonal");
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


function updateElementProperties(elementId){
  	var $elementType = $('#'+elementId).attr('type');
  	var $pageElementsArray
  	for (var i=0;i<page_elements.length;i++)
		{
			if(page_elements[i].id == elementId){
				$pageElementsArray = i;
			}
		}
	//alert(page_elements[$pageElementsArray].contents);
	switch ($elementType){
		case "text":
			page_elements[$pageElementsArray].attribution = "";
			page_elements[$pageElementsArray].backgroundColor = $('#'+elementId).css('backgroundColor');
			page_elements[$pageElementsArray].color = $('#'+elementId).css('color');
			page_elements[$pageElementsArray].contents = $('#'+elementId).text();
			page_elements[$pageElementsArray].description = "";
			page_elements[$pageElementsArray].filename = "";
			page_elements[$pageElementsArray].fontFamily = $('#'+elementId).css('fontFamily');
			page_elements[$pageElementsArray].fontSize = $('#'+elementId).css('font-size');
			page_elements[$pageElementsArray].height = $('#'+elementId).css('height');
			page_elements[$pageElementsArray].id = elementId;
			page_elements[$pageElementsArray].keywords = "";
			page_elements[$pageElementsArray].license = "";
			page_elements[$pageElementsArray].opacity = $('#'+elementId).css('opacity');
			page_elements[$pageElementsArray].pages_id = $('#'+elementId).attr('pages_id');
			page_elements[$pageElementsArray].textAlign = $('#'+elementId).css('text-align');
			page_elements[$pageElementsArray].timeline = "";
			page_elements[$pageElementsArray].type = $('#'+elementId).attr('type');
			page_elements[$pageElementsArray].width = $('#'+elementId).css('width');
			page_elements[$pageElementsArray].x = $('#'+elementId).css('left');
			page_elements[$pageElementsArray].y = $('#'+elementId).css('top');
			page_elements[$pageElementsArray].z = $('#'+elementId).css('z-index');
			break;
		case "image":
			page_elements[$pageElementsArray].attribution = $('#'+elementId).attr('attribution');
			page_elements[$pageElementsArray].backgroundColor = "";
			page_elements[$pageElementsArray].color = "";
			page_elements[$pageElementsArray].contents = "";
			page_elements[$pageElementsArray].description = $('#'+elementId).children().attr('alt');
			$url = $('#'+elementId).children().attr('src');
			$positionOfSlash = $url.lastIndexOf("/");
			$urlLength = $url.length;
			$filename = $url.slice($positionOfSlash+1,$urlLength); 
			page_elements[$pageElementsArray].filename = $filename;			
			page_elements[$pageElementsArray].fontFamily = "";
			page_elements[$pageElementsArray].fontSize = "";
			page_elements[$pageElementsArray].height = $('#'+elementId).css('height');
			page_elements[$pageElementsArray].id = elementId;
			page_elements[$pageElementsArray].keywords = $('#'+elementId).attr('keywords');
			page_elements[$pageElementsArray].license = $('#'+elementId).attr('license');
			page_elements[$pageElementsArray].opacity = $('#'+elementId).css('opacity');
			page_elements[$pageElementsArray].pages_id = $('#'+elementId).attr('pages_id');
			page_elements[$pageElementsArray].textAlign = $('#'+elementId).css('text-align');
			page_elements[$pageElementsArray].timeline = "";
			page_elements[$pageElementsArray].type = $('#'+elementId).attr('type');
			page_elements[$pageElementsArray].width = $('#'+elementId).css('width');
			page_elements[$pageElementsArray].x = $('#'+elementId).css('left');
			page_elements[$pageElementsArray].y = $('#'+elementId).css('top');
			page_elements[$pageElementsArray].z = $('#'+elementId).css('z-index');
			break;
	}
	
	// Ajax the values to the pages controller 
	//alert('elementData=' + JSON.stringify(page_elements[$pageElementsArray])); 
	$.ajax({
		url: base_url + 'index.php/pages/updateElement',
		data: 'elementData=' + JSON.stringify(page_elements[$pageElementsArray]),
		type: 'POST',
		success: function(data, status)
		{
			//alert("Returned data = "+data);
		}
	});
}


</script>