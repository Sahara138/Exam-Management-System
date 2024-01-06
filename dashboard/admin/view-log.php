<?php include_once dirname(__FILE__, 3) . "/db.php"; ?>

<?php
// var_dump($_SESSION);
// exit;
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true || !isset($_SESSION['utype']) || $_SESSION['utype'] != 'admin') {
    header("Location: " . _get_base_url() . "login.php");
}

$logs = viewLog();

// echo '<pre>';
// var_dump($logs);
// echo '</pre>';
// exit();
?>

<?php include_once dirname(__FILE__, 3) . "/header.php"; ?>
<link rel="stylesheet" href="<?php echo _get_base_url(); ?>style.css">

<table>
    <?php if (count($logs) > 0) : ?>
        <tr>
            <th>Log ID</th>
            <th>User Name</th>
            <th>Actions</th>
            <th>Time</th>
        </tr>
        <?php foreach ($logs as $log) : ?>
            <tr>
                <td><?php echo $log['LOG_ID']; ?></td>
                <td><?php echo $log['USER_SYS']; ?></td>
                <td><?php echo $log['LOG_DETAILS']; ?></td>
                <td><?php echo $log['LOG_TIME']; ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="6">There are no log</td>
        </tr>
    <?php endif; ?>
</table>

<?php include_once dirname(__FILE__, 3) . "/footer.php"; ?>