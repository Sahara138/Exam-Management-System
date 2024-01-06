<?php include_once dirname(__FILE__, 3) . "/db.php"; ?>

<?php
// var_dump($_SESSION);
// exit;
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true || !isset($_SESSION['utype']) || $_SESSION['utype'] != 'admin') {
    header("Location: " . _get_base_url() . "login.php");
}

$question = viewQues();

// echo '<pre>';
// var_dump($question);
// echo '</pre>';
// exit();
?>

<?php include_once dirname(__FILE__, 3) . "/header.php"; ?>
<link rel="stylesheet" href="<?php echo _get_base_url(); ?>style.css">

<table>
    <?php if (count($question) > 0) : ?>
        <tr>
            <th>ID</th>
            <th>Subject</th>
            <th>Question</th>
            <th>Answer</th>
            <th>Marks</th>
            <th>Teacher ID</th>
            <th>Teacher Name</th>
            <th>Action</th>
        </tr>
        <?php foreach ($question as $ques) : ?>
            <tr>
                <td><?php echo $ques['Q_ID']; ?></td>
                <td><?php echo $ques['Q_SUB']; ?></td>
                <td><?php echo $ques['Q_QUES']; ?></td>
                <td><?php echo $ques['Q_ANS']; ?></td>
                <td><?php echo $ques['Q_MARKS']; ?></td>
                <td><?php echo $ques['T_ID']; ?></td>
                <td><?php echo $ques['T_NAME']; ?></td>
                <td>
                    <a href="./edit-user.php?id=<?php echo $ques['Q_ID'];; ?>">Edit</a>
                    <a href="./delete-user.php?id=<?php echo $ques['Q_ID'];; ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="6">There are no Questions Available at this moment</td>
        </tr>
    <?php endif; ?>
</table>

<?php include_once dirname(__FILE__, 3) . "/footer.php"; ?>