<?php
session_start();
require_once("db.php");

if(!isset($_SESSION["user_id"])) 
{
    echo "Invalid User.";
} 
else 
{
    $user_id = $_SESSION["user_id"];
}
try
{
    $db = new PDO($attr, $db_user, $db_pwd, $options);
} 
catch (PDOException $e) 
{
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
$query="SELECT t.title,t.created_at,t.last_edited_at,
        count(n.topic_id) AS total_notes
        FROM topic t 
        LEFT JOIN note n ON t.topic_id = n.topic_id
        LEFT JOIN access a ON t.topic_id = a.topic_id 
        WHERE a.status=1 and a.user_id='$user_id'
        GROUP BY t.title,t.created_at,t.last_edited_at
        ORDER BY t.created_at DESC;"
$result= $db->query($query);

?>
