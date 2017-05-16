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
		<button class = "button"> Track Your Package </button>
		<button class = "button"> View Your Bills </button>
		<button class = "button"> View Your Account Information </button>
	</div>

</body>

</html>
