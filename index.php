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
    $uname = $_POST['username'];
    $password = md5($_POST['password']);
    $sql = "SELECT EmailId,Password,Status,id FROM tblemployees WHERE EmailId=:uname and Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uname', $uname, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $status = $result->Status;
            $_SESSION['eid'] = $result->id;
        }
        if ($status == 0) {
            $msg = "Your account is Inactive. Please contact admin";
        } else {
            $_SESSION['emplogin'] = $_POST['username'];
            echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
        }
    } else {

        echo "<script>alert('Invalid Details');</script>";

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


</head>

<body style="background-image: url('assets/images/background.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat; height: 100vh; filter: blur(0.1px);">
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

        <main class="mn-inner">
            <div class="row">
                <div class="col s12">
                    <div class="col s12 m6 l6 offset-l2 offset-m3">
                        <div style="text-align:center"><img src="assets\images\favicon.png" alt="Logo" width="150px"></div>

                        <div class="card white darken-1">
                            <div class="card-content">
                                <span class="card-title" style="font-size:20px;">Staff Login</span>
                                <?php if ($msg) { ?>
                                    <div class="card-panel red lighten-4 red-text text-darken-4">
                                        <strong>Error:</strong> <?php echo htmlentities($msg); ?>
                                    </div>
                                <?php } ?>
                                <div class="row">
                                    <form class="col s12" name="signin" method="post">
                                        <div class="input-field col s12">
                                            <input id="username" type="text" name="username" class="validate" autocomplete="off" required>
                                            <label for="username">Email Address</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input id="password" type="password" class="validate" name="password" autocomplete="off" required>
                                            <label for="password">Password</label>
                                        </div>
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <div class="col s12 m-t-sm">
                                            <input type="submit" name="signin" value="Sign in" class="waves-effect waves-light btn indigo m-b-xs" style="width: 100%;">
                                        </div>
                                    </form>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <ul class="sidebar-menu">
                                        <li class="no-padding">
                                            <a class="waves-effect waves-light" href="forgot-password.php" style="text-decoration:wavy; color: #3f51b5; font-weight: bold;">
                                                Forgot Password?
                                            </a>
                                        </li>
                                        <!-- Uncomment if Admin Login is needed -->
                                        <!-- <li class="no-padding">
                                            <a class="waves-effect waves-light" href="admin/">
                                                <i class="material-icons">admin_panel_settings</i> Admin Login
                                            </a>
                                        </li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="page-footer" style="position:relative; bottom: 0; width: 100%; z-index: 1000; background-color: #3f51b5; color: white;">
        <div class="footer-copyright" style="background-color: #3f51b5;">
            <div class="container">
                © 2025 CUT-LMS. All rights reserved.
                <span class="right">Follow us on 
                    <a href="https://facebook.com" target="_blank" style="color: white;">Facebook</a> | 
                    <a href="https://twitter.com" target="_blank" style="color: white;">Twitter</a> | 
                    <a href="https://instagram.com" target="_blank" style="color: white;">Instagram</a>
                </span>
            </div>
        </div>
    </footer>

    <!-- Javascripts -->
    <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="assets/js/alpha.min.js"></script>
</body>

</html>