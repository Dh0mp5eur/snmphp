# snmphp
![alt tag](https://i.imgur.com/1yR0goD.png)

PHP classes for monitoring various devices via SNMP

Could be the building block for the poller in a monitoring suite

Requires php5-snmp and snmp-mibs-downloader packages to be installed

Currently tested on HP ProCurve 1920-48G and Synology RS815+.

Easily extensible to any other SNMP enabled device.

##### Sample map_oids($query) function
```
$iface_list = $this->snmp->map_oids($this->snmp->walk("IF-MIB::ifDescr"));

Array
(
    [IF-MIB::ifDescr.1] => lo
    [IF-MIB::ifDescr.2] => sit0
    [IF-MIB::ifDescr.3] => eth0
    [IF-MIB::ifDescr.4] => eth1
    [IF-MIB::ifDescr.5] => eth2
    [IF-MIB::ifDescr.6] => eth3
    [IF-MIB::ifDescr.7] => bond0
    [IF-MIB::ifDescr.9] => tun0
)
Array
(
    [lo] => 1
    [sit0] => 2
    [eth0] => 3
    [eth1] => 4
    [eth2] => 5
    [eth3] => 6
    [bond0] => 7
    [tun0] => 9
)
```

##### Sample get_network_info() iteration output on Synology RS815+
```
function get_network_info() {
  $iface_list = $this->snmp->map_oids($this->snmp->walk("IF-MIB::ifDescr"));
  foreach($iface_list as $name=>$oid) {
    $netinfo[$name]['In octets']= $this->snmp->get("IF-MIB::ifInOctets.$oid");
    $netinfo[$name]['Out octets']= $this->snmp->get("IF-MIB::ifOutOctets.$oid");
  }
  return $netinfo;
}

Array
(
    [lo] => Array
        (
            [In octets] => 146186
            [Out octets] => 146186
        )

    [sit0] => Array
        (
            [In octets] => 0
            [Out octets] => 0
        )

    [eth0] => Array
        (
            [In octets] => 77262691
            [Out octets] => 5131418
        )

    [eth1] => Array
        (
            [In octets] => 12311560
            [Out octets] => 69724411
        )

    [eth2] => Array
        (
            [In octets] => 0
            [Out octets] => 0
        )

    [eth3] => Array
        (
            [In octets] => 0
            [Out octets] => 0
        )

    [bond0] => Array
        (
            [In octets] => 89575556
            [Out octets] => 74855829
        )

    [tun0] => Array
        (
            [In octets] => 9794197
            [Out octets] => 49403006
        )

)
```
