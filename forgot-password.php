<?php
session_start();
error_reporting(0);
include('includes/config.php');
// Code for change password 
if(isset($_POST['change']))
    {
$newpassword=md5($_POST['newpassword']);
$empid=$_SESSION['empid'];

$con="update tblemployees set Password=:newpassword where id=:empid";
$chngpwd1 = $dbh->prepare($con);
$chngpwd1-> bindParam(':empid', $empid, PDO::PARAM_STR);
$chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
$chngpwd1->execute();
$msg="Your Password succesfully changed";
}

?><!DOCTYPE html>
<html lang="en">
    <head>
        
        <!-- Title -->
        <title>CUT | Password Recovery</title>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
        <meta charset="UTF-8">
        <meta name="description" content="Responsive Admin Dashboard Template" />
        <meta name="keywords" content="admin,dashboard" />
        <meta name="author" content="Steelcoders" />
        
        <!-- Styles -->
        <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css"/>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">        

        	<!-- favicoins -->
        <link href="assets\images\favicon.png" rel="icon">
        <link href="assets\images\favicon.png" rel="apple-touch-icon">

        <!-- Theme Styles -->
        <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/custom.css" rel="stylesheet" type="text/css"/>
  <style>
        .errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
        </style>
        
    </head>
    <body style="display: flex; flex-direction: column; min-height: 100vh; background-image: url('assets/images/background.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat; filter: blur(0.1px);">
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

    <div class="mn-content fixed-sidebar" style="flex: 1;">
        <header class="mn-header navbar-fixed">
            <nav class="#82b1ff blue accent-1">
                <div class="nav-wrapper row">
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
                        <div style="text-align:center; margin-bottom: 20px;">
                            <img src="assets/images/favicon.png" alt="Logo" width="150px" style="border-radius: 50%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                        </div>
                        <div class="card white darken-1" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 12px; margin: 0 auto;">
                            <div class="card-content" style="padding: 30px; background-color: rgba(255, 255, 255, 0.9); border-radius: 12px;">
                                <span class="card-title" style="font-size: 20px; font-weight: bold; color: #3f51b5; text-align: center;">Reset Your Password</span>
                                <?php if ($msg) { ?>
                                    <div class="succWrap"><strong>Success:</strong> <?php echo htmlentities($msg); ?></div>
                                <?php } ?>
                                <div class="row">
                                    <form class="col s12" name="signin" method="post">
                                        <div class="input-field col s12">
                                            <input id="empid" type="text" name="empid" class="validate" autocomplete="off" required>
                                            <label for="empid">Employee ID</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input id="emailid" type="email" class="validate" name="emailid" autocomplete="off" required>
                                            <label for="emailid">Email Address</label>
                                        </div>
                                        <div class="col s12 m-t-sm">
                                            <input type="submit" name="submit" value="Reset" class="waves-effect waves-light btn blue m-b-xs" style="width: 100%; font-size: 16px; font-weight: bold;">
                                        </div>
                                    </form>
                                </div>
                                <div class="row" style="margin-top: 20px; text-align: center;">
                                    <a href="index.php" style="text-decoration: underline; color: #3f51b5; font-weight: bold;">Back to Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="page-footer" style="background-color: #3f51b5; color: white; margin-top: auto; position:relative;">
        <div class="footer-copyright">
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