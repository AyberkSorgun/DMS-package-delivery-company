<?php
	include("session.php");

	mysqli_begin_transaction($db, MYSQLI_TRANS_START_READ_WRITE);
	$cust_ID = $_POST['cust_ID'];
	$receiver_name = $_POST['receiver_name'];
	$receiver_phone = $_POST['receiver_phone'];
	$to_st_ID = $_POST['to_st_ID'];

	if ($_POST['package1'] == "package1"){
		echo "package 1 is selected <br>";
		$wtype = $_POST['wtype'];
		$ttype = $_POST['ttype'];
		$content = $_POST['content'];
		$weight = $_POST['weight'];
	}

	if ($_POST['package2'] == "package2"){
		echo "package 2 is selected <br>";
		$package_ID2 = $_POST['package_ID2'];
		$wtype2 = $_POST['wtype2'];
		$ttype2 = $_POST['ttype2'];
		$content2 = $_POST['content2'];
		$weight2 = $_POST['weight2'];
	}

	if ($_POST['package3'] == "package3"){
		echo "package 3 is selected <br>";
		$package_ID3 = $_POST['package_ID3'];
		$wtype3 = $_POST['wtype3'];
		$ttype3 = $_POST['ttype3'];
		$content3 = $_POST['content3'];
		$weight3 = $_POST['weight3'];
	}
	
	$admin = $_SESSION['username'];
	if($qsql = mysqli_query($db,"select st_ID from store where emp_ID in (SELECT emp_ID from employee where username = '$admin')")){
		$row = mysqli_fetch_array($qsql);
		$from_st_ID = $row[0];
	} else {
		echo "Error: This employee does not belong to any store!<br>";
	}
	
	$now = getdate();
	$dateofshipment = $now[0];
	echo $dateofshipment;
	echo "<br>";

	$result = mysqli_query($db,"CALL assign_tn(@result)") or die("Query fail:" .mysqli_error($db));
	$result = mysqli_query($db,"select @result") or die("Query fail:" .mysqli_error($db));
	$row = mysqli_fetch_array($result);
	echo $row[0];

	/*
	$sql = "INSERT INTO package VALUES ('".$cust_ID."','".$package_ID."','".$wtype."','".$ttype."', '".$content."', '".floatval($weight)."')";
	if ($result = mysqli_query($db,$sql)){
		//echo "Package successfully added to the system!";
		//echo "<br>";
		header("Location: welcome_employee.php");
		die();
	} else {
		//printf("Error: %s\n",mysqli_error($db));
		//$message = "wrong answer";
		//echo "<script type='text/javascript'>alert('$message');</script>";
		header("Location: send_package.html");
		die();
	}*/
	
?>
