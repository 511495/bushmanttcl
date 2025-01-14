<?php
include_once('db_config.php');
include("db.php");

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT order_id, order_date, total_amount FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/styles.css">
    <link rel="stylesheet" href="/styles/orders.css">

    <title>Orders - BMT</title>
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


<br>
    <div class="orders-container">
        <h2>Order History</h2> <br><hr><br><br>
        <?php
        while ($row = $result->fetch_assoc()) {
            $orderId = $row['order_id'];
            $orderDate = date("F j, Y", strtotime($row['order_date']));
            $totalAmount = $row['total_amount'];

            echo "<div class='order-item'>";
            echo "<p>Order ID: $orderId</p>";
            echo "<p>Order Date: $orderDate</p>";
            echo "<p>Total Amount: $totalAmount</p>";
            echo "<button class='return-button' onclick='requestReturn($orderId)'>Return</button>";
            echo "<button class='contact-button' onclick='contactCustomer($user_id)'>Contact Customer</button>";
            echo "</div>";
        }
        ?>
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
