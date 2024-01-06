<?php
include_once dirname(__FILE__, 3) . "/db.php";

if (isset($_POST['searchajax']) && $_POST['searchajax'] == "true") {
    echo json_encode(searchUser(trim($_POST['search'])));
}
