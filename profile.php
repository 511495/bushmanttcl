<?php
session_start();

include_once('db_config.php');
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT fname, lname, profile_photo, timestamp FROM form WHERE id='$user_id'";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $fname = $row['fname'];
    $lname = $row['lname'];
    $profilePhoto = $row['profile_photo'];
    $registrationDate = $row['timestamp'];  
} else {
    header("Location: login.php");
    exit();
}

if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
    $tempFile = $_FILES['profilePhoto']['tmp_name'];
    $targetPath = 'uploads/'; 
    $fileName = $_FILES['profilePhoto']['name'];
    $targetFile = $targetPath . $fileName;

    echo "Temp File: " . $tempFile . "<br>";
    echo "Target File: " . $targetFile . "<br>";

    if (move_uploaded_file($tempFile, $targetFile)) {
        $query = "UPDATE form SET profile_photo = '$targetFile' WHERE id = '$user_id'";
        mysqli_query($con, $query);
    
        echo $targetFile;
    } else {
        echo "Move uploaded file failed. Error: " . $_FILES['profilePhoto']['error'];
    }
    

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/styles.css">
    <link rel="stylesheet" href="/styles/profile.css">

    <title>Profile - BMT</title>
</head>
<body>
<input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>">

    <header>
        <?php include 'navbar.php'; ?>
    </header>
    
    <button class="backto" onclick="scrollToPrevious()">⬅</button>

<style>.backto {
margin-top: 30px;
margin-left: 50px;
    background-color: #6b1508;
    color: #fff;
    border-radius: 50%;
    width: 40px; 
    height: 40px; 
    border: none;
    cursor: pointer;
    font-size: 1.5em;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>


<div class="profile-section">
<form id="uploadForm" enctype="multipart/form-data" action="upload_profile_photo.php" method="POST">
    <label for="profilePhoto">Profile Photo</label>
</form>


    <img src="<?php echo $profilePhoto; ?>" alt="" id="profileImage"> <br>

    <input type="file" name="profilePhoto" id="profilePhoto" accept="image/*">
    <button type="submit">Upload Photo</button> 

    <br><br><hr><br>

    <p>First Name: <?php echo $fname; ?></p>
    <p>Last Name: <?php echo $lname; ?></p><br>
    <p>Member Since: <?php echo $registrationDate; ?></p>
</div>
<br>
<hr>

<script>
function scrollToPrevious() {
    window.history.back();
}

function uploadPhoto() {
    var formData = new FormData(document.getElementById('uploadForm'));

    formData.append('user_id', <?php echo $user_id; ?>);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'upload_profile_photo.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('profileImage').src = xhr.responseText;
        } else {
            console.error('Upload failed. Status: ' + xhr.status);
        }
    };

    xhr.send(formData);
}
</script>

        <br><br>


    <?php include 'footer.php'; ?>

</body>
</html>
