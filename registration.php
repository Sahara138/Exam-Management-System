<?php require_once __DIR__ . "/db.php"; ?>
<?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        header("Location: dashboard.php");
    }
?>
<?php
$has_err = false;

$name = "";
$email = "";
$phone = "";
$password = "";
$cpassword = "";
$utype = "";

$err_name = "";
$err_email = "";
$err_phone = "";
$err_password = "";
$err_cpassword = "";
$err_utype = "";

if (isset($_POST['registration_submit'])) {

    if (empty($_POST['name'])) {
        $err_name = "Name is required";
        $has_err = true;
    } else if (strlen(trim($_POST['name'])) < 2) {
        $err_name = "Name must be larger than 2 character";
        $has_err = true;
    } else {
        $err_name = "";
        $has_err = false;
        $name = htmlspecialchars(trim($_POST['name']));
    }

    if (empty($_POST['email'])) {
        $err_email = "Email is required";
        $has_err = true;
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $err_email = "Email is not valid";
        $has_err = true;
    } else {
        $err_email = "";
        $has_err = false;
        $email = htmlspecialchars(trim($_POST['email']));
    }

    if (empty($_POST['phone'])) {
        $err_phone = "Phone is required";
        $has_err = true;
    } else if(!is_numeric($_POST['phone'])) {
        $err_phone = "Phone is not valid";
        $has_err = true;
    } else if (strlen(trim($_POST['phone'])) != 11) {
        $err_phone = "Phone must be 11 digit";
        $has_err = true;
    } else {
        $err_phone = "";
        $has_err = false;
        $phone = htmlspecialchars(trim($_POST['phone']));
    }

    if (empty($_POST['utype'])) {
        $err_utype = "User Type is required";
        $has_err = true;
    } else {
        $err_utype = "";
        $has_err = false;
        $utype = htmlspecialchars(trim($_POST['utype']));
    }

    if (empty($_POST['password'])) {
        $err_password = "Passwrod is required";
        $has_err = true;
    } else if (strlen(trim($_POST['password'])) < 8) {
        $err_password = "Password must be 8 or greater character";
        $has_err = true;
    } else {
        $err_password = "";
        $has_err = false;
        $password = $_POST['password'];
    }

    if (empty($_POST['cpassword'])) {
        $err_cpassword = "Confirm Password is required";
        $has_err = true;
    } else if ($_POST['password'] !== $_POST['cpassword']) {
        $err_cpassword = "Password not matched";
        $has_err = true;
    } else {
        $err_cpassword = "";
        $has_err = false;
        $cpassword = $_POST['cpassword'];
    }
    if (!$has_err && isset($_POST['registration_submit'])) {
        require_once __DIR__ . "/db.php";
    
        // Start here using $connection variable
        //echo '<script>alert("Registration Complete");</script>';

        
        $setuser = setUser($name, $email, $phone, $password, $utype);
        
        // echo "<pre>";
        // var_dump($setuser);
        // echo "</pre>";

        // exit;
        
        if ($setuser) {
            echo '<script>alert("Registration Complete");</script>';
            // header("location: login.php");
        }
        else
        {
            echo '<script>alert("Registration In Complete");</script>';
        }
    }
}
?>
<?php include_once __DIR__ . "/header.php"; ?>
<link rel="stylesheet" href="<?php echo _get_base_url(); ?>style.css">

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <fieldset>
        <legend>Registration</legend>
        <lable for="name">Name: </lable>
        <input type="text" name="name" id="name">
        <span class="err-msg"><?php echo $err_name; ?></span><br><br>

        <lable for="email">Email: </lable>
        <input type="text" name="email" id="email">
        <span class="err-msg"><?php echo $err_email; ?></span><br><br>

        <lable for="phone">Phone: </lable>
        <input type="text" name="phone" id="phone">
        <span class="err-msg"><?php echo $err_phone; ?></span><br><br>

        <label>User Type:</label>
        <input type="radio" name="utype" value="student" id="student">
        <label for="student">Student</label>
        <input type="radio" name="utype" value="teacher" id="teacher">
        <label for="teacher">Teacher</label>
        <span class="err-msg"><?php echo $err_utype; ?></span><br><br>

        <lable for="password">Passoword: </lable>
        <input type="password" name="password" id="password">
        <span class="err-msg"><?php echo $err_password; ?></span><br><br>

        <lable for="cpassword">Confirm Password: </lable>
        <input type="password" name="cpassword" id="cpassword">
        <span class="err-msg"><?php echo $err_cpassword; ?></span><br><br>

        <input type="submit" name="registration_submit" value="Registration"><br>
    </fieldset>
</form>

<?php include_once __DIR__ . "/footer.php"; ?>
