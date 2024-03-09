<?php

/**

 * @package     Joomla.Administrator

 * @subpackage  com_ordermanage

 * @copyright   Copyright (C) .

 * @license     GNU General Public License version 2 or later;

 */

// No direct access to this file

defined('_JEXEC') or die('Restricted access');


Use Joomla\CMS\Factory;
/**

 * Addtag Controller

 *

 * @since  0.0.1

 */

class OrdersManageControllerOrdersList extends JControllerAdmin

{

    public function updateorder(){
        if (!class_exists( 'VmConfig' )){
            require(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
        }
        VmConfig::loadConfig();
        VmConfig::setdbLanguageTag();
        $jinput = JFactory::getApplication()->input;
        $id = $jinput->get("id");
        $cur_state = $jinput->get("cur_state");
        $new_state = $jinput->get("new_state");

        // $rdata = $jinput->getArray(array('on'=>'','op'=>'','os'=>''));
        $model = VmModel::getModel('orders');
        $order = $model->getOrder($id);
        // echo "<pre/>";print_r($order);exit;
        // $virtuemart_order_id = $model->getOrderIdByOrderPass($rdata['on'],$rdata['op']);
        $orders = array();
        $orders['order_status'] = $new_state;
        $orderstatusForShopperEmail = VmConfig::get('email_os_s',array('U','C','S','R','X'));
        if(!is_array($orderstatusForShopperEmail)) $orderstatusForShopperEmail = array($orderstatusForShopperEmail);
        $orders['customer_notified'] = (in_array($orders['order_status'],$orderstatusForShopperEmail)) ? 1 : 0;
        $orders['include_comment'] = 0;
        $result = $model->updateStatusForOneOrder ($id,$orders,TRUE);
        $user = JFactory::getUser();
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $columns = array('virtuemart_order_id', 'user_id');
        $values = array($id, $user->id);
        $query
            ->insert($db->quoteName('#__virtuemart_order_state_change'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));
        $db->setQuery($query);
        $result = $db->execute();
        echo $result;exit;
    }

    public function updateshipinfo(){
        $jinput = JFactory::getApplication()->input;
        $id = $jinput->get("id");
        $info = $jinput->getHTML("info");
        $temp = $jinput->getHTML("date");
        $date = date("Y-m-d h:i:s", strtotime($temp));
       // echo $date;exit;
        // $date .= " 00:00:01";
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id')))
              ->from($db->quoteName('#__virtuemart_shipment_tracking'))
              ->where($db->quoteName('virtuemart_order_id') .'='.$db->quote($id));
        $db->setQuery($query);
        $ship_id = $db->loadResult();
        if($ship_id){
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->update($db->quoteName('#__virtuemart_shipment_tracking'))
                   ->set($db->quoteName('tracking_code') .'='.$db->quote($info))
                   ->set($db->quoteName('post_date') .'='.$db->quote($date))
                   ->where($db->quoteName('virtuemart_order_id') .'='.$db->quote($id));
            // echo $query->dump();exit;
            $db->setQuery($query);
            $result = $db->execute();
        }else{
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $columns = array('virtuemart_order_id', 'tracking_code','post_date');
            $values = array($id, $db->quote($info), $db->quote($date));
            $query
                ->insert($db->quoteName('#__virtuemart_shipment_tracking'))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
            $db->setQuery($query);
            $result = $db->execute();
        }
        echo $result;exit;
    }
    public function emailtrackinginfo()
    {
        $jinput = Factory::getApplication()->input;
        $emailadd =  $jinput->get('emailadd', 'default_value', 'filter');
        $bcc = $jinput->get('bcc', 'default_value', 'filter');
        $subject = $jinput->get("subject");
        $fullname = $jinput->get("fullname");
        $trackingcode = $jinput->get("trackingcode");
        $config = Factory::getConfig();
        $sender = array( 
        $config->get('mailfrom'),
        $config->get('fromname') 
        );
        $mailer = Factory::getMailer();
        $mailer->setSender($sender);
        $mailer->addRecipient($emailadd);
        $mailer->addBCC($bcc);
        $mailer->setSubject($subject);
        $mailer->isHtml(true);
        $mailer->Encoding = 'base64';
        $html_msgBody = "Bom dia ". $fullname ."!\n<br><br>Seu pedido de orgonites foi postado nos correios e pode ser rastreado pelo link seguinte:<br/><br/>
			   <span style='font-weight:bold;font-size:18px;'>Código:".$trackingcode."</span><br/><span style='font-weight:bold;font-size:18px;'>Link:http://www.websro.com.br/rastreamento-correios.php?P_COD_UNI=".$trackingcode."</span><br/><br/>
			   Agradeçemos a sua confiança e por gentileza pedimos a retornar uma pequena confirmação quando recebeu!<br/><br/>
			   Atenciosamente<br/>Bernhard Aggeler<br/>Orgonite Brasil<br/>";
        $body = $html_msgBody;
        $mailer->setBody($body);
        $send = $mailer->Send();
        if ($send !== true) 
        {
        echo 'Error sending email: ' . $send->__toString();
        } 
        else 
        {
        echo '1';
        }
        exit;
    }
    public function updateocnote(){
        $jinput = JFactory::getApplication()->input;
        $id = $jinput->get("id");
        $oc_note = $jinput->getHTML("oc_note");
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->update("#__virtuemart_orders")->set("`oc_note` = ".$db->quote($oc_note));
        $query->where("`virtuemart_order_id` = ".$id);
        $db->setQuery($query);
        $result = $db->execute();
        echo $result;exit;
    }
    public function getModel($name = 'orderslist', $prefix = 'OrdersmanageModel', $config = array())
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));

        return $model;
    }
    public function exportcsv(){
        $models = $this->getModel('orderslist','OrdersmanageModel');
        $models->exportcsv();
        return true;
    }
}