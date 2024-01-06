<?php include_once dirname(__FILE__, 3) . "/db.php"; ?>

<?php
    // var_dump($_SESSION);
    // exit;
    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true || !isset($_SESSION['utype']) || $_SESSION['utype'] != 'student') {
        header("Location: " . _get_base_url() . "login.php");
    }
?>

<?php include_once dirname(__FILE__, 3) . "/header.php"; ?>

<h1 style="text-align: center;">Welcome to Online Exam Management System</h1>
<h1>Student Dashboard</h1>

<?php include_once dirname(__FILE__, 3) . "/footer.php"; ?>