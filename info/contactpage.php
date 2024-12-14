<!DOCTYPE html>
<head>
  <title>Contact Us - CALABARZON Harvest Hub</title>
  <link rel="stylesheet" href="../css/contactpage.css">
  <link rel="stylesheet" href="../css/common.css">
  <link rel="stylesheet" href="../css/aboutus.css">
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
