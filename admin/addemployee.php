<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $empcode = $_POST['empcode'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $mobileno = $_POST['mobileno'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $department = $_POST['department'];
    $password = md5($_POST['password']); // Use password_hash in production

    // Calculate age
    $dobDate = new DateTime($dob);
    $today = new DateTime();
    $age = $today->diff($dobDate)->y;

    if ($age < 18) {
        $error = "Employee must be at least 18 years old.";
    } else {
        $sql = "INSERT INTO tblemployees (EmpId, FirstName, LastName, Dob, Gender, EmailId, Phonenumber, Address, City, Country, Department, Password)
                VALUES (:empcode, :firstName, :lastName, :dob, :gender, :email, :mobileno, :address, :city, :country, :department, :password)";
        $stmt = $dbh->prepare($sql);
        $result = $stmt->execute([
            ':empcode' => $empcode,
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':dob' => $dob,
            ':gender' => $gender,
            ':email' => $email,
            ':mobileno' => $mobileno,
            ':address' => $address,
            ':city' => $city,
            ':country' => $country,
            ':department' => $department,
            ':password' => $password
        ]);
        if ($result) {
            // Redirect to manageemployee.php after successful insert
            header("Location: manageemployee.php");
            exit;
        } else {
            $errorInfo = $stmt->errorInfo();
            $error = "Failed to add employee. Error: " . $errorInfo[2];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CUT | Add Employee</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Materialize & Flatpickr -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">  
    <link rel="stylesheet" href="../assets/css/addemployee.css">

     <!-- favicoins -->
    <link href="../assets/images/favicon.png" rel="icon">
    <link href="../assets/images/favicon.png" rel="apple-touch-icon">

  
</head>
<body class="background">
    

<div class="card">
    <h4 class="center-align">Add Employee</h4>
    <?php if (!empty($error)): ?>
        <div class="errorWrap"><strong>ERROR:</strong> <?= $error ?></div>
    <?php elseif (!empty($msg)): ?>
        <div class="succWrap"><strong>SUCCESS:</strong> <?= $msg ?></div>
    <?php endif; ?>

    <form method="post" onsubmit="return validateForm();">
        <div class="row">
            <div class="input-field col s12">
                <input name="empcode" id="empcode" type="text" placeholder="Emp. Code (auto-gen)" readonly required>
            </div>
        </div>
        <div class="row">
            <div class="input-field col m6 s12">
                <input name="firstName" type="text" placeholder="First Name" required>
            </div>
            <div class="input-field col m6 s12">
                <input name="lastName" type="text" placeholder="Last Name" required>
            </div>
        </div>
        <div class="row">
            <div class="input-field col m6 s12">
                <input id="start" name="dob" type="text" placeholder="Date of Birth" required>
            </div>
            <div class="input-field col m6 s12">
                <select name="gender" required>
                    <option disabled selected>Gender</option>
                    <option>Male</option>
                    <option>Female</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="input-field col m6 s12">
                <input name="email" type="email" placeholder="Email" required>
            </div>
            <div class="input-field col m6 s12">
                <input name="mobileno" type="text" maxlength="10" placeholder="Phone" required>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <input name="address" type="text" placeholder="Address" required>
            </div>
        </div>
        <div class="row">
            <div class="input-field col m6 s12">
                <select name="city" required>
                    <option disabled selected>City/Town</option>
                    <option>Harare</option>
                    <option>Bulawayo</option>
                </select>
            </div>
            <div class="input-field col m6 s12">
                <select name="country" required>
                    <option value="Zimbabwe" selected>Zimbabwe</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12">
                <select name="department" id="department" onchange="generateEmpCode();" required>
                    <option disabled selected>Department</option>
                    <?php
                    $deps = $dbh->query("SELECT DepartmentName FROM tbldepartments");
                    foreach ($deps as $d) {
                        echo "<option>" . htmlentities($d['DepartmentName']) . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="input-field col m6 s12">
                <input id="pwd" name="password" type="password" placeholder="Password" required>
            </div>
            <div class="input-field col m6 s12">
                <input name="confirmpassword" id="confirmpassword" type="password" placeholder="Confirm Password" required>
            </div>
        </div>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <div class="row">
            <div class="input-field col s12 center-align">
                <button type="submit" name="add" class="btn indigo">Add Employee</button>
            </div>
        </div>
    </form>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        flatpickr("#start", { dateFormat: "Y-m-d", maxDate: "today" });
        M.FormSelect.init(document.querySelectorAll('select'));
    });

    function generateEmpCode() {
        const dept = document.querySelector('[name=department]').value.slice(0, 3).toUpperCase();
        const date = new Date().toISOString().slice(0, 10).replace(/-/g, '');
        const rand = Math.floor(Math.random() * 9000 + 1000);
        document.getElementById('empcode').value = `${dept}-${date}-${rand}`;
    }

    function validateForm() {
        const pwd = document.getElementById('pwd').value;
        const cpw = document.getElementById('confirmpassword').value;
        if (pwd !== cpw) {
            alert('Passwords do not match.');
            return false;
        }
        return true;
    }
</script>
</body>
</html>
