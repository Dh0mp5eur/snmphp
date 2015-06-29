<?php
class snmp_querier {
  private $ip;
  private $community;
  private $type;

  function __construct($ip, $community, $type) {
    $this->ip = $ip;
    $this->community = $community;
    $this->type = $type;

  }

  function query($oid) {
    if($this->type == "walk") {
      $result = $this->clear_snmp_string(snmpwalk($this->ip, $this->community, $oid));
    } else if($this->type == "get") {
      $result = $this->clear_snmp_string(snmpget($this->ip, $this->community, $oid));
    }
    return $result;
  }

  function clear_snmp_string($data) { // clean this up
    if(is_array($data)) {
      foreach($data as $key=>&$val) {
        $val = str_replace('STRING: "', '', $val);
        $val = str_replace('"', '', $val);
      }
    }
    else {
      $data = str_replace('STRING: "', '', $data);
      $data = str_replace('INTEGER: ', '', $data);
      $data = str_replace('"', '', $data);
      $data = trim($data);
    }
    return $data;
  }
}

?>
