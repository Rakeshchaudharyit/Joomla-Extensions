<?php

defined('_JEXEC') or die ('Restricted access');

/**
 * 
 */
class colorViewcolor extends JViewLegacy
{
	
	
	function display($tpl = null)
	{

		

		$this->item = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->addTollbar();
		parent::display($tpl);
	}

  function addTollbar(){

    JToolbarHelper::title('Media Colors');
    // JToolbarHelper::addNew('add');
    // JToolbarHelper::editList('edit');
    // JToolbarHelper::deleteList('Are you sure?','delete');

  

  }






}