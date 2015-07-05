<?php
class snmp_querier {
	private $ip;
	private $community;
	private $type;

	function __construct($ip, $community) {
		$this->ip = $ip;
		$this->community = $community;
	}

	function walk($oid) {
		// we're using realwalk so that we can build iterators with map_oids(), see:
		// walk     [0] => eth0
		// realwalk [IF-MIB::ifDescr.2] => eth0
		$result = $this->clear_snmp_string(snmprealwalk($this->ip, $this->community, $oid));
		return $result;
	}

	function get($oid) {
		$result = $this->clear_snmp_string(snmpget($this->ip, $this->community, $oid));
		return $result;
	}

	function get_oids($query) {
		// this function builds iterators for snmp gets startin from a walk, e.g.
		// IF-MIB::ifDescr.1 and IF-MIB::ifDescr.3 --> [0] => 1, [1] => 3
		foreach($query as $oid=>$value) {
		$oid_split = explode(".", $oid);
		$oidlist[] = $oid_split[count($oid_split)-1];
		}
		return $oidlist;
	}

	function map_oids($query) {
		// this function maps the name to the last OID digit, e.g.:
		// [IF-MIB::ifDescr.1] => eth0, [IF-MIB::ifDescr.3] => eth1
		// [eth0] => 1, [eth1] => 3
		// just like get_oids() this turns useful for iterating over items
		// we're swapping keys with their values and doing some cleanup..
		$oidmap = array_flip($query);
		foreach($oidmap as $item=>&$oid) {
		$oid_split = explode(".", $oid);
		$oid = $oid_split[count($oid_split)-1];
		}
		return $oidmap;
	}

	function get_uptime_from_timeticks($str) {
		$re = "/Timeticks: \\((.*)\\) (.*)\\./";
		preg_match_all($re, $str, $matches);
		return $matches[2][0];
	}
	
	function clear_snmp_string($data) {
		// clean this all up (filter?)
		if(is_array($data)) {
			foreach($data as $key=>&$val) {
				$val = str_replace('STRING: ', '', $val);
				$val = str_replace('Counter32: ', '', $val);
				$val = str_replace('"', '', $val);
				$val = trim($val);
			}
		}
		else {
			$data = str_replace('STRING: ', '', $data);
			$data = str_replace('INTEGER: ', '', $data);
			$data = str_replace('Counter32: ', '', $data);
			$data = str_replace('"', '', $data);
			$data = trim($data);
		}
		return $data;
	}
}

?>
