<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Ordersmanage
 * @author     sagar <sagar.beatbrain@gmail.com>
 * @copyright  2021 sagar
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

use \Joomla\CMS\Helper\TagsHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\Utilities\ArrayHelper;
/**
 * Methods supporting a list of Ordersmanage records.
 *
 * @since  1.6
 */
class OrdersmanageModelProductcenntrics extends JModelList
{




public function __construct($config = array()){
	 if (empty($config['filter_fields'])){
			$config['filter_fields'] = array(
				'id', 'o.virtuemart_order_id',
				'created', 'o.created_on',
                'escolhida','o.escolhida',
				'order_item_name','oi.order_item_name',
				'produzida','o.produzida',
                'virtuemart_order_id','o.virtuemart_order_id',
                'first_name','u.first_name'
			);
	  }
	  parent::__construct($config);
	}


    /**
     * Method to build an SQL query to load the list data.
     * @return string  An SQL query
     */
    function getListQuery(){
        $task = JFactory::getApplication()->input->get("task");

		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select(
			$this->getState(
				'list.select', 'oi.*'
			)
		);
		$query->from('#__virtuemart_order_items as oi');
		// Join over the userinfos as u
		$query->select("o.virtuemart_user_id,o.order_number")
			->join('LEFT', $db->quoteName('#__virtuemart_orders') . ' AS o ON oi.virtuemart_order_id = o.virtuemart_order_id');
		$query->select('u.*,u.city AS st_city, u.email as order_email')
			->join('LEFT', $db->quoteName('#__virtuemart_order_userinfos') . ' AS u ON u.virtuemart_order_id = oi.virtuemart_order_id AND u.address_type="BT"');
        $revenda = $this->getState('revenda');
        if(!empty($revenda)){
            $query->where("o.virtuemart_paymentmethod_id = 32");
        }
		// $query->select('cp.virtuemart_bgcolor')
		// 	->join('INNER', $db->quoteName('#__colorswitchorder_products') . ' AS cp ON cp.virtuemart_product_id = oi.virtuemart_product_id');
		// $query->select('cp.virtuemart_media_id, cp.virtuemart_bgcolor, cp.virtuemart_simbcolor')
		// 	->join('LEFT', $db->quoteName('#__colorswitchorder_products') . ' AS cp ON cp.virtuemart_order_id = oi.virtuemart_order_id AND cp.virtuemart_product_id=oi.virtuemart_product_id');

		// $order_status = $this->getState('filter.order_status_code');

		// if (!empty($order_status)){
		// 	$query->where( 'o.order_status = "'.$order_status.'"' );
		// }
		// $only_open = $this->getState('only_open');
		// if(!empty($only_open)){
		// 	$status = array("X","R");
		// 	$query->where( 'o.order_status NOT IN ("X","R")' );
		// 	$query->where('o.created_on >= DATE_SUB(CURDATE(), INTERVAL 20 DAY)');
		// }
		// $view_all = $this->getState('view_all');


		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', 'o.created_on');
		$orderDirn = $this->state->get('list.direction', 'DESC');
		
		//echo $orderDirn;
	

		$order_sortby = $this->getState('list.order_sortby');
		// echo $order_sortby;exit;
		if (!empty($order_sortby)){
			$query->order($db->escape($order_sortby));
		// }else if($orderCol === 'o.created_on'){
		// 	$orderDirn = 'DESC';
		// 	$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		} else{
			if($orderCol == 'o.escolhida')
			{
				$query->join('LEFT', $db->quoteName('#__pick_productlocation_order') . ' AS ppl ON oi.virtuemart_order_id = ppl.virtuemart_order_id');
				$query->order($db->escape('ppl.picked') . ' ' . $db->escape($orderDirn));
			}else{
			    if($orderCol == 'o.produzida')
				{
					$query->join('LEFT', $db->quoteName('#__pick_productlocation_order') . ' AS ppl ON oi.virtuemart_order_id = ppl.virtuemart_order_id');
				$query->order($db->escape('ppl.produced') . ' ' . $db->escape($orderDirn));
				}else
				{
				    $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
				}
				
			}
		    
		}
		$app = JFactory::getApplication();
		$limit = $this->getUserStateFromRequest($this->context . '.list.limit','limit', $this->state->get('list.limit') ,'uint');
	 $this->setState('list.limit', $limit);
	
		//echo "limit start:".$limit;
		$query->setLimit($limit);
	//echo $query;
	//exit;
	    return $query;
    }
    public function exportcsv(){
        $items = $this->getItems();
        $header = array("Product Name & SKU","QTY","OrderID & Date","Client Name & City","Picked","Produced");
        $filename = "orgonite-brasil_csv.csv";
         $fp = fopen("php://output", 'w');
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
        fputcsv($fp, $header);
        foreach ($items as $key => $item) {
            $check_picked = $this->getPicked($item->virtuemart_order_id, $item->virtuemart_product_id,$variant->virtuemart_media_id);
            $check_productlocation = $this->getProductLocation($item->virtuemart_order_id, $item->virtuemart_product_id,$variant->virtuemart_media_id);
            $data = array();
            $data[] = $item->order_item_name.' & '.$item->order_item_sku;
            $data[] =  $item->product_quantity;
            $data[] =  $item->virtuemart_order_id.' & '.$item->created_on;
            $data[] = $item->first_name.' '.$item->last_name.' & '.$item->city;
            if($check_picked){
                $data[] = "yes";
            }else{
                $data[] = "no";
            }
            if($check_productlocation){
                $data[] = "yes";
            }else{
                $data[] = "no";
            }
            // echo "<pre/>";print_r($data);
            fputcsv($fp, $data);
        }
        exit;
    }
    public function getItems(){
    	$db = JFactory::getDBO();
    	$query = $db->getQuery(true);
    	$query = $this->getListQuery();
    	$db->setQuery($query);
    	$items = $db->loadObjectList();
    	$temp = array();
    	// echo count($items);
    	foreach ($items as $key => $item) {
    		$temp[$item->virtuemart_order_id.'_'.$item->virtuemart_product_id] = $item;
    	}
    	foreach ($temp as $key => $item) {
    		/*$query1 = $db->getQuery(true);
    		$query1->select("cp.virtuemart_media_id,m.file_url")->from($db->quoteName("#__colorswitchorder_products","cp"));
    		$query1->join("INNER",$db->quoteName("#__virtuemart_medias","m").' ON '.$db->quoteName("cp.virtuemart_media_id").' = '.$db->quoteName("m.virtuemart_media_id"));
    		$query1->where("`cp`.`virtuemart_product_id` = ".$item->virtuemart_product_id);
    		$query1->where("`cp`.`virtuemart_order_id` = ".$item->virtuemart_order_id);
			echo $query1;*/
			$query = $db->getQuery(true);
			$query->select(array('cp.virtuemart_media_id', 'm.file_url'))
			->from($db->quoteName('#__colorswitchorder_products', 'cp'))
			->join('INNER', $db->quoteName('#__virtuemart_medias', 'm') . ' ON ' . $db->quoteName('cp.virtuemart_media_id') . ' = ' . $db->quoteName('m.virtuemart_media_id'))
			->where($db->quoteName('cp.virtuemart_product_id') . ' LIKE ' . $db->quote($item->virtuemart_product_id))
			->where($db->quoteName('cp.virtuemart_order_id') . ' LIKE ' . $db->quote($item->virtuemart_order_id));
    		$db->setQuery($query);
    		$item->product_variant = $db->loadObjectList();
    	}
    	 //echo "<pre/>";print_r($temp);
    	return $temp;
    }
	/*protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.order_status_code');
		return parent::getStoreId($id);
	}*/
	public function getpdfrecords()
	{
		
	}
	public function pmedia($pid){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__virtuemart_product_medias'));
		$query->where('ordering=1');
		$query->where($db->quoteName('virtuemart_product_id')." = ".$db->quote($pid) );
		$db->setQuery($query);
		$row = $db->loadAssoc();
		$mid=$row['virtuemart_media_id'];
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__virtuemart_medias'));
		$query->where($db->quoteName('virtuemart_media_id')." = ".$db->quote($mid));
		$db->setQuery($query);
		$row2 =$db->loadAssoc();
		$image=$row2['file_url'];
		return $image ;
		}
	 public function mediaid($pid)
	 {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__virtuemart_product_medias'));
		$query->where('ordering=1');
		$query->where($db->quoteName('virtuemart_product_id')." = ".$db->quote($pid) );
		$db->setQuery($query);
		$row = $db->loadAssoc();
		$mid=$row['virtuemart_media_id'];
		return $mid;
	 }	
	public function getProductImage($order_id,$product_id){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("virtuemart_media_id")
				->from($db->quoteName('#__colorswitchorder_products'))
				->where($db->quoteName('virtuemart_order_id') . '=' . $db->quote($order_id))
				->where($db->quoteName('virtuemart_product_id') . ' LIKE ' . $db->quote($$product_id));
		/*$query->select("virtuemart_media_id")
			->from("#__colorswitchorder_products")
			->where("`virtuemart_order_id` = ".$order_id)
			->where("`virtuemart_product_id` = ".$product_id);	*/
		$db->setQuery($query);
		$media_id = $db->loadResult();
		$file_url = "";
		if($media_id){
			$query = $db->getQuery(true);
			$query->select("file_url")
			      ->from($db->quoteName('#__virtuemart_medias'))
				  ->where($db->quoteName('virtuemart_media_id') .'='.$db->quoteName($media_id));
			$db->setQuery($query);
			$file_url = $db->loadResult();
		}
		return $file_url;
	}
	public function getPicked($order_id, $product_id, $media_id = null){
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		if($media_id){
			$query->select("picked")
			->from($db->quoteName("#__pick_productlocation_order"))
			->where($db->quoteName('virtuemart_order_id').'='.$db->quote($order_id))
			->where($db->quoteName('virtuemart_product_id').'='.$db->quote($product_id))
			->where($db->quoteName('virtuemart_media_id') .'='. $db->quote($media_id));
		}
		else
		{
			$query->select("picked")
			->from($db->quoteName("#__pick_productlocation_order"))
			->where($db->quoteName('virtuemart_order_id').'='.$db->quote($order_id))
			->where($db->quoteName('virtuemart_product_id').'='.$db->quote($product_id));
		}
		
		$db->setQuery($query);
		$check = $db->loadResult();
		return $check;
	}
	public function getProductLocation($order_id, $product_id, $media_id = null){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("produced")
		      ->from($db->quoteName("#__pick_productlocation_order"))
			  ->where($db->quoteName('virtuemart_order_id') .'='.$db->quote($order_id))
			  ->where($db->quoteName('virtuemart_product_id') .'='.$db->quote('$product_id'));
		if($media_id){
			$query->where($db->quoteName('virtuemart_media_id') .'='.$db->quote('$media_id'));
		}
		$db->setQuery($query);
		$check = $db->loadResult();
		return $check;
	}
	protected function populateState( $ordering = null, $direction = null ) {

	// Initialise variables.
	 $app = JFactory::getApplication();
	 $limit = $this->getUserStateFromRequest($this->context . '.list.limit','limit', $this->state->get('list.limit') ,'uint');
	 $this->setState('list.limit', $limit);
	
	
     $revenda = $this->getUserStateFromRequest($this->context . '.filter.revenda', 'filter_revenda','');
     $this->setState('revenda',$revenda);
	 $order_sortby = $this->getUserStateFromRequest($this->context . '.list.order_sortby', 'list_order_sortby');
     $this->setState('list.order_sortby', $order_sortby);

     $limitstart = $app->input->get('limitstart', 0, 'uint');
     $this->setState('list.start', $limitstart);
		

	 // List state information.
	// parent::populateState('o.created_on', 'DESC');
        parent::populateState($ordering,$direction);
     }
}
