<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_orderslist
 * @copyright   Copyright (C) .
 * @license     GNU General Public License version 2 or later;
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HelloWorldList Model
 *
 * @since  0.0.1
 */
class OrdersManageModelOrdersList extends JModelList{
	/*function __construct() {
		parent::__construct();
		//VmConfig::importVMPlugins('vmpayment');
	}*/

	public function __construct($config = array()){
	 if (empty($config['filter_fields'])){
			$config['filter_fields'] = array(
				'id', 'o.virtuemart_order_id',
				'created', 'o.created_on'
			);
	  }
	  parent::__construct($config);
	}

	function getVmOrderShipment($vm_order_id = 0, $vm_shipmentmethod_id = 0){

	 if( ($vm_order_id > 0) && ($vm_shipmentmethod_id > 0) ){
		$db = JFactory::getDBO();
        $q1= 'SELECT shipment_element FROM #__virtuemart_shipmentmethods WHERE virtuemart_shipmentmethod_id = '.$vm_shipmentmethod_id.' and published=1';
		$db->setQuery($q1);
		$plg_shipment = $db->loadResult();
	    if($plg_shipment){
          $q2= 'SELECT * FROM #__virtuemart_shipment_plg_'.$plg_shipment.' WHERE (virtuemart_order_id = '.$vm_order_id.') and virtuemart_shipmentmethod_id = '.$vm_shipmentmethod_id;
		  $db->setQuery($q2);
		  return $db->loadObjectList();
	    }
	 }
	}

	function getVmOrderShipmentTrack($vm_order_id = 0){
		if( $vm_order_id > 0){
        $qs= "SELECT stc.* FROM #__virtuemart_shipment_tracking as stc  WHERE stc.virtuemart_order_id = ".$vm_order_id;
			$db = JFactory::getDBO();
			$db->setQuery($qs);
		    $shipment_tracking = $db->loadObject();
        return $shipment_tracking;
		}
	}

	function getVmOrderPayment($vm_order_id = 0, $vm_paymentmethod_id = 0){

	 if( ($vm_order_id > 0) && ($vm_paymentmethod_id > 0) ){
		$db = JFactory::getDBO();
        $q1= 'SELECT payment_element FROM #__virtuemart_paymentmethods WHERE virtuemart_paymentmethod_id = '.$vm_paymentmethod_id.' and published=1';
		$db->setQuery($q1);
		$plg_payment = $db->loadResult();
	    if($plg_payment){
            $q2= 'SELECT * FROM #__virtuemart_payment_plg_'.$plg_payment.' WHERE (virtuemart_order_id = '.$vm_order_id.') and virtuemart_paymentmethod_id = '.$vm_paymentmethod_id;
			$db->setQuery($q2);
		    return $db->loadObjectList();

	    }
	 }
	 //return '';
	}

	function getVmOrderCurrency($ordercurrency = 0){
		if( $ordercurrency > 0){
		$db = JFactory::getDBO();
        $q = 'SELECT * FROM #__virtuemart_currencies WHERE virtuemart_currency_id = "'.(int)$ordercurrency.'"';
		$db->setQuery($q);
		 return $db->loadObjectList();
		}
	}

	function getVmOrderState($stateId = 0){
		if( $stateId > 0){
        $qs= "SELECT s.* FROM #__virtuemart_states as s  WHERE s.virtuemart_state_id = ".$stateId;
			$db = JFactory::getDBO();
			$db->setQuery($qs);
		    $SingleState = $db->loadObjectList();
        return $SingleState;
		}
	}

	static public function getOrderStatusNames ($published = true) {
		static $orderStatusNames=0;
		if(empty($orderStatusNames)){
			if($published){
				$published = 'WHERE published = "1"';
			} else {
				$published = '';
			}
			$q = 'SELECT `order_status_name`,`order_status_code`,`order_stock_handle` FROM `#__virtuemart_orderstates` '.$published.' order by `ordering` ';
			$db = JFactory::getDBO();
			$db->setQuery($q);
			$orderStatusNames = $db->loadAssocList('order_status_code');
		}
		return $orderStatusNames;
	}


