$(document).ready(function(){
	$(".filter1").chosen({
			disable_search_threshold: 10,
			allow_single_deselect: true,
			no_results_text: "Oops, nothing found!",
			width: "40%"
		});	
		
	$(".filter2").chosen({
			disable_search_threshold: 10,
			no_results_text: "Oops, nothing found!",
			width: "100%"
		});  
	
	$('.filter2').on('change', function(evt, params) {
		if(params.selected != undefined){
			var v=$("#f2v").val();
			if(v==""){
				v += params.selected;
			}else{
				v += "," + params.selected;
			}
			$("#f2v").val(v);
		}
		else if(params.deselected != undefined){
			 var v=$("#f2v").val();
			if(v==""){
				return
			}else{
				a = v.split(",");
				a = jQuery.grep(a,function(value){
				return value != params.deselected;
				});
			}
			$("#f2v").val(a.join(','));
		}
	});
	
	a= currentColoursSelected.split(",");
	$('.filter2').val(a).trigger('chosen:updated');
})

	//$(".filter2").chosen().change(alert("EE"));
