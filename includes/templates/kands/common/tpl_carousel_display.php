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

<ul class="<?php echo $carousel_class;?>">

    <?php
    if(is_array($images_array)>0){
        for($row=0;$row<sizeof($images_array);$row++) {
            echo '<li>';
            echo '<a href="'.zen_href_link(zen_get_info_page($images_array[$row]['id']),($cPath ? 'cPath='.$cPath.'&':'').'products_id='.$images_array[$row]['id']).'"><img src="images/'.$images_array[$row]['image'].'"></a>';
            echo '<br><span class="familyName">'.$images_array[$row]['name'].'</span>';
            echo '</li>';
        }
    }
    ?>

</ul>

