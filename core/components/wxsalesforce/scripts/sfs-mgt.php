<?php
define('MODX_API_MODE', true);
require_once '/mnt/stor9-wc1-dfw1/627233/dev.dealerwebinars.com/web/content/index.php';
if (!($modx instanceof modX)) exit();
# Get wxSalesforce service
$wxSalesforce = $modx->getService('wxsalesforce','wxSalesforce',$modx->getOption('wxSalesforce.core_path',null,$modx->getOption('core_path').'components/wxsalesforce/').'model/wxsalesforce/', array('addtolists' => array(990), 'wsdl' => 'mgt.wsdl', 'classmap' => array(
				'notifications' => 'notifications',
				'notificationsResponse' => 'notificationsResponse',
				'notificationsRequest' => 'notificationsRequest',
				'Actions_Results_Report__cNotification' => 'Actions_Results_Report__cNotification',
				'sObject' => 'sObject',
				'Actions_Results_Report__c' => 'Actions_Results_Report__c',
			)));
if (!($wxSalesforce instanceof wxSalesforce)) return 'could not instantiate wxSalesforce';

$modx->log(modX::LOG_LEVEL_ERROR, 'Salesforce SOAP server invoked.');

$wxSalesforce->handleNotification();

?>