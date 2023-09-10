<?php
    class kiznickXmlRpc {
        public function xmlrpc_encode_request($name, $params) {
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
        
        public function xmlrpc_decode($xml) {
            $xml = simplexml_load_string($xml);
            if ($xml === false) {
                return false;
            }
            
            return $this->parseXML($xml->params->param->value->struct->member);
        }
        
        private function parseXML($xml) {
            $ret = [];
            foreach ($xml as $member) {
                if (isset($member->name) && isset($member->value)) {
                    if(isset($member->value->struct)) {
                        $ret[(string)$member->name] = $this->parseXML($member->value->struct->member);
                    } else if(isset($member->value->array)) {
                        $ret[(string)$member->name] = [];
                        foreach($member->value->array->data->value as $key => $value) {
                            $ret[(string)$member->name][] = (string)$value;
                        }
                    } else if(isset($member->value->boolean)) {
                        $ret[(string)$member->name] = (boolean)$member->value->boolean;
                    } else if(isset($member->value->{"dateTime.iso8601"})) {
                        $ret[(string)$member->name] = (string)$member->value->{"dateTime.iso8601"};
                    } else {
                        $ret[(string)$member->name] = (string)$member->value;
                    }
                }
            }
        
            return $ret;
        }
    }
?>
