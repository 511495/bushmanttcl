<?php
include_once('db_config.php');
include("db.php");

session_start();

$errors = [];
$success_message = "";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT fname, lname, gender, pnumber, email, country, birthday, pass FROM form WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fname = htmlspecialchars($row['fname']);
    $lname = htmlspecialchars($row['lname']);
    $gender = $row['gender'];
    $pnumber = $row['pnumber'];
    $email = htmlspecialchars($row['email']);
    $country = htmlspecialchars($row['country']);  
    $birthday = $row['birthday'];
    $storedPassword = $row['pass'];
} else {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['fname'], $_POST['lname'], $_POST['gender'], $_POST['country'], $_POST['birthday'])) {
    }

    if (isset($_POST['oldPassword'], $_POST['newPassword'], $_POST['confirmPassword'])) {
        $oldPassword = $_POST['oldPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($oldPassword === $storedPassword) {
            if ($oldPassword !== $newPassword) {
                if ($newPassword === $confirmPassword) {
                    $updateStmt = $con->prepare("UPDATE form SET pass = ? WHERE id = ?");
                    $updateStmt->bind_param("si", $newPassword, $user_id);
                    $updateStmt->execute();
                    $updateStmt->close();
                    $success_message = "Your password has been changed successfully!";
                } else {
                    $errors[] = "The new passwords you entered do not match. Please try again.";
                }
            } else {
                $errors[] = "Choose a new password that is different from your old one.";
            }
        } else {
            $errors[] = "The old password you entered is incorrect. Please try again.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/styles.css">
    <link rel="stylesheet" href="/styles/account.css">

    <title>Account - BMT</title>
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


<div class="account-section">
        <form id="updateAccountForm" action="account.php" method="post">
            <hr><br><h3 class="emailandphone">EMAIL AND PHONE</h3><br><hr><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" disabled>

            <br><br>

            <label for="pnumber">Phone Number:</label>
            <input type="tel" id="pnumber" name="pnumber" value="<?php echo $pnumber; ?>" disabled>


            <br><br><br><hr><br><h3 class="accountdetails">ACCOUNT DETAILS</h3><br><hr><br><br>

            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="fname" value="<?php echo $fname; ?>"disabled>

            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="lname" value="<?php echo $lname; ?>"disabled>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender">
                <option value="male" <?php echo ($gender === 'male') ? 'selected' : ''; ?>>Male</option>
                <option value="female" <?php echo ($gender === 'female') ? 'selected' : ''; ?>>Female</option>
            </select>

            <label for="birthday">Birthday:</label>
            <input type="date" id="birthday" name="birthday" value="<?php echo $birthday; ?>" min="1945-01-01" max="2010-01-01">
            <br><br>

            <button type="submit">Update Details</button>
        </form>
        <br><br><hr><br><h3 class="passworddd">PASSWORD</h3><br><hr><br>

        <form id="resetPasswordForm" action="account.php" method="post">
            <label for="oldPassword">Old Password:</label>
            <input type="password" id="oldPassword" name="oldPassword">

            <label for="newPassword">New Password:</label>
            <input type="password" id="newPassword" name="newPassword">

            <label for="confirmPassword">Confirm New Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword">

            <?php foreach ($errors as $error): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endforeach; ?>

            <?php if (!empty($success_message)) : ?>
                <p class="success-message"><?php echo $success_message; ?></p>
            <?php endif; ?>
            <br>
            <button type="submit">Reset Password</button>
        </form>
        <br><br>
    </div>
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
