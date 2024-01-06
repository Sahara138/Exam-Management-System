<?php include_once dirname(__FILE__, 3) . "/db.php"; ?>

<?php
// var_dump($_SESSION);
// exit;
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true || !isset($_SESSION['utype']) || $_SESSION['utype'] != 'admin') {
    header("Location: " . _get_base_url() . "login.php");
}

$prev_id = "";

$id = "";

if (isset($_POST['verify_submit'])) {

    $id = trim($_POST['id']);

    // var_dump($id, $_POST['id']);
    // exit();

    $is_verified = verifieUser($id);
    // echo "<pre>";
    // var_dump($is_verified);
    // echo "</pre>";

    // exit;

    if ($is_verified) {
        // echo '<script>alert("Edited Complete");</script>';
        header("location: view-user.php");
    }
} else if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $prev_id = trim($_GET['id']);
} else {
    header("Location: view-user.php");
}
?>

<?php include_once dirname(__FILE__, 3) . "/header.php"; ?>
<link rel="stylesheet" href="<?php echo _get_base_url(); ?>style.css">

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <fieldset>
        <legend>Are you sure you verified the user ?</legend>
        <input type="hidden" name="id" value="<?php echo $prev_id; ?>">

        <input type="submit" name="verify_submit" value="Verify"><br>
    </fieldset>
</form>

<?php include_once dirname(__FILE__, 3) . "/footer.php"; ?>