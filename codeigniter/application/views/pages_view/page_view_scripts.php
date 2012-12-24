
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
	
	//iterate through divs on the page and instantiate them
	$('.element').each(function() {
		//sort out which function needs to fill the elements
		switch ($(this).attr('type')){
    		case "text":
    			//ask for div id
    			var $id = $(this).attr('id');
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
				}).resizable({
					stop: function(event, ui) {
						updateElementProperties($(this).attr('id'));
					}
				});
    			break;
    		case "image":
    			var $id = $(this).attr('id');
    			for (var i=0;i<page_elements.length;i++)
				{
					if(page_elements[i].id == $id){
						injectImageElements(i,$id);
					}
				}
    			break;
			case "audio":
				break;
			case "movie":
				break;
    	}
 	}); 
});

var page_elements = $.parseJSON('<?php echo json_encode($page_elements); ?>');

function injectTextElements(pageElementsArray, elementId){
	$('#'+elementId).css('backgroundColor', page_elements[pageElementsArray].backgroundColor);
	$('#'+elementId).css('color', page_elements[pageElementsArray].color);
	$('#'+elementId).text(page_elements[pageElementsArray].contents);
	$('#'+elementId).css('font-family', page_elements[pageElementsArray].fontFamily);
	$('#'+elementId).css('font-size', page_elements[pageElementsArray].fontSize+'px');
	$('#'+elementId).css('height', page_elements[pageElementsArray].height+'px');
	$('#'+elementId).attr('license', page_elements[pageElementsArray].license);
	$('#'+elementId).css('opacity', page_elements[pageElementsArray].opacity);
	$('#'+elementId).css('position', 'absolute');
	$('#'+elementId).attr('pages_id', page_elements[pageElementsArray].pages_id);
	$('#'+elementId).css('text-align', page_elements[pageElementsArray].textAlign);
	$('#'+elementId).css('width', page_elements[pageElementsArray].width+'px');
	$('#'+elementId).css('left', page_elements[pageElementsArray].x+'px');
	$('#'+elementId).css('top', page_elements[pageElementsArray].y+'px');
	$('#'+elementId).css('z-index', page_elements[pageElementsArray].z);
}

function injectImageElements(pageElementsArray, elementId){
	$('#'+elementId).html('<img src="'+base_url+'assets/image/'+page_elements[pageElementsArray].filename+'" />');
	$('#'+elementId).attr('attribution', page_elements[pageElementsArray].attribution);
	$('#'+elementId).children().attr('alt', page_elements[pageElementsArray].description);
	$('#'+elementId).css('height', page_elements[pageElementsArray].height+'px');
	$('#'+elementId).children().attr('height', page_elements[pageElementsArray].height+'px');
	$('#'+elementId).attr('keywords', page_elements[pageElementsArray].keywords);
	$('#'+elementId).attr('license', page_elements[pageElementsArray].license);
	$('#'+elementId).css('opacity', page_elements[pageElementsArray].opacity);
	$('#'+elementId).css('position', 'absolute');
	$('#'+elementId).attr('pages_id', page_elements[pageElementsArray].pages_id);
	$('#'+elementId).css('width', page_elements[pageElementsArray].width+'px');
	$('#'+elementId).children().attr('width', page_elements[pageElementsArray].width+'px');
	$('#'+elementId).css('left', page_elements[pageElementsArray].x+'px');
	$('#'+elementId).css('top', page_elements[pageElementsArray].y+'px');
	$('#'+elementId).css('z-index', page_elements[pageElementsArray].z);
}
						
/*function injectElementsProperties(pageElementsArray, elementId){
	$('#'+elementId).attr('attribution', page_elements[pageElementsArray].attribution);
	$('#'+elementId).css('backgroundColor', page_elements[pageElementsArray].backgroundColor);
	$('#'+elementId).css('color', page_elements[pageElementsArray].color);
	$('#'+elementId).text(page_elements[pageElementsArray].contents);
	$('#'+elementId).attr('description', page_elements[pageElementsArray].description);
	$('#'+elementId).attr('filename', page_elements[pageElementsArray].filename);
	$('#'+elementId).css('font-family', page_elements[pageElementsArray].fontFamily);
	$('#'+elementId).css('font-size', page_elements[pageElementsArray].fontSize+'px');
	$('#'+elementId).css('height', page_elements[pageElementsArray].height+'px');
	$('#'+elementId).attr('id', page_elements[pageElementsArray].id);
	$('#'+elementId).attr('keywords', page_elements[pageElementsArray].keywords);
	$('#'+elementId).attr('license', page_elements[pageElementsArray].license);
	$('#'+elementId).css('opacity', page_elements[pageElementsArray].opacity);
	$('#'+elementId).css('position', 'absolute');
	$('#'+elementId).attr('pages_id', page_elements[pageElementsArray].pages_id);
	$('#'+elementId).css('text-align', page_elements[pageElementsArray].textAlign);
	$('#'+elementId).attr('timeline', page_elements[pageElementsArray].timeline);
	$('#'+elementId).attr('type', page_elements[pageElementsArray].type);
	$('#'+elementId).css('width', page_elements[pageElementsArray].width+'px');
	$('#'+elementId).css('left', page_elements[pageElementsArray].x+'px');
	$('#'+elementId).css('top', page_elements[pageElementsArray].y+'px');
	$('#'+elementId).css('z-index', page_elements[pageElementsArray].z);
}*/


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