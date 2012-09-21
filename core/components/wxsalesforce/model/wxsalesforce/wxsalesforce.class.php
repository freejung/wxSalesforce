<?php

class notificationsResponse
{

  /**
   * 
   * @var bool $Ack
   * @access public
   */
  public $Ack;

  /**
   * 
   * @param bool $Ack
   * @access public
   */
  public function __construct($Ack)
  {
    $this->Ack = $Ack;
  }

}

class Member
{

  /**
   * 
   * @var string $EntityId
   * @access public
   */
  public $EntityId;

  /**
   * 
   * @param string $EntityId
   * @access public
   */
  public function __construct($EntityId)
  {
    $this->EntityId = $EntityId;
  }

}

class notificationsRequest
{

  /**
   * 
   * @var notifications $request
   * @access public
   */
  public $request;

  /**
   * 
   * @param notifications $request
   * @access public
   */
  public function __construct($request)
  {
    $this->request = $request;
  }

}

class CaseNotification
{

  /**
   * 
   * @var ID $Id
   * @access public
   */
  public $Id;
   /**
   * 
   * @var Case $sObject
   * @access public
   */
  public $sObject;

  /**
   * 
   * @param string $Id
   * @access public
   * 
   * @param Case $sObject
   * @access public
   */
  public function __construct($Id, $sObject)
  {
    $this->Id = $Id;
    $this->sObject = $sObject;
  }

}

class sObject
{

  /**
   * 
   * @var ID $Id
   * @access public
   */
  public $Id;
   /**
   * 
   * @var string $fieldsToNull
   * @access public
   */
  public $fieldsToNull;

  /**
   * 
   * @param string $fieldsToNull
   * @access public
   * 
   * @param ID $Id
   * @access public
   */
  public function __construct($fieldsToNull, $Id)
  {
    $this->Id = $Id;
    $this->fieldsToNull = $fieldsToNull;
  }

}

class sCase extends sObject
{
	/**
   * 
   * @var ID $contactId
   * @access public
   */
  public $contactId;
  
  /**
   * 
   * @param string $fieldsToNull
   * @access public
   * 
   * @param ID $Id
   * @access public
   * 
   * @param ID $ContactId
   * @access public
   */
  public function __construct($fieldsToNull, $Id, $ContactId)
  {
    $this->ContactId = $ContactId;
    parent::__construct($fieldsToNull, $Id);
  }
}

class notifications
{

  /**
   * 
   * @var string $OrganizationId
   * @access public
   */
  public $OrganizationId;
  
  /**
   * 
   * @var string $ActionId
   * @access public
   */
  public $ActionId;
  
/**
   * 
   * @var string $SessionId
   * @access public
   */
  public $SessionId;
  
  /**
   * 
   * @var string $EnterpriseUrl
   * @access public
   */
  public $EnterpriseUrl;
  /**
   * 
   * @var string $PartnerUrl
   * @access public
   */
  public $PartnerUrl;
  /**
   * 
   * @var CaseNotification $Notification
   * @access public
   */
  public $Notification;
  /**
   * 
   * @var array of CaseNotification $NotificationArray
   * @access public
   */
  public $NotificationArray;

  /**
   * 
   * @param string $OrganizationId
   * @access public
   *
   * @param string $ActionId
   * @access public
   *
   * @param string $SessionId
   * @access public
*
   * @param string $EnterpriseUrl
   * @access public
   *
   * @param string $PartnerUrl
   * @access public
   *
   * @param CaseNotification $Notification
   * @access public
   */
  public function __construct($OrganizationId, $ActionId, $SessionId, $EnterpriseUrl, $PartnerUrl, $Notification)
  {
    $this->OrganizationId = $OrganizationId;
	$this->ActionId = $ActionId;
	$this->SessionId = $SessionId;
	$this->EnterpriseUrl = $EnterpriseUrl;
	$this->PartnerUrl = $PartnerUrl;
	$this->Notification = $Notification;
  }
  
  public function getNotificationArray () {
  	if(is_array($this->Notification)){
		$this->NotificationArray = $this->Notification;
	}else{
		$this->NotificationArray = array($this->Notification);
	}
	return $this->NotificationArray;
  }

}

class wxNotifications {
	public $modx;
    public $config = array();
    public function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge(array(
			'addtolists' => array(714),        
        ),$config);
    }
    
    public function notifications($request) {
		$wxEloqua = $this->modx->getService('wxeloqua','wxEloqua',$this->modx->getOption('wxEloqua.core_path',null,$this->modx->getOption('core_path').'components/wxeloqua/').'model/wxeloqua/');
		if (!($wxEloqua instanceof wxEloqua)) {
			$response = new notificationsResponse(false);
			return $response;
		}
		$caseNotifications = $request->getNotificationArray();
		foreach ($caseNotifications as $caseNotification) {
			$contactIds[] = substr($caseNotification->sObject->ContactId, 0, 15);
		}
		$elqContactIds = $wxEloqua->getContactsByFieldValues($contactIds, 'C_SFDCContactID');
		if(count($elqContactIds) < count($contactIds)) {
			foreach ($contactIds as $contactId) {
				$this->modx->log(modX::LOG_LEVEL_ERROR, 'Salesforce Case Notification Server error: contact '.$contactId. 'was not added to shared list, because some contacts in notification were not found.');
			}
			$response = new notificationsResponse(false);
			return $response;
		}
		$members = array();
		$addToLists = array();
		foreach ($elqContactIds as $contactId) {
			$this->modx->log(modX::LOG_LEVEL_ERROR, 'Notification received for Eloqua contact: '.$contactId);
			$members[] = new Member($contactId);
			$addToLists[$contactId] = $this->config['addtolists'];
		}
		$wxEloqua->members = $members;
		$addResult = $wxEloqua->addToLists($addToLists);
		$success = true;
		foreach ($addResult as $id => $lists) {
			foreach ($lists as $list => $result) {
				$this->modx->log(modX::LOG_LEVEL_ERROR, 'Add contact '.$id.' to list '.$list.' result: '.$result);
				if(!$result) $success = false;
			}
		}
		$response = new notificationsResponse($success);
		return $response;
	}
}


class wxSalesforce {
    public $modx;
    public $config = array();
    private $ClassMap = array();
    private $caseWSDL = '';
    
    public function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
        $this->config = array_merge(array(
        	'classmap' => array(
				'notifications' => 'notifications',
				'notificationsResponse' => 'notificationsResponse',
				'notificationsRequest' => 'notificationsRequest',
				'CaseNotification' => 'CaseNotification',
				'sObject' => 'sObject',
				'Case' => 'sCase',
			),
			'casewsdl' => $this->modx->getOption('wxSalesforce.core_path',null,$this->modx->getOption('core_path').'components/wxsalesforce/').'model/wxsalesforce/wsdl/sfs.wsdl',
			'addtolists' => array(714),
        ),$config);
        $this->ClassMap = $this->config['classmap'];
        $this->caseWSDL = $this->config['casewsdl'];
    }
	
	public function handleCaseNotification() {
		$server = new SoapServer($this->caseWSDL, array('classmap' => $this->ClassMap));
		$wxNotifications = new wxNotifications($this->modx, array('addtolists' => $this->config['addtolists']));
		$server->setObject($wxNotifications);
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			try {
			$server->handle();
			}catch (Exception $e)
			{
			$this->modx->log(modX::LOG_LEVEL_ERROR, 'Salesforce server error: '.$e->getMessage());
			}
		} else {
			$this->modx->log(modX::LOG_LEVEL_ERROR, 'Salesforce server error: called without post data');
		}
		return true;
	}
	
}