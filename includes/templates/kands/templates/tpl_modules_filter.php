<script src="includes/modules/pages/index/nouislider.min.js" type="text/javascript"></script>
<script src="includes/modules/pages/index/wNumb.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="includes/modules/pages/index/nouislider.css">
<link rel="stylesheet" type="text/css" href="includes/modules/pages/index/nouislider.tooltips.css">
<link rel="stylesheet" type="text/css" href="includes/modules/pages/index/nouislider.pips.css">
<script type="text/javascript">
 var currentColoursSelected = "<?php echo $_SESSION['OptionFilter']->get_colour_string(); ?>";
 var bulbmin = "<?php echo $_SESSION['OptionFilter']->get_filter_options_on(BULB_QTY_MIN); ?>";
 var bulbmax = "<?php echo $_SESSION['OptionFilter']->get_filter_options_on(BULB_QTY_MAX); ?>";

 $(document).ready(function(){
		var bulbSlider = document.getElementById('bulbslider');
		noUiSlider.create(bulbSlider, {
			start: [1,18 ],
			step: 1,
			connect: true,
			tooltips: [  wNumb({ decimals: 0 }), true ],
			format: {
			from: function(value) {
							return  parseInt(value);
					},
			to: function(value) {
							if(value==18)return "All";
							return parseInt(value);
					}
			},
			range: {
				'min':   1 ,
				'10%': [2,2],
				'20%': [3,3],
				'30%': [4,4],
				'40%': [5,5],
				'50%': [6,6],
				'60%': [8,8],
				'70%': [10,10],
				'80%': [12,12],
				'90%': [15,15],
				'max': 18 
			},
			pips: {
				mode: 'positions',
			orientation: 'horizontal',
		values: [0,10,20,30,40,50,60,70,80,90,100],
		density: 10
			}
			//range: {'min':[1], 'max':[18]}
		});
 
		bulbSlider.noUiSlider.on('change', function(){
				var v = bulbSlider.noUiSlider.get();
				$('#bmin').val(v[0]);
				$('#bmax').val(v[1]);
		});
		 
		bulbSlider.noUiSlider.set([bulbmin,bulbmax]);
 });
 
</script>
<?php
$style_array = array();
$colour_array = array();

$sel_val_style = $_SESSION['OptionFilter']->get_options($style_array, 'Style');
$sel_val_colour = $_SESSION['OptionFilter']->get_options($colour_array, 'Colour');


$filter_box_content .= '<div class="filterContainer" id="filterBox">
<div class="filterHead">Filter your search</div>';

		$filter_box_content .= zen_draw_form('products_filter', zen_href_link(FILENAME_DEFAULT, zen_get_all_get_params(array('action'))), 'get', 'onsubmit(alert($(.filter2).val();))' );
		$filter_box_content .= zen_draw_hidden_field('main_page', FILENAME_DEFAULT);
		$filter_box_content .= zen_hide_session_id();
		$filter_box_content .= zen_draw_hidden_field('cPath', $cPath);
		
		if(is_array($style_array)){
		$filter_box_content .= ' By Style<br>' . zen_draw_pull_down_menu('f1',$style_array, $sel_val_style, 'class="filter1" data-placeholder="Select a Style"');
		}
		
		
		if($_SESSION['department']==1){
			//$filter_box_content .= '<div class="bulbsliderWraper"><span id="bulbsliderlable">By number of lights/bulbs</span><br><div id="bulbslider"></div></div>' . '<br class="clearBoth"><br>';
			$filter_box_content .= '<br><br>';
		}else{
			$filter_box_content .= '<br><br>';
		}
		$filter_box_content .= zen_draw_hidden_field('bmin', $_SESSION['OptionFilter']->get_filter_options_on(BULB_QTY_MIN), 'id="bmin"');
		$filter_box_content .= zen_draw_hidden_field('bmax', $_SESSION['OptionFilter']->get_filter_options_on(BULB_QTY_MAX), 'id="bmax"');
				
		if(is_array($colour_array)){
			$filter_box_content .= ' By Colour ' . zen_draw_pull_down_menu('f2', $colour_array, $sel_val_colour, 'id="filter2" class="filter2" multiple data-placeholder="Select up to 4 colours"');
			$filter_box_content .= zen_draw_hidden_field('f2v', $_SESSION['OptionFilter']->get_colour_string(), 'id="f2v"');
		}

		$filter_box_content .= '<br><br><input type="submit" name="fap" value="Apply Filter"</>';
		$filter_box_content .= '</form>';

		$filter_box_content .= '</div>';

if(is_array($style_array) || is_array($colour_array))		
	echo $filter_box_content;
?>