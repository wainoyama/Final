<?php
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (empty($name) || empty($email) || empty($message)) {
        $error_message = "Please fill out all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        $error_message = "Thank you for reaching out! Your message has been received.";
    }
}
?>

<!DOCTYPE html>
<head>
  <title>Contact Us - CALABARZON Harvest Hub</title>
  <link rel="stylesheet" href="contactpage.css" />
</head>
<body>

  <header>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li> 
        <li><a href="aboutpage.php">About</a></li>
        <li><a href="contactpage.php">Contact</a></li>
      </ul>
    </nav>
    <h1 class="project-name">CALABARZON Harvest Hub</h1>
  </header>

  <div class="container">
    <h2>Contact Us</h2>

    <?php if (!empty($error_message)): ?>
      <div class="error-message">
        <p><?php echo htmlspecialchars($error_message); ?></p>
      </div>
    <?php endif; ?>

    <form action="contactpage.php" method="POST" class="contact-form">
      <label for="name">Your Name:</label>
      <input type="text" id="name" name="name" placeholder="Your Name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required />

      <label for="email">Your Email:</label>
      <input type="email" id="email" name="email" placeholder="Your Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required />

      <label for="message">Your Message:</label>
      <textarea id="message" name="message" rows="5" placeholder="Your Message" required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>

      <button type="submit" class="submit-button">Submit</button>
    </form>
  </div>

</body>
</html>
