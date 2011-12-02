Description
===========
Sharepoint PHP tool, PHP5.3

Available commands
==================
Lists:
    - GetListItems
    - GetList

Webs:
    - GetWebCollection

Use it
======

    $sharepoint = new SharepointConnect();
    $sharepoint->setUser('DOMAIN\USERNAME');
    $sharepoint->setPassword('MYPASSWORD');
    $sharepoint->setAuthentification('ntlm');
    $sharepoint->setSharepointUrl('intranet');
    $sharepoint->setSite('/SubSite');
    
    $guid = "1995FFE5-D64E-4E51-9428-5D591E7403C1";
    $results = $sharepoint->GetList($guid);
    $results = $sharepoint->GetListItems($guid);
    $results = $sharepoint->GetWebCollection();