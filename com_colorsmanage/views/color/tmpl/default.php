
<?php
defined('_JEXEC') or die('Restricted Access');
$mail = JFactory::getMailer();
jimport('joomla.mail.mail');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
// JHtml::_('behavior.tooltip');
// JHtml::_('behavior.multiselect');
// JHtml::_('formbehavior.chose'.'select');

// $listOrder = $this->escape($this->state->get('list.ordering'));
// $listDirn = $this->escape($this->state->s('list.direction'));

?>
<div class="container">
<form action="<?php echo JRoute::_('index.php?option=com_colorsmanage&c=colorcontroller');?>" method="post" name="adminForm" id="adminForm">
<button id="btnEdit"class="sm<?php echo $item->virtuemart_media_id; ?> btn btn-primary" style="display:block;" type="submit">Edit Colors</button>
<button id="btnUpdate"class="sm<?php echo $item->virtuemart_media_id; ?> btn btn-success" style="display:none;" type="submit">Update Colors</button>
<div id="j-main-container">
	<div id="filter-bar" class="btn-toolbar">
		<?php 
		if(isset($_POST["filter-search"]))
$searchValue = $_POST["filter-search"];
else
$searchValue = "";

		?>
     <input type="text" name="filter-search" id="filter-search" placeholder="Search By Title" value="<?php echo $searchValue ?>">
<button type="submit" class="btn tip" title="Search" style="margin-top: -1%;"><i class="icon-search"></i></button>
  
	</div>

<div class="btn-group pull-left">
<!-- <button type="button" class="btn tip" onclick="document.id('filter_search').value'';$this.forms.submit();"><i class="icon-remove"></button> -->

</div>


</div>
<div class="clearfix"></div>


 <h2>Media Colors</h2>          
  <table class="table table-striped">
    <thead>
      <tr>
      <th>MediaID</th>
      <th>Image</th>
      <th>Title</th>
      <th>bgcolor</th>
      <th>simbcolor</th>
      <th>location</th>
      <th>LocationSymb</th>
      <th>LocationProd</th>
      
      </tr>
    </thead>

   
        <tbody>
     <?php foreach($this->item as $item){?>

<tr>

<td><?php echo $item->virtuemart_media_id; ?></td>
<td>
	<img style="width: 150%;" src="<?php echo "https://orgonite-brasil.com/".$item->file_url; ?>" />
</td>

<td><?php echo substr($item->file_title,0,100) ?></td>
<input type="hidden" name="id2[]" value="<?php echo $item->virtuemart_media_id; ?>">
<td><a href="#"class="changeColor" id="<?php echo $item->virtuemart_media_id; ?>" style="text-decoration: none;cursor: hand"><?php echo $item->bgcolor; ?></a><br>
	<input class="bg"style="display: none" type="text" name="bg[]" value="<?php echo $item->bgcolor; ?>">

</td>
<td><a class="changeColor" href="#" id="<?php echo $item->virtuemart_media_id; ?>" style="text-decoration: none;cursor: hand"><?php echo $item->simbcolor; ?></a>
<br>

	<input class="sm"style="display: none" type="text" name="sm[]" value="<?php echo $item->simbcolor; ?>">

</td>

<td><a class="changeColor" href="#" id="<?php echo $item->virtuemart_media_id; ?>" style="text-decoration: none;cursor: hand"><?php echo $item->location; ?></a>
<br>

	<input class="loc"style="display: none" type="text" name="loc[]" value="<?php echo $item->location; ?>">

</td>
<td><a class="changeColor" href="#" id="<?php echo $item->virtuemart_media_id; ?>" style="text-decoration: none;cursor: hand"><?php echo $item->locationsymb; ?></a>
<br>

	<input class="losy"style="display: none" type="text" name="losy[]" value="<?php echo $item->locationsymb; ?>">

</td>
<td><a class="changeColor" href="#" id="<?php echo $item->virtuemart_media_id; ?>" style="text-decoration: none;cursor: hand"><?php echo $item->locationprod; ?></a>
<br>

	<input class="losy"style="display: none" type="text" name="losp[]" value="<?php echo $item->locationprod; ?>">

</td>
</tr>


<?php }?>
    </tbody>
 
  </table>

	 <?php echo $this->pagination->getListFooter(); ?>
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0">
<input type="hidden" name="c" value="colorcontroller">
<!-- <input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" /> -->
<?php echo JHtml::_('form.token'); ?>
</form>




</div>

<script type="text/javascript">
jQuery(document).ready(function (){
  jQuery("#adminForm #btnEdit").on('click',function(e){

e.preventDefault();
document.getElementById("btnEdit").style="display:none";
document.getElementById("btnUpdate").style="display:block";

    img = document.getElementsByTagName("img");

 for (var i = 0; i < img.length; i++){
  if(i>=5)
        img[i].style ="width:150px;max-width: none;";
    }
    

    id = jQuery(this).attr('id');
 elements = document.getElementsByClassName("bg");
     for (var i = 0; i < elements.length; i++){
        elements[i].style.display ="block";
    }
     elementssm = document.getElementsByClassName("sm");
     for (var i = 0; i < elementssm.length; i++){
        elementssm[i].style.display ="block";
    }
     elementsloc = document.getElementsByClassName("loc");
     for (var i = 0; i < elementsloc.length; i++){
        elementsloc[i].style.display ="block";
    }
    elementsloc = document.getElementsByClassName("losy");
     for (var i = 0; i < elementsloc.length; i++){
        elementsloc[i].style.display ="block";
    }
// document.getElementsByClassName("bg")[0].style="display:block";
// document.getElementsByClassName("sm"+id)[0].style="display:block";
// document.getElementsByClassName("loc"+id)[0].style="display:block";
document.getElementById("btnUpdate").style="display:block";
  })
});

</script>

