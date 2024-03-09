<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_ordersmanage
 * @copyright   Copyright (C) .
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$mail = JFactory::getMailer();
jimport('joomla.mail.mail');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
// Load language file


//$listOrder = $this->escape($this->state->get('list.ordering'));
//$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->

<style>
  #change_state_select_chosen
  {
    width: 100% !important;
  }
.btn-group-toggle>.btn input[type=checkbox], .btn-group-toggle>.btn input[type=radio], .btn-group-toggle>.btn-group>.btn input[type=checkbox], .btn-group-toggle>.btn-group>.btn input[type=radio] {
     position: absolute;
    clip: rect(0,0,0,0);
    pointer-events: none;
}
.btn-secondary:not(:disabled):not(.disabled).active, .btn-secondary:not(:disabled):not(.disabled):active, .show>.btn-secondary.dropdown-toggle {
    box-shadow: 0 0 0 0.2rem rgb(108 117 125 / 50%);
}
.btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show>.btn-primary.dropdown-toggle {
    box-shadow: 0 0 0 0.2rem rgb(108 117 125 / 50%);
}
.custom_filter{
  width: 100%;

}
.btn-group-toggle.first{

  float: left;
  margin-bottom: 10px;
  margin-right: 10px;
}
.btn-group-toggle.second{
  width: 7%;
  float: left;
  margin-bottom: 10px;
}
.fancybox-content{
    padding-left: 50px;
    padding-right: 90px;
    padding-top: 50px;
    padding-bottom: 20px;
}
.row {
    width: 100%;
}
.col-sm-6 {
    width: 50%;
    float: left;
}
a.btn.view_nav, .export_btn_box {
    margin-bottom: 15px;
    float: left;
    margin-right: 10px;
}

</style>
<form action="<?php echo JRoute::_('index.php?option=com_ordersmanage&view=orderslist') ?>" method="post" id="adminForm" name="adminForm">
  <a class="btn btn-success view_nav" href="index.php?option=com_ordersmanage&view=productcenntrics"> <?php echo JText::_('COM_ORDER_PRODUCS'); ?></a>
    <div class="export_btn_box">
        <a href="index.php?option=com_ordersmanage&task=orderslist.exportcsv" class="btn btn-primary" target="_blank"><?php echo JText::_('COM_ORDERSMANAGE_ORDERLIST_EXPORT_CSV_BTN'); ?></a>
    </div>
  <div class="custom_filter">
      <div class="btn-group-toggle first">
          <label class="btn btn-<?php if($this->state->only_open == "only_open"){ echo 'primary'; }else{ echo 'secondary'; } ?>" onclick="onlableclick1(this)">
              <input type="hidden" id="filter_only_open" name="filter[only_open]" value="<?php echo $this->state->only_open; ?>"> <?php echo JText::_('COM_ORDERSMANAGE_ORDERSLIST_OPEN_ONLY_BTN'); ?>
          </label>
      </div>
      <div class="btn-group-toggle second">
          <label class="btn btn-<?php if($this->state->view_all == "view_all"){ echo 'primary'; }else{ echo 'secondary'; } ?>" onclick="onlableclick2(this)">
              <input type="hidden" id="filter_view_all" name="filter[view_all]" value="<?php echo $this->state->view_all; ?>"> <?php echo JText::_('COM_ORDERSMANAGE_ORDERSLIST_VIEW_ALL_BTN') ?>
          </label>
      </div>
      <div class="btn-group-toggle third">
          <label class="btn btn-<?php if($this->state->revenda == "revenda"){ echo 'primary active'; }else{ echo 'secondary'; } ?>" onclick="onlableclick3(this)">
              <input type="hidden" id="filter_revenda" name="filter[revenda]" value="<?php echo $this->state->revenda; ?>"> <?php echo JText::_('COM_ORDERSMANAGE_ORDERLIST_CONSIGNA_REVENDA_FILTER_BTN') ?>
          </label>
      </div>
  </div>
  <br>

  <br>
