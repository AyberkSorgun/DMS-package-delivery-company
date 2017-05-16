<?php
	include("session.php");
?>

<html>

<head>
	<title> Welcome <?php echo $_SESSION['username']; ?>!  </title>

	<style>

		.button {
		background-color: #4CAF50;
		border: none;
		color: white;
		padding: 15px 32px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 16px;
		margin: 4px 2px;
		cursor: pointer;
	}
	</style>
</head>

<body>

	<div style= "clear: both">
		<h2 style="float: left"	> Welcome <?php echo $_SESSION['username']; ?>! </h2>
		<h3 style="float: right"> <a href = "logout.php"> Sign Out </a></h3>
	</div>

	<br><br><br><br>
	
	<div>
		<a href = "send_package.html"> <button class = "button"> Send New Package </button> </a>
		<br>
		<a href = "view_upcoming_sent_packages.php"> <button class = "button"> View Upcoming/Sent Packages </button> </a>
		<br>
		<a href = "create_customer.html"> <button class = "button"> Create New Customer </button> </a>
		<br>
		<a href = "process_delivered_packages.php"> <button class = "button"> Process Delivered Packages </button> </a>
		<br>
		<button class = "button"> Generate Bill </button>
	</div>
	<!-- <?php
		$res =  mysqli_query($db,"select * from c_authentication" );
		echo '<table class= "table table-striped table-bordered table-hover" border = "1">';
		echo "<tr><th>Username</th><th>Password</th></tr>";
		while($row = mysqli_fetch_array($res)){
			echo "<tr><td>";
			echo $row['username'];
			echo "</td><td>";
			echo $row['password'];
			echo "</td></tr>";
		}
		echo "</table>";
	?> -->

</body>

</html>
