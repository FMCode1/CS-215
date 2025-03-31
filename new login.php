<?php
require_once("db.php");

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{ 
    $errors = array();
    $dataOK = TRUE;
    
    $email= test_input($_POST["email"]);
    $emailRegex = "/^[^\s@]+@[^\s@]+\.[^\s@]+$/";
    if (!preg_match($emailRegex, $email)) 
    {
        $errors["email"] = "Invalid Email.";
        $dataOK = FALSE;
    }

    $password = test_input($_POST["pwd"]);
    $passwordRegex = "/^.{8}$/";
    if (!preg_match($passwordRegex, $password)) {
        $errors["pwd"] = "Invalid Password";
        $dataOK = FALSE;
    }
    if ($dataOK) 
    {
        try 
        {
            $db= new PDO("mysql:host=localhost; dbname=mpc777", "mpc777", "Shreyal2009");
        } 
        catch (PDOException $e) 
        {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }

        $query = "SELECT user_id, user_email, user_password, user_name, userDofB, user_PicName from Loggers where user_email='$email' and user_password='$password'";
        $result = $db->query($query);

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
            $db->null;
            header("Location : topiclist.php");
            exit();
        } 
        else 
        {
            $errors["Login Failed"] = "That username/password combination does not exist.";
        }

        $db = null;

    } 
    else 
    {
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
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css" />
    <script src="js/eventHandlers.js" ></script>
</head>
<body>
    <div id="container">
        <div id="headi">
            <h1>Login</h1>
        </div>
        <main id="main">

            <form class="auths-form" id="loginform" method="post" action="topiclist.php">

                <div class="grid">
                    <label for="email">Email Address</label>
                    <input type="text" id="email" name="email" />
                    &nbsp;
                    <div id="error-text-email" class="error-text hidden">
                        Email is invalid
                    </div>

                    <label for="pwd">Password</label>
                    <input type="password" id="pwd" name="pwd" />
                    &nbsp;
                    <div id="error-text-password" class="error-text hidden">
                        Password is invalid
                    </div>
                </div>

                <div class="clickright">
                    <input type="submit" value="Login" />
                </div>
            </form>

            <div class="form-note01">
                <p>
                    Create a new account? <a href="signup.php">SignUp</a>
                </p>
            </div>
        </main>
    </div>
    <footer>
     Group 9 <br />
        Mahee Patel - 200483136<br />
        Feechi Onyenali Mgbeoduru - 200455227<br />
        Komalpreet Kaur - 200499009
    </footer>
    <script src="js/eventRegisterLogin.js"></script>
</body>
</html>
