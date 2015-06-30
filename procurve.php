<?php

  class procurve {

    // doesn't do much for now...

		private $walker;
		private $getter;
    private $snmp;
		function __construct($ip, $community) {
      $this->snmp = new snmp_querier($ip, $community);
		}

    function get_iface_names() {
      $iface_names = $this->snmp->walk("IF-MIB::ifName");
      return $iface_names;
    }
	}

?>
