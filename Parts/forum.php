<?php
session_start();
require_once 'DBConnection.php';

$db = new DBConnection();
$pdo = $db->getPdo();

echo '<link rel="stylesheet" href="forum.css">';

function displayForum($pdo, $forum_id) {
    try {
        $stmt_forum = $pdo->prepare("SELECT * FROM forums WHERE forum_id = :forum_id");
        $stmt_forum->bindParam(':forum_id', $forum_id);
        $stmt_forum->execute();
        $forum = $stmt_forum->fetch(PDO::FETCH_ASSOC);

        if ($forum) {
            echo "<h2 class='forum-title'>" . htmlspecialchars($forum['forum_name']) . "</h2>";

            $stmt_messages = $pdo->prepare("SELECT * FROM messages WHERE forum_id = :forum_id ORDER BY created_at DESC");
            $stmt_messages->bindParam(':forum_id', $forum_id);
            $stmt_messages->execute();
            $messages = $stmt_messages->fetchAll(PDO::FETCH_ASSOC);

            echo '<div class="messages-section">';
            echo '<h3 class="messages-title">Повідомлення:</h3>';
            echo '<div class="messages-container">';
            foreach ($messages as $message) {
                echo "<div class='message'>";
                echo "<strong class='message-user'>Користувач " . $message['user_id'] . ":</strong> " . htmlspecialchars($message['message_text']);
                echo "<br><small class='message-date'>" . $message['created_at'] . "</small>";
                echo "</div><hr>";
            }
            echo '</div>';
            echo '</div>';

            echo '<h3 class="send-message-title">Відправити повідомлення:</h3>';
            echo '<form class="message-form" method="POST" action="">
                    <input type="hidden" name="forum_id" value="' . $forum_id . '">
                    <textarea class="message-text" name="message_text" required></textarea><br>
                    <input class="submit-button" type="submit" value="Відправити">
                  </form>';
        } else {
            echo "<p>Форум не найден.</p>";
        }
    } catch (PDOException $e) {
        echo "Ошибка при выполнении запроса: " . $e->getMessage();
    }
}

if (isset($_GET['forum_id'])) {
    $forum_id = $_GET['forum_id'];
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message_text'])) {
        try {
            $message_text = $_POST['message_text'];
            $user_id = $_SESSION['user_id']; 

            $stmt = $pdo->prepare("INSERT INTO messages (forum_id, user_id, message_text, created_at) VALUES (:forum_id, :user_id, :message_text, NOW())");
            $stmt->bindParam(':forum_id', $forum_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':message_text', $message_text);
            $stmt->execute();

            header("Location: " . $_SERVER['PHP_SELF'] . "?forum_id=" . $forum_id);
            exit();
        } catch (PDOException $e) {
            echo "Ошибка при отправке сообщения: " . $e->getMessage();
        }
    } else {
        displayForum($pdo, $forum_id);
    }
} else {
    try {
        $stmt = $pdo->query("SELECT * FROM forums");
        $forums = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<h2 class='forums-title'><span class='forums-header'>Форуми</span> для обговорень</h2>";
        foreach ($forums as $forum) {
            echo "<div class='forum'>";
            echo "<div class='forum-header'>";
            echo "<strong class='forum-name'>" . htmlspecialchars($forum['forum_name']) . "</strong>";
            echo "<a class='join-button' href='?forum_id=" . $forum['forum_id'] . "'><button>Долучитися</button></a>";
            echo "</div>";
            echo "<p class='forum-description'>" . htmlspecialchars($forum['forum_description']) . "</p>";
            echo "</div><hr>";
        }

    } catch (PDOException $e) {
        echo "Ошибка при выполнении запроса: " . $e->getMessage();
    }
}
?>
