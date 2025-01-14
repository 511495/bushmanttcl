<?php
session_start();
include("db.php");

$error_message = "";

function handleLoginFormSubmission() {
    global $con, $error_message;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
        $email = mysqli_real_escape_string($con, $_POST['email']); 
        $password = mysqli_real_escape_string($con, $_POST['pass']);

        $query = "SELECT * FROM form WHERE email='$email' AND pass='$password'";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $row['id']; 
            header("Location: dashboard.php"); 
            exit();
        } else {
            $error_message = 'Incorrect email or password. Please try again.';
        }
    }
}

handleLoginFormSubmission();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/styles.css">
    <link rel="stylesheet" href="/styles/user.css">

    <title>Login - BMT</title>
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>
    

    <div class="container">


            <br>

            <div class="content-box">

            <form class="form" id="login" method="post" action="">
            <h1 class="form__title">Login</h1>
            <div class="form__message form__message--error">
                <?php
                if (!empty($error_message)) {
                    echo $error_message;
                }
                ?>
            </div>
            <div class="form__input-group">
                <input type="text" class="form__input" name="email" autofocus placeholder="Username or email">
                <div class="form__input-error-message"></div>
            </div>
            <div class="form__input-group">
                <input type="password" class="form__input" name="pass" autofocus placeholder="Password">
                <div class="form__input-error-message"></div>
            </div>
            <button class="form__button" type="submit">Continue</button>
            <p class="form__text">
            <br>
                <a href="register.php" class="form__link">Don't have an account? Sign up</a>
            </p>
            <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "baraboss";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description TEXT,
    price DECIMAL(10, 2),
    image1 VARCHAR(255)
)";

if ($conn->query($sql) === FALSE) {
    echo "Error creating table: " . $conn->error;
}
?>

<br>
<p class="form__text">
    <a href="forgot_password.php" class="form__link">Forgot your password?</a>
</p>
        </form>
        </div>
        <br>
        <br>


    <?php include 'footer.php'; ?>
    <script src="js/user.js"></script>

</body>
</html>