    /**
     * Method to build an SQL query to load the list data.
     * @return string  An SQL query
     */
    function getListQuery(){
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select(
			$this->getState(
				'list.select', 'o.*'
			)
		);
		$query->from('#__virtuemart_orders as o');
		$query->select('os.shipping_info AS shipping_info')
			->join('LEFT', $db->quoteName('#__virtuemart_order_shipping_info') . ' AS os ON os.virtuemart_order_id = o.virtuemart_order_id');
		// Join over the userinfos as u
		$query->select('u.*, u.email as order_email')
			->join('LEFT', $db->quoteName('#__virtuemart_order_userinfos') . ' AS u ON u.virtuemart_order_id = o.virtuemart_order_id AND u.address_type="BT"');
		// Join over the userinfos as st
		$query->select('st.address_type AS st_type, st.company AS st_company, st.city AS st_city, st.zip AS st_zip')
			->join('LEFT', $db->quoteName('#__virtuemart_order_userinfos') . ' AS st ON st.virtuemart_order_id = o.virtuemart_order_id AND st.address_type="ST"');
		// Join over the paymentmethods
		$query->select('pm.*')
			->join('LEFT', $db->quoteName('#__virtuemart_paymentmethods_pt_br') . ' AS pm ON o.virtuemart_paymentmethod_id = pm.virtuemart_paymentmethod_id');
		// Join over the shipmentmethod
		$query->select('sm.*')
			->join('LEFT', $db->quoteName('#__virtuemart_shipmentmethods_pt_br') . ' AS sm ON o.virtuemart_shipmentmethod_id = sm.virtuemart_shipmentmethod_id');

		$order_status = $this->getState('filter.order_status_code');

		if (!empty($order_status)){
			$query->where( 'o.order_status = "'.$order_status.'"' );
		}
		$only_open = $this->getState('only_open');
		if(!empty($only_open)){
			$status = array("X","R");
			$query->where( 'o.order_status NOT IN ("X","R")' );
			$query->where('o.created_on >= DATE_SUB(CURDATE(), INTERVAL 20 DAY)');
		}
		$view_all = $this->getState('view_all');
		$revenda = $this->getState('revenda');
		if(!empty($revenda)){
			$query->where("o.virtuemart_paymentmethod_id = 32");
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'o.created_on');
		$orderDirn = $this->state->get('list.direction', 'DESC');
		$order_sortby = $this->getState('filter.order_sortby');
		if (!empty($order_sortby)){
			$query->order($db->escape($order_sortby));
		}else if($orderCol === 'o.created_on'){
			$orderDirn = 'DESC';
			$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		} else{
		    $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		}
		// echo $query->dump();exit;
	    return $query;
    }
    public function exportcsv(){
        $items = $this->getItems();
        $order_statuses = $this->getOrderStatusNames();
        // echo "<pre/>";print_r($items);exit;
        $header = array("Order Number","Order Date","Order Total","Status","Address","Contact","Email","Payment","Shipment","Note");
        $filename = "orgonite-brasil_csv.csv";
        $fp = fopen("php://output", 'w');
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        fputcsv($fp, $header);
        foreach ($items as $key => $order) {
        	$orderstate = $this->getVmOrderState( $order->virtuemart_state_id );
        	$orderpayment = $this->getVmOrderPayment( $order->virtuemart_order_id, $order->virtuemart_paymentmethod_id );
        	$ordershipment = $this->getVmOrderShipment( $order->virtuemart_order_id, $order->virtuemart_shipmentmethod_id );
        	$shipment_tracking = $this->getVmOrderShipmentTrack( $order->virtuemart_order_id );
            // $check_picked = $this->getPicked($item->virtuemart_order_id, $item->virtuemart_product_id,$variant->virtuemart_media_id);
            // $check_productlocation = $this->getProductLocation($item->virtuemart_order_id, $item->virtuemart_product_id,$variant->virtuemart_media_id);
            $data = array();
            $data[] = $order->order_number;
            $data[] =  JHtml::_('date', $order->created_on, 'd, M y H:i');
            $data[] =  round( $order->order_total, 2);
            if($order->order_status){
            	foreach($order_statuses as $order_status){
            		if($order_statuses[ $order->order_status ]){
            			if($order_status['order_status_code'] === $order->order_status){
            				$current_state = $order->order_status;
            				$data[] = $order_status['order_status_name'];
            			}
            		}
            	}
            }else{
            	$data[] = '';
            }
            $address = $order->first_name.' '.$order->last_name.'<br>'.$order->address_1.'<br>'.$order->city.'<br>';
            foreach($orderstate as $state){
		     $address.= $state->state_2_code;
	        }
	        $address .= '<br>'.$order->zip;
	        $data[] = $address;
	        $data[] = $order->phone_1.' & '.$order->phone_2;
	        $data[] = $order->order_email;
	        $payment_data = $order->payment_name.'<br>';
	        foreach($orderpayment as $payment){
				$payment_data .= "<br>".JHtml::_('date', $payment->created_on, 'd.m.Y');
	       }
	       $data[] = $payment_data;
	       $shipment_data = $order->shipment_name.'<br>';
	       if($shipment_tracking){
	       		$shipment_data .= $shipment_tracking->tracking_code;
	       		$arr = explode(' ', $shipment_tracking->post_date)[0];
				$arr1 = explode('-', $arr);
				$newDate = $arr1[2].'-'.$arr1[1].'-'.$arr1[0];
				$shipment_data .= $newDate;
	       }
	       foreach($ordershipment as $shipment){
	       		$shipment_data .= '<br>'.JHtml::_('date', $shipment->created_on, 'd.m.Y');
	       }
	       $data[] = $shipment_data;
	       $data[] = $order->oc_note;
            // echo "<pre/>";print_r($data);
            fputcsv($fp, $data);
        }
        exit;
    }

	/*protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.order_status_code');
		return parent::getStoreId($id);
	}*/


	protected function populateState( $ordering = null, $direction = null ) {

	// Initialise variables.
	 $app = JFactory::getApplication();
	 $limit = $this->getUserStateFromRequest($this->context . '.list.limit','limit', $this->state->get('list.limit') ,'uint');
	 $this->setState('list.limit', $limit);

	 $only_open = $this->getUserStateFromRequest($this->context . '.filter.only_open', 'filter_only_open');
	 $this->setState('only_open',$only_open);
	 $view_all = $this->getUserStateFromRequest($this->context . '.filter.view_all', 'filter_view_all','');
	 $revenda = $this->getUserStateFromRequest($this->context . '.filter.revenda', 'filter_revenda','');
	 // echo "<pre/>";print_r($revenda);exit;
	 if($only_open == ""){
	 	$view_all = 'view_all';
	 }
	 $this->setState('revenda',$revenda);
	 $this->setState('view_all',$view_all);
	 $order_status = $this->getUserStateFromRequest($this->context . '.filter.order_status_code', 'filter_order_status_code');
     $this->setState('filter.order_status_code', $order_status);

	 $order_sortby = $this->getUserStateFromRequest($this->context . '.filter.order_sortby', 'filter_order_sortby');
     $this->setState('filter.order_sortby', $order_sortby);

     $limitstart = $app->input->get('limitstart', 0, 'uint');
     $this->setState('list.start', $limitstart);


	 // List state information.
	 parent::populateState('o.created_on', 'DESC');
     }

}
?>
