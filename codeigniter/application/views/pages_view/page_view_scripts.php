
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.fancybox-1.3.4.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fineuploader.jquery-3.0/jquery.fineuploader-3.0.min.js"></script>
<script type="text/javascript">

// Save the base url as a a javascript variable
var base_url = "<?php echo base_url(); ?>";
var initDiagonal;
var initFontSize;

$(document).ready(function(){
	
	// as soon as the page is ready initiate all elements on the page
	initElements();
	
	// toggle the page info view
	$('#page_info_form_triger').click(function(){
		$('#page_info_form').toggleClass('hidden');
	});
	// animate color of page_info_trigger
	$('#page_info_form_triger').hover(function(){
		$(this).animate({ 'background-color': '#666' });
	}, function(){
		$(this).animate({ 'background-color': '#333' });
	});
	
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
		$("a#add_element_form_trigger").trigger('click');
		
		$('input[name="x"]').val(e.pageX);
		$('input[name="y"]').val(e.pageY);
	});
	
	// double click elements
	$('.element').dblclick(function(){
		
		// Only allow inline editing for text elements
		if( $(this).hasClass('text') )
		{
			// make content editable and disable drag
			$(this).attr('contenteditable','true');
			$(this).draggable({ disabled: true });
			
			// listen for when the user shifts focus out of the box
			$(this).bind('focusout', updateTextElementContent);
			
			// callback for focus out
			function updateTextElementContent()
			{
				// remove the event
				$(this).unbind('focusout', updateTextElementContent);
			
				// undo changes to element for editing
				$(this).removeAttr('contenteditable');
				$(this).draggable({ disabled: false });	
				
				// get the id of the container
				var link_id = $(this).attr('id');
				
				// get the  a list of all links
				var links = $(this).find('a'); 
				
				$.each( links, function( key, value ) {
  					var page_title = $(this).html();
  					$(this).replaceWith('[[' + page_title + ']]');
				});
				
				
				console.log($(this).html());
			}
		}
	});
	
	// Ajax for adding element
	$('#submit_element').click(function(e){
		e.preventDefault();
		
		var element_file = $('#element_file').get(0).files[0];
		var element_description = $('#element_description').val();
		var pages_id = $('input[name="pages_id"]').val();
		var x = $('input[name="x"]').val();
		var y = $('input[name="y"]').val();
		 
		var linkString = checkForLinks(element_description);
		console.log(linkString);
		
		// AJAX to server
		var uri = base_url + "index.php/elements/add";
		var xhr = new XMLHttpRequest();
		var fd = new FormData();

		xhr.open("POST", uri, true);
		
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && xhr.status == 200) {
				// Handle response.
				//alert(xhr.responseText); // handle response.
				location.reload();
			}
		};
		
		// check to see if a file has been selected 
		// if there is a file the text box will become the description
		// if there is no file the textbox is the content
		
		if (typeof element_file !== "undefined")
		{
			fd.append('file', element_file);
			fd.append('description', element_description);
		}else
		{
			fd.append('contents', element_description);
		}
		
		fd.append('pages_id', pages_id);
		fd.append('x', x);
		fd.append('y', y);
		if (linkString != null) fd.append('linkPageIds', linkString);
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
						'font-size'			:		page_elements_json[i].fontSize + 'px',				
						'font-family'		: 		page_elements_json[i].fontFamily,
						'height'			:		page_elements_json[i].height+'px',
						'opacity'			:		page_elements_json[i].opacity,
						'text-align'		:		page_elements_json[i].textAlign,
						'width'				:		page_elements_json[i].width+'px',
						'left'				:		page_elements_json[i].x+'px',
						'top'				:		page_elements_json[i].y+'px',
						'z-index'  			:		page_elements_json[i].z,
						'position'			:		'absolute'
					}
		
		if (page_elements_json[i].type === 'text') style.height = 'auto';

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
		$(elm).addClass(page_elements_json[i].type);
		
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
				updateElement(ui.helper[0].id , 'position');
			}
		});
		
		// *** GLOBAL VARIABLES CAUSING HAVOC WITH THIS FUNCTION
		// if the file type is not audio then add resize 
		if (page_elements_json[i].type !== 'audio')
		{
			$(elm).resizable({
				create: function(event, ui) {
					
				},
				start: function(e, ui) {
					initDiagonal = getContentDiagonal(this);
					initFontSize = parseInt($(ui.element).css("font-size"));
				},
				resize: function(e, ui) {
					var newDiagonal = getContentDiagonal(this);
					var ratio = newDiagonal / initDiagonal;
					$(this).css({"font-size" : initFontSize*ratio});
				},
				stop: function(event, ui) {
					updateElement(ui.helper[0].id, 'size');
					if ($(this).hasClass('text')) $(this).css({'height':'auto'});
				}
			});
		}		
		
		// add new element to the array
		page_elements.push(elm);
	}
	
	// add all the elements in the array to the page.
	$('body').append(page_elements);
}

function getContentDiagonal(element) {
	
    var contentWidth = $(element).width()-10;
    var contentHeight = $(element).height()-10;
    return Math.sqrt((contentWidth * contentWidth) + (contentHeight * contentHeight));
}


// ----------------------------------------------- TEXT
function initText(elm, index)
{
	//console.log( page_elements_json[index].description);
	// display the content not the description
	$(elm).append( '<span id="text-content">' + page_elements_json[index].contents + '</span>'); 
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

// Update only the changes that have been made
function updateElement(elementId, change){

	// create an object with only the id 
	var changes = {'id':elementId};
	
	// add the specific changes to the object
	switch(change)
	{
		case 'size':
			// update width and height
			changes.width = parseInt($('#' + elementId).css('width'), 10);
			changes.height = parseInt($('#' + elementId).css('height'), 10);
			// only update font size if the element type is text (found some problems with positions otherwise)
			if ($('#' + elementId).hasClass('text')) changes.fontSize = $('#' + elementId).css('font-size');
			break;
		case 'position':
			// change the x and y for left and top ( tut tut  for mixing up terminology from data base to css )
			changes.x = parseInt($('#' + elementId).css('left'), 10);
			changes.y = parseInt(	$('#' + elementId).css('top'), 10);
			break;
	}
	
	// Ajax the values to the pages controller 
	//alert('elementData=' + JSON.stringify(page_elements[$pageElementsArray])); 
	$.ajax({
		url		: base_url + 'index.php/elements/update',
		data	: changes,
		type	: 'POST',
		success	: function(data, status)
		{
			//DO NOTHING
		}
	});
}

function htmlDecode(input){
  var e = document.createElement('div');
  e.innerHTML = input;
  return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
}

function checkForLinks(text)
{
	var linkRegEx = /[a-zA-Z0-9_ ]+(?=\]\])/g;
	
	// check for matches
	var matches = text.match( linkRegEx );
	//console.log(matches);
	var matchString = "";
	
	if (matches != null)
	{
		for (var i = 0; i < matches.length; i++)
		{
			matchString+= "-" + matches[i];
		}
		return matchString;
	}else
	{
		return null;
	}
}

</script>