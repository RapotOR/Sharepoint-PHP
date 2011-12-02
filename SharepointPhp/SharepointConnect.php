<?php

namespace SharepointPhp;

class SharepointConnect
{
    protected $soapClient;
    
    protected $authentification;
    protected $usr = false;
    protected $pwd = false;
    
    protected $sharepointUrl = 'intranet';
    protected $site = '';
    protected $ssl = false;
    
    protected $methods = array(
        'GetListItems' => array(
            'Lists',
            '\SharepointPhp\Result\GetListItemsResult',
            array('listName'),
            array('rowLimit' => 100) // optionnal
        ),
        'GetList' => array(
            'Lists',
            '\SharepointPhp\Result\GetListResult',
            array('listName')
        ),
        'GetWebCollection' => array(
            'Webs',
            '\SharepointPhp\Result\GetWebCollectionResult',
            array()
        )
    );
    
    public function __construct($user = false, $password = false, $authentification = false)
    {
        $this->setUser( $user );
        $this->setPassword( $password );
        $this->setAuthentification( $authentification );
        
        $this->soapClient = false;
    }
    
    public function setAuthentification($authentification)
    {
        $this->authentification = $authentification;
    }
    
    public function setUser($user)
    {
        $this->usr = $user;
    }
    
    public function setPassword($password)
    {
        $this->pwd = $password;
    }
    
    public function setSharepointUrl($sharepointUrl)
    {
        $this->sharepointUrl = $sharepointUrl;
    }
    
    public function setSite($sharepointUrl)
    {
        $this->site = $sharepointUrl;
    }
    
    public function hasSSl($ssl)
    {
        $this->ssl = (bool) $ssl;
    }
    
    protected function getServiceUrl($service = "Lists")
    {
        return 'http' . ($this->ssl ? 's' : '') . '://' . $this->sharepointUrl . $this->site . '/_vti_bin/'  . $service . '.asmx?WSDL';
    }
    
    protected function generateXml($action, $parameters = false)
    {
        //DOMElement class could be use... but the reply is not complex.
        return '<'.$action.' xmlns="http://schemas.microsoft.com/sharepoint/soap/">'.$this->generateParameters($parameters).'</'.$action.'>';
    }
    
    protected function generateParameters($parameters = false)
    {
        if(!$parameters)
            return '';
        
        $xml = '';
        foreach($parameters as $parameter => $value)
            $xml .= '<'.$parameter.'>'.$value.'</'.$parameter.'>';
        
        return $xml;
    }
    
    protected function soapCall($action, $parameters = false)
    {
        return $this->soapClient->call($action, $this->generateXml($action, $parameters));
    }
    
    protected function init($wsdl)
    {
        $this->soapClient = new \nusoap_client($wsdl, true);
        
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
    
    public function __call($action, $args)
    {
        if(!isset($this->methods[$action]))
            throw new Exception( "Method (" . $action . " does not exist", 0 );
            
        if(sizeof($args) < sizeof($this->methods[$action][2]))
            throw new Exception( "Method " . $action . " requires ".sizeof($this->methods[$action][2])." args", 0 );
        
        $this->init($this->getServiceUrl($this->methods[$action][0]));
        
        $returnObject = $this->methods[$action][1];
        
        $parameters = false;
        if(sizeof($args) > 0) {
            $parameters = array();
            
            $idx=0;
            foreach($this->methods[$action][2] as $argName)
            {
                $parameters[ $argName ] = $args[$idx];
                $idx++;
            }
            
            //optionnal args
            if(isset($this->methods[$action][3]))
            {
                foreach($this->methods[$action][3] as $argName => $argDefault)
                {
                    $parameters[ $argName ] = (isset($args[$idx]) ? $args[$idx] : $argDefault );
                    $idx++;
                }
            }
        }
        
        return new $returnObject( $this->soapCall($action, $parameters) );
    }
}