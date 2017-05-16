<?php
	include("session.php");

	$cust_ID = $_POST['cust_ID'];
	$receiver_name = $_POST['receiver_name'];
	$receiver_phone = $_POST['receiver_phone'];
	$to_st_ID = $_POST['to_st_ID'];
	$package_ID = 10000;
	$ttype = $_POST['ttype'];

	//mysqli_begin_transaction($db, MYSQLI_TRANS_START_READ_WRITE);
	$result = mysqli_query($db,"CALL assign_tn(@a);") or die("Query fail:" .mysqli_error($db));
	if($result){
		//mysqli_commit($db);
		echo "Call successfull <br>";
	} else {
		//mysqli_rollback($db);
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
	$master_query = mysqli_query($db,"INSERT INTO shipment VALUES ('".$tracknumber."','".$cust_ID."','".$receiver_name."','".$receiver_phone."', '".$from_st_ID."', '".$to_st_ID."','".$dateofshipment."','".$estimated."','".$status."','".$cost."')");

	if ($master_query){
		//mysqli_commit($db);
		echo "Shipment query is successfull!<br>";

	} else {
		printf("Error on shipment query: %s\n", mysqli_error($db));
		//mysqli_rollback($db);
		die();
	}

	if ($qsql and $result and $result2 and $master_query){
		//mysqli_begin_transaction($db, MYSQLI_TRANS_START_READ_WRITE);
		$doCommit = True;

		if ($_POST['package1'] == "package1"){
			echo "package 1 is selected <br>";
			$wtype = $_POST['wtype1'];
			$content = $_POST['content1'];
			$weight = $_POST['weight1'];
			$sql = "INSERT INTO package VALUES ('".$cust_ID."','".$package_ID."','".$wtype."','".$ttype."', '".$content."', '".floatval($weight)."')";
			$send1 = mysqli_query($db,$sql);

			if (!$send1){
				$doCommit = False;	
				printf("Error on package 1: %s\n",mysqli_error($db));
				echo "<br>";					
			}
		}

		if ($_POST['package2'] == "package2"){
			echo "package 2 is selected <br>";
			$package_ID2 = $_POST['package_ID2'];
			$wtype2 = $_POST['wtype2'];
			$content2 = $_POST['content2'];
			$weight2 = $_POST['weight2'];
			$sql = "INSERT INTO package VALUES ('".$cust_ID."','".$package_ID."','".$wtype2."','".$ttype."', '".$content2."', '".floatval($weight2)."')";
			$send2 = mysqli_query($db,$sql);

			if (!$send2){
				$doCommit = False;
				printf("Error on package 2: %s\n",mysqli_error($db));
				echo "<br>";											
			}
		}

		if ($_POST['package3'] == "package3"){
			echo "package 3 is selected <br>";
			$package_ID3 = $_POST['package_ID3'];
			$wtype3 = $_POST['wtype3'];
			$content3 = $_POST['content3'];
			$weight3 = $_POST['weight3'];
			$sql = "INSERT INTO package VALUES ('".$cust_ID."','".$package_ID."','".$wtype3."','".$ttype."', '".$content3."', '".floatval($weight3)."')";
			$send3 = mysqli_query($db,$sql);
			if (!$send3){
				$doCommit = False;
				printf("Error on package 3: %s\n",mysqli_error($db));	
				echo "<br>";							
			}
		}
		
		if($doCommit){
			//mysqli_commit($db);
			echo "WE DID IT!";	
		} else {
			//mysqli_rollback($db);
			echo "fucked up!";
		}
	}

?>
