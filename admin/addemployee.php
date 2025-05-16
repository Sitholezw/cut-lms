<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{
if(isset($_POST['add']))
{
$empcode = $_POST['empcode'];

// Check if the employee code already exists
$sql = "SELECT EmpId FROM tblemployees WHERE EmpId = :empcode";
$query = $dbh->prepare($sql);
$query->bindParam(':empcode', $empcode, PDO::PARAM_STR);
$query->execute();

if ($query->rowCount() > 0) {
    $error = "Employee Code already exists. Please try again.";
} else {
    // Proceed with inserting the employee record
    $dob = $_POST['dob'];
    $fname = htmlspecialchars(strip_tags(trim($_POST['firstName'])), ENT_QUOTES, 'UTF-8');
    $lname = htmlspecialchars(strip_tags(trim($_POST['lastName'])), ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $gender = $_POST['gender']; 
    $department = $_POST['department']; 
    $address = $_POST['address']; 
    $city = $_POST['city']; 
    $country = $_POST['country']; 
    $mobileno = $_POST['mobileno']; 
    $status = 1;

    $sql = "INSERT INTO tblemployees(EmpId,FirstName,LastName,EmailId,Password,Gender,Dob,Department,Address,City,Country,Phonenumber,Status) 
            VALUES(:empcode,:fname,:lname,:email,:password,:gender,:dob,:department,:address,:city,:country,:mobileno,:status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':empcode', $empcode, PDO::PARAM_STR);
    $query->bindParam(':fname', $fname, PDO::PARAM_STR);
    $query->bindParam(':lname', $lname, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->bindParam(':gender', $gender, PDO::PARAM_STR);
    $query->bindParam(':dob', $dob, PDO::PARAM_STR);
    $query->bindParam(':department', $department, PDO::PARAM_STR);
    $query->bindParam(':address', $address, PDO::PARAM_STR);
    $query->bindParam(':city', $city, PDO::PARAM_STR);
    $query->bindParam(':country', $country, PDO::PARAM_STR);
    $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);

    if (!$query->execute()) {
        $error = "Failed to add employee. Please try again.";
    } else {
        $msg = "Employee record added successfully.";
    }
}
}

    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title -->
    <title>Admin | Add Employee</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />

    <!-- Favicons -->
    <link href="../assets/images/favicon.png" rel="icon">
    <link href="../assets/images/favicon.png" rel="apple-touch-icon">

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css"/>
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet"> 
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .card {
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-section {
            margin-bottom: 30px;
        }
        .form-section h5 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #3f51b5;
        }
        .input-field {
            margin-bottom: 20px;
        }
        .btn {
            width: 100%;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12 m10 l8 offset-m1 offset-l2">
                <div class="card">
                    <h4 class="center-align">Add Employee</h4>
                    <?php if ($error) { ?>
                        <div class="errorWrap"><strong>ERROR:</strong> <?php echo htmlentities($error); ?></div>
                    <?php } elseif ($msg) { ?>
                        <div class="succWrap"><strong>SUCCESS:</strong> <?php echo htmlentities($msg); ?></div>
                    <?php } ?>

                    <form id="example-form" method="post" name="addemp">
                        <!-- Personal Details -->
                        <div class="form-section">
                            <h5>Personal Details</h5>
                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <input name="empcode" id="empcode" type="text" placeholder="Employee Code (Generated Automatically)" autocomplete="off" readonly required>
                                </div>
                                <div class="input-field col m6 s12">
                                    <input id="firstName" name="firstName" type="text" placeholder="First Name" required>
                                </div>
                                <div class="input-field col m6 s12">
                                    <input id="lastName" name="lastName" type="text" placeholder="Last Name" autocomplete="off" required>
                                </div>
                                <div class="input-field col m6 s12">
                                    <input id="birthdate" name="dob" type="text" placeholder="Birthdate" autocomplete="off" required>
                                </div>
                                <div class="input-field col m6 s12">
                                    <select name="gender" autocomplete="off">
                                        <option value="" disabled selected>Gender...</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Details -->
                        <div class="form-section">
                            <h5>Contact Details</h5>
                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <input name="email" type="email" id="email" placeholder="Email" onBlur="checkAvailabilityEmailid()" autocomplete="off" required>
                                    <span id="emailid-availability" style="font-size:12px;"></span>
                                </div>
                                <div class="input-field col m6 s12">
                                    <input id="phone" name="mobileno" type="tel" maxlength="10" placeholder="Mobile Number" autocomplete="off" required>
                                </div>
                                <div class="input-field col m6 s12">
                                    <input id="address" name="address" type="text" placeholder="Address" autocomplete="off" required>
                                </div>
                                <div class="input-field col m6 s12">
                                    <select id="city" name="city" class="browser-default" required>
                                        <option value="" disabled selected>City/Town</option>
                                        <option value="Harare">Harare</option>
                                        <option value="Bulawayo">Bulawayo</option>
                                        <!-- Add other cities here -->
                                    </select>
                                </div>
                                <div class="input-field col m6 s12">
                                    <select id="country" name="country" class="browser-default" required>
                                        <option value="Zimbabwe" selected>Country: Zimbabwe</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Department Details -->
                        <div class="form-section">
                            <h5>Department Details</h5>
                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <select name="department" id="department" autocomplete="off" onchange="generateEmpCode();">
                                        <option value="" disabled selected>Department...</option>
                                        <?php
                                        $sql = "SELECT DepartmentName FROM tbldepartments";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $result) { ?>
                                                <option value="<?php echo htmlentities($result->DepartmentName); ?>">
                                                    <?php echo htmlentities($result->DepartmentName); ?>
                                                </option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-section">
                            <h5>Set Password</h5>
                            <div class="row">
                                <div class="input-field col m6 s12">
                                    <input id="password" name="password" type="password" placeholder="Password" autocomplete="off" required>
                                </div>
                                <div class="input-field col m6 s12">
                                    <input id="confirm" name="confirmpassword" type="password" placeholder="Confirm Password" autocomplete="off" required>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="input-field col s12">
                            <button type="submit" name="add" onclick="return valid();" id="add" class="waves-effect waves-light btn indigo m-b-xs">ADD EMPLOYEE</button>
                        </div>

                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Javascripts -->
    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#birthdate", {
                dateFormat: "Y-m-d",
                maxDate: "today"
            });
        });
    </script>
</body>
</html>
<?php } ?>