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

	$fullname_query = mysqli_query($db,"SELECT * FROM customer natural join fullname where username = '$customer'");
	$fullname = mysqli_fetch_array($fullname_query);
	$isFrequent_query = mysqli_query($db,"SELECT * FROM customer natural join isFrequent where username = '$customer'");
	$isFrequent = mysqli_fetch_array($isFrequent_query);
	$address_query = mysqli_query($db,"SELECT * FROM customer, address WHERE customer.cust_ID = address.ID and customer.cust_ID = '$cust_ID'");
	$address = mysqli_fetch_array($address_query);
	

	echo "<b><h3> Your Account Information</h3> </b>";
	echo "Your username: ". $customer . "<br>";
	echo "Your email: " .$row['email'] . "<br>";
	echo "Your phone: " .$row['phone'] . "<br>";
	echo "First name: " .$fullname['fname'] . "<br>";
	if ($fullname['mname'] != ''){
		echo "Middle name: " .$fullname['mname'] . "<br>";
	}
	echo "Last name: " .$fullname['lname'] . "<br>";
	echo "Has contract: ";
	if ($isFrequent['hasContract'] == 1){
		echo "YES" . "<br>";
	} else if ($isFrequent['hasContract'] == 0){
		echo "NO" . "<br>";
	}
	echo "<br><b><u> Your address:</u></b><br>";
	echo "Street: " .$address['street'] . "<br>";
	echo "District: " .$address['district'] . "<br>";
	echo "City: " .$address['city'] . "<br>";
	echo "Country: " .$address['country'] . "<br>";
		
		
?>

<html>
	<head>
	<title> View Your Account Information </title>
	</head>

	<body>
		
	</body>
</html>
