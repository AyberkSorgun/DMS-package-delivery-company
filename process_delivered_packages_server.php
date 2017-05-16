<?php

	function setAsDelivered($tn){
		include("session.php");
		$result = mysqli_query($db,"UPDATE shipment SET ship_status = 'delivered' WHERE track_number = '$tn'");
		if (!$result){
			printf("Error on setting shipment as delivered: %s\n", mysqli_error($db));		
		} else {
			echo "Success on setting shipment as delivered<br>";
		}
	}
	echo setAsDelivered($_GET['tn']);
?>
