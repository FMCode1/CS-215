<?php
session_start();
// Only allow access if the user is logged in; otherwise, redirect to login page
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once("db.php");  // Optional file for shared database settings

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$errors = array();
$topic = "";
$genericError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $topic = test_input($_POST["topic"]);

    if (empty($topic) || strlen($topic) > 256) {
        $genericError = "Topic name must be non-blank and 256 characters or less.";
    } else {
        try {
            $db = new PDO("mysql:host=localhost;dbname=your_database_name", "your_db_username", "your_db_password");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
        $stmt = $db->prepare("INSERT INTO topics (topic_name) VALUES (:topic)");
        $result = $stmt->execute([':topic' => $topic]);
        if ($result) {
            header("Location: topiclist.php");
            exit();
        } else {
            $genericError = "There was an error creating the topic. Please try again.";
        }
        $db = null;
    }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8" />
    <title>Create Topic - CS215 Homepage</title>
    <link rel="stylesheet" href="css/styles.css" />
    <script src="js/eventHandlersNewTopic.js"></script>
</head>
<body>
    <nav>
        <div>
            <ul>
                <li><img src="image/9.jpg" alt="logo" class="logoimg" /></li>
                <li><a href="newtopic.php">Create Topic</a></li>
                <li><a href="topiclist.php">Topic List</a></li>
                <li><a href="access.php">Access</a></li>
                <li><a href="signup.php">Signup</a></li>
                <li><a href="login.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="h1style">
        <h1>Create a New Topic</h1>
    </div>
    <div id="container">
        <main id="main">
            <?php
            if (!empty($genericError)) {
                echo "<p class='error-text'>{$genericError}</p>";
            }
            ?>
            <form class="topic-form" action="newtopic.php" method="post">
                <div class="align-center">
                    <label for="topic">Topic Name</label>
                    <input type="text" id="topic" name="topic" value="<?= $topic ?>" required />
                </div>
                <div class="align-center">
                    <button type="submit" class="submit-button">Submit</button>
                </div>
            </form>
        </main>
    </div>
    <footer>
        Group 9 <br />
        Mahee Patel - 200483136<br />
        Feechi Onyenali Mgbeoduru - 200455227<br />
        Komalpreet Kaur - 200499009
    </footer>
    <script src="js/eventRegisterNewTopic.js"></script>
</body>
</html>
