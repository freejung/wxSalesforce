<?php
define('MODX_API_MODE', true);
require_once '/mnt/stor9-wc1-dfw1/627233/dev.dealerwebinars.com/web/content/index.php';
if (!($modx instanceof modX)) exit();
# Get wxSalesforce service
$wxSalesforce = $modx->getService('wxsalesforce','wxSalesforce',$modx->getOption('wxSalesforce.core_path',null,$modx->getOption('core_path').'components/wxsalesforce/').'model/wxsalesforce/', array('addtolists' => array(988), 'wsdl' => 'task.wsdl', 'classmap' => array(
				'notifications' => 'notifications',
				'notificationsResponse' => 'notificationsResponse',
				'notificationsRequest' => 'notificationsRequest',
				'TaskNotification' => 'TaskNotification',
				'sObject' => 'sObject',
				'Task' => 'Task',
			)));
if (!($wxSalesforce instanceof wxSalesforce)) return 'could not instantiate wxSalesforce';

$modx->log(modX::LOG_LEVEL_ERROR, 'Salesforce SOAP server invoked.');

$wxSalesforce->handleNotification();

?>