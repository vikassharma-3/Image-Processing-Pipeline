<?php
$target_file = $_FILES["fileToUpload"]["name"];
error_reporting(E_ERROR | E_PARSE);
$temp = explode(".", $_FILES["fileToUpload"]["name"]);

$connection = mysqli_connect("localhost", "root", ""); // Establishing Connection with Server
$db = mysqli_select_db($connection, "azure"); // Selecting Database

//MySQL Query to read data
$select = "select username from video_file_details where actual_file_name like '$target_file'";
$query = mysqli_query($connection, $select);
$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
$row = implode(" ",$row);

$newfilename = $row . '.' . end($temp);
move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "video_uploads/" . $newfilename);

$uploadOk = 1;
$imageFileType = strtolower(pathinfo($newfilename,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo " ";
    $uploadOk = 1;
  } else {
    echo "File is not a video.";
    $uploadOk = 0;
  }
}

// Check if file already exists
elseif (file_exists($newfilename)) {
  echo '<script>alert("Sorry, File Already Exists...\nChoose another File.");
			window.location.href = "file_generation.php";
			</script>';
  $uploadOk = 0;
}

// Allow certain file formats
elseif($imageFileType != "mp4" && $imageFileType != "avi" && $imageFileType != "mov"
&& $imageFileType != "gif" ) {
  echo "Sorry, only mp4, avi, mov & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
elseif ($uploadOk == 0) {
  echo '<script>alert("Sorry your File was not Uploaded.");
			window.location.href = "file_generation.php";
			</script>';
// if everything is ok, try to upload file
} 
else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $newfilename)) {
    echo '<script>alert("Your Video has been uploaded Successfully.");
			window.location.href = "login.php";
			</script>';
  } else {
		echo '<script>alert("Your Video will be uploaded soon...\nPlease Login First.");
			window.location.href = "login.php";
			</script>';
  }
}
?>