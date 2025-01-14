<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/styles.css">
    <link rel="stylesheet" href="/styles/dashbord.css">

    <title>Dashbord - BMT</title>
</head>
<body>
    <header>
    <nav>
    <div class="dropdown left" id="sidebar">
        <button>☰</button>
        <div class="dropdown-content">
            <a href="profile.php" class="w3-bar-item w3-button">Profile</a>
            <a href="account.php" class="w3-bar-item w3-button">Account</a>
            <a href="orders.php" class="w3-bar-item w3-button">Orders</a>
            <a href="logout.php" class="w3-bar-item w3-button">Logout</a>
            <br>
            <button class="close-btn" id="closeSidebar">✕ Close</button>
        </div>
    </div>

    <a href="index.php" class="logo-link">
        <img src="/images/BMT.webp" alt="BMT Logo" class="logo">
    </a>

    <a href="cart.php" class="button">🛒</a>
    </nav>
    </header>
    
    <br>
    <div class="content-box">
        <input type="hidden" id="isLoggedIn" value="<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>">

        <br><br>

        <p>This is your personalized dashboard. Click&nbsp;&nbsp;&nbsp;<button>☰</button>&nbsp;&nbsp;&nbsp;in the navbar to navigate.</p><br/>
    </div>
    



        <?php include 'footer.php'; ?>

    <br>
    <br>


    <script>
        const hamburgerBtn = document.getElementById("hamburgerBtn");
        const dropdownMenu = document.getElementById("dropdownMenu");
        const closeBtn = document.getElementById("closeSidebar");

        hamburgerBtn.addEventListener("click", function() {
            dropdownMenu.style.display = (dropdownMenu.style.display === "block") ? "none" : "block";
        });

        closeBtn.addEventListener("click", function() {
            dropdownMenu.style.display = "none";
        });
    </script>
</body>
</html>
