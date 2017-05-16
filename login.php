
<?php

include("config.php");
session_start();

if($_SERVER["REQUEST_METHOD"] == 'POST'){
	$username =  mysqli_real_escape_string($db, $_POST['username']);
	$password =  mysqli_real_escape_string($db, $_POST['password']);

	$sql = "SELECT username FROM c_authentication WHERE username = '$username' and password = '$password';";
	$sql .= "SELECT username FROM e_authentication WHERE username = '$username' and password = '$password'";

	if( mysqli_multi_query($db,$sql)){
		$result1 = mysqli_store_result($db);
		mysqli_next_result($db);
		$result2 = mysqli_store_result($db);
	}
	
	$count = mysqli_num_rows($result1);
	$count2 = mysqli_num_rows($result2);

	if ($count == 1){
		echo "<b>" . $username . "</b>";
		echo " has been logged in.";
		echo "<br>";
		$_SESSION['username'] = $username;
		header("Location: welcome_customer.php");
		die();
	}  elseif ($count2 == 1){
		$_SESSION['username'] = $username;
		header("Location: welcome_employee.php");
		die();
	} else {
		header("Location: try_again.html");
	}
}

?>
