<?php

define('DB_SERVER', 'istavrit.eng.ku.edu.tr');
define('DB_USERNAME', 'group9');
define('DB_PASSWORD', '1491484840500121117');
define('DB_DATABASE', 'group9');

$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
if ($db){
  //echo "successfully connected to DB server";
  //echo "<br>";
}
else {
  echo "no proper connection to DB server\n";
}

?>
