<?php include_once dirname(__FILE__, 3) . "/db.php"; ?>

<?php
// var_dump($_SESSION);
// exit;
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true || !isset($_SESSION['utype']) || $_SESSION['utype'] != 'admin') {
    header("Location: " . _get_base_url() . "login.php");
}

$users = viewNonVerifiedUser();

// echo '<pre>';
// var_dump($users);
// echo '</pre>';
// exit();
?>

<?php include_once dirname(__FILE__, 3) . "/header.php"; ?>
<link rel="stylesheet" href="<?php echo _get_base_url(); ?>style.css">

<table>
    <?php if (count($users) > 0) : ?>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>User Type</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?php echo $user['U_ID']; ?></td>
                <td><?php echo $user['U_NAME']; ?></td>
                <td><?php echo $user['U_EMAIL']; ?></td>
                <td><?php echo $user['U_PHONE']; ?></td>
                <td><?php echo $user['U_TYPE']; ?></td>
                <td>
                    <a href="./verify-user.php?id=<?php echo $user['U_ID']; ?>">Verify</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="6">There are no User</td>
        </tr>
    <?php endif; ?>
</table>

<?php include_once dirname(__FILE__, 3) . "/footer.php"; ?>