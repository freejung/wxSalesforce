<?php
$client = new SoapClient('sfs.wsdl', array('trace' => 1));
$result = $client->__doRequest('<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
 <soapenv:Body>
  <notifications xmlns="http://soap.sforce.com/2005/09/outbound">
   <OrganizationId>00D70000000IVNpEAO</OrganizationId>
   <ActionId>12364</ActionId>
   <SessionId>00D70000000IVNp!ARMAQDLhMSEzaNIetXsbgpJOxlJRIdEIcPL1qnEPW7N09hw9rWgAr4_UCWsKcIfXkluc2gOWqvNICPGxDu5IJyhd1sS9WFDS</SessionId>
   <EnterpriseUrl>https://na5-api.salesforce.com/services/Soap/c/25.0/00D70000000IVNp</EnterpriseUrl>
   <PartnerUrl>https://na5-api.salesforce.com/services/Soap/u/25.0/00D70000000IVNp</PartnerUrl>
   <Notification>
    <Id>04l7000000DjbKjAAJ</Id>
    <sObject xsi:type="sf:Case" xmlns:sf="urn:sobject.enterprise.soap.sforce.com">
     <sf:Id>5007000000OFL5FAAX</sf:Id>
     <sf:ContactId>0037000000xicp7AAA</sf:ContactId>
    </sObject>
   </Notification>
  </notifications>
 </soapenv:Body>
</soapenv:Envelope>', 'http://sfs.kpaonline.com/sfs.php', 'notifications',1,0);
print_r($result);
?>