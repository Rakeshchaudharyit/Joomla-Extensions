<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Ordersmanage
 * @author     sagar <sagar.beatbrain@gmail.com>
 * @copyright  2021 sagar
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Session\session;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

/**
 * Productcenntrics list controller class.
 *
 * @since  1.6
 */
class OrdersmanageControllerProductcenntrics extends JControllerAdmin
{
	/**
	 * Method to clone existing Productcenntrics
	 *
	 * @return void
     *
     * @throws Exception
	 */
	public function duplicate()
	{
		// Check for request forgeries
		session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		// Get id(s)
		$pks = $this->input->post->get('cid', array(), 'array');

		try
		{
			if (empty($pks))
			{
				throw new Exception(Text::_('COM_ORDERSMANAGE_NO_ELEMENT_SELECTED'));
			}

			ArrayHelper::toInteger($pks);
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(Text::_('COM_ORDERSMANAGE_ITEMS_SUCCESS_DUPLICATED'));
		}
		catch (Exception $e)
		{
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}

		$this->setRedirect('index.php?option=com_ordersmanage&view=productcenntrics');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @return  object	The Model
	 *
	 * @since    1.6
	 */
	public function getModel($name = 'productcenntric', $prefix = 'OrdersmanageModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	public function exportcsv(){
		$models = $this->getModel('productcenntrics','OrdersmanageModel');
		$models->exportcsv();
		return true;
	}



	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
     *
     * @throws Exception
     */
	public function saveOrderAjax()
	{
		// Get the input
		$input = Factory::getApplication()->input;
		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		Factory::getApplication()->close();
	}
	public function changePicked(){
		$id = JFactory::getApplication()->input->get("id");
		$checked = JFactory::getApplication()->input->get("checked");
		$order_id = explode("_", $id)[0];
		$product_id = explode("_", $id)[1];
		$media_id = explode("_", $id)[2];

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("id")->from("#__pick_productlocation_order")->where("`virtuemart_order_id` = ".$order_id);
		$query->where("`virtuemart_product_id` = ".$product_id);
		if($media_id){
			$query->where("`virtuemart_media_id` = ".$media_id);
		}
		$db->setQuery($query);
		$check_id = $db->loadResult();
		// $check_id."<br>".$checked."<br>".$order_id."<br>".$product_id."<br>".$media_id;exit;
		if($check_id){
			$query = $db->getQuery(true);
			$query->update("#__pick_productlocation_order")->set("`picked` = ".$checked);
			$query->where("`virtuemart_product_id` = ".$product_id);
			$query->where("`virtuemart_order_id` = ".$order_id);
			if($media_id){
				$query->where("`virtuemart_media_id` = ".$media_id);
			}
			// echo $query->dump();exit;
			$db->setQuery($query);
			$result = $db->execute();
		}else{
			$query = $db->getQuery(true);
			$columns = array('virtuemart_order_id', 'virtuemart_product_id', 'picked', 'produced');
			if($media_id){
				$columns[] = "virtuemart_media_id";
			}
			$values = array($order_id, $product_id, $checked, 0);
			if($media_id){
				$values[] = $media_id;
			}
			$query
			    ->insert($db->quoteName('#__pick_productlocation_order'))
			    ->columns($db->quoteName($columns))
			    ->values(implode(',', $values));
			// echo $query->dump();exit;
			$db->setQuery($query);
			$result = $db->execute();
		}
		echo json_encode($result);exit;
	}

	public function changeProduced(){
		$id = JFactory::getApplication()->input->get("id");
		$checked = JFactory::getApplication()->input->get("checked");
		$order_id = explode("_", $id)[0];
		$product_id = explode("_", $id)[1];
		$media_id = explode("_", $id)[2];

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("id")->from("#__pick_productlocation_order")->where("`virtuemart_order_id` = ".$order_id);
		$query->where("`virtuemart_product_id` = ".$product_id);
		if($media_id){
			$query->where("`virtuemart_media_id` = ".$media_id);
		}
		$db->setQuery($query);
		$check_id = $db->loadResult();

		if($check_id){
			$query = $db->getQuery(true);
			$query->update("#__pick_productlocation_order")->set("`produced` = ".$checked);
			$query->where("`virtuemart_product_id` = ".$product_id);
			$query->where("`virtuemart_order_id` = ".$order_id);
			if($media_id){
				$query->where("`virtuemart_media_id` = ".$media_id);
			}
			// echo $query->dump();exit;
			$db->setQuery($query);
			$result = $db->execute();
		}else{
			$query = $db->getQuery(true);
			$columns = array('virtuemart_order_id', 'virtuemart_product_id', 'picked', 'produced');
			if($media_id){
				$columns[] = "virtuemart_media_id";
			}
			$values = array($order_id, $product_id, 0, $checked);
			if($media_id){
				$values[] = $media_id;
			}
			$query
			    ->insert($db->quoteName('#__pick_productlocation_order'))
			    ->columns($db->quoteName($columns))
			    ->values(implode(',', $values));
			// echo $query->dump();exit;
			$db->setQuery($query);
			$result = $db->execute();
		}
		echo json_encode($result);exit;
	}
	public function exportpdf()
	{ 
		
		 $input = Factory::getApplication()->input;
		$inputids = Factory::getApplication()->input->post->get("array");
		
		$proimage =  $input->post->get("proimage", array(), 'array');
		$orderitem = $input->post->get("orderitem", array(), 'array');
		$ordersku = $input->post->get("ordersku", array(), 'array');
		$orderitemqty = $input->post->get("orderitemqty", array(), 'array');
		$orderitemorderid = $input->post->get("orderitemorderid", array(), 'array');
		$orderitemdate = $input->post->get("orderitemdate", array(), 'array');
		$orderitemname = $input->post->get("orderitemname", array(), 'array');
		$orderitemcity = $input->post->get("orderitemcity", array(), 'array');
		
		$itemlocationsymb = $input->post->get("itemlocationsymb", array(), 'array');
		$itemlocationprod = $input->post->get("itemlocationprod", array(), 'array');
		$result='<style>
	table {
	  font-family: arial, sans-serif;
	  border-collapse: collapse;
	  width: 100%;
	}
	
	td, th {
	  border: 1px solid #dddddd;
	  text-align: left;
	  padding: 8px;
	}
	
	tr:nth-child(even) {
	  background-color: #dddddd;
	}
	</style>';
	$result.='<table>
	<tr>
	  <th>Imagem do Produto</th>
	  <th>Nome do Produto & SKU</th>
	  <th>QTY</th>
	  <th>OrderID & Encontro</th>
	  <th>Nome do cliente & Cidade</th>
	  <th>MediaLocation</th>
	</tr>';
		for($k=0;$k<count($inputids);$k++) {
			$result.='<tr>
				<td><img src="'.$proimage[$k].'" width="100" height="100"/></td>
				<td>'.$orderitem[$k].' <br/> '.$ordersku[$k].'</td>
				<td>'.$orderitemqty[$k].'</td>
				<td>'.$orderitemorderid[$k].'<br/>'.$orderitemdate[$k].'</td>
				<td>'.$orderitemname[$k].'<br/> City: '.$orderitemcity[$k].'</td>
				<td>Locationsymb: '.$itemlocationsymb[$k].' <br/> Locationprod: '.$itemlocationprod[$k].'</td>
				</tr>';
			}
			
	$result.='</table>';
  echo $result;
	exit;
	}
}
