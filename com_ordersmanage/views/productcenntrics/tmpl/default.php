<?php
defined('_JEXEC') or die('Restricted Access');
use Joomla\CMS\Factory;
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');

?>
<style>
  a.btn.view_nav {
    margin-bottom: 15px;
      float: left;
      margin-right: 10px;
}

  .custom_filter
  {
      width: 15%;
      float: left;
  }
  #link-image::before
  {
display: none;
  }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  function onlableclick3(el){
    jQuery(el).toggleClass("active");
    if(jQuery(el).hasClass("active")){
        jQuery("#filter_revenda").val("revenda");
    }else{
        jQuery("#filter_revenda").val("");
    }
    jQuery("#adminForm").submit();
  }
  jQuery(document).ready(function(){
    jQuery(".export_csv_btn").click(function(){
      jQuery("#csvExport").submit();
    });
  });
  jQuery(document).ready(function(){
    jQuery('body').on('click', '#exportpdf', function(event) {
    event.preventDefault();

    if ($('#orderList input:checkbox').filter(':checked').length < 1){
      alert("Please Check at least one Check Box");
        return false;
    }
    else
    {
      var array = [];
      var proimage=[];
      var orderitem=[];
      var ordersku=[];
      var orderitemqty=[];
      var orderitemorderid=[];
      var orderitemdate=[];
      var orderitemname=[];
      var orderitemcity=[];
      var itemlocationsymb=[];
      var itemlocationprod=[];
      jQuery('#orderList input:checkbox:checked').each(function () {
        array.push($(this).val());
        proimage.push($(this).attr("proimage"));
        orderitem.push($(this).attr("orderitem"));
        ordersku.push($(this).attr("ordersku"));
        orderitemqty.push($(this).attr("orderitemqty"));
        orderitemorderid.push($(this).attr("orderitemorderid"));
        orderitemdate.push($(this).attr("orderitemdate"));
        orderitemname.push($(this).attr("orderitemname"));
        orderitemcity.push($(this).attr("orderitemcity"));
        
        itemlocationsymb.push($(this).attr("itemlocationsymb"));
        itemlocationprod.push($(this).attr("itemlocationprod"));
      });
      jQuery.ajax({
          type: "POST",
          url: "index.php?option=com_ordersmanage&task=productcenntrics.exportpdf",
          data: {array: array,proimage: proimage,orderitem: orderitem,ordersku:ordersku,orderitemqty: orderitemqty,orderitemorderid: orderitemorderid,orderitemdate: orderitemdate,orderitemname: orderitemname,orderitemcity:orderitemcity,itemlocationsymb: itemlocationsymb,itemlocationprod: itemlocationprod},
          success: function(data){
            //alert(data);
            var getFullContent = document.body.innerHTML;
            document.body.innerHTML = data;
           window.print();
           document.body.innerHTML = getFullContent;
            return false;
          }
      });

    }
        
      
    });
  });
</script>
<form action="<?php echo JRoute::_('index.php?option=com_ordersmanage&view=productcenntrics'); ?>" method="post" id="csvExport">
  <input type="hidden" name="task" value="productcenntrics.exportcsv"/>
</form>
<form action="<?php echo JRoute::_('index.php?option=com_ordersmanage&view=productcenntrics') ?>" method="post" id="adminForm" name="adminForm">
  <a class="btn btn-success view_nav" href="index.php?option=com_ordersmanage&view=orderslist">Pedidos (Orders)</a>
  <div class="custom_filter">
      <div class="btn-group-toggle third">
          <label class="btn btn-<?php if($this->state->revenda == "revenda"){ echo 'primary active'; }else{ echo 'secondary'; } ?>" onclick="onlableclick3(this)">
              <input type="hidden" id="filter_revenda" name="filter[revenda]" value="<?php echo $this->state->revenda; ?>"> <?php echo JText::_('COM_ORDERSMANAGE_ORDERLIST_CONSIGNA_REVENDA_FILTER_BTN') ?>
          </label>
      </div>
  </div>

