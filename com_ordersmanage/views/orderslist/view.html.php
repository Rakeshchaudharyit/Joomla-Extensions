<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_ordersmanage
 * @copyright   Copyright (C) .
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
/**
 * Addtag View
 *
 * @since  0.0.1
 */
jimport('joomla.application.component.view');

class OrdersManageViewOrdersList extends JViewLegacy
{
	/**
	 * An array of items
	 * @var  array
	 */
	protected $items;

	/**
	 * The pagination object
	 * @var  JPagination
	 */
	protected $pagination;
	/**
	 * Form object for search filters
	 *
	 * @var  JForm
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var  array
	 */
	public $activeFilters;

	/**
	 * Display the vmordermanage view
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 * @return  void
	 */
	function display($tpl = null)
    {   // Get data from the model
	    $app = JFactory::getApplication();
	    $model = $this->getModel();
		$orderStates = $model->getOrderStatusNames();

		$this->items = $this->get('Items');
		$this->state = $this->get("State");

		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->pagination = $model->getPagination();

		//$this->assignRef('orderslist', $this->items);
		$this->orderslist = &$this->items;
    	//$this->assignRef('orderstatuses', $orderStates);
		$this->orderstatuses = &$orderStates;
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JToolbarHelper::title(JText::_('COM_ORDERSMANAGE_TITLE'));
	}


}
?>