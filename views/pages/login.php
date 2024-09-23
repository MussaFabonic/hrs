<?php
if (session_status() == 1) {
    session_start();
    session_destroy();
}
// include_once('../../controllers/login.php');

$errorMessage = isset($_SESSION['error']) ? $_SESSION['error'] : null;
unset($_SESSION['error']);


?>
<!DOCTYPE html>
<html>
<head>
	<title>HRS</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../assets/vendor/pnotify/pnotify.custom.css" />
	<script src="../../assets/vendor/pnotify/pnotify.custom.js"></script>
</head>
<body>
	<img class="wave" src="../../img/wave.png">
	<div class="container">
		<div class="img">
			<img src="../../img/bg.svg" style="height: 50%;">
		</div>
		<div class="login-content">
        <form method="POST" id="loginForm">
            <img src="../../images/logo.png"></br>
            <span id="error-message" style="color : red;display : none;"></span>
            <h2 class="title">Welcome</h2>
            <div class="input-div one">
                <div class="i">
                    <i class="fas fa-user"></i>
                </div>
                <div class="div">
                    <h5>Username</h5>
                    <input type="text" required class="input" name="username" id="username" placeholder="Username">
                </div>
            </div>
            <div class="input-div pass">
                <div class="i"> 
                    <i class="fas fa-lock"></i>
                </div>
                <div class="div">
                    <h5>Password</h5>
                    <input type="password" required class="input" name="password" id="password" placeholder="Password">
                </div>
            </div>
            <input type="submit" class="btn" value="Login">
        </form>
        </div>
    </div>
    <script type="text/javascript" src="../../js/main.js"></script>
</body>
</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(event) {
            event.preventDefault(); 
            $.ajax({
                type: 'POST',
                url: '../../controllers/login.php',
                data: $(this).serialize(), 
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.redirect;
                        $('#error-message').text(response.error).hide();
                    } else {
                        $('#error-message').text(response.error).show();
                        // alert(response.error);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>
