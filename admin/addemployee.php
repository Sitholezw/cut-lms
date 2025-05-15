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

          <!-- favicoins -->
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
    <script type="text/javascript">
function valid()
{
if(document.addemp.password.value!= document.addemp.confirmpassword.value)
{
alert("New Password and Confirm Password Field do not match  !!");
document.addemp.confirmpassword.focus();
return false;
}
return true;
}
</script>

<script>
function checkAvailabilityEmpid() {
$("#loaderIcon").show();
jQuery.ajax({
url: "check_availability.php",
data:'empcode='+$("#empcode").val(),
type: "POST",
success:function(data){
$("#empid-availability").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}
</script>

<script>
function checkAvailabilityEmailid() {
$("#loaderIcon").show();
jQuery.ajax({
url: "check_availability.php",
data:'emailid='+$("#email").val(),
type: "POST",
success:function(data){
$("#emailid-availability").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}
</script>

<script>
function validateForm() {
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;

    // Validate email format
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Please enter a valid email address.');
        return false;
    }

    // Validate phone number
    if (phone.length !== 10 || isNaN(phone)) {
        alert('Please enter a valid 10-digit mobile number.');
        return false;
    }

    return true;
}
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#birthdate", {
            dateFormat: "Y-m-d", // Format: YYYY-MM-DD
            maxDate: "today",   // Prevent selecting future dates
        });
    });
</script>

    </head>
    <body>
  <?php include('includes/header.php');?>
            
       <?php include('includes/sidebar.php');?>
   <main class="mn-inner">
                <div class="row">
                    <div class="col s12">
                        <div class="page-title">Add employee</div>
                    </div>
                    <div class="col s12 m12 l12">
                        <div class="card">
                            <div class="card-content">
                                <form id="example-form" method="post" name="addemp">
                                    <div>
                                        <h3>Employee Info</h3>
                                        <section>
                                            <div class="wizard-content">
                                                <div class="row">
                                                    <div class="col m6">
                                                        <div class="row">
     <?php if ($error) { ?>
    <div class="errorWrap"><strong>ERROR:</strong> <?php echo htmlentities($error); ?></div>
<?php } elseif ($msg) { ?>
    <div class="succWrap"><strong>SUCCESS:</strong> <?php echo htmlentities($msg); ?></div>
<?php }?>


 <div class="input-field col m6 s12">
    <input name="empcode" id="empcode" type="text" placeholder="Employee Code (Generated Automatically)" autocomplete="off" readonly required>
</div>


<div class="input-field col m6 s12">
    <input id="firstName" name="firstName" type="text" placeholder="First Name" required>
</div>

<div class="input-field col m6 s12">
    <input id="lastName" name="lastName" type="text" placeholder="Last Name" autocomplete="off" required>
</div>

<div class="input-field col s12">
    <input name="email" type="email" id="email" placeholder="Email" onBlur="checkAvailabilityEmailid()" autocomplete="off" required>
    <span id="emailid-availability" style="font-size:12px;"></span>
</div>

<div class="input-field col s12">
    <input id="password" name="password" type="password" placeholder="Password" autocomplete="off" required>
</div>

<div class="input-field col s12">
    <input id="confirm" name="confirmpassword" type="password" placeholder="Confirm Password" autocomplete="off" required>
</div>

<div class="input-field col m6 s12">
    <select name="gender" autocomplete="off">
        <option value="" disabled selected>Gender...</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>
</div>

<div class="input-field col m6 s12">
    <input id="birthdate" name="dob" type="text" placeholder="Birthdate" autocomplete="off" required>
</div>

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

<div class="input-field col m6 s12">
    <input id="address" name="address" type="text" placeholder="Address" autocomplete="off" required>
</div>

<div class="input-field col m6 s12">
    <select id="city" name="city" class="browser-default" required>
        <option value="" disabled selected>City/Town</option>
        <option value="Harare">Harare</option>
        <option value="Bulawayo">Bulawayo</option>
        <option value="Chitungwiza">Chitungwiza</option>
        <option value="Mutare">Mutare</option>
        <option value="Gweru">Gweru</option>
        <option value="Kwekwe">Kwekwe</option>
        <option value="Kadoma">Kadoma</option>
        <option value="Masvingo">Masvingo</option>
        <option value="Marondera">Marondera</option>
        <option value="Bindura">Bindura</option>
        <option value="Zvishavane">Zvishavane</option>
        <option value="Victoria Falls">Victoria Falls</option>
        <option value="Hwange">Hwange</option>
        <option value="Rusape">Rusape</option>
        <option value="Chinhoyi">Chinhoyi</option>
        <option value="Norton">Norton</option>
        <option value="Beitbridge">Beitbridge</option>
        <option value="Redcliff">Redcliff</option>
        <option value="Kariba">Kariba</option>
        <option value="Karoi">Karoi</option>
        <option value="Chipinge">Chipinge</option>
        <option value="Shurugwi">Shurugwi</option>
        <option value="Chegutu">Chegutu</option>
        <option value="Gokwe">Gokwe</option>
    </select>
</div>

<div class="input-field col m6 s12">
    <select id="country" name="country" class="browser-default" required>
        <option value="Zimbabwe" selected>Country: Zimbabwe</option>
    </select>
</div>

<div class="input-field col s12">
    <input id="phone" name="mobileno" type="tel" maxlength="10" placeholder="Mobile Number" autocomplete="off" required>
 </div>

                                                        
<div class="input-field col s12">
<button type="submit" name="add" onclick="return valid();" id="add" class="waves-effect waves-light btn indigo m-b-xs">ADD</button>

</div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                     
                                    
                                        </section>
                                    </div>
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div class="left-sidebar-hover"></div>
        
        <!-- Javascripts -->
        <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="../assets/js/alpha.min.js"></script>
        <script src="../assets/js/pages/form_elements.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
            document.getElementById('birthdate').addEventListener('change', function () {
                const birthdate = new Date(this.value);
                const today = new Date();

                // Check if the birthdate is in the future
                if (birthdate > today) {
                    alert('Birthdate cannot be in the future.');
                    this.value = '';
                    return;
                }

                // Check if the applicant is at least 18 years old
                const age = today.getFullYear() - birthdate.getFullYear();
                const monthDiff = today.getMonth() - birthdate.getMonth();
                const dayDiff = today.getDate() - birthdate.getDate();

                if (age < 18 || (age === 18 && (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)))) {
                    alert('Applicants must be at least 18 years old.');
                    this.value = '';
                }
            });
        </script>

<script>
    function generateEmpCode() {
        const department = document.getElementById('department').value;
        const empCodeField = document.getElementById('empcode');

        if (department) {
            // Map department names to their codes
            const departmentCodes = {
                "Information Technology": "ICT",
                "Human Resources": "HR",
                "Finance": "FIN",
                "Marketing": "MKT",
                "Operations": "OPS",
                "Other": "OTH"
            };

            // Get the department code
            const departmentCode = departmentCodes[department] || "GEN";

            // Generate a unique number (e.g., based on timestamp)
            const uniqueNumber = Math.floor(1000 + Math.random() * 9000);

            // Combine the department code and unique number
            const empCode = `${departmentCode}${uniqueNumber}`;

            // Set the generated code in the Employee Code field
            empCodeField.value = empCode;
        } else {
            empCodeField.value = ""; // Clear the field if no department is selected
        }
    }
</script>
    </body>
</html>
<?php } ?>