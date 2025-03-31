<?php
require_once("db.php");

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data); //encodes
    return $data;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{ 
    $errors = array();
    $dataOK = TRUE;
    
    $email= test_input($_POST["user_email"]);
    $emailRegex = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/";
    if (!preg_match($emailRegex, $email)) 
    {
        $errors["email"] = "Invalid Email.";
        $dataOK = FALSE;
    }

    $password = test_input($_POST["user_password"]);
    $passwordRegex = "/^.{8}$/";
    if (!preg_match($passwordRegex, $password)) {
        $errors["password"] = "Invalid Password";
        $dataOK = FALSE;
    }
    if ($dataOK) 
    {
        try 
        {
            $conn= new PDO("mysql:host=localhost; dbname=mpc777", "mpc777", "Shreyal2009");
        } 
        catch (PDOException $e) 
        {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

        $query = "SELECT user_id, user_email, user_password, user_name, userDofB, user_PicName from Loggers where user_email='$email' and user_password='$password'";
        $result = $conn->query($query);

        if (!$result) 
        {
            $errors["Database Error"] = "Could not retrieve user information";
        } 
        elseif ($row = $result->fetch()) 
        {
            session_start();
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["user_email"] = $row["user_email"];
            $_SESSION["user_password"] = $row["user_password"];
            $_SESSION["user_name"] = $row["user_name"];
            $_SESSION["user_DofB"] = $row["user_DofB"];
            $_SESSION["user_PicName"] = $row["user_PicName"];

            $ip = $_SERVER['REMOTE_ADDR'];
            $conn->null;
            header("Location : topiclist.php");
            exit();
        } 
        else 
        {
            $errors["Login Failed"] = "That username/password combination does not exist.";
        }

        $db = null;

    } 
    else {

        $errors['Login Failed'] = "You entered invalid data while logging in.";
    }
    if(!empty($errors))
    {
        foreach($errors as $type => $message) 
        {
            echo "$type: $message <br />\n";
        }
    }
}
