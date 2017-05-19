<?php
	include("session.php");

	$customer = $_SESSION['username'];
	if($qsql = mysqli_query($db,"select * FROM customer WHERE username = '$customer'")){
			$row = mysqli_fetch_array($qsql);
			$cust_ID = $row['cust_ID'];
	} else {
			echo "Error: This customer does not exist!<br>";
			die();
	}

	$contract_query = mysqli_query($db,"SELECT hasContract FROM customer natural join isFrequent WHERE username = '$customer'");
	$contract = mysqli_fetch_array($contract_query);
	
	$name_query = mysqli_query($db,"SELECT * FROM customer natural join fullname where username = '$customer'");
	$name = mysqli_fetch_array($name_query);

	$address_query = mysqli_query($db, "SELECT * FROM customer, address WHERE customer.cust_ID = address.ID and customer.cust_ID = '$cust_ID'");

	$address = mysqli_fetch_array($address_query);
	
	/*if ($contract['hasContract'] == 0){

		$shipment_query = mysqli_query($db, "SELECT * FROM customer, shipment WHERE customer.cust_ID = '$cust_ID' and customer.cust_ID = shipment.sender_ID");

		while($shipment = mysqli_fetch_array($shipment_query)) {
			$item_number = 0;
			echo "<div style='border:2px solid black'; margin: auto;'>";
			$item_number += 1;
			echo "<b>Customer ID, Name: </b>" . $cust_ID . ", " .ucfirst($name['fname']) . " " . ucfirst($name['mname']) . " " . ucfirst($name['lname']) . "<br>";
			echo "<b>Customer Address: </b>" . ucfirst($address['street']) . " , " . ucfirst($address['district']) . " , " . ucfirst($address['city']) . " , " . ucfirst($address['country']) . "<br>";
			echo "<b>Customer Phone Number: </b>" . $name['phone'] . "<br>";	
			echo "<b>Billing Date: </b>" . gmdate('r', $shipment['dateofshipment']) . "<br>";
			
			echo '<table class= "table table-striped table-bordered table-hover" border = "1">';
			echo "<tr>
					<th> Item Number </th>
					<th> Tracking Number </th>
					<th> Number of Packages </th>
					<th> Shipment Date </th>
					<th> Shipped From </th>
					<th> Price </th>
					</tr>";
			$tn = $shipment['track_number'];
			$num_packages_query = mysqli_query($db, "SELECT count(package_ID) FROM shipment natural join ship_packs WHERE track_number = '$tn' ");
			$cnt = mysqli_fetch_array($num_packages_query);


			$from_query = mysqli_query($db, "SELECT * FROM shipment, st_info WHERE shipment.from_st_ID = st_info.st_ID");
			$from = mysqli_fetch_array($from_query);

				echo "<tr><td align='center'>";
					echo $item_number;
					echo "</td><td align='center'>";
					echo $shipment['track_number'];
					echo "</td><td align='center'>";
					echo $cnt['count(package_ID)'];
					echo "</td><td align='center'>";
					echo gmdate('r', $shipment['dateofshipment']);
					echo "</td><td align='center'>";
					echo ucfirst($from['street']) . " , ". ucfirst($from['district'] ." , ". ucfirst($from['city']) . " , ". ucfirst($from['country']));
					echo "</td><td align='center'>";
					echo $shipment['cost'];
				echo "</td></tr>";

			echo "<tr>
					<td align='center'> - </td>
					<td align='center'> - </td>
					<td align='center'> - </td>
					<td align='center'> - </td>
					<td align='center'> - </td>
					<td align='center'> - </td>
				</tr>";
			$cost = $shipment['cost'];
			echo "<tr>
					<td> </td>
					<td> </td>
					<td> </td>
					<td> TOTAL </td>
					<td> </td>
					<td>" . $cost . "</td>
				</tr>";

			echo '</table>';
		echo "</div>";

			echo "<br><br><br><br>";
		}
	}*/
	/* else */ {
		$bill_query = mysqli_query($db, "SELECT * FROM ((bill natural join bill_ships) natural join shipment) WHERE sender_ID = '$cust_ID' and sender_ID = cust_ID");
		$table_count = 0;

		if (!$bill_query){
			echo "Bill query failed!";		
		} else {
			$prev_bill_ID = NULL;
			$total_cost = 0;

			while($bill = mysqli_fetch_array($bill_query)){
				if ($prev_bill_ID != $bill['bill_ID']){
					if ($table_count != 0){
						//finish previous tables
						echo "<tr>
							<td align='center'> - </td>
							<td align='center'> - </td>
							<td align='center'> - </td>
							<td align='center'> - </td>
							<td align='center'> - </td>
							<td align='center'> - </td>
						</tr>";

						echo "<tr>
								<td> </td>
								<td> </td>
								<td> </td>
								<td align='center'> TOTAL </td>
								<td> </td>
								<td align='center'>" . $total_cost . "</td>
							</tr>";

						echo '</table>';
					echo "</div>";

						echo "<br><br><br><br>";
						
					} else {
						$table_count += 1;
						// new bill
						$item_number = 0;
						$total_cost = 0;
						echo "<div style='border:2px solid black'; margin: auto;'>";
						echo "<b>Customer ID, Name: </b>" . $cust_ID . ", " .ucfirst($name['fname']) . " " . ucfirst($name['mname']) . " " . ucfirst($name['lname']) . "<br>";
						echo "<b>Customer Address: </b>" . ucfirst($address['street']) . " , " . ucfirst($address['district']) . " , " . ucfirst($address['city']) . " , " . ucfirst($address['country']) . "<br>";
						echo "<b>Customer Phone Number: </b>" . $name['phone'] . "<br>";	
						echo "<b>Billing Date: </b>" .$bill['billing_date'] . "<br>";
						echo "<b>Bill ID: </b>" . $bill['bill_ID'] . "<br>";
						echo '<table class= "table table-striped table-bordered table-hover" border = "1">';
						echo "<tr>
								<th> Item Number </th>
								<th> Tracking Number </th>
								<th> Number of Packages </th>
								<th> Shipment Date </th>
								<th> Shipped From </th>
								<th> Price </th>
							</tr>";

						$item_number += 1;
						$tn = $bill['track_number'];
						$num_packages_query = mysqli_query($db, "SELECT count(package_ID) FROM shipment natural join ship_packs WHERE track_number = '$tn' ");
						$cnt = mysqli_fetch_array($num_packages_query);


						$from_query = mysqli_query($db, "SELECT * FROM shipment, st_info WHERE shipment.from_st_ID = st_info.st_ID");
						$from = mysqli_fetch_array($from_query);

						echo "<tr><td align='center'>";
							echo $item_number;
							echo "</td><td align='center'>";
							echo $tn;
							echo "</td><td align='center'>";
							echo $cnt['count(package_ID)'];
							echo "</td><td align='center'>";
							echo gmdate('r', $bill['dateofshipment']);
							echo "</td><td align='center'>";
							echo ucfirst($from['street']) . " , ". ucfirst($from['district'] ." , ". ucfirst($from['city']) . " , ". ucfirst($from['country']));
							echo "</td><td align='center'>";
							echo $bill['cost'];
							$total_cost += $bill['cost'];
						echo "</td></tr>";
						}

				} else {
					// continue adding shipments to the bill
					$item_number += 1;
					$tn = $bill['track_number'];
					$num_packages_query = mysqli_query($db, "SELECT count(package_ID) FROM shipment natural join ship_packs WHERE track_number = '$tn' ");
					$cnt = mysqli_fetch_array($num_packages_query);


					$from_query = mysqli_query($db, "SELECT * FROM shipment, st_info WHERE shipment.from_st_ID = st_info.st_ID");
					$from = mysqli_fetch_array($from_query);

					echo "<tr><td align='center'>";
						echo $item_number;
						echo "</td><td align='center'>";
						echo $tn;
						echo "</td><td align='center'>";
						echo $cnt['count(package_ID)'];
						echo "</td><td align='center'>";
						echo gmdate('r', $bill['dateofshipment']);
						echo "</td><td align='center'>";
						echo ucfirst($from['street']) . " , ". ucfirst($from['district'] ." , ". ucfirst($from['city']) . " , ". ucfirst($from['country']));
						echo "</td><td align='center'>";
						echo $bill['cost'];
						$total_cost += $bill['cost'];
					echo "</td></tr>";
				}
				$prev_bill_ID = $bill['bill_ID'];
			}

			echo "<tr>
				<td align='center'> - </td>
				<td align='center'> - </td>
				<td align='center'> - </td>
				<td align='center'> - </td>
				<td align='center'> - </td>
				<td align='center'> - </td>
			</tr>";

			echo "<tr>
					<td> </td>
					<td> </td>
					<td> </td>
					<td align='center'> TOTAL </td>
					<td> </td>
					<td align='center'>" . $total_cost . "</td>
				</tr>";

			echo '</table>';
		echo "</div>";

			echo "<br><br><br><br>";
			
		}
	}
	
?>

<html>
	<head>
		<title> View Bills </title>
		<meta http-equiv="Content-Type" content="text/html" charset="utf-8"/>
	</head>

	<body>
		
	</body>
</html>