<script>
    var i = 0;
    var m = 0;
    // jQuery(document).ready(function(){
    function onlableclick1(el){
        i = i + 1;
        var j = i%2;
        if(j>0){
        // alert("click");
        // jQuery(".btn-group-toggle label").click(function(){
            jQuery(el).toggleClass("active");
            if(jQuery(el).hasClass("active")){
                jQuery("#filter_only_open").val("only_open");
                jQuery("#filter_view_all").val("");
            }else{
                jQuery("#filter_only_open").val("");
            }
            jQuery("#adminForm").submit();
        // });
        }
    }
    function onlableclick2(el){
        m = m + 1;
        var j = m%2;
        if(j>0){
        // alert("click");
        // jQuery(".btn-group-toggle label").click(function(){
            jQuery(el).toggleClass("active");
            if(jQuery(el).hasClass("active")){
                jQuery("#filter_view_all").val("view_all");
                jQuery("#filter_only_open").val("");
            }else{
                jQuery("#filter_view_all").val("");
            }
            jQuery("#adminForm").submit();
        // });
        }
    }
    function onlableclick3(el){
      jQuery(el).toggleClass("active");
      if(jQuery(el).hasClass("active")){
          jQuery("#filter_revenda").val("revenda");
      }else{
          jQuery("#filter_revenda").val("");
      }
      jQuery("#adminForm").submit();
    }
    // });
   
</script>
<script type="text/javascript">
            function printSection(el){
                var getFullContent = document.body.innerHTML;
                var printsection = document.getElementById(el).innerHTML;
                document.body.innerHTML = printsection;
                window.print();
                document.body.innerHTML = getFullContent;
            }
        </script>