<!--   <div class="custom_csv_export">
    <label class="btn btn-primary export_csv_btn">
      <?php //echo JText::_('COM_ORDERSMANAGE_ORDERLIST_EXPORT_CSV_BTN') ?>
    </label>
  </div> -->
 
   
  <div>
   <a href="index.php?option=com_ordersmanage&task=productcenntrics.exportcsv" class="btn btn-primary" target="_blank"><?php echo JText::_('COM_ORDERSMANAGE_ORDERLIST_EXPORT_CSV_BTN'); ?></a>  
   <a href="javscript:void(0)" id="exportpdf" class="btn btn-primary"><?php echo JText::_('COM_ORDER_PDF'); ?></a>    

  </div>
  <br>
  <?php  echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
  <div id="resultscounter"><?php echo $this->pagination->getResultsCounter(); ?></div>
  <table class="table table-striped" id="orderList">
    <thead>
      <tr>
        <th><?php echo JHtml::_('grid.checkall'); ?></th>
        <th><?php echo 'Imagem do Produto'; ?></th>
        <th><?php echo JHtml::_('grid.sort',  'Nome do Produto<br> & SKU', 'oi.order_item_name', $listDirn, $listOrder); ?></th>
        <th><?php echo 'QTY'; ?></th>
        <th><?php echo JHtml::_('grid.sort',  'OrderID<br>& Encontro', 'o.virtuemart_order_id', $listDirn, $listOrder); ?><?php echo ''; ?></th>
        <th><?php echo JHtml::_('grid.sort',  'Nome do cliente<br> & Cidade', 'u.first_name', $listDirn, $listOrder); ?></th>
        <th><?php echo JHtml::_('grid.sort',  'escolhida', 'o.escolhida', $listDirn, $listOrder); ?></th>
        <th><?php echo JHtml::_('grid.sort',  'Produzida', 'o.produzida', $listDirn, $listOrder); ?><?php echo ''; ?></th>
        <th><?php echo JHtml::_('grid.sort',  'MediaLocation', 'o.medialocation', $listDirn, $listOrder); ?></th>
        <th>
      </tr>
    </thead>
    <tfoot><tr><td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
    <tbody>
      <?php
      $model = $this->getModel();
      foreach($this->items as $k=>$item){
      
        //$image = $model->getProductImage($item->virtuemart_order_id, $item->virtuemart_product_id);
        $proimage=$model->pmedia($item->virtuemart_product_id);
        if(count($item->product_variant) > 0){
         
        	foreach ($item->product_variant as $key => $variant) {
        		$check_picked = $model->getPicked($item->virtuemart_order_id, $item->virtuemart_product_id,$variant->virtuemart_media_id);
        		$check_productlocation = $model->getProductLocation($item->virtuemart_order_id, $item->virtuemart_product_id,$variant->virtuemart_media_id);
            /* get media location symb and location prod code start here*/
          $mid=$model->mediaid($item->virtuemart_product_id);
          $db = Factory::getDBO();
           $query = $db->getQuery(true);

           $query->select($db->quoteName(array('locationsymb', 'locationprod')));
           $query->from($db->quoteName('onite_vmmedia'));
           $query->where($db->quoteName('mediaID') . '=' . $db->quote($mid));
           $db->setQuery($query);
          $result = $db->loadAssocList();
          /* get media location symb and location prod code end here*/
        		?>
        			<tr>
			          <td><input type="checkbox" name="cid[]" value="<?php echo $item->virtuemart_order_id.'_'.$item->virtuemart_product_id.'_'.$variant->virtuemart_media_id; ?>"
                proimage="<?php  if($proimage!="") { echo JUri::root().$proimage;}?>" orderitem="<?php echo $item->order_item_name;?>" ordersku="<?php echo $item->order_item_sku;?>"
                orderitemqty="<?php echo $item->product_quantity;?>" orderitemorderid="<?php echo $item->virtuemart_order_id;?>" orderitemdate="<?php echo $item->created_on?>"
                orderitemname="<?php echo $item->first_name.' '.$item->last_name.'';?>" orderitemcity="<?php echo $item->city;?>" itemlocationsymb="<?php if($result[0]['locationsymb']!='') { echo $result[0]['locationsymb'];}?>"
                itemlocationprod="<?php if($result[0]['locationprod']!='') { echo $result[0]['locationprod'];}?>"></td>
                    <td>
			            <?php 
			              if($variant->file_url != ""){   ?>
			                <img src="<?php echo JUri::root().$variant->file_url; ?>" alt="<?php echo $item->order_item_name; ?>" title="<?php echo $item->order_item_name; ?>" width="100" height="100"/>
			                <?php
                      $mid=$variant->virtuemart_media_id;
			              }
                    else
                    {
                      $mid=$model->mediaid($item->virtuemart_product_id);
                      ?>
                        <a href="<?php echo JUri::root().$proimage; ?>" target="_blank" id="link-image"> <img src="<?php echo JUri::root().$proimage; ?>" alt="<?php echo $item->order_item_name; ?>" title="<?php echo $item->order_item_name; ?>" width="100" height="100"/></a>
                      <?php
                    }
			            ?>
			          </td>
			          <td><?php echo $item->order_item_name.'<br><br />SKU:&nbsp;'.$item->order_item_sku; ?></td>
			          <td><?php echo $item->product_quantity; ?></td>
			          <td><?php echo $item->virtuemart_order_id.'<br><br />Date:&nbsp;'.$item->created_on; ?></td>
			          <td><?php echo $item->first_name.' '.$item->last_name.'<br>City:&nbsp;'.$item->city; ?></td>
			          <td><input type="checkbox" name="jform[picked]" class="picked_change" value="<?php echo $item->virtuemart_order_id.'_'.$item->virtuemart_product_id.'_'.$variant->virtuemart_media_id; ?>" <?php if($check_picked){ echo 'checked'; } ?>></td>
			          <td><input type="checkbox" name="jform[product_location]" class="product_location_change" value="<?php echo $item->virtuemart_order_id.'_'.$item->virtuemart_product_id.'_'.$variant->virtuemart_media_id; ?>" <?php if($check_productlocation){ echo 'checked'; } ?>></td>
                <td>
                <?php
               
               $db = Factory::getDBO();
                $query = $db->getQuery(true);

                $query->select($db->quoteName(array('locationsymb', 'locationprod')));
                $query->from($db->quoteName('onite_vmmedia'));
                $query->where($db->quoteName('mediaID') . '=' . $db->quote($mid));
                $db->setQuery($query);
               $result = $db->loadAssocList();
               echo "Location Symb: <strong>".$result[0]['locationsymb']."</strong><br>";
               echo "Location Prod: <strong>".$result[0]['locationprod']."</strong>";
               ?>
                </td>
			        </tr>
        		<?php
        	}
        }else{
        	$check_picked = $model->getPicked($item->virtuemart_order_id, $item->virtuemart_product_id);
        	$check_productlocation = $model->getProductLocation($item->virtuemart_order_id, $item->virtuemart_product_id);

          /* get media location symb and location prod code start here*/
          $mid=$model->mediaid($item->virtuemart_product_id);
          $db = Factory::getDBO();
           $query = $db->getQuery(true);

           $query->select($db->quoteName(array('locationsymb', 'locationprod')));
           $query->from($db->quoteName('onite_vmmedia'));
           $query->where($db->quoteName('mediaID') . '=' . $db->quote($mid));
           $db->setQuery($query);
          $result = $db->loadAssocList();
          /* get media location symb and location prod code end here*/
	        ?>
	        <tr>
	          <td><input type="checkbox" name="cid[]" value="<?php echo $item->virtuemart_order_id.'_'.$item->virtuemart_product_id; ?>" proimage="<?php  if($proimage!="") { echo JUri::root().$proimage;}?>" orderitem="<?php echo $item->order_item_name;?>" ordersku="<?php echo $item->order_item_sku;?>"
            orderitemqty="<?php echo $item->product_quantity;?>" orderitemorderid="<?php echo $item->virtuemart_order_id;?>" orderitemdate="<?php echo $item->created_on?>"
            orderitemname="<?php echo $item->first_name.' '.$item->last_name.'';?>" orderitemcity="<?php echo $item->city;?>" itemlocationsymb="<?php if($result[0]['locationsymb']!='') { echo $result[0]['locationsymb'];}?>"
            itemlocationprod="<?php if($result[0]['locationprod']!='') { echo $result[0]['locationprod'];}?>">
          </td>
	          <td>
            <?php 
                 //echo "Product Id:".$item->virtuemart_product_id."<br>";
                if($proimage!="") { ?>
           <a href="<?php echo JUri::root().$proimage; ?>" target="_blank" id="link-image"> <img src="<?php echo JUri::root().$proimage; ?>" alt="<?php echo $item->order_item_name; ?>" title="<?php echo $item->order_item_name; ?>" width="100" height="100"/></a>
              <?php
                }
                
                ?>
	            <?php
	             /* if($image != ""){ ?>
	                <img src="<?php echo JUri::root().$image; ?>" alt="<?php echo $item->order_item_name; ?>" title="<?php echo $item->order_item_name; ?>" />
	                <?php
	              }*/
	            ?>
	          </td>
	          <td><?php echo $item->order_item_name.'<br><br />SKU:&nbsp;'.$item->order_item_sku; ?></td>
	          <td><?php echo $item->product_quantity; ?></td>
	          <td><?php echo $item->virtuemart_order_id.'<br><br />Date:&nbsp;'.$item->created_on; ?></td>
	          <td><?php echo $item->first_name.' '.$item->last_name.'<br>City:&nbsp;'.$item->city; ?></td>
	          <td><input type="checkbox" name="jform[picked]" class="picked_change" value="<?php echo $item->virtuemart_order_id.'_'.$item->virtuemart_product_id; ?>" <?php if($check_picked){ echo 'checked'; } ?>></td>
	          <td><input type="checkbox" name="jform[product_location]" class="product_location_change" value="<?php echo $item->virtuemart_order_id.'_'.$item->virtuemart_product_id; ?>" <?php if($check_productlocation){ echo 'checked'; } ?>></td>
              <td> 
              <?php
              
                if($result[0]['locationsymb']!='') {
                    echo "Location Symb: <strong>".$result[0]['locationsymb']."</strong><br>";
                }
            if($result[0]['locationprod']!='') {
                echo "Location Prod: <strong>".$result[0]['locationprod']."</strong>";
            }
               ?>
              </td>
	        </tr>
	    <?php } ?>
      <?php } ?>
    </tbody>
  </table>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
</form>
<script>
  jQuery(document).ready(function(){
    jQuery(".picked_change").change(function(){
      var id = jQuery(this).val();
      var checked = 0;
      if(this.checked){
        checked = 1;
      }
      jQuery.ajax({
        url : 'index.php?option=com_ordersmanage&view=productcenntrics&task=productcenntrics.changePicked',
        data: {id: id, checked: checked},
        dataType: 'json',
        success: function(data){
          location.reload();
        }
      });
    });
    jQuery(".product_location_change").change(function(){
      var id = jQuery(this).val();
      var checked = 0;
      if(this.checked){
        checked = 1;
      }
      jQuery.ajax({
        url : 'index.php?option=com_ordersmanage&view=productcenntrics&task=productcenntrics.changeProduced',
        data: {id: id, checked: checked},
        dataType: 'json',
        success: function(data){
        	location.reload();
        }
      });
    });
  });
</script>