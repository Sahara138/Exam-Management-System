<?php require_once __DIR__ . "/db.php"; ?>
<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header("Location: dashboard/" . $_SESSION['utype'] . "/index.php");
}
?>
<?php
$has_err = false;

$email = "";
$password = "";
$utype = "";

$err_email = "";
$err_password = "";
$err_utype = "";

if (isset($_POST['login_submit'])) {

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
    } else {
        $err_password = "";
        $has_err = false;
        $password = $_POST['password'];
    }

    if (!$has_err) {
        $authUser = getLogin($email, $password, $utype);
        // echo '<pre>';
        // var_dump($authUser);
        // echo '</pre>';
        // exit();

        if ($authUser && isset($authUser['A_STATUS']) && $authUser['A_STATUS'] == 'true') {
            $_SESSION['id'] = $authUser['U_ID'];
            $_SESSION['name'] = $authUser['U_NAME'];
            $_SESSION['email'] = $authUser['U_EMAIL'];
            $_SESSION['phone'] = $authUser['U_PHONE'];
            $_SESSION['password'] = $authUser['U_PASSWORD'];
            $_SESSION['utype'] = $authUser['U_TYPE'];
            $_SESSION['status'] = $authUser['A_STATUS'];

            $_SESSION['loggedin'] = true;
            header('Location: dashboard/' . $_SESSION['utype'] . '/index.php');
        } else if (isset($authUser['A_STATUS']) && $authUser['A_STATUS'] == 'false') {
            echo '<script>alert("You need to wait for verification!!");</script>';
        } else {
            echo '<script>alert("Invalid Login Credentials");</script>';
        }
    }
}
?>
<?php include_once __DIR__ . "/header.php"; ?>
<link rel="stylesheet" href="<?php echo _get_base_url(); ?>style.css">

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <fieldset>
        <legend>Login</legend>
        <lable for="email">Email: </lable>
        <input type="text" name="email" id="email">
        <span class="err-msg"><?php echo $err_email; ?></span><br><br>

        <lable for="password">Passoword: </lable>
        <input type="password" name="password" id="password">
        <span class="err-msg"><?php echo $err_password; ?></span><br><br>

        <label>User Type:</label>
        <input type="radio" name="utype" value="student" id="student">
        <label for="student">Student</label>
        <input type="radio" name="utype" value="teacher" id="teacher">
        <label for="teacher">Teacher</label>
        <input type="radio" name="utype" value="admin" id="admin">
        <label for="admin">Admin</label>
        <span class="err-msg"><?php echo $err_utype; ?></span><br><br>

        <input type="submit" name="login_submit" value="Login"><br>
    </fieldset>
</form>

<?php include_once __DIR__ . "/footer.php"; ?>