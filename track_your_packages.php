<?php
	include("session.php");

	function calculateCost($wtype, $ttype, $content, $weight){
		$cost = 0.00;
		if ($wtype == 'flat' or $wtype == 'envelope'){
			$cost += 1;		
		} else if ($wtype == 'small box'){
			$cost += 5;		
		} else if ($wtype == 'mid-sized box'){
			$cost += 10;
		} else if ($wtype == 'large box'){
			$cost += 20;	
			if ($weight > 10){
				$cost += ($weight - 10);			
			}
		}
		
		if ($ttype == 'overnight'){
			$cost += 25;		
		} else if ($ttype == '2nd day'){
			$cost += 10;
		} else if ($ttype == '7-day shipping'){
			$cost += 0;
		}

		if ($content == 'hazardous'){
			$cost += 10;
		} else if ($content == 'international'){
			$cost *= 1.1;
		}


		return $cost;
	}

	$customer = $_SESSION['username'];
	if($qsql = mysqli_query($db,"select * FROM customer WHERE username = '$customer'")){
			$row = mysqli_fetch_array($qsql);
			$cust_ID = $row['cust_ID'];
	} else {
			echo "Error: This customer does not exist!<br>";
			die();
	}

	$query = mysqli_query($db,"SELECT * FROM (shipment natural join ship_packs) natural join package where cust_ID = '$cust_ID' and sender_ID = cust_ID");
	
	echo "<b><h3> Your Packages</h3> </b>";
	echo '<table class= "table table-striped table-bordered table-hover" border = "1">';

	echo "<tr>
			<th>Track Number</th>
		    <th>Package ID</th>
			<th>Box type</th>
			<th>Timeliness</th>
			<th>Content</th>
			<th>Weight</th>
			<th>Receiver Name</th>
			<th>Receiver Phone</th>
			<th>From Store ID</th>
			<th>To Store ID</th>
			<th>Date of Shipment</th>
			<th>Estimated date of arrival</th>
			<th>Cost</th>
			<th>Shipment Status</th>
			<th>Bill ID</th>
		</tr>";
	
		while($result = mysqli_fetch_array($query)){
			echo "<tr><td align='center'>";
			echo $result['track_number'];

			echo "</td><td align='center'>";
			echo $result['package_ID'];

			echo "</td><td align='center'>";
			echo $result['wtype'];

			echo "</td><td align='center'>";
			echo $result['ttype'];

			echo "</td><td align='center'>";
			echo $result['content'];

			echo "</td><td align='center'>";
			echo $result['weight'];

			echo "</td><td align='center'>";
			echo $result['receiver_name'];

			echo "</td><td align='center'>";
			echo $result['receiver_phone'];

			echo "</td><td align='center'>";
			echo $result['from_st_ID'];

			echo "</td><td align='center'>";
			echo $result['to_st_ID'];

			echo "</td><td align='center'>";
			echo gmdate('r', $result['dateofshipment']);

			echo "</td><td align='center'>";
			echo gmdate('r',$result['estdateofshipment']);

			echo "</td><td align='center'>";
			echo calculateCost($result['wtype'],$result['ttype'],$result['content'],$result['weight']);
			echo " $";
			
			if ($result['ship_status'] == 'delivered'){
				echo "</td><td align='center' style='color:#FF0000'>";
			} else {
				echo "</td><td align='center'>";
			}
			echo $result['ship_status'];

			echo "</td></tr>";
	}
	echo "</table>";
?>

<html>
	<head>
	<title> Track your packages </title>
	</head>
</html>
