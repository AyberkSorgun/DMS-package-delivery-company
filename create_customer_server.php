<?php
		include("session.php");

		$cust_ID = 10000;
		$username = $_POST['username'];
		$password = $_POST['password'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$fname = $_POST['fname'];
		$mname = $_POST['mname'];
		$lname = $_POST['lname'];
		$isFreq = $_POST['isFreq'];

		$street = $_POST['street'];
		$district = $_POST['district'];
		$city = $_POST['city'];
		$country = $_POST['country'];

		$shouldDie = False;

		if (strlen($password) < 6){
			echo "ERROR! Your password should have at least 6 characters!<br>";
			$shouldDie = True;
		} else if (!ctype_alnum($password)){
			echo "ERROR! Your password should be consisting of only alphanumerical characters!<br>";
			$shouldDie = True;
		}

		if ($email == "" or $phone == "" or $street == "" or $district == "" or $city == "" or $country == ""){
			echo "ERROR! Email, phone or address cannot be left blank!<br>";
			$shouldDie = True;
		}

		if ($username == ""){
			echo "ERROR! Username cannot be left blank!<br>";
			$shouldDie = True;
		}
		
		if ($shouldDie){
			die();
		}

		$sql1 = "INSERT INTO customer VALUES ('".$cust_ID."','".$username."','".$email."','".$phone."')";
		$sql2 = "INSERT INTO c_authentication VALUES ('".$username."','".$password."')";
		$sql3 = "INSERT INTO fullname VALUES ('".$username."','".$fname."','".$mname."','".$lname."')";
		$sql4 = "INSERT INTO isFrequent VALUES ('".$username."','".$isFreq."')";
		$sql5 = "INSERT INTO address VALUES ('".$cust_ID."','".$street."','".$district."','".$city."','".$country."')";

		mysqli_begin_transaction($db, MYSQLI_TRANS_START_READ_WRITE);
		$a1 = mysqli_query($db, $sql1);
		if (!$a1){
			printf("Error 1: %s\n",mysqli_error($db));
		}
		echo "<br>";
		$a2 = mysqli_query($db, $sql2);
		if (!$a2){
			printf("Error 2: %s\n",mysqli_error($db));
		}
		echo "<br>";
		$a3 = mysqli_query($db, $sql3);
		if (!$a3){
			printf("Error 3: %s\n",mysqli_error($db));
		}
		echo "<br>";
		$a4 = mysqli_query($db, $sql4);
		if (!$a4){
			printf("Error 4: %s\n",mysqli_error($db));
		}
		echo "<br>";
		$a5 = mysqli_query($db, $sql5);
		if (!$a5){
			printf("Error 5: %s\n",mysqli_error($db));
		}
		echo "<br>";

		if ($a1 and $a2 and $a3 and $a4 and $a5){
			mysqli_commit($db);
			header("Location: welcome_employee.php");
		} else {
			echo "Error occured while creating customer, rolling back";
			mysqli_roolback($db);
		}
?>
