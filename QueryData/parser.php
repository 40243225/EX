<?php

$path="C:\\Program Files\\Apache Software Foundation\\Tomcat 8.5\\webapps\\Cloud-based GIS\\data\\workspaces\\*";
$id="NamespaceInfoImpl--2fbca141:15dbc16962b:-7ffe";
create_workspace($path);
dataParser($path);
header("Location:index.php"); 

function dataParser($dir)
{
	$alldir=listdirs($dir);
	$domDoc = new DOMDocument('1.0', 'UTF-8');
	$domDoc->formatOutput = true;
	$data = $domDoc->createElement('data');
	$dataNode = $domDoc->appendChild($data);
	foreach ($alldir as $filedir) {
	$file=$filedir;
		if(glob($file."\{coverage.xml}",GLOB_BRACE))
			{	
				$file = $file."\coverage.xml";
				if (file_exists($file)) {
		$xml = simplexml_load_file($file);
		$featureNode=$dataNode->appendChild($domDoc->createElement('feature'));
		$idNode=$featureNode->appendChild($domDoc->createElement('id'));
		$idNode->appendChild($domDoc->createTextNode($xml->id));
		$nameNode=$featureNode->appendChild($domDoc->createElement('name'));
		$nameNode->appendChild($domDoc->createTextNode($xml->name));
		$w_idNode=$featureNode->appendChild($domDoc->createElement('WorkspaceID'));
		$w_idNode->appendChild($domDoc->createTextNode($xml->namespace->id));
					
		$w_NameNode=$featureNode->appendChild($domDoc->createElement('WorkspaceName'));
		$w_NameNode->appendChild($domDoc->createTextNode(namesearch($xml->namespace->id)));
					
		$SRSNode=$featureNode->appendChild($domDoc->createElement('SRS'));
		$SRSNode->appendChild($domDoc->createTextNode($xml->srs));
		
		$Min_xNode=$featureNode->appendChild($domDoc->createElement('Min_x'));
		$Min_xNode->appendChild($domDoc->createTextNode($xml->nativeBoundingBox->minx));
		
		$Min_yNode=$featureNode->appendChild($domDoc->createElement('Min_y'));
		$Min_yNode->appendChild($domDoc->createTextNode($xml->nativeBoundingBox->miny));
		
		$Max_xNode=$featureNode->appendChild($domDoc->createElement('Max_x'));
		$Max_xNode->appendChild($domDoc->createTextNode($xml->nativeBoundingBox->maxx));
		
		$Max_yNode=$featureNode->appendChild($domDoc->createElement('Max_y'));
		$Max_yNode->appendChild($domDoc->createTextNode($xml->nativeBoundingBox->maxy));
					
		$Min_xNode=$featureNode->appendChild($domDoc->createElement('Min_lat'));
		$Min_xNode->appendChild($domDoc->createTextNode($xml->latLonBoundingBox->minx));
					
		$Min_yNode=$featureNode->appendChild($domDoc->createElement('Min_lng'));
		$Min_yNode->appendChild($domDoc->createTextNode($xml->latLonBoundingBox->miny));
					
		$Max_xNode=$featureNode->appendChild($domDoc->createElement('Max_lat'));
		$Max_xNode->appendChild($domDoc->createTextNode($xml->latLonBoundingBox->maxx));
					
		$Max_yNode=$featureNode->appendChild($domDoc->createElement('Max_lng'));
		$Max_yNode->appendChild($domDoc->createTextNode($xml->latLonBoundingBox->maxy));
			
		} 
	else 
		exit('Failed to open xml.');
			}
		else if(glob($file."\{featuretype.xml}",GLOB_BRACE))
			{	
				$file = $file."\\featuretype.xml";
				if (file_exists($file)) {
		$xml = simplexml_load_file($file);
		$featureNode=$dataNode->appendChild($domDoc->createElement('feature'));
		$idNode=$featureNode->appendChild($domDoc->createElement('id'));
		$idNode->appendChild($domDoc->createTextNode($xml->id));
		$nameNode=$featureNode->appendChild($domDoc->createElement('name'));
		$nameNode->appendChild($domDoc->createTextNode($xml->name));
		$w_idNode=$featureNode->appendChild($domDoc->createElement('WorkspaceID'));
		$w_idNode->appendChild($domDoc->createTextNode($xml->namespace->id));
					
		$w_NameNode=$featureNode->appendChild($domDoc->createElement('WorkspaceName'));
		$w_NameNode->appendChild($domDoc->createTextNode(namesearch($xml->namespace->id)));
					
		$SRSNode=$featureNode->appendChild($domDoc->createElement('SRS'));
		$SRSNode->appendChild($domDoc->createTextNode($xml->srs));
					
		$Min_xNode=$featureNode->appendChild($domDoc->createElement('Min_x'));
		$Min_xNode->appendChild($domDoc->createTextNode($xml->nativeBoundingBox->minx));
		
		$Min_yNode=$featureNode->appendChild($domDoc->createElement('Min_y'));
		$Min_yNode->appendChild($domDoc->createTextNode($xml->nativeBoundingBox->miny));
		
		$Max_xNode=$featureNode->appendChild($domDoc->createElement('Max_x'));
		$Max_xNode->appendChild($domDoc->createTextNode($xml->nativeBoundingBox->maxx));
		
		$Max_yNode=$featureNode->appendChild($domDoc->createElement('Max_y'));
		$Max_yNode->appendChild($domDoc->createTextNode($xml->nativeBoundingBox->maxy));
		
		$Min_xNode=$featureNode->appendChild($domDoc->createElement('Min_lat'));
		$Min_xNode->appendChild($domDoc->createTextNode($xml->latLonBoundingBox->minx));
		
		$Min_yNode=$featureNode->appendChild($domDoc->createElement('Min_lng'));
		$Min_yNode->appendChild($domDoc->createTextNode($xml->latLonBoundingBox->miny));
		
		$Max_xNode=$featureNode->appendChild($domDoc->createElement('Max_lat'));
		$Max_xNode->appendChild($domDoc->createTextNode($xml->latLonBoundingBox->maxx));
		
		$Max_yNode=$featureNode->appendChild($domDoc->createElement('Max_lng'));
		$Max_yNode->appendChild($domDoc->createTextNode($xml->latLonBoundingBox->maxy));
			
		} 
	else 
		exit('Failed to open xml.');
			}
	}
	$domDoc->save("data.xml");	
}

