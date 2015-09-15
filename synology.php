<?php

class synology {
    
    private $snmp;
    
    function __construct($ip, $community, $version) {
        $this->snmp = new snmp_querier($ip, $community, $version);
    }

    function map_disk_status($status) {
        switch($status) {
            case '1': $status = "Normal"; break;
            case '2': $status = "Initialized, no data"; break;
            case '3': $status = "Not initialized"; break;
            case '4': $status = "Partitions damaged"; break;
            case '5': $status = "Disk damaged"; break;
            default: $status = "Unknown"; break;
        }
        return $status;
    }

    function get_disk_info() {

        $disk_names = array_values($this->snmp->walk("1.3.6.1.4.1.6574.2.1.1.2"));
        $disk_num = count($disk_names);

        for($i = 0; $i < $disk_num; $i++) {
            //$diskinfo[$disk_names[$i]]['Name'] = $this->snmp->get("1.3.6.1.4.1.6574.2.1.1.2.$i");
            $diskinfo[$disk_names[$i]]['PartID'] = $this->snmp->get("1.3.6.1.4.1.6574.2.1.1.3.$i");
            $diskinfo[$disk_names[$i]]['Type'] = $this->snmp->get("1.3.6.1.4.1.6574.2.1.1.4.$i");
            $diskinfo[$disk_names[$i]]['Temperature'] = $this->snmp->get("1.3.6.1.4.1.6574.2.1.1.6.$i");
            $diskinfo[$disk_names[$i]]['Status'] = $this->map_disk_status($this->snmp->get("1.3.6.1.4.1.6574.2.1.1.5.$i"));
        }
        return $diskinfo;
    }

    function get_system_info() {
        $system_info['Hostname'] = $this->snmp->get("1.3.6.1.2.1.1.5.0");
        $system_info['Email'] = $this->snmp->get("1.3.6.1.2.1.1.4.0");
        $system_info['Location'] = $this->snmp->get("1.3.6.1.2.1.1.6.0");
        $system_info['Uptime'] = $this->snmp->get_uptime_from_timeticks($this->snmp->get("1.3.6.1.2.1.1.3.0"));

        $system_info['CPU_user'] = $this->snmp->get("UCD-SNMP-MIB::ssCpuUser.0");
        $system_info['CPU_system'] = $this->snmp->get("UCD-SNMP-MIB::ssCpuSystem.0");
        $system_info['CPU_idle'] = $this->snmp->get("UCD-SNMP-MIB::ssCpuIdle.0");

        $system_info['MemTotal'] = $this->snmp->get("UCD-SNMP-MIB::memTotalReal.0");
        $system_info['MemAvail'] = $this->snmp->get("UCD-SNMP-MIB::memAvailReal.0");
        $system_info['MemFree'] = $this->snmp->get("UCD-SNMP-MIB::memTotalFree.0");
        $system_info['MemBuffers'] = $this->snmp->get("UCD-SNMP-MIB::memBuffer.0");
        $system_info['MemCached'] = $this->snmp->get("UCD-SNMP-MIB::memCached.0");

        $system_info['SwapTotal'] = $this->snmp->get("UCD-SNMP-MIB::memTotalSwap.0");
        $system_info['SwapAvail'] = $this->snmp->get("UCD-SNMP-MIB::memAvailSwap.0");

        // div by 100 since we're getting an integer, number_format to keep UNIX like two-pos decimals (e.g. 0.20)
        $system_info['Load_1min'] = number_format($this->snmp->get("UCD-SNMP-MIB::laLoadInt.1")/100, 2);
        $system_info['Load_5min'] = number_format($this->snmp->get("UCD-SNMP-MIB::laLoadInt.2")/100, 2);
        $system_info['Load_15min'] = number_format($this->snmp->get("UCD-SNMP-MIB::laLoadInt.3")/100, 2);
        return $system_info;

    }

    function get_network_info() {
        $iface_list = $this->snmp->map_oids($this->snmp->walk("IF-MIB::ifDescr"));
        foreach($iface_list as $name=>$oid) {
            $netinfo[$name]['InOctets']= $this->snmp->get("IF-MIB::ifInOctets.$oid");
            $netinfo[$name]['OutOctets']= $this->snmp->get("IF-MIB::ifOutOctets.$oid");
        }
        return $netinfo;
    }
}

?>
