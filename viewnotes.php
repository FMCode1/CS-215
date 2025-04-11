<?php
require_once("db.php");

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$topic_id = isset($_GET['topic_id']) ? intval($_GET['topic_id']) : 0;

// Handle AJAX request for new note submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax'] === '1') {
    $note_content = trim($_POST['note_content']);
    $user_id = $_SESSION['user_id'];

    if (!empty($note_content)) {
        try {
            $conn = new PDO("mysql:host=localhost; dbname=mpc777", "mpc777", "Shreyal2009");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Insert the new note
            $insertQuery = "INSERT INTO Notes (topic_id, user_id, note_content, date_added) 
                            VALUES (:topic_id, :user_id, :note_content, NOW())";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bindParam(':topic_id', $topic_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':note_content', $note_content);
            $stmt->execute();

            // Fetch the updated list of notes
            $query = "SELECT n.note_id, n.note_content, n.date_added, u.user_name
                      FROM Notes n
                      JOIN Users u ON n.user_id = u.user_id
                      WHERE n.topic_id = :topic_id
                      ORDER BY n.date_added DESC";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':topic_id', $topic_id);
            $stmt->execute();
            $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch the updated note count
            $countQuery = "SELECT COUNT(*) AS note_count FROM Notes WHERE topic_id = :topic_id";
            $countStmt = $conn->prepare($countQuery);
            $countStmt->bindParam(':topic_id', $topic_id);
            $countStmt->execute();
            $noteCount = $countStmt->fetch(PDO::FETCH_ASSOC)['note_count'];

            // Return the updated notes and count as JSON
            echo json_encode(['notes' => $notes, 'note_count' => $noteCount]);
            exit();
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
            exit();
        }
    }
    exit();
}

// Fetch initial notes and count for page load
try {
    $conn = new PDO("mysql:host=localhost; dbname=mpc777", "mpc777", "Shreyal2009");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT n.note_id, n.note_content, n.date_added, u.user_name
              FROM Notes n
              JOIN Users u ON n.user_id = u.user_id
              WHERE n.topic_id = :topic_id
              ORDER BY n.date_added DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':topic_id', $topic_id);
    $stmt->execute();
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $countQuery = "SELECT COUNT(*) AS note_count FROM Notes WHERE topic_id = :topic_id";
    $countStmt = $conn->prepare($countQuery);
    $countStmt->bindParam(':topic_id', $topic_id);
    $countStmt->execute();
    $noteCount = $countStmt->fetch(PDO::FETCH_ASSOC)['note_count'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Notes</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional: Add a CSS file for styling -->
</head>
<body>
    <h1>Notes for Topic ID: <?php echo htmlspecialchars($topic_id); ?></h1>
    <p>Total Notes: <span id="noteCount"><?php echo $noteCount; ?></span></p>

    <!-- Form to add a new note -->
    <textarea id="noteContent" rows="4" cols="50" placeholder="Write your note here..."></textarea><br>
    <button id="addNoteButton">Add Note</button>

    <h2>All Notes</h2>
    <ul id="notesList">
        <?php foreach ($notes as $note): ?>
            <li>
                <strong><?php echo htmlspecialchars($note['user_name']); ?>:</strong>
                <?php echo htmlspecialchars($note['note_content']); ?>
                <em>(<?php echo htmlspecialchars($note['date_added']); ?>)</em>
            </li>
        <?php endforeach; ?>
    </ul>

    <script src="viewnotes.js"></script> <!-- Link to your external JavaScript file -->
</body>
</html>