function create_workspace($dir){
	$domDoc = new DOMDocument('1.0', 'UTF-8');
	$domDoc->formatOutput = true;
	$data = $domDoc->createElement('data');
	$dataNode = $domDoc->appendChild($data);
	$worksapce_dir=glob($dir,GLOB_ONLYDIR | GLOB_MARK); 
	foreach($worksapce_dir as $filedir)
	{
		if(glob($filedir."\{namespace.xml}",GLOB_BRACE))
			{
				$file =$filedir."namespace.xml";
				if (file_exists($file)) {
					$xml = simplexml_load_file($file);
					$workspaceNode=$dataNode->appendChild($domDoc->createElement('workspace'));
					$idNode=$workspaceNode->appendChild($domDoc->createElement('id'));
					$idNode->appendChild($domDoc->createTextNode($xml->id));
					$NameNode=$workspaceNode->appendChild($domDoc->createElement('Name'));
					$NameNode->appendChild($domDoc->createTextNode($xml->prefix));
	
				} else {
					exit('Failed to open xml.');
				}
			}
	}
$domDoc->save("workspace.xml");
	
}

function namesearch($id)
{
	$file_dir="workspace.xml";
	if (file_exists($file_dir)) {
					$xml = simplexml_load_file($file_dir);
					//echo $xml->workspace[1]->name;
					//print_r($xml);
				} else {
					exit('Failed to open xml.');
				}
	foreach ($xml->children() as $worksapce) {
		if(strcmp($id,$worksapce->id)==0)
		{
			return $worksapce->Name;
		}
    
}
}

function listdirs($dir) {
    static $alldirs = array();
    $dirs = glob($dir . '/*', GLOB_ONLYDIR);
    if (count($dirs) > 0) {
        foreach ($dirs as $d) $alldirs[] = $d;
    }
    foreach ($dirs as $dir) listdirs($dir);
    return $alldirs;
}

?>