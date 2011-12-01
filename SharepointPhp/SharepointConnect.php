<?php

namespace SharepointPhp;

class SharepointConnect
{
    protected $soapClient;
    protected $authentification;
    
    protected $usr;
    protected $pwd;
    
    protected $url;
    protected $guid;
    
    public function __construct($user = false, $password = false, $authentification = false)
    {
        $this->url = false;
        $this->guid = false;
        
        $this->authentification = $authentification;
        
        $this->usr = $user;
        $this->pwd = $password;
        
        $this->soapClient = false;
    }
    
    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    public function setGuid($guid)
    {
        $this->guid = $guid;
    }
    
    public function init()
    {
        if(!$this->soapClient){
            $this->soapClient = new \nusoap_client($this->url, true);
            
            $this->soapClient->soap_defencoding = 'UTF-8';
            $this->soapClient->setUseCurl(true);
            $this->soapClient->useHTTPPersistentConnection();
            
            if($this->authentification == "ntlm"){
                $this->soapClient->setCredentials("","","ntlm");
                $this->soapClient->setCurlOption(CURLOPT_USERPWD, $this->usr . ":" . $this->pwd);
            }else{
                throw new \Exception("Only ntlm authentification is currently supported.");
            }
        }
    }
    
    public function GetListItems($rowLimit = 100)
    {
        $this->init();
        
        $xml ='
        <GetListItems xmlns="http://schemas.microsoft.com/sharepoint/soap/">
        <listName>'.$this->guid.'</listName>
        <rowLimit>'.$rowLimit.'</rowLimit>
        </GetListItems>
        ';
        
        return new Result\GetListItemsResult( $this->soapClient->call("GetListItems", $xml) );
        
    }
    
    public function GetList()
    {
        $this->init();
        
        $xml ='
        <GetList xmlns="http://schemas.microsoft.com/sharepoint/soap/">
        <listName>'.$this->guid.'</listName>
        </GetList>
        ';
        
        return new Result\GetListResult( $this->soapClient->call("GetList", $xml) );
        
    }
    
}