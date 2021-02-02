<?php
session_start();
?>
<?php
/* @var $_POST type */
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "azure";

$conn = mysqli_connect($servername,$username,$password,$dbname);

if($conn) {	
	$x = "SELECT user_id FROM geographic_details ORDER BY id DESC LIMIT 1";
	$queryx = mysqli_query($conn, $x);
	$rowx = mysqli_fetch_array($queryx, MYSQLI_ASSOC);
	
	$rowx = implode(" ",$rowx);
	$rowx = $rowx + 1;

	$name = $_POST['fullName'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$postcode = $_POST['postcode'];
	$country = $_POST['country'];
	$video = $_POST['video'];
	$date = $_POST['date'];
	
	$select = "INSERT INTO geographic_details (user_id, username, city, state, area, country, video_name, date) VALUES ('$rowx', '$name', '$city', '$state', '$postcode', '$country', '$video', '$date')";
	$queryresult = mysqli_query($conn, $select);
	$row = mysqli_fetch_array($queryresult, MYSQLI_ASSOC);
	$count = mysqli_num_rows($queryresult);
	
	Header("Location: file_details.php");
	echo "Welcome "." $queryresult";
}
?>