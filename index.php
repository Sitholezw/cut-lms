<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (isset($_POST['signin'])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

    // Sanitize input
    $uname = filter_var($_POST['username'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($uname, FILTER_VALIDATE_EMAIL)) {
        $loginError = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $loginError = "Password must be at least 6 characters long.";
    } else {
        // Proceed with database query
        $sql = "SELECT EmailId,Password,Status,id FROM tblemployees WHERE EmailId=:uname";
        $query = $dbh->prepare($sql);
        $query->bindParam(':uname', $uname, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
            foreach ($results as $result) {
                $status = $result->Status;
                $_SESSION['eid'] = $result->id;
                if (password_verify($password, $result->Password)) {
                    if ($status == 0) {
                        $loginError = "Your account is inactive. Please contact admin.";
                    } else {
                        $_SESSION['emplogin'] = $_POST['username'];
                        echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
                    }
                } else {
                    $loginError = "Invalid email or password.";
                }
            }
        } else {
            $loginError = "Invalid email or password.";
        }
    }
}

?><!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>CUT | Home Page</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />
    <meta name="robots" content="noindex, nofollow">
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
    <link href="assets/css/materialdesign.css" rel="stylesheet">
    <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <!-- favicoins -->
    <link href="assets\images\favicon.png" rel="icon">
    <link href="assets\images\favicon.png" rel="apple-touch-icon">

    <!-- Theme Styles -->
    <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.css" rel="stylesheet" type="text/css" />

    <style>
        .card {
            background: linear-gradient(to bottom, #ffffff, #f9f9f9);
        }
    </style>

</head>

<body style="background-image: url('assets/images/background.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat; height: 100vh; filter: blur(0.1px);" loading="lazy">
    <div class="loader-bg"></div>
    <div class="loader">
        <div class="preloader-wrapper big active">
            <div class="spinner-layer spinner-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="mn-content fixed-sidebar">
        <header class="mn-header navbar-fixed">
            <nav class="#82b1ff blue accent-1">
                <div class="nav-wrapper row">
                    <section class="material-design-hamburger navigation-toggle">
                        <a href="#" class="button-collapse show-on-large material-design-hamburger__icon">
                            <span class="material-design-hamburger__layer"></span>
                        </a>
                    </section>
                    <div class="header-title col s3">
                        <span class="chapter-title">CUT | Leave Management System</span>
                    </div>
                </div>
            </nav>
        </header>

        <main class="mn-inner" style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 120px);">
            <div class="row" style="width: 100%; margin: 0;">
                <div class="col s12 m6 l4 offset-l4">
                    <div class="card white darken-1" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px; margin: 0;">
                        <div class="card-content" style="padding: 30px;">
                            <div style="text-align: center; margin-bottom: 20px;">
                                <img src="assets/images/favicon.png" alt="Logo" width="100px" style="border-radius: 50%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                            </div>
                            <span class="card-title" style="font-size: 24px; font-weight: bold; color: #3f51b5; text-align: center; display: block; margin-bottom: 20px;">
                                Staff Login
                            </span>
                            <?php if ($msg) { ?>
                                <div class="card-panel red lighten-4 red-text text-darken-4" style="border-radius: 8px; padding: 10px;">
                                    <strong>Error:</strong> <?php echo htmlentities($msg); ?>
                                </div>
                            <?php } ?>
                            <form class="col s12" name="signin" method="post" onsubmit="return validateForm();" style="margin-top: 20px;">
                                <div class="input-field col s12">
                                    <input id="username" type="email" name="username" class="validate" autocomplete="off" required aria-label="Email Address">
                                    <label for="username" style="font-size: 16px;">Email Address</label>
                                </div>
                                <div class="input-field col s12">
                                    <input id="password" type="password" class="validate" name="password" autocomplete="off" required minlength="6" style="font-size: 16px;">
                                    <label for="password" style="font-size: 16px;">Password</label>
                                </div>
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <div class="col s12" style="margin-top: 20px;">
                                    <button type="submit" name="signin" class="waves-effect waves-light btn indigo" style="width: 100%; font-size: 18px; font-weight: bold; background-color: #3f51b5;">
                                        Sign In
                                    </button>
                                </div>
                            </form>
                            <div class="row" style="margin-top: 20px; text-align: center;">
                                <a href="forgot-password.php" style="text-decoration: underline; color: #3f51b5; font-weight: bold; font-size: 16px;">
                                    Forgot Password?
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!--
    <footer class="page-footer" style="background-color: #3f51b5; color: white; padding: 20px 0; margin-top: 0; z-index: 1000;">
        <div class="container">
            <div class="row">
                <div class="col s12 m6">
                    <h6>Contact Us</h6>
                    <p>
                        Chinhoyi University of Technology<br>
                        Private Bag 7724, Chinhoyi, Zimbabwe<br>
                        Tel: +263 784840335<br>
                        Email: <a href="mailto:techub@outlook.com" style="color: white;">techub@outlook.com</a>
                    </p>
                </div>
                <div class="col s12 m6 right-align">
                    <h6>Follow Us</h6>
                    <a href="https://facebook.com" target="_blank" style="color: white; margin-right: 15px;">Facebook</a>
                    <a href="https://twitter.com" target="_blank" style="color: white; margin-right: 15px;">Twitter</a>
                    <a href="https://instagram.com" target="_blank" style="color: white;">Instagram</a>
                </div>
            </div>
            <div class="row">
                <div class="col s12 center-align">
                    <p>© <?php echo date("Y"); ?> CUT LMS. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
    -->
    <!-- Javascripts -->
    <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="assets/js/alpha.min.js"></script>
    <script>
        document.querySelector('form[name="signin"]').addEventListener('submit', function () {
            const button = document.querySelector('button[name="signin"]');
            button.disabled = true;
            button.innerHTML = 'Signing In...';
        });

        function validateForm() {
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            if (username == "" || password == "") {
                alert("Username and password must be filled out");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>