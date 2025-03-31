<?php
require_once("db.php");

session_start();

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Check if user is admin (assuming admin_id = 1 for simplicity)
if ($_SESSION['user_id'] != 1) {
    header("Location: topiclist.php");
    exit();
}

$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : 0;
$users = [];

try {
    $conn = new PDO("mysql:host=localhost; dbname=mpc777", "mpc777", "Shreyal2009");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get all users and their access status for the given topic
    $query = "SELECT u.user_id, u.user_name, u.user_PicName, 
                     IFNULL(ta.access, 0) AS access
              FROM Users u
              LEFT JOIN Topic_Access ta ON u.user_id = ta.user_id AND ta.topic_id = :topic_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':topic_id', $topic_id);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Handle access change
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $access = isset($_POST['access']) ? 1 : 0;

    try {
        // Check if access exists, then update, else insert
        $query = "SELECT * FROM Topic_Access WHERE user_id = :user_id AND topic_id = :topic_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':topic_id', $topic_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $query = "UPDATE Topic_Access SET access = :access WHERE user_id = :user_id AND topic_id = :topic_id";
        } else {
            $query = "INSERT INTO Topic_Access (user_id, topic_id, access) VALUES (:user_id, :topic_id, :access)";
        }

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':topic_id', $topic_id);
        $stmt->bindParam(':access', $access);
        $stmt->execute();
        
        header("Location: access.php?topic_id=$topic_id");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Access</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="newtopic.html">Create Topic</a></li>
            <li><a href="topiclist.html">Topic List</a></li>
            <li><a href="viewnotes.html">View Notes</a></li>
            <li><a href="signup.html">Signup</a></li>
            <li><a href="login.html">Logout</a></li>
        </ul>
    </nav>

    <div class="h1style">
        <h1>Set Access</h1>
    </div>

    <div class="accessgrid">
        <h2>Username</h2>
        <h2>Avatar</h2>
        <h2>Access</h2>
    </div>

    <?php foreach ($users as $user): ?>
        <div class="accessgrid">
            <h3><?= $user['user_name'] ?></h3>
            <div class="avatar-item">
                <img src="assets/<?= $user['user_PicName'] ?>" />
            </div>
            <form method="post" action="">
                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                <input type="checkbox" name="access" <?= $user['access'] == 1 ? 'checked' : '' ?>>
                <button type="submit">Update</button>
            </form>
        </div>
    <?php endforeach; ?>

    <footer>
        Group 9 <br>
        Mahee Patel - 200483136<br>
        Feechi Onyenali Mgbeoduru - 200455227<br>
        Komalpreet Kaur - 200499009
    </footer>
</body>
</html>
