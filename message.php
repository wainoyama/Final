<?php
$questions_and_answers = [
    "How much for this product?" => "The price varies, but most items are between 25 pesos and 500 pesos.",
    "Are your eggs free-range?" => "Yes, our eggs are from free-range hens that roam the farm freely.",
    "Do you offer home delivery?" => "We deliver locally for orders over 500 pesos.",
    "What’s your return policy?" => "If you're not happy with your purchase, bring it back for an exchange within 3 days.",
    "Are your products organic?" => "Yes, we use organic practices on our farm for most of our crops.",
    "Do you sell plants or seedlings?" => "Yes, we sell young plants, herbs, and flowers for home gardening.",
];

$default_response = "Please type your question and click 'Ask Question'.";

$user_input = '';
$response = $default_response;
$chat_history = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_question'])) {
    $user_input = trim($_POST['user_question']);
    
    $chat_history[] = [
        'sender' => 'user',
        'message' => $user_input
    ];
    
    if (array_key_exists($user_input, $questions_and_answers)) {
        $response = $questions_and_answers[$user_input];
    } else {
        $response = "Sorry, I don't have an answer to that question. Please ask something else.";
    }

    $chat_history[] = [
        'sender' => 'bot',
        'message' => $response
    ];
}
?>

<!DOCTYPE html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Chat Bot</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="chat-container">
    <h1>Chat with Our Farm Bot</h1>

    <div class="header-tabs">
        <a href="index.php">Home</a> 
    </div>
    
    <div id="chat-history">
        <?php if (!empty($chat_history)): ?>
            <?php foreach ($chat_history as $message): ?>
                <div class="message <?php echo $message['sender']; ?>">
                    <div class="message-text"><?php echo nl2br(htmlspecialchars($message['message'])); ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <form method="post">
        <input type="text" name="user_question" placeholder="Ask your question..." required value="<?php echo htmlspecialchars($user_input); ?>">
        <input type="submit" value="Send">
    </form>

    <hr>

    <div class="questions-list">
        <h3>Questions You Can Ask:</h3>
        <ul>
            <?php foreach ($questions_and_answers as $question => $answer): ?>
                <li><?php echo htmlspecialchars($question); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

</div>

</body>
</html>
