<?php
session_start();
include("db.php");


$error_message = "";

function isValidName($name) {
    return preg_match("/^[a-zA-Z-]+$/", $name);
}

function handleRegistrationFormSubmission() {
    global $con, $error_message;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $firstname = $_POST['fname'];
        $lastname = $_POST['lname'];
        $Gender = $_POST['gender'];
        $CountryCode = $_POST['country_code'];
        $PhoneNumber = $_POST['pnumber'];
        $Email = $_POST['email'];
        $password = $_POST['pass'];
        $confirmPassword = $_POST['confirm_pass'];
        if ($password !== $confirmPassword) {
            $error_message .= "Passwords do not match.<br>";
        }

        if (!isValidName($firstname) || !isValidName($lastname)) {
            $error_message .= "Invalid characters in first or last name.<br>";
        }

        if (empty($error_message)) { 
            $query = "INSERT INTO form (fname, lname, gender, country_code, pnumber, email, pass) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $query);

            if ($stmt) {
                $checkQuery = "SELECT * FROM form WHERE email = ?";
                $checkStmt = mysqli_prepare($con, $checkQuery);
                mysqli_stmt_bind_param($checkStmt, "s", $Email);
                mysqli_stmt_execute($checkStmt);
                mysqli_stmt_store_result($checkStmt);
            
                if (mysqli_stmt_num_rows($checkStmt) > 0) {
                    $error_message .= "Email already exists.<br>";
                } else {
                    $query = "INSERT INTO form (fname, lname, gender, country_code, pnumber, email, pass) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($con, $query);
            
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "sssssss", $firstname, $lastname, $Gender, $CountryCode, $PhoneNumber, $Email, $password);
            
                        if (mysqli_stmt_execute($stmt)) {
                            echo "<script type='text/javascript'> alert('Successfully Registered')</script>";
                        } else {
                            $error_message .= "Error executing the statement: " . mysqli_error($con) . "<br>";
                        }
                    } else {
                        $error_message .= "Error in prepared statement: " . mysqli_error($con) . "<br>";
                    }
                }
            
                mysqli_stmt_close($checkStmt);
                mysqli_stmt_close($stmt);
            } else {
                $error_message .= "Error in prepared statement: " . mysqli_error($con) . "<br>";
            }
            
        }
    }
}

$countryFlags = [
    "+31" => "🇳🇱 Netherlands",
    "+32" => "🇧🇪 Belgium",
    "+49" => "🇩🇪 Germany",
];


handleRegistrationFormSubmission();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/styles.css">
    <link rel="stylesheet" href="/styles/user.css">

    <title>Sign Up - BMT</title>
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>
    

    <div class="container">


            <br>

            <div class="content-box">

            <form class="form" id="createAccount" method="post" action="">
            <h1 class="form__title">Create Account</h1>
            <br>
            <div class="form__error-message">
                <?php echo $error_message; ?>
            </div>

            <input type="text" id="fname" class="form__input" name="fname" autofocus placeholder="First Name">
            <div class="form__input-error-message"></div>

            <input type="text" id="lname" class="form__input" name="lname" autofocus placeholder="Last Name">
            <div class="form__input-error-message"></div>

            <select id="gender" class="form__input" name="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <div class="form__input-error-message"></div>

            <div class="form__input-group">
                <select id="country_code" class="form__input" name="country_code">
                    <?php
                    foreach ($countryFlags as $code => $flag) {
                        echo "<option value=\"$code\">$flag $code</option>";
                    }
                    ?>
                </select>
            <br>
                <input type="text" id="pnumber" class="form__input" name="pnumber" autofocus placeholder="Phone Number">
                <div class="form__input-error-message"></div>
            </div>

            <input type="text" id="email" class="form__input" name="email" autofocus placeholder="Email Address">
            <div class="form__input-error-message"></div>

            <input type="password" id="pass" class="form__input" name="pass" autofocus placeholder="Password">
            <div class="form__input-error-message"></div>

            <input type="password" id="confirm_pass" class="form__input" name="confirm_pass" autofocus placeholder="Confirm Password">
            <div class="form__input-error-message"></div>

            <button class="form__button" type="submit">Continue</button>
            <br>
            <p class="form__text">
                <a class="form__link" href="login.php" id="linkLogin">Already have an account? Sign in</a>
            </p>
        </form>

        </div>
        <br>
        <br>


    <?php include 'footer.php'; ?>
    <script src="js/user.js"></script>
<script>
        document.getElementById('country_code').addEventListener('change', function() {
            var selectedCountryCode = this.value;
            var selectedFlag = "<?php echo $countryFlags['+1']; ?>";

            if (selectedCountryCode in <?php echo json_encode($countryFlags); ?>) {
                selectedFlag = <?php echo json_encode($countryFlags); ?>[selectedCountryCode];
            }

            document.getElementById('selected_flag').textContent = selectedFlag;
        });
    </script>

</body>
</html>
