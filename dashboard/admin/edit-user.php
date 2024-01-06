<?php include_once dirname(__FILE__, 3) . "/db.php"; ?>

<?php
// var_dump($_SESSION);
// exit;
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true || !isset($_SESSION['utype']) || $_SESSION['utype'] != 'admin') {
    header("Location: " . _get_base_url() . "login.php");
}

$has_err = false;

$prev_id = "";
$prev_name = "";
$prev_email = "";
$prev_phone = "";

$id = "";
$name = "";
$email = "";
$phone = "";

$err_name = "";
$err_email = "";
$err_phone = "";


if (isset($_POST['edit_submit'])) {


    if (empty($_POST['name'])) {
        $err_name = "Name is required";
        $has_err = true;
    } else if (strlen(trim($_POST['name'])) < 2) {
        $err_name = "Name must be larger than 2 character";
        $has_err = true;
    } else {
        $err_name = "";
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
        $email = htmlspecialchars(trim($_POST['email']));
    }

    if (empty($_POST['phone'])) {
        $err_phone = "Phone is required";
        $has_err = true;
    } else if (!is_numeric($_POST['phone'])) {
        $err_phone = "Phone is not valid";
        $has_err = true;
    } else if (strlen(trim($_POST['phone'])) != 11) {
        $err_phone = "Phone must be 11 digit";
        $has_err = true;
    } else {
        $err_phone = "";
        $phone = htmlspecialchars(trim($_POST['phone']));
    }

    $id = trim($_POST['id']);

    // var_dump($id, $name, $email, $phone, $_POST['email'], htmlspecialchars(trim($_POST['email'])));
    // exit;

    if (!$has_err) {
        $is_edited = editUser($id, $name, $email, $phone);
        // echo "<pre>";
        // var_dump($is_edited);
        // echo "</pre>";

        // exit;

        if ($is_edited) {
            // echo '<script>alert("Edited Complete");</script>';
            header("location: ./view-user.php");
        }
    }
} else if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user = getUser($_GET['id']);
    if ($user) {
        $prev_id = $user['U_ID'];
        $prev_name = $user['U_NAME'];
        $prev_email = $user['U_EMAIL'];
        $prev_phone = $user['U_PHONE'];
    }
    // echo "<pre>";
    // var_dump($user);
    // echo "</pre>";
    // exit;
} else {
    // echo '<script>alert("Invalid ID");</script>';
    header("Location: view-user.php");
}

?>

<?php include_once dirname(__FILE__, 3) . "/header.php"; ?>
<link rel="stylesheet" href="<?php echo _get_base_url(); ?>style.css">

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <fieldset>
        <legend>Edit</legend>
        <input type="hidden" name="id" value="<?php echo $prev_id; ?>">
        <lable for="name">Name: </lable>
        <input type="text" name="name" id="name" value="<?php echo $prev_name; ?>">
        <span class="err-msg"><?php echo $err_name; ?></span><br><br>

        <lable for="email">Email: </lable>
        <input type="text" name="email" id="email" value="<?php echo $prev_email; ?>">
        <span class="err-msg"><?php echo $err_email; ?></span><br><br>

        <lable for="phone">Phone: </lable>
        <input type="text" name="phone" id="phone" value="<?php echo $prev_phone; ?>">
        <span class="err-msg"><?php echo $err_phone; ?></span><br><br>

        <input type="submit" name="edit_submit" value="Edit"><br>
    </fieldset>
</form>

<?php include_once dirname(__FILE__, 3) . "/footer.php"; ?>