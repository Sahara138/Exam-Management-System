<?php
session_start();

function _get_base_url()
{
    $project_name = "ExamManagementSystem";
    return sprintf(
        "%s://%s%s",
        (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== "off") ? "https" : "http",
        $_SERVER['SERVER_NAME'],
        "/$project_name/"
    );
}

// var_dump(_get_base_url());
// exit();

function getConnection()
{
    $dbname = "orcl";
    $dbuser = "system";
    $dbpass = "orcl";
    $connection = null;

    try {
        $connection = new PDO("oci:dbname=$dbname", $dbuser, $dbpass);

        // if ($connection) {
        //     echo "Connected";
        // } else {
        //     echo "Not Connected";
        // }
    } catch (\Throwable $th) {
        throw $th;
    }

    return $connection;
}

function getLogin($email, $pass, $utype)
{
    $sql = "SELECT * FROM user_tb WHERE u_email = '$email' AND u_password = '$pass' AND u_type = '$utype'";
    $result = null;

    $_SESSION['loggedin'] = false;

    // if ($utype == "student") {
    //     $sql .= "";
    // } else if ($utype == "teacher") {
    //     $sql .= "teacher WHERE t_email = '$email' AND t_password = '$pass'";
    // } else if ($utype == "admin") {
    //     $sql .= "admin_tb WHERE ad_email = '$email' AND ad_password = '$pass'";
    // }

    try {
        $stmt = getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
    }

    // echo $sql;

    return $result;
}

function getUser($id)
{
    $sql = "SELECT * FROM user_tb WHERE u_id = '$id'";

    $result = null;

    try {
        $stmt = getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }
    // echo $sql;

    return $result;
}

function setUser($name, $email, $phone, $pass, $utype)
{

    // echo '<script>alert("in");</script>';
    $sql = "INSERT INTO user_tb VALUES(user_tb_sq.NEXTVAL, '$name', '$email', '$phone', '$pass', '$utype', 'false')";

    $result = null;

    // echo $sql . "<br>";
    // exit;

    try {
        $stmt = getConnection()->prepare($sql);
        $stmt->execute();

        $result = $stmt->rowCount();
    } catch (PDOException $e) {
        // echo $e;
    }

    return $result;
}

function viewUser()
{
    $sql = "SELECT * FROM user_tb";

    $result = null;

    try {
        $stmt = getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }

    return $result;
}

function searchUser($search_value)
{
    if (empty(trim($search_value))) {
        $sql = "SELECT * FROM user_tb WHERE NOT (u_type = 'admin' OR a_status = 'false')";
    } else {
        $search_value = trim($search_value);

        $sql = "SELECT * FROM user_tb WHERE LOWER(u_name) LIKE LOWER('%$search_value%') AND NOT (u_type = 'admin' OR a_status = 'false')";
    }

    $result = null;

    try {
        $stmt = getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }

    return $result;
}

function editUser($id, $name, $email, $phone)
{
    $sql = "BEGIN update_user('$id', '$name', '$email', '$phone'); END;";

    $result = null;

    // echo $sql . '<br><br><br>';

    try {
        $stmt = getConnection()->query($sql);

        $result = $stmt->rowCount();
    } catch (PDOException $e) {
        echo $e;
    }

    return $result;
}

function deleteUser($id)
{
    $sql = "BEGIN delete_user('$id'); END;";

    $result = null;

    // echo $sql . '<br><br><br>';

    try {
        $stmt = getConnection()->query($sql);
        $result = $stmt->rowCount();
    } catch (PDOException $e) {
        echo $e;
    }

    return $result;
}

function verifieUser($id)
{

    $sql = "UPDATE USER_TB SET A_STATUS = 'true' WHERE U_ID = $id";

    $result = null;

    // echo $sql . '<br><br><br>';

    try {
        $stmt = getConnection()->query($sql);
        $result = $stmt->rowCount();
    } catch (PDOException $e) {
        echo $e;
    }

    return $result;
}

// echo '<pre>';
// var_dump(editUser('1001', 'Mohibor Rahman', 'clash.kingrahat.gmail.com', '01760761654'));
// echo '</pre>';

function viewQues()
{
    $sql = "SELECT * FROM view_ques";

    $result = null;

    try {
        $stmt = getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }

    return $result;
}

function viewResult()
{
    $sql = "SELECT * FROM view_marks";

    $result = null;

    try {
        $stmt = getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }

    return $result;
}

function viewNonVerifiedUser()
{
    $sql = "SELECT * FROM user_tb WHERE a_status = 'false'";

    $result = null;

    try {
        $stmt = getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }

    return $result;
}

function viewLog()
{
    $sql = "SELECT * FROM log_tb";

    $result = null;

    try {
        $stmt = getConnection()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e;
    }

    return $result;
}
