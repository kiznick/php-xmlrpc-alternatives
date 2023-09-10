<?php
	require_once __DIR__.'/xmlrpc.class.php';
    $kiznick = new kiznickXmlRpc();

    function request($url, $name, $params) {
        $data = $kiznick->xmlrpc_encode_request($name, $params);
    
        // Send request
        $headers = array('Content-type: text/xml', 'Content-length: ' . strlen($data));
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $resp = curl_exec($ch);
        curl_close($ch);
		
        return $kiznick->xmlrpc_decode($resp);
	}
?>
