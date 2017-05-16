<?php
		include("session.php");

		$employee = $_SESSION['username'];
		if($qsql = mysqli_query($db,"select st_ID from store where emp_ID in (SELECT emp_ID from employee where username = '$employee')")){
			$row = mysqli_fetch_array($qsql);
			$st_ID = $row[0];
		} else {
			echo "Error: This employee does not belong to any store!<br>";
		}

		$res =  mysqli_query($db,"SELECT * FROM shipment where to_st_ID = '$st_ID' or from_st_ID = '$st_ID'");

		echo "<b> Upcoming/sent packages <b><br><br>";		
		echo '<table class= "table table-striped table-bordered table-hover" border = "1">';
		echo "<tr><th>Type</th><th>Track Number</th><th>Sender ID</th><th>Receiver Name</th><th>Receiver Phone</th><th>From Store ID</th><th>To Store ID</th><th>Date of Shipment</th><th>Estimated date of arrival</th><th>Cost</th><th>Shipment Status</th></tr>";
		
	while($row = mysqli_fetch_array($res)){
			echo "<tr><td align='center'>";
			if ($row['from_st_ID'] == $st_ID){
				echo "Sent";
			} else {
				echo "Upcoming";
			}
			
			echo "</td><td align='center'>";
			echo $row['track_number'];
			echo "</td><td align='center'>";
			echo $row['sender_ID'];
			echo "</td><td align='center'>";
			echo $row['receiver_name'];
			echo "</td><td align='center'>";
			echo $row['receiver_phone'];
			echo "</td><td align='center'>";
			echo $row['from_st_ID'];
			echo "</td><td align='center'>";
			echo $row['to_st_ID'];
			echo "</td><td align='center'>";
			echo gmdate('r', $row['dateofshipment']);
			echo "</td><td align='center'>";
			echo gmdate('r',$row['estdateofshipment']);
			echo "</td><td align='center'>";
			echo $row['cost'];
			echo "</td><td align='center'>";
			echo $row['ship_status'];
			echo "</td></tr>";
		}
		echo "</table>";
?>

<html>
	<head>
	<title> View upcoming/sent packages </title>
	</head>

	<body>
		
	</body>
</html>
