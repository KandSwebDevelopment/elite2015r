<script src="includes/modules/pages/index/nouislider.min.js" type="text/javascript"></script>
<script src="includes/modules/pages/index/wNumb.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="includes/modules/pages/index/nouislider.css">
<link rel="stylesheet" type="text/css" href="includes/modules/pages/index/nouislider.tooltips.css">
<script type="text/javascript">
 var currentColoursSelected = "<?php echo $_SESSION['OptionFilter']->get_colour_string(); ?>";
 

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
			}
			//range: {'min':[1], 'max':[18]}
		});
 });
</script>
<?php
$dd_array = array();

$sel_val = $_SESSION['OptionFilter']->get_options($dd_array, 'Style');


$filter_box_content .= '<div class="filterContainer" id="filterBox">
<div class="filterHead">Filter your search</div>';

		$filter_box_content .= zen_draw_form('products_filter', zen_href_link(FILENAME_DEFAULT, zen_get_all_get_params(array('action'))), 'get', 'onsubmit(alert($(.filter2).val();))' );
		$filter_box_content .= zen_draw_hidden_field('main_page', FILENAME_DEFAULT);
		$filter_box_content .= zen_hide_session_id();
		$filter_box_content .= zen_draw_hidden_field('cPath', $cPath);

		$filter_box_content .= ' By Style<br>' . zen_draw_pull_down_menu('f1',$dd_array, $sel_val, 'class="filter1" data-placeholder="Select a Style"');

		$filter_box_content .= '<div class="bulbsliderWraper"><span id="bulbsliderlable">By number of lights/bulbs</span><br><div id="bulbslider"></div></div>' . '<br class="clearBoth"><br>';
		
		$sel_val = $_SESSION['OptionFilter']->get_options($dd_array, 'Colour');

		$filter_box_content .= ' By Colour ' . zen_draw_pull_down_menu('f2', $dd_array, $sel_val, 'id="filter2" class="filter2" multiple data-placeholder="Select up to 4 colours"');
		$filter_box_content .= zen_draw_hidden_field('f2v', $_SESSION['OptionFilter']->get_colour_string(), 'id="f2v"');

		$filter_box_content .= '<br><br><input type="submit" name="fap" value="Apply Filter"</>';
		$filter_box_content .= '</form>';

		$filter_box_content .= '</div>';

		
echo $filter_box_content;
?>