<?php  echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
<div id="resultscounter"><?php echo $this->pagination->getResultsCounter(); ?></div>
<table class="table table-striped" id="orderList">
 <thead>
	<tr>
    <th width="1%" class="nowrap"><?php echo JHtml::_('grid.checkall'); ?></th>
	<th width="1%" class="nowrap"><?php echo 'Encomenda'; ?></th>
    <th width="1%" class="nowrap"><?php echo 'Endereço completo'; ?></th>
    <th width="1%" class="nowrap"><?php echo 'Contato'; ?></th>
    <th width="1%" class="nowrap"><?php echo 'Pagamento'; ?></th>
    <th width="1%" class="nowrap"><?php echo 'Envio'; ?></th>
    <th width="1%" class="nowrap"><?php echo 'Observação'; ?></th>
    <!-- <th width="1%" class="nowrap"><?php //echo JText::_("COM_ORDERSMANAGE_ORDERSLIST_STATUS_LBL"); ?></th>
    <th width="1%" class="nowrap"><?php //echo JText::_("COM_ORDERSMANAGE_ORDERSLIST_SHIPPING_INFO_LBL"); ?></th> -->
    <!--<th width="1%" class="nowrap"><?php //echo 'Order Status'; ?></th>-->
    </tr>
 </thead>
 <tbody>
    <?php
	if (count($this->orderslist) > 0) {
	 $modal = $this->getModel();
	 $i = 0; $k = 0;
	foreach ($this->orderslist as $order) :
        // echo "<pre/>";print_r($order);exit;
     $orderstate = $modal->getVmOrderState( $order->virtuemart_state_id );
	 $orderpayment = $modal->getVmOrderPayment( $order->virtuemart_order_id, $order->virtuemart_paymentmethod_id );
	 $ordershipment = $modal->getVmOrderShipment( $order->virtuemart_order_id, $order->virtuemart_shipmentmethod_id );
	 $ordercurrency = $modal->getVmOrderCurrency( $order->order_currency );
	 $shipment_tracking = $modal->getVmOrderShipmentTrack( $order->virtuemart_order_id );
	?>
	<tr class="row<?php echo $k % 2; ?>">
        <td class="order "><?php echo JHtml::_('grid.id', $i, $order->virtuemart_order_id); ?></td>
		<td class="order ">
          <?php $link = 'index.php?option=com_virtuemart&view=orders&task=edit&virtuemart_order_id=' . $order->virtuemart_order_id; ?>
		  <?php echo JHtml::_ ('link', JRoute::_ ($link, FALSE), $order->order_number); ?>
          <?php echo '<br>'.JHtml::_('date', $order->created_on, 'd, M y H:i'); ?>
          <?php echo '<br>';
		   foreach($ordercurrency as $currency){
			 echo 'Total: '. $currency->currency_symbol.' '.round( $order->order_total, 2);
	        }
		  ?>
         <?php  echo '<br>';
         $current_state = "";
		   if($order->order_status){
             foreach($this->orderstatuses as $order_status){
		         if($this->orderstatuses[ $order->order_status ]){
			       if($order_status['order_status_code'] === $order->order_status){
                    $current_state = $order->order_status;//$order_status['order_status_name'];
					 $order_editlink = JROUTE::_ ('index.php?option=com_virtuemart&view=orders&task=edit&virtuemart_order_id='. $order->virtuemart_order_id);
					 echo '<a style="color:#6aa84f;" href="'. $order_editlink .'">'. $order_status['order_status_name'] .'</a>';
			        }
		          }
		      }
		    }
		?>
		</td>
        <td class="order address">
        <?php $random_code = 'id-'.$order->virtuemart_user_id.mt_rand(); ?>
          <div class="elm_ccb" data-ccb="<?php echo $random_code; ?>"><?php
			if ($order->virtuemart_user_id) {
			$userlink = JROUTE::_ ('index.php?option=com_virtuemart&view=user&task=edit&virtuemart_user_id[]=' . $order->virtuemart_user_id, FALSE);
			 echo '<a href="'. $userlink .'">'. $order->first_name.' '.$order->last_name .'</a>';
			} else {
			  echo $order->first_name.' '.$order->last_name;
			}
			echo '<br>'.$order->address_1;
			echo '<br>'.$order->zip;
			echo '<br>'.$order->city;
			foreach($orderstate as $state){
		     echo '<br>'.$state->state_2_code;
	        }
		 ?>
         </div>
         <div id="address-info-<?php echo $order->virtuemart_user_id;?>" style="display:none;">
         <?php
			if ($order->virtuemart_user_id) {
			$userlink = JROUTE::_ ('index.php?option=com_virtuemart&view=user&task=edit&virtuemart_user_id[]=' . $order->virtuemart_user_id, FALSE);
			 echo '<a href="'. $userlink .'">'. $order->first_name.' '.$order->last_name .'</a>';
			} else {
			  echo $order->first_name.' '.$order->last_name;
			}
			echo '<br>'.$order->address_1;
			echo '<br>'.$order->zip;
			echo '<br>'.$order->city;
			foreach($orderstate as $state){
		     echo '<br>'.$state->state_2_code;
	        }
		 ?></div>
   </div>
         <div style="margin-top: -80px;"><i class="icon-copy icon-position" onclick="copyClipboard('<?php echo $random_code; ?>')"></i></div>
         <div style="margin-top:50px;"><i class="fa fa-print icon-position" onclick="printSection('address-info-<?php echo $order->virtuemart_user_id;?>')"></i></div>
		 </td>
        <td class="order contact">
		 <?php
			if( $order->phone_1 ){
			  echo 'Ph1: <a href="tel:'.$order->phone_1.'">'.$order->phone_1.'</a>';
			}
			if( $order->phone_2 ){ echo '<br>';
			  echo 'Ph2: <a href="tel:'.$order->phone_2.'">'.$order->phone_2.'</a>';
			}
			if( $order->order_email ){ echo '<br>';
			echo '<a href="mailto:'. $order->order_email .'">'. $order->order_email .'</a>';
			}
		 ?>
		 </td>
         <td class="order payment"><?php
		   echo $order->payment_name.'<br>';
		   if($orderpayment)
             {
                 foreach($orderpayment as $payment){
                     // echo $payment->status;
                     echo JHtml::_('date', $payment->created_on, 'd.m.Y');
                     //echo '<br>'.JHtml::_('date', $payment->modified_on, 'd.m.y');
                 }
             }

		 ?>
     <a class="change_state" data-id="<?php echo $order->virtuemart_order_id; ?>" data-status="<?php echo $current_state; ?>">
        <i class="icon-pencil"></i>
        <?php //echo JText::_("COM_ORDERSMANAGE_ORDERSLIST_CHANGE_STATE_BTN"); ?>
    </a>

     </td>
         <td class="order shipment">
		 <?php echo $order->shipment_name.'<br>'; ?>
          <?php
			/*$mailfrom = "admin@orgonite-brasil.com";
            $fromname = "Bernhard Aggeler";
            $sender = array($mailfrom, $fromname);
            $email = base64_decode( $order->order_email );*/
            $subject_text = 'Pedido Orgonite Brasil - Código de rastreio';
            $bcc_email = 'admin@orgonite-brasil.com';
            /*$mail->setSender($sender);
            $mail->addRecipient($email);
            $mail->addBCC($bcc_email);
            $mail->isHTML('TRUE');
            $mail->setSubject($subject_text);*/
			$full_name = $order->first_name.' '.$order->last_name;
		 ?>
      <?php if($shipment_tracking){//foreach($shipment_tracking as $track){ ?>
        	 <?php $html_msgBody = "Bom dia ". $full_name ."!\n<br><br>Seu pedido de orgonites foi postado nos correios e pode ser rastreado pelo link seguinte:<br/><br/>
			   Código:<br/>".$shipment_tracking->tracking_code."<br/>Link:<br>http://www.websro.com.br/rastreamento-correios.php?P_COD_UNI=".$shipment_tracking->tracking_code."<br/><br/>
			   Agradeçemos a sua confiança e por gentileza pedimos a retornar uma pequena confirmação quando recebeu!<br/><br/>
			   Atenciosamente<br/>Bernhard Aggeler<br/>Orgonite Brasil<br/>"; ?>
             <?php
			  /*$mail->setBody($html_msgBody);  */
			 ?>
       		 <div class="shipment-track">
             <?php echo $shipment_tracking->tracking_code; ?>
		      <a href="javascript:void(0)"  id="emailtrackinginfo" eid="<?php echo $order->order_email ?>" bcc="<?php echo $bcc_email; ?>" subject="<?php echo $subject_text; ?>" fullname="<?php echo $full_name; ?>" trackingcode="<?php echo $shipment_tracking->tracking_code;?>">
                <i class="icon-mail icon-send-mail"></i>
              </a>
             <?php //echo "<pre>";
             //print_r($shipment_tracking);die;
              $arr = explode(' ', $shipment_tracking->post_date)[0];
              $arr1 = explode('-', $arr);
              $newDate = $arr1[2].'-'.$arr1[1].'-'.$arr1[0];
              echo $newDate;
              //echo JHtml::_('date', explode(' ',$shipment_tracking->post_date)[0], 'd.m.Y');?>
             </div>
           <?php } ?>
             <div id="custom_note_<?php echo $order->virtuemart_order_id; ?>" style="display: none;">
                <div class="row">
                  <div class="col-sm-6">
                    <label style="width: 20px;"><?php echo JText::_('COM_ORDERSMANAGE_ORDERSLIST_TRACKING_CODE_LBL'); ?></label>
                  </div>
                  <div class="col-sm-6">
                    <textarea class="shipping_info" id="ship_in_<?php echo $order->virtuemart_order_id; ?>"><?php if($shipment_tracking) echo $shipment_tracking->tracking_code; ?></textarea>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <label style="width: 20px;"><?php echo JText::_('COM_ORDERSMANAGE_ORDERSLIST_DATE_LBL'); ?></label>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" id="shipi_in_date<?php echo $order->virtuemart_order_id; ?>" value="">
                  </div>
                </div>
                <script>
                jQuery( function() {
                  jQuery( "#shipi_in_date<?php echo $order->virtuemart_order_id; ?>" ).datepicker({dateFormat: 'dd-mm-yy'});
                } );
                </script>
                <br>
                <div class="btn save_ship_info" data-id="<?php echo $order->virtuemart_order_id; ?>"><?php echo JText::_("COM_ORDERSMANAGE_ORDERSLIST_SHIPPING_INFO_SAVE_BTN"); ?></div>
            </div>
            <div class="open_ship_info" data-id="<?php echo $order->virtuemart_order_id; ?>"><img src="<?php echo JURI::root(); ?>images/shipping_info.png"></div>
       <?php //} ?>
         <?php //echo '<br>'.$order->delivery_date; ?>
         <?php foreach($ordershipment as $shipment){
			 //echo $shipment->status.'<br>';
			 echo '<br>'.JHtml::_('date', $shipment->created_on, 'd.m.Y');
			 //echo '<br>'.JHtml::_('date', $shipment->modified_on, 'd.m.y');
	     }?>
         </td>
         <td class="order note">
          <?php echo $order->oc_note; ?>
          <br>
          <div id="edit_oc_note_sec_<?php echo $order->virtuemart_order_id; ?>" style="display: none;">
            <textarea id="oc_note_<?php echo $order->virtuemart_order_id; ?>">
              <?php echo $order->oc_note; ?>
            </textarea>
            <br>
            <div class="btn save_oc_note" data-id="<?php echo $order->virtuemart_order_id; ?>">save</div>
          </div>
          <span class="icon-pencil open_edit_oc_note" data-id="<?php echo $order->virtuemart_order_id; ?>"></span>
        </td>
         <!-- <td>

        </td> -->
        <!-- <td>

        </td> -->
         <!--<td class="order nowrap"><?php /*
		  if($order->order_status){
		   echo JHtml::_ ('select.genericlist', $this->orderstatuses, "orders[" . $order->virtuemart_order_id . "][order_status]", 'class="orderstatus_select"', 'order_status_code', 'order_status_name', $order->order_status, 'order_status' . $i, TRUE);
          } */
		 ?></td>-->
	  </tr>
	 <?php $k = 1 - $k;
	       $i++;
		   endforeach;
		}
	 ?>
	</tbody>
    <tfoot><tr><td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td></tr></tfoot>
  </table>
  <input type="hidden" name="task" value="" />
  <input type="hidden" name="boxchecked" value="0" />
