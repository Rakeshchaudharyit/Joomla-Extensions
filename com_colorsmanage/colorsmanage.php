<?php

defined('_JEXEC') or die("Restricted access");

if(!defined('DS')){

	define('DS', DIRECTORY_SEPARATOR);
}

$c = JRequest::getVar('c');

if($c==''){

	$c='colorcontroller';
}

$path = JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.$c.'.php';

if($path){

	require_once $path;
}else{

	
	JError::raiseError('500',JText::_('JTUNKNOWN_COMPONENT').' '.$C.' '.$path);
}


	$classname = 'ColorcontrollerColor';
	$controller = new $classname();

	$controller->execute(JFactory::getApplication()->input->get('task'));
	$controller->redirect();



