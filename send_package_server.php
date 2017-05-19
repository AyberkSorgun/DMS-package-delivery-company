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

	function checkWeight($pid, $wtype, $weight){

		$result = True;

		if ($wtype == 'flat' or $wtype == 'envelope'){
			if ($weight > 1){
				$result = False;
				echo "Error on package " . $pid . ". Flat or envelope cannot contain more than 1 kg! <br>";
			}
		}

		else if ($wtype == 'small box'){
			if ($weight > 5){
				$result = False;
				echo "Error on package " . $pid . ". Small box cannot contain more than 5 kg! <br>";
			}
		}

		else if ($wtype == 'mid-sized box'){
			if ($weight > 7){
				$result = False;
				echo "Error on package " . $pid . ". Mid-sized box cannot contain more than 7 kg! <br>";
			}
		}

		return $result;
	}


	$cust_ID = $_POST['cust_ID'];
	$receiver_name = $_POST['receiver_name'];
	$receiver_phone = $_POST['receiver_phone'];
	$to_st_ID = $_POST['to_st_ID'];
	$package_ID = 10000;
	$ttype = $_POST['ttype'];

	$result = mysqli_query($db,"CALL assign_tn(@a);") or die("Query fail:" .mysqli_error($db));
	if($result){
		echo "Call successfull <br>";
	} else {
		echo "Call failed <br>";
		die();
	}
	
	$result2 = mysqli_query($db,"select @a;") or die("Query fail:" .mysqli_error($db));
	$row = mysqli_fetch_array($result2);
	$tracknumber = $row[0];
	echo $tracknumber;

	$now = getdate();
	$dateofshipment = $now[0];
	$estimated = $dateofshipment;

	if ($ttype == 'overnight'){
		$estimated = $estimated + 86400;
	} else if ($ttype == '2nd day'){
		$estimated = $estimated + 2 * 86400;
	} else if ($ttype == '7-day shipping'){
		$estimated = $estimated + 7 * 86400;
	}

	$cost = 0;
	echo "<br>";
	echo $dateofshipment;
	echo "<br>";

	$admin = $_SESSION['username'];
	if($qsql = mysqli_query($db,"select st_ID from store where emp_ID in (SELECT emp_ID from employee where username = '$admin')")){
		$row = mysqli_fetch_array($qsql);
		$from_st_ID = $row[0];
	} else {
		echo "Error: This employee does not belong to any store!<br>";
	}

	$status = "on-the-way";

	//mysqli_begin_transaction($db, MYSQLI_TRANS_START_READ_WRITE);
	
	if ($qsql and $result and $result2){
		//mysqli_begin_transaction($db, MYSQLI_TRANS_START_READ_WRITE);
		$w = True;

		if ($_POST['package1'] == "package1"){
			echo "package 1 is selected <br>";
			$wtype = $_POST['wtype1'];
			$content = $_POST['content1'];
			$weight = $_POST['weight1'];
			$sql1 = "INSERT INTO package VALUES ('".$cust_ID."','".$package_ID."','".$wtype."','".$ttype."', '".$content."', '".floatval($weight)."')";
			
			$wresult1 = checkWeight(1, $wtype, $weight);
			$w = $w & $wresult1;
			$cost += calculateCost($wtype, $ttype, $content, $weight);
		}

		if ($_POST['package2'] == "package2"){
			echo "package 2 is selected <br>";
			$package_ID2 = $_POST['package_ID2'];
			$wtype2 = $_POST['wtype2'];
			$content2 = $_POST['content2'];
			$weight2 = $_POST['weight2'];
			$sql2 = "INSERT INTO package VALUES ('".$cust_ID."','".$package_ID."','".$wtype2."','".$ttype."', '".$content2."', '".floatval($weight2)."')";

			$wresult2 = checkWeight(2, $wtype2, $weight2);
			$w = $w & $wresult2;
			$cost += calculateCost($wtype2, $ttype, $content2, $weight2);
		}

		if ($_POST['package3'] == "package3"){
			echo "package 3 is selected <br>";
			$package_ID3 = $_POST['package_ID3'];
			$wtype3 = $_POST['wtype3'];
			$content3 = $_POST['content3'];
			$weight3 = $_POST['weight3'];
			$sql3 = "INSERT INTO package VALUES ('".$cust_ID."','".$package_ID."','".$wtype3."','".$ttype."', '".$content3."', '".floatval($weight3)."')";

			$wresult3 = checkWeight(3, $wtype3, $weight3);
			$w = $w & $wresult3;
			$cost += calculateCost($wtype3, $ttype, $content3, $weight3);
		}

		if ($w){
			//mysqli_begin_transaction($db, MYSQLI_TRANS_START_READ_WRITE);
			$master_query = mysqli_query($db,"INSERT INTO shipment VALUES ('".$tracknumber."','".$cust_ID."','".$receiver_name."','".$receiver_phone."', '".$from_st_ID."', '".$to_st_ID."','".$dateofshipment."','".$estimated."','".$status."','".$cost."')");

			if ($master_query){
				echo "Shipment query is successfull!<br>";
				$rollback = False;

				if ($_POST['package1'] == "package1"){
					$send1 = mysqli_query($db,$sql1);
					if (!$send1){
						printf("Error on package 1: %s\n",mysqli_error($db));
						$rollback = True;
						echo "<br>";					
					} else {
						if ($_POST['package2'] == "package2"){
							$send2 = mysqli_query($db,$sql2);
							if (!$send2){
								printf("Error on package 2: %s\n",mysqli_error($db));
								$rollback = True;
								echo "<br>";					
							} else {
								if ($_POST['package3'] == "package3"){
									$send3 = mysqli_query($db,$sql3);
									if (!$send3){
										printf("Error on package 3: %s\n",mysqli_error($db));
										$rollback = True;
										echo "<br>";					
									} else {
										echo "Packages are successfully sent <br>";
									}
								}
							}
						}
					}
				}

			} else {
				printf("Error on shipment query: %s\n Rolling back", mysqli_error($db));
				$rollback = True;
			}

			if ($rollback){
				echo "Error on some packages, rolling back ... <br>";
				$rback = mysqli_query($db, "DELETE FROM shipment WHERE track_number = '$tracknumber'");
				if ($rback){
					echo "Rollback is successfull.<br>";	
				} else {
					echo "Rollback failed.<br>";	
				}
			}
		}
	}
?>

<html>
	<head>
	<title> Send Packages </title>
	</head>

	<body>
		
	</body>
</html>
