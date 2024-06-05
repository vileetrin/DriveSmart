<?php
require_once 'DBConnection.php';

$db = new DBConnection();
$pdo = $db->getPdo();

$columnCheckQuery = $pdo->query("SHOW COLUMNS FROM forum LIKE 'forum_id'");
$columnExists = $columnCheckQuery->fetch();

if (!$columnExists) {

    $pdo->exec("ALTER TABLE forum ADD COLUMN forum_id INT NOT NULL");

    $pdo->exec("UPDATE forum SET forum_id = 1 WHERE forum_id IS NULL");
}

$forum_id = isset($_GET['forum_id']) ? (int)$_GET['forum_id'] : null;
$forum_name = '';

if ($forum_id) {
    switch ($forum_id) {
        case 1:
            $forum_name = 'Рекомендації щодо вибору автомобіля';
            break;
        case 2:
            $forum_name = 'Спільні поїздки та різні маршрути';
            break;
        case 3:
            $forum_name = 'Послуги та тарифи';
            break;
        default:
            $forum_name = 'Форум';
            break;
    }

    $stmt = $pdo->prepare("SELECT f.*, u.login AS username FROM forum f JOIN users u ON f.user_id = u.user_id WHERE f.forum_id = :forum_id ORDER BY created_at DESC");
    $stmt->execute(['forum_id' => $forum_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $messages = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json'); // Устанавливаем заголовок JSON
    $user_id = $_POST['user_id'];
    $review_text = $_POST['review_text'];
    $rating = $_POST['rating'];
    $forum_id = $_POST['forum_id'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO forum (user_id, review_text, rating, created_at, forum_id) VALUES (:user_id, :review_text, :rating, NOW(), :forum_id)");
        $stmt->execute(['user_id' => $user_id, 'review_text' => $review_text, 'rating' => $rating, 'forum_id' => $forum_id]);
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форум</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="chat-container">
        <h1>Форум</h1>
        <div class="forums-container">
            <p class="choose-forum-text">Оберіть форум:</p>
            <nav>
                <ul class="forums-list">
                    <li><a href="?forum_id=1">Рекомендації щодо вибору автомобіля</a></li>
                    <li><a href="?forum_id=2">Спільні поїздки та різні маршрути</a></li>
                    <li><a href="?forum_id=3">Послуги та тарифи</a></li>
                </ul>
            </nav>
        </div>
        <div id="forum-content" style="display: <?= $forum_id ? 'block' : 'none' ?>;">
            <h2 id="forum-title"><?= htmlspecialchars($forum_name) ?></h2>
            <div class="chat-box" id="chat-box">
                <?php foreach ($messages as $message): ?>
                    <div class="chat-message">
                        <strong class="username"><?= htmlspecialchars($message['username']) ?></strong>
                        <p class="message-text"><?= htmlspecialchars($message['review_text']) ?></p>
                        <div class="message-footer">
                            <span class="rating">Оценка: <?= htmlspecialchars($message['rating']) ?></span>
                            <span class="timestamp"><?= date('Y-m-d H:i:s', strtotime($message['created_at'])) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <form class="formforum" id="chat-form">
                <input type="hidden" id="user_id" value="1"> <!-- Измените это в соответствии с вашей системой управления пользователями -->
                <input type="hidden" id="forum_id" value="<?= $forum_id ?>">
                <textarea id="review_text" placeholder="Повідомлення" required></textarea>
                <select id="rating" required>
                    <option value="" disabled selected>Оцініти</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <button type="submit">Відправити</button>
            </form>
        </div>
    </div>
    <script>
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const user_id = document.getElementById('user_id').value;
        const review_text = document.getElementById('review_text').value;
        const rating = document.getElementById('rating').value;
        const forum_id = document.getElementById('forum_id').value;

        fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                'user_id': user_id,
                'review_text': review_text,
                'rating': rating,
                'forum_id': forum_id
            })
        }).then(response => response.json())
          .then(data => {
              if (data.status === 'success') {
                  document.getElementById('review_text').value = '';
                  document.getElementById('rating').value = '';
                  loadMessages(); // Обновление сообщений после успешной отправки
              } else {
                  console.error('Error:', data.message);
              }
          }).catch(error => console.error('Fetch Error:', error));
    });

    function loadMessages() {
        const forum_id = document.getElementById('forum_id').value;
        fetch('?forum_id=' + forum_id)
            .then(response => response.text())
            .then(data => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, 'text/html');
                const chatBox = doc.querySelector('#chat-box');
                if (chatBox) {
                    const messages = chatBox.innerHTML;
                    document.getElementById('chat-box').innerHTML = messages;
                }
                const forumTitle = doc.querySelector('#forum-title');
                if (forumTitle) {
                    document.querySelector('#forum-title').innerText = forumTitle.innerText;
                }
                document.getElementById('forum-content').style.display = 'block';
            }).catch(error => console.error('Load Messages Error:', error));
    }

    setInterval(loadMessages, 5000); // Обновление сообщений каждые 5 секунд
</script>
</body>
</html>
