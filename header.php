<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>

<body>

    <header>
        <nav class="navigation">
            <ul>
                <h1 style="text-align: center;">
                    <li><a href="<?php echo _get_base_url(); ?>">Online Exam Management System</a></li>
                </h1>
            </ul>
            <br>
            <ul>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin') : ?>

                    <li><a href="<?php echo _get_base_url(); ?>dashboard/admin/view-user.php">View Users</a></li>
                    <li><a href="<?php echo _get_base_url(); ?>dashboard/admin/view-ques.php">View Questions</a></li>
                    <li><a href="<?php echo _get_base_url(); ?>dashboard/admin/view-result.php">View Results</a></li>
                    <li><a href="<?php echo _get_base_url(); ?>dashboard/admin/view-nonverified-user.php">Verify User</a></li>
                    <li><a href="<?php echo _get_base_url(); ?>dashboard/admin/view-log.php">View log</a></li>
                    <li><a href="<?php echo _get_base_url(); ?>logout.php">Logout</a></li>

                <?php else : ?>

                    <li><a href="<?php echo _get_base_url(); ?>login.php">Login</a></li>
                    <li><a href="<?php echo _get_base_url(); ?>registration.php">Registration</a></li>

                <?php endif; ?>
            </ul>
        </nav>
    </header>