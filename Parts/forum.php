<?php
require_once 'DBConnection.php';

$db = new DBConnection();
$pdo = $db->getPdo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $review_text = $_POST['review_text'];
    $rating = $_POST['rating'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO forum (user_id, review_text, rating, created_at) VALUES (:user_id, :review_text, :rating, NOW())");
        $stmt->execute(['user_id' => $user_id, 'review_text' => $review_text, 'rating' => $rating]);
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

$messages = $pdo->query("SELECT f.*, u.login AS username FROM forum f JOIN users u ON f.user_id = u.user_id ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форум</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="chat-container">
        <h1>Форум</h1>
        <div class="chat-box" id="chat-box" id="stor">
            <?php foreach ($messages as $message): ?>
                <div class="chat-message">
                    <strong class="username"><?= htmlspecialchars($message['username']) ?></strong>
                    <p class="message-text"><?= htmlspecialchars($message['review_text']) ?></p>
                    <div class="message-footer">
                        <span class="rating">Оцінка: <?= htmlspecialchars($message['rating']) ?></span>
                        <span class="timestamp"><?= date('Y-m-d H:i:s', strtotime($message['created_at'])) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <form id="chat-form">
            <input type="hidden" id="user_id" value="1"> <!-- Change this as per your user management system -->
            <textarea id="review_text" placeholder="Повідомлення" required></textarea>
            <select id="rating" required>
                <option value="" disabled selected>Оцініть</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <button type="submit">Відправити</button>
        </form>
    </div>
    <script>setInterval(function(){
$("#stor").load("# #stor"); }, 1000); // 1000 это 1 секунда
</script>
    <script>
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const user_id = document.getElementById('user_id').value;
        const review_text = document.getElementById('review_text').value;
        const rating = document.getElementById('rating').value;

        fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                'user_id': user_id,
                'review_text': review_text,
                'rating': rating
            })
        }).then(response => response.json())
          .then(data => {
              if (data.status === 'success') {
                  document.getElementById('review_text').value = '';
                  document.getElementById('rating').value = '';
                  location.reload(); 
              } else {
                  console.error('Error:', data.message);
              }
          });
    });

    function loadMessages() {
        fetch('')
            .then(response => response.text())
            .then(data => {
                const messages = JSON.parse(data);
                const chatBox = document.getElementById('chat-box');
                chatBox.innerHTML = messages.map(message => `
                    <div class="chat-message">
                        <strong class="username">${message.username}</strong>
                        <p class="message-text">${message.review_text}</p>
                        <div class="message-footer">
                            <span class="rating">Оцінка: ${message.rating}</span>
                            <span class="timestamp">${new Date(message.created_at).toLocaleString()}</span>
                        </div>
                    </div>
                `).join('');
                chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom
            });
    }

    loadMessages();
    setInterval(loadMessages, 5000); // Refresh messages every 5 seconds
</script>

</body>
</html>
