<?php include_once dirname(__FILE__, 3) . "/db.php"; ?>

<?php
// var_dump($_SESSION);
// exit;
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true || !isset($_SESSION['utype']) || $_SESSION['utype'] != 'admin') {
    header("Location: " . _get_base_url() . "login.php");
}

$results = viewResult();

// echo '<pre>';
// var_dump($results);
// echo '</pre>';
// exit();
?>

<?php include_once dirname(__FILE__, 3) . "/header.php"; ?>
<link rel="stylesheet" href="<?php echo _get_base_url(); ?>style.css">

<table>
    <?php if (count($results) > 0) : ?>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Question</th>
            <th>Answer</th>
            <th>Total Mark</th>
            <th>Obtained Mark</th>
            <th>Action</th>
        </tr>
        <?php foreach ($results as $result) : ?>
            <tr>
                <td><?php echo $result['U_ID']; ?></td>
                <td><?php echo $result['U_NAME']; ?></td>
                <td><?php echo $result['Q_QUES']; ?></td>
                <td><?php echo $result['Q_ANS']; ?></td>
                <td><?php echo $result['Q_MARKS']; ?></td>
                <td><?php echo $result['R_MARKS']; ?></td>
                <td>
                    <a href="./edit-user.php?id=<?php echo $result['U_ID'];; ?>">Edit</a>
                    <a href="./delete-user.php?id=<?php echo $result['U_ID'];; ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="6">There are no Result</td>
        </tr>
    <?php endif; ?>
</table>

<?php include_once dirname(__FILE__, 3) . "/footer.php"; ?>