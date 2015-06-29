<?php

  class synology {
		private $walker;
		private $getter;
		function __construct($ip, $community) {
			$this->walker = new snmp_querier($ip, $community, "walk");
			$this->getter = new snmp_querier($ip, $community, "get");
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

			$disk_names = array_values($this->walker->query("1.3.6.1.4.1.6574.2.1.1.2"));
			$disk_num = count($disk_names);

			for($i = 0; $i < $disk_num; $i++) {
				$diskinfo[$i]['Name'] = $this->getter->query("1.3.6.1.4.1.6574.2.1.1.2.$i");
				$diskinfo[$i]['PartID'] = $this->getter->query("1.3.6.1.4.1.6574.2.1.1.3.$i");
				$diskinfo[$i]['Type'] = $this->getter->query("1.3.6.1.4.1.6574.2.1.1.4.$i");
				$diskinfo[$i]['Temperature'] = $this->getter->query("1.3.6.1.4.1.6574.2.1.1.6.$i");
				$diskinfo[$i]['Status'] = $this->map_disk_status($this->getter->query("1.3.6.1.4.1.6574.2.1.1.5.$i"));
			}
			return $diskinfo;
		}

    function get_system_info() {
      $sysinfo['Hostname'] = $this->getter->query("1.3.6.1.2.1.1.5.0");
      $sysinfo['Email'] = $this->getter->query("1.3.6.1.2.1.1.4.0");
      $sysinfo['Location'] = $this->getter->query("1.3.6.1.2.1.1.6.0");

      $sysinfo['CPU: % user'] = $this->getter->query("1.3.6.1.4.1.2021.11.9.0");
      $sysinfo['CPU: % system'] = $this->getter->query("1.3.6.1.4.1.2021.11.10.0");
      $sysinfo['CPU: % idle'] = $this->getter->query("1.3.6.1.4.1.2021.11.11.0");

      $sysinfo['Mem: total'] = $this->getter->query("1.3.6.1.4.1.2021.4.5.0");
      $sysinfo['Mem: available'] = $this->getter->query("1.3.6.1.4.1.2021.4.6.0");
      $sysinfo['Mem: shared'] = $this->getter->query("1.3.6.1.4.1.2021.4.13.0");
      $sysinfo['Mem: buffers'] = $this->getter->query("1.3.6.1.4.1.2021.4.14.0");
      $sysinfo['Mem: cached'] = $this->getter->query("1.3.6.1.4.1.2021.4.15.0");

      $sysinfo['Swap: total'] = $this->getter->query("1.3.6.1.4.1.2021.4.3.0");
      $sysinfo['Swap: avail'] = $this->getter->query("1.3.6.1.4.1.2021.4.4.0");

      // div by 100 since we're getting an integer, number_format to keep UNIX like two-pos decimals (e.g. 0.20)
      $sysinfo['Load: 1 min'] = number_format($this->getter->query("1.3.6.1.4.1.2021.10.1.5.1")/100, 2);
      $sysinfo['Load: 5 min'] = number_format($this->getter->query("1.3.6.1.4.1.2021.10.1.5.2")/100, 2);
      $sysinfo['Load: 15 min'] = number_format($this->getter->query("1.3.6.1.4.1.2021.10.1.5.3")/100, 2);
      return $sysinfo;

    }

    function get_network_info() {
      $iface_names = array_values($this->walker->query("1.3.6.1.2.1.31.1.1.1.1"));
      $iface_num  = count($iface_names);
      // bugged: see page 9 of DiskStation MIB guide
      for($i = 0; $i < $iface_num; $i++) {
        //$netinfo[$i]['???']= $this->getter->query("1.3.6.1.2.1.31.1.1.1.2.$i");
      }
      // let's juts return interface names for now
      $netinfo = $iface_names;
      return $netinfo;
    }
	}

?>
