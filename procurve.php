<?php

	class procurve {

		// doesn't do much for now...

		private $walker;
		private $getter;
		private $snmp;
		function __construct($ip, $community) {
			$this->snmp = new snmp_querier($ip, $community);
		}

		function get_iface_status_short() {
			$iface_list = $this->snmp->map_oids($this->snmp->walk("IF-MIB::ifDescr"));
			foreach($iface_list as $iface_name=>$iface_oid) {
				$iface_status_short[$iface_name] = $this->snmp->get("IF-MIB::ifOperStatus.$iface_oid");
			}
			return $iface_status_short;
			}

		function get_iface_status_detail() {
			$iface_list = $this->snmp->map_oids($this->snmp->walk("IF-MIB::ifDescr"));
			foreach($iface_list as $iface_name=>$iface_oid) {
				$iface_status_detail[$iface_name]['Link:'] = $this->snmp->get("IF-MIB::ifOperStatus.$iface_oid");
			}
		return $iface_status_detail;
		}
	
	}

?>
