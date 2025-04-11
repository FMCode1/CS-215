<?php
session_start();
require_once("db.php");

function test_input($data)
 {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data); 
    return $data;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode("Error.");
    exit();

    $user_id=$_SESSION['user_id'];
}
    try {
        $db = new PDO($attr, $db_user, $db_pwd, $options);
        $query = "SELECT t.title, t.topic_id, t.created_at 
              FROM topics t 
              JOIN access a ON t.user_id = a.user_id
              WHERE t.user_id = :user_id AND a.status = 1 
              ORDER BY t.created_at DESC";
        $result = $db->query($query);

        $jsonArray = array();

       while($row = $result->fetch())
       {
           $jsonArray[]=$row;
       }
        echo json_encode($jsonArray);

        $db = null;
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }

?>
