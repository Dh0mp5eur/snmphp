<?php

class procurve {

    // doesn't do much for now...
    
    private $snmp;

    function __construct($ip, $community, $version) {
        $this->snmp = new snmp_querier($ip, $community, $version);
    }

    function get_system_info() {
        $system_info['Hostname'] = $this->snmp->get("1.3.6.1.2.1.1.5.0");
        $system_info['Email'] = $this->snmp->get("1.3.6.1.2.1.1.4.0");
        $system_info['Location'] = $this->snmp->get("1.3.6.1.2.1.1.6.0");
        // first line only, second is useless copyright information
        $system_info['Version'] = strtok($this->snmp->get("1.3.6.1.2.1.1.1.0"), "\n");
        $system_info['Uptime'] = $this->snmp->get_uptime_from_timeticks($this->snmp->get("1.3.6.1.2.1.1.3.0"));
        return $system_info;
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
            $iface_status_detail[$iface_name]['Status'] = $this->snmp->get("IF-MIB::ifOperStatus.$iface_oid");
            $iface_status_detail[$iface_name]['InOctets'] = $this->snmp->get("IF-MIB::ifInOctets.$iface_oid");
            $iface_status_detail[$iface_name]['OutOctets'] = $this->snmp->get("IF-MIB::ifOutOctets.$iface_oid");
        }
        return $iface_status_detail;
    }

}

?>
