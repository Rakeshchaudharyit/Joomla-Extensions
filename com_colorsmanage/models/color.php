<?php

defined('_JEXEC') or die ('Restricted access');

/**
 * 
 */
class colorModelColor extends JModelList
{
	
	public function __construct($config = array()){
	if(empty($config['filter_fields'])){

         $config['filter_fields'] = array(

           'ID','ID',
           'mediaID','mediaID',
           'location','location',
           'bgcolor','bgcolor',
           'simbcolor','simbcolor',
           'file_title','file_title',
		   'locationsymb','locationsymb',
		   'locationprod','locationprod'
      );
        
		}

		parent::__construct($config);


	}


protected function populateState($ordering = null, $direction = null){
		
	$search = $this->getUserStateFromRequest($this->context.'.filter.search','filter_search');
	$this->setState('filter.search',$search);

	parent::populateState('virtuemart_media_id','asc');
}


public function getListQuery(){
		
	
	if(isset($_POST["bg"])&&isset($_POST["sm"])){
$db = $this->getDbo();
	$query2 = $db->getQuery(true);

$length = count($_POST["bg"]);

for($i=0;$i<=$length;$i++){
if(isset($_POST["bg"][$i])){
	
$bg = $_POST["bg"][$i];
$sm = $_POST["sm"][$i];
$loc = $_POST["loc"][$i];
$losy = $_POST["losy"][$i];
$losp = $_POST["losp"][$i];
$id2 = $_POST["id2"][$i];
if($id2){
$updateNulls = true;

// Create an object for the record we are going to update.
$object = new stdClass();

// Must be a valid primary key value.
$object->mediaID = $id2;
$object->bgcolor = $bg;
$object->simbcolor = $sm;
$object->location = $loc;
$object->locationsymb = $losy;
$object->locationprod = $losp;

$db = $this->getDbo();
	$query = $db->getQuery(true);
$query->select('*')
	       ->from('#__vmmedia');
	       $searches = array('mediaID ='.$id2);
        $query->where('('.implode($searches).')');
	       
	       $db->setQuery($query);

	       $results = $db->loadObjectList();
	
	 if(isset($results[0]->mediaID)){

 $result = JFactory::getDbo()->updateObject('#__vmmedia', $object, 'mediaID', $updateNulls);
}else{
	
 $result = JFactory::getDbo()->insertObject('#__vmmedia', $object);

}

}

}

}

}

$db = $this->getDbo();
	$query = $db->getQuery(true);

	 $query->select('*')
	       ->from('#__virtuemart_medias a')
	       ->join('LEFT', '#__vmmedia AS b ON (a.virtuemart_media_id = b.mediaID)' )
	->order('a.virtuemart_media_id ASC');
	
	if(isset($_POST["filter-search"])){
 

		$token = $db->Quote('%'.$db->escape($_POST["filter-search"]).'%');
		$searches = array('file_title LIKE'.$token);
        $query->where('('.implode(" OR ",$searches).')');;

	}

	 $query->order($db->escape($this->getState('list.ordering')).' '.$db->escape($this->getState('list.direction','ASC')));
	

	return $query;
}








}

