<?php
# Get webinex, wxEloqua, and wxSalesforce services
$webinex = $modx->getService('webinex','Webinex',$modx->getOption('webinex.core_path',null,$modx->getOption('core_path').'components/webinex/').'model/webinex/',$scriptProperties);
if (!($webinex instanceof Webinex)) return 'could not instantiate Webinex';
$wxEloqua = $modx->getService('wxeloqua','wxEloqua',$modx->getOption('wxEloquaDev.core_path',null,$modx->getOption('core_path').'components/wxeloquadev/').'model/wxeloqua/',$scriptProperties);
if (!($wxEloqua instanceof wxEloqua)) return 'could not instantiate wxEloqua';
$wxSalesforce = $modx->getService('wxsalesforce','wxSalesforce',$modx->getOption('wxSalesforce.core_path',null,$modx->getOption('core_path').'components/wxsalesforce/').'model/wxsalesforce/',$scriptProperties);
if (!($wxSalesforce instanceof wxSalesforce)) return 'could not instantiate wxSalesforce';

$request = $modx->getOption('request',$scriptProperties,'');
$sharedList = $modx->getOption('sharedList',$scriptProperties,'');

$response = $wxSalesforce->handleCaseNotification($request);

$elqContactIds = $wxEloqua->getContactsBySFID($wxSalesforce->contactIds);

foreach ($elqContactIds as $contactId) {
	$modx->log(modX::LOG_LEVEL_ERROR, 'Notification received for Eloqua contact: '.$contactId);
}
$modx->log(modX::LOG_LEVEL_ERROR, $response);
return $response;