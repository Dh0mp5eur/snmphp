<?php
include("snmputils.php");
include("synology.php");
include("procurve.php");
$hp1920 = new procurve("192.168.10.3", "public");
$hp1920_ifaces = $hp1920->get_iface_status_short();
$hp1920_ifaces_d = array_reverse($hp1920->get_iface_status_detail(), TRUE);
$hp1920_info = $hp1920->get_system_info();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>SNMP diag</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="navbar.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
      <br>
      <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">SNMP diag</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#">Manage devices</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li class="active"><a href="./">Default view <span class="sr-only">(current)</span></a></li>
              <li><a href="../navbar-static-top/">Detailed view</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
      <div class="col-sm-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">sw01</h3>
          </div>
          <div class="panel-body">
            <div class="row">
            <div class="col-md-6">
              <h3>Device info</h3>

   <table class="table">
        <tr><td><b>Hostname</b></td><td><?=$hp1920_info['Hostname']?></td></tr>
        <tr><td><b>Location</b></td><td><?=$hp1920_info['Location']?></td></tr>
        <tr><td><b>Version</b></td><td><?=$hp1920_info['Version']?></td></tr>
        <tr><td><b>Uptime</b></td><td><?=$hp1920_info['Uptime']?></td></tr>
     <tbody>


     </tbody>
   </table>
 </div>
 </div>
       <div class="row">
         <div class="col-sm-11">
           <h3>Port status</h3>
			<?php
			$i = 1;
			foreach($hp1920_ifaces as $iface_name=>$iface_status) {
				if($iface_status == "up(1)") {
					echo "<button type=\"button\" class=\"btn btn-xs btn-success\" style=\"width: 25px;\">$i</button>&nbsp;";
				} else {
					echo "<button type=\"button\" class=\"btn btn-xs btn-danger\" style=\"width: 25px;\">$i</button>&nbsp;";
				}
				if($i == "28") { echo "<br><br>"; }
				$i++;
			}
			?>

            <br><h3>Port statistics</h3>
            <?php
				foreach($hp1920_ifaces_d as $iface_name=>$iface_values) {
					if($iface_values['Status'] == "up(1)") {
						echo "<br><button type=\"button\" class=\"btn btn-xs btn-success\" style=\"width: 120px;\">$iface_name</button>&nbsp;";
					} else {
						echo "<br><button type=\"button\" class=\"btn btn-xs btn-danger\" style=\"width: 120px;\">$iface_name</button>&nbsp;";
					}
					echo "<span class=\"label label-default\">IN: ".$iface_values['InOctets']."</span>&nbsp;
					<span class=\"label label-default\">OUT: ".$iface_values['OutOctets']."</span><br>";
				}
			?>

				</div>
			</div>
          </div>
        </div>
      </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
