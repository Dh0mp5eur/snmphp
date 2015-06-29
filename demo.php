<?php

  include("snmputils.php");
  include("synology.php");

  $rs815 = new synology("192.168.10.15", "public");
  print_r($rs815->get_disk_info());

?>
