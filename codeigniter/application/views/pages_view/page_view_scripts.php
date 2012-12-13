<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.fancybox-1.3.4.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/fancybox/jquery.easing-1.3.pack.js"></script>

<script type="text/javascript">

var base_url = "<?php echo base_url(); ?>";

$(document).ready(function(){
	
	console.log('jquery worked');

	$('#page_info_submit').click(function(e){
		
		e.preventDefault();		
		
		var idVal = $('input[name="id"]').val();
		
		var descriptionVal = $('textarea[name="description"]').val();
        
        var keywordsVal = $('textarea[name="keywords"]').val();
        
        var publicVal = $('select[name="public"]').val();
		 
		$.post(base_url + "index.php/pages/update", { id: idVal , description: descriptionVal, keywords: keywordsVal, public:publicVal },
   			function(data) {
     		alert("Data Loaded: " + data);
   		});
		
	});
	
	$('#content_wrapper').dblclick(function(){
		
			
	});
	
	$("a#inline").fancybox({
		'hideOnContentClick': true
	});
});

</script>