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
	}

?>
