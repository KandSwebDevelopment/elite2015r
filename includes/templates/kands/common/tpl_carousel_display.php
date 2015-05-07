<?php
////////////////////////////////////////////////////////////////////////////////////
//
//    Produces a carousel from the images in $list_box_contents
//
//    Set the id and the class in the calling page
//
////////////////////////////////////////////////////////////////////////////////////

  if ($title) {
  	echo $title;
 }

 ?>
 <!--<div class="loading"></div>
<div class="whatsNewDisp">-->
<ul class="familySlider">

<?php
if(is_array($images_array)>0){
    for($row=0;$row<sizeof($images_array);$row++) {
        echo '<li><img src="images/products/'.$images_array[$row]['image'].'"></li>';
    }
}
?>

</ul>

<!--//$items_count=0;
//if (is_array($list_box_contents) > 0 ) {
// for($row=0;$row<sizeof($list_box_contents);$row++) {
//   for($col=0;$col<sizeof($list_box_contents[$row]);$col++){
//     $items_count++;
// ?>
 <!--   <li> <?php echo $list_box_contents[$row][$col]['text']; ?></li>-->-->
 <?php
//   }
// }
// echo '</ul>';
//}
 ?>
<!--</div>-->