<?php echo JHtml::_('form.token'); ?>
</form>
<div id="custom_state_change" style="display: none;height: 250px;width: 320px;">
    <select id="change_state_select" name="change_state_select">
        <?php foreach($this->orderstatuses AS $k=>$order_state){ ?>
            <option value="<?php echo $k; ?>" <?php if($current_state == $order_state['order_status_name']){ echo "selected"; } ?>><?php echo $order_state['order_status_name']; ?></option>
        <?php } ?>
    </select>
    <br><br>
    <div class="btn btn-secondary change_state_save_btn">Save</div>
    <input type="hidden" id="virtuemart_orders_id" value="" />
    <input type="hidden" id="virtuemart_orders_status" value="" />
</div>
<style>
.shipment-track{display:block;}
.shipment-track .icon-mail {position:relative;top:5px;margin-left:5px;margin-right:10px}
.icon-position {position:relative;left:80%;bottom:20px;cursor:pointer; color:#3071a9;}
.icon-position:hover{color:#000;}
.icon-send-mail{font-size:20px;}
.shipment a{text-decoration:none !important;}
.js-stools .js-stools-container-filters, .js-stools .js-stools-container-filter.hidden-phone {display:inline-block !important;}
</style>
<script>
  
     //jQuery("#emailtrackinginfo").click(function()
     jQuery('body').on('click', '#emailtrackinginfo', function()
     {
    
      var emailadd=jQuery(this).attr('eid');
      var bcc=jQuery(this).attr('bcc');
      var subject=jQuery(this).attr('subject');
      var fullname=jQuery(this).attr('fullname');
      var trackingcode=jQuery(this).attr('trackingcode');
      
      jQuery.ajax({
          type: "POST",
          url: "index.php?option=com_ordersmanage&task=orderslist.emailtrackinginfo",
          data: {emailadd: emailadd,bcc: bcc,subject: subject,fullname:fullname,trackingcode:trackingcode},
          success: function(data){
          
              if(data == 1){
                alert("Successfully Mail Sent")
                  location.reload();
              }
          }
      });
     });
    jQuery(".open_edit_oc_note").click(function(){
        var id = jQuery(this).data("id");
        jQuery.fancybox.open({
          src: '#edit_oc_note_sec_'+id,
          type : 'inline'
        });
    });
    jQuery(".save_oc_note").click(function(){
        var id = jQuery(this).data("id");
        var oc_note = jQuery("#oc_note_"+id).val();
        jQuery.ajax({
          type: "POST",
          url: "index.php?option=com_ordersmanage&task=orderslist.updateocnote",
          data: {id: id, oc_note: oc_note},
          success: function(data){
              if(data == 1){
                  location.reload();
              }
          }
      });
    });
    jQuery(".save_ship_info").click(function(){
        var id = jQuery(this).data("id");
        var info = jQuery("#ship_in_"+id).val();
        var date = jQuery("#shipi_in_date"+id).val();
        jQuery.ajax({
          type: "POST",
          url: "index.php?option=com_ordersmanage&task=orderslist.updateshipinfo",
          data: {id: id, info: info, date: date},
          success: function(data){
              if(data == 1){
                  location.reload();
              }
          }
      });
    });
function copyClipboard(q) {
  var elm = document.body.querySelector('.elm_ccb[data-ccb='+q+']');
  //elm = elm.trim();
  // for Internet Explorer
  if(document.body.createTextRange) {
    var range = document.body.createTextRange();
    range.moveToElementText(elm);
    range.select();
    document.execCommand("Copy");
    //alert("Copied div content to clipboard");
  }
  else if(window.getSelection) {
    // other browsers
    var selection = window.getSelection();
    var range = document.createRange();
    range.selectNodeContents(elm);
    selection.removeAllRanges();
    selection.addRange(range);
    document.execCommand("Copy");
    //alert("Copied div content to clipboard");
  }
}
jQuery(".change_state").click(function(){
    var id = jQuery(this).data("id");
    jQuery("#virtuemart_orders_id").val(id);
    var status = jQuery(this).data("status");
    jQuery("#change_state_select").val(status);
    jQuery("#change_state_select").trigger('liszt:updated');
    jQuery("#virtuemart_orders_status").val(status);
    // alert(status);
    jQuery.fancybox.open({
        src: '#custom_state_change',
        type : 'inline'
    });
});
jQuery(".change_state_save_btn").click(function(){
    var id = jQuery("#virtuemart_orders_id").val();
    var cur_state = jQuery("#virtuemart_orders_status").val();
    var new_state = jQuery("#change_state_select").val();
    jQuery.ajax({
        type: "POST",
        url: "index.php?option=com_ordersmanage&task=orderslist.updateorder",
        data: {id: id, cur_state: cur_state,new_state: new_state},
        success: function(data){
            if(data == 1){
                location.reload();
            }
        }
    });
});
jQuery(".open_ship_info").click(function(){
    var id = jQuery(this).data("id");
    jQuery.fancybox.open({
        src: '#custom_note_'+id,
        type : 'inline'
    });
});
</script>
