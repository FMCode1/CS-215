<?php
require_once("db.php");

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] : 0;
$notes = [];

try {
    $conn = new PDO("mysql:host=localhost; dbname=mpc777", "mpc777", "Shreyal2009");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get all notes for the given topic_id
    $query = "SELECT n.note_id, n.note_content, n.date_added, u.user_name, u.user_PicName
              FROM Notes n
              JOIN Users u ON n.user_id = u.user_id
              WHERE n.topic_id = :topic_id
              ORDER BY n.date_added DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':topic_id', $topic_id);
    $stmt->execute();
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Handling the note submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $note_content = test_input($_POST["note_content"]);
    $user_id = $_SESSION["user_id"];

    if (!empty($note_content)) {
        try {
            $query = "INSERT INTO Notes (topic_id, user_id, note_content, date_added) 
                      VALUES (:topic_id, :user_id, :note_content, NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':topic_id', $topic_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':note_content', $note_content);
            $stmt->execute();
            header("Location: viewnotes.php?topic_id=$topic_id");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Helper function to clean input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>View Notes</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="newtopic.html">Create Topic</a></li>
            <li><a href="topiclist.html">Topic List</a></li>
            <li><a href="access.html">Access</a></li>
            <li><a href="signup.html">Signup</a></li>
            <li><a href="login.html">Logout</a></li>
        </ul>
    </nav>

    <div class="h1style">
        <h1>View Notes</h1>
    </div>

    <div class="notesgrid">
        <?php foreach ($notes as $note): ?>
            <div class="noteversions">
                <div class="writeup">
                    <h4><?= nl2br($note['note_content']) ?></h4>
                </div>

                <div class="datescreen">
                    <div class="form-input-grid">
                        <label for="datetime">Date and Time:</label>
                        <input type="datetime-local" value="<?= $note['date_added'] ?>" readonly>

                        <label for="screenname">Screen Name:</label>
                        <input type="text" value="<?= $note['user_name'] ?>" readonly>
                    </div>
                    <div class="avatar-item">
                        <img src="assets/<?= $note['user_PicName'] ?>" class="noteimage" />
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="newnotegrid">
            <form method="post" action="">
                <div>
                    <label>Add a new note</label>
                    <textarea name="note_content" class="newnoteinput" required></textarea>
                </div>
                <div class="newnotesubmit">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <footer>
        Group 9 <br>
        Mahee Patel - 200483136<br>
        Feechi Onyenali Mgbeoduru - 200455227<br>
        Komalpreet Kaur - 200499009
    </footer>
</body>
</html>
