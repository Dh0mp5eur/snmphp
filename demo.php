<?php

    include("snmputils.php");
    include("synology.php");
    include("procurve.php");

    $hp1920 = new procurve("192.168.10.3", "public", "2c");
    $rs815 = new synology("192.168.10.20", "public", "1");

    print_r($hp1920->get_system_info());
    print_r($hp1920->get_iface_status_detail());
    print_r($rs815->get_system_info());
    print_r($rs815->get_disk_info());
    print_r($rs815->get_network_info())

?>
