<script type="text/javascript">

var base_url = "<?php echo base_url(); ?>";

$(document).ready(function(){
	
	console.log('jquery worked');

	$('#page_info_submit').click(function(e){
		
		e.preventDefault();		
		
		var idVal = $('input[name="id"]').val();
		console.log();
		var descriptionVal = $('textarea[name="description"]').val();
        console.log(descriptionVal);
        var keywordsVal = $('input[name="keywords"]').val();
        
        var publicVal = $('input[name="public"]').val();
		
		//var form_values = { id: idVal , description: descriptionVal, keywords: keywordsVal, public:publicVal }; 
		 
		$.post(base_url + "index.php/pages/update", { id: "3" , description: descriptionVal, keywords: "dfa adsf asdfasd", public:"1" },
   			function(data) {
     		alert("Data Loaded: " + data);
   		});
		
	});

});

</script>