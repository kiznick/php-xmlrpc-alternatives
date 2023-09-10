<?php
	function xmlrpc_encode_request($url, $name, $params) {
	    $xml = new SimpleXMLElement('<methodCall></methodCall>');
	    $xml->addChild('methodName', $name);
	    $paramsXml = $xml->addChild('params');
	    foreach ($params as $param) {
	        $paramXml = $paramsXml->addChild('param');
	        $valueXml = $paramXml->addChild('value');
	        $valueXml->addChild('string', htmlspecialchars($param));
	    }
	    return $xml->asXML();
	}
	
	function xmlrpc_decode($xml) {
	    $xml = simplexml_load_string($resp);
	    if ($xml === false) {
	        return false;
	    }
	    
	    return parseXML($xml);
	}
	
	function parseXML($xml) {
	    $ret = [];
	    foreach ($xml as $member) {
	        if (isset($member->name) && isset($member->value)) {
	            $ret[(string)$member->name] = is_string($member->value) ? (string)$member->value : $this->parseXML($member->value);
	        }
	    }
	
	    return $ret;
	}
?>
