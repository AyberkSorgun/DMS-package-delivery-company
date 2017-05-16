<?php
		include("session.php");

		$employee = $_SESSION['username'];
		if($qsql = mysqli_query($db,"select st_ID from store where emp_ID in (SELECT emp_ID from employee where username = '$employee')")){
			$row = mysqli_fetch_array($qsql);
			$st_ID = $row[0];
		} else {
			echo "Error: This employee does not belong to any store!<br>";
		}

		$res =  mysqli_query($db,"SELECT * FROM shipment where to_st_ID = '$st_ID'");

		echo "<b> All packages<b><br><br>";		
		echo '<table class= "table table-striped table-bordered table-hover" border = "1">';
		echo "<tr><th> </th><th>Track Number</th><th>Sender ID</th><th>Receiver Name</th><th>Receiver Phone</th><th>From Store ID</th><th>To Store ID</th><th>Date of Shipment</th><th>Estimated date of arrival</th><th>Cost</th><th>Shipment Status</th></tr>";
		
	while($row = mysqli_fetch_array($res)){
			$tn = $row['track_number'];
			if ($row['ship_status'] == 'delivered'){
				echo "<tr><td align='center'>";
				//echo "<input type='checkbox' name = 'status_check' disabled value= '".$tn."'>";
			} else {
				echo "<tr><td align='center'>";
				echo "<input type='checkbox' name = 'status_check' value= '".$tn."'>";
			}
			
			echo "</td><td align='center'>";
			echo $row['track_number'];
			echo "</td><td align='center'>";
			echo $row['sender_ID'];
			echo "</td><td align='center'>";
			echo $row['receiver_name'];
			echo "</td><td align='center'>";
			echo $row['receiver_phone'];
			echo "</td><td align='center'>";
			echo $row['from_st_ID'];
			echo "</td><td align='center'>";
			echo $row['to_st_ID'];
			echo "</td><td align='center'>";
			echo gmdate('r', $row['dateofshipment']);
			echo "</td><td align='center'>";
			echo gmdate('r',$row['estdateofshipment']);
			echo "</td><td align='center'>";
			echo $row['cost'];
			echo "</td><td align='center'>";
			echo $row['ship_status'];
			echo "</td></tr>";
		}
		echo "</table>";
?>

<html>
	<head>
	<title> Process Packages </title>
	<script>

	function AjaxCaller(){
		var xmlhttp=false;
		try{
		    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
		    try{
		        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		    }catch(E){
		        xmlhttp = false;
		    }
		}

		if(!xmlhttp && typeof XMLHttpRequest!='undefined'){
		    xmlhttp = new XMLHttpRequest();
		}
		return xmlhttp;
	}

	function callPage(url){
		ajax=AjaxCaller(); 
		ajax.open("GET", url, true); 
		ajax.onreadystatechange=function(){
		    if(ajax.readyState==4){
		        if(ajax.status==200){
		            //console.log(ajax.responseText);
		        }
		    }
		}
		ajax.send(null);
	}


	function getCheckedBoxes() {
		  var checkboxes = document.getElementsByName("status_check");

		  for (var i=0; i<checkboxes.length; i++) {
			 if (checkboxes[i].checked) {
				callPage('process_delivered_packages_server.php?tn='+checkboxes[i].value);
				console.log(checkboxes[i].value);
			 }
		  }

 			setTimeout(function(){ 
				location.reload(); 
			}, 1500);
	}

	</script>

	</head>

	<body>
		<br>
		<button style="float: right" type="button" onclick="getCheckedBoxes()"> Set as delivered </button>
	</body>
</html>
