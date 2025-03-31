<?php
require_once("db.php");  // Optional file with database settings


function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Global variables to hold form values and errors
$errors = array();
$email = "";
$sname = "";
$password = "";
$confirm_password = "";
$genericError = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and store input values
    $email = test_input($_POST["email"]);
    $sname = test_input($_POST["sname"]);
    $password = test_input($_POST["pwd"]);
    $confirm_password = test_input($_POST["confirm_password"]);

    if (empty($email) || empty($sname) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Set a default avatar image (if no file is provided or on error)
    $avatar_url = "uploads/avatars/default.png";

    if (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES["avatar"]["tmp_name"];
        $file_name = basename($_FILES["avatar"]["name"]);
        $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowedTypes = array("jpg", "jpeg", "png", "gif");

        if (!in_array($imageFileType, $allowedTypes)) {
            $errors[] = "Avatar must be a JPG, JPEG, PNG, or GIF file.";
        } elseif ($_FILES["avatar"]["size"] > 1000000) {
            $errors[] = "Avatar file is too large (max 1MB).";
        } else {
            $newFileName = time() . "_" . uniqid() . "." . $imageFileType;
            $target_dir = "uploads/avatars/";
            $target_file = $target_dir . $newFileName;
            if (file_exists($target_file)) {
                $errors[] = "An error occurred with the avatar file upload.";
            } else {
                if (move_uploaded_file($file_tmp, $target_file)) {
                    $avatar_url = $target_file;
                } else {
                    $errors[] = "Error uploading avatar file.";
                }
            }
        }
    } elseif (isset($_FILES["avatar"]) && $_FILES["avatar"]["error"] !== UPLOAD_ERR_NO_FILE) {
        
        $errors[] = "Error processing the avatar upload.";
    }

    if (!empty($errors)) {
        $genericError = "There was an error processing your submission. Please try again.";
    } else {
        // If all is good so far, proceed to add the user to the database
        try {
            $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_db_username", "your_db_password");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }

        
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = :email OR screen_name = :sname");
        $stmt->execute([':email' => $email, ':sname' => $sname]);
        if ($stmt->fetchColumn() > 0) {
            $genericError = "An account with that email or screen name already exists.";
        } else {
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (email, screen_name, password, avatar_url) VALUES (:email, :sname, :password, :avatar_url)");
            $result = $stmt->execute([
                ':email'      => $email,
                ':sname'      => $sname,
                ':password'   => $hashedPassword,
                ':avatar_url' => $avatar_url
            ]);
            if ($result) {
                // On success, redirect to the login page
                header("Location: login.php");
                exit();
            } else {
                $genericError = "There was an error creating your account. Please try again.";
            }
        }
        $db = null;
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8" />
    <title>Sign Up - CS215 Homepage</title>
    <link rel="stylesheet" href="css/styles.css" />
    <script src="js/eventHandlers.js"></script>
</head>
<body>
    <div id="container">
        <header id="header-auth">
            <h1>Sign Up</h1>
        </header>
        <main id="main">
            <?php
            
            if (!empty($genericError)) {
                echo "<p class='error-text'>{$genericError}</p>";
            }
            ?>
            <form class="auths-form" id="signupForm" action="signup.php" method="post" enctype="multipart/form-data">
                <div class="form-input-grid">
                    <label for="email">Email address</label>
                    <input type="text" id="email" name="email" value="<?= $email ?>" required />

                    <label for="sname">Screen Name</label>
                    <input type="text" id="sname" name="sname" value="<?= $sname ?>" required />

                    <label for="pwd">Password</label>
                    <input type="password" id="pwd" name="pwd" required />

                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required />

                    <label for="avatar">Avatar</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" />
                </div>
                <div class="align-right">
                    <button type="submit" class="submit-button">Create account</button>
                </div>
            </form>
            <div class="form-note">
                Already have an account? <a href="login.php">Login</a>
            </div>
        </main>
    </div>
    <footer>
        Group 9 <br />
        Mahee Patel - 200483136<br />
        Feechi Onyenali Mgbeoduru - 200455227<br />
        Komalpreet Kaur - 200499009
    </footer>
    <script src="js/eventRegisterSignup.js"></script>
</body>
</html>
