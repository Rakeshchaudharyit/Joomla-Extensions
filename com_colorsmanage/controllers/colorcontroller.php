<?php 
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\MVC\Controller\BaseController;
class ColorcontrollerColor extends JControllerLegacy{


	
	public function display($cachable = false, $urlparams = []){

	
		//JRequest::SetVar('view','color');
		$view   = $this->input->get('view', 'color');
		//exit;
		//parent::display($cachable,$urlparams);
		return parent::display($cachable, $urlparams);
	}
}