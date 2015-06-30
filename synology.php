<?php

  class synology {
		private $walker;
		private $getter;
    private $snmp;
		function __construct($ip, $community) {
			$this->snmp = new snmp_querier($ip, $community);
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
      $sysinfo['Hostname'] = $this->snmp->get("1.3.6.1.2.1.1.5.0");
      $sysinfo['Email'] = $this->snmp->get("1.3.6.1.2.1.1.4.0");
      $sysinfo['Location'] = $this->snmp->get("1.3.6.1.2.1.1.6.0");

      $sysinfo['CPU: % user'] = $this->snmp->get("UCD-SNMP-MIB::ssCpuUser.0");
      $sysinfo['CPU: % system'] = $this->snmp->get("UCD-SNMP-MIB::ssCpuSystem.0");
      $sysinfo['CPU: % idle'] = $this->snmp->get("UCD-SNMP-MIB::ssCpuIdle.0");

      $sysinfo['Mem: total'] = $this->snmp->get("UCD-SNMP-MIB::memTotalReal.0");
      $sysinfo['Mem: available'] = $this->snmp->get("UCD-SNMP-MIB::memAvailReal.0");
      $sysinfo['Mem: free'] = $this->snmp->get("UCD-SNMP-MIB::memTotalFree.0");
      $sysinfo['Mem: buffers'] = $this->snmp->get("UCD-SNMP-MIB::memBuffer.0");
      $sysinfo['Mem: cached'] = $this->snmp->get("UCD-SNMP-MIB::memCached.0");

      $sysinfo['Swap: total'] = $this->snmp->get("UCD-SNMP-MIB::memTotalSwap.0");
      $sysinfo['Swap: avail'] = $this->snmp->get("UCD-SNMP-MIB::memAvailSwap.0");

      // div by 100 since we're getting an integer, number_format to keep UNIX like two-pos decimals (e.g. 0.20)
      $sysinfo['Load: 1 min'] = number_format($this->snmp->get("UCD-SNMP-MIB::laLoadInt.1")/100, 2);
      $sysinfo['Load: 5 min'] = number_format($this->snmp->get("UCD-SNMP-MIB::laLoadInt.2")/100, 2);
      $sysinfo['Load: 15 min'] = number_format($this->snmp->get("UCD-SNMP-MIB::laLoadInt.3")/100, 2);
      return $sysinfo;

    }


    function get_network_info() {
      $iface_list = $this->snmp->map_oids($this->snmp->walk("IF-MIB::ifDescr"));
      foreach($iface_list as $name=>$oid) {
        $netinfo[$name]['In octets']= $this->snmp->get("IF-MIB::ifInOctets.$oid");
        $netinfo[$name]['Out octets']= $this->snmp->get("IF-MIB::ifOutOctets.$oid");
      }
      return $netinfo;
    }
	}

?>
