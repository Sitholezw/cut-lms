<?php
session_start();
include('includes/config.php');
if (isset($_POST['signin'])) {
    $uname = $_POST['username'];
    $password = md5($_POST['password']);
    $sql = "SELECT UserName,Password FROM admin WHERE UserName=:uname and Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':uname', $uname, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        $_SESSION['alogin'] = $_POST['username'];
        echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
    } else {

        echo "<script>alert('Invalid Details');</script>";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>CUT | Adminstrator</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />

     <!-- favicoins -->
    <link href="../assets/images/favicon.png" rel="icon">
    <link href="../assets/images/favicon.png" rel="apple-touch-icon">

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />

    <style>
        body.signin-page {
            position: relative;
            overflow: hidden;
            background: #FFFFFF;
        }

        body.signin-page::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #FFFFFF;
            opacity: 0.5;
            z-index: -1;
        }
    </style>
    
</head>
    


<body class="signin-page">

    <div class="mn-content valign-wrapper">

        <main class="mn-inner container">
            <div class="row" style="text-align:center"><img src="../assets/images/favicon.png" alt="Logo"
                    width="150px"></div>
            <div class="valign">
                <div class="row">

                    <div class="col s12 m8 l6 offset-l3 offset-m2">
                        <div class="card white darken-2" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <div class="card-content">
                                <span class="card-title">Admin Sign In</span>
                                <div class="row">
                                    <form class="col s12" name="signin" method="post">
                                        <div class="input-field col s12">
                                            <input id="username" type="text" name="username" class="validate"
                                                autocomplete="off" required>
                                            <label for="email">Username</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input id="password" type="password" class="validate" name="password"
                                                autocomplete="off" required>
                                            <label for="password">Password</label>
                                        </div>
                                        <div class="col s12 right-align m-t-sm">
                                            <input type="submit" name="signin" value="Sign in"
                                                class="waves-effect waves-light btn indigo m-b-xs" style="width: 100%;">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Javascripts -->
    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/js/alpha.min.js"></script>

    <!-- Footer -->
    <footer class="page-footer" style="position: fixed; bottom: 0; width: 100%; background-color: #3f51b5; color: white;">
        <div class="footer-copyright">
            <div class="container">
                © 2025 CUT LMS. All rights reserved.
                <span class="right">Follow us on 
                    <a href="https://facebook.com" target="_blank" style="color: white;">Facebook</a> | 
                    <a href="https://twitter.com" target="_blank" style="color: white;">Twitter</a> | 
                    <a href="https://instagram.com" target="_blank" style="color: white;">Instagram</a>
                </span>
            </div>
        </div>
    </footer>
                </span>
            </div>
        </div>
    </footer>

</body>

</html>