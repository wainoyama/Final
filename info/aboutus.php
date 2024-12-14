<?php
  $members = [
    ["name" => "Emman", "role" => "Role Placeholder", "tasks" => "Tasks Placeholder", "img" => "emman.jpg"],
    ["name" => "Paul", "role" => "Role Placeholder", "tasks" => "Tasks Placeholder", "img" => "paul.jpg"],
    ["name" => "Klyzza", "role" => "Role Placeholder", "tasks" => "Tasks Placeholder", "img" => "klyzza.jpg"]
  ]; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Page - CALABARZON Harvest Hub</title>
  <link rel="stylesheet" href="../css/common.css" />
  <link rel="stylesheet" href="../css/aboutpage.css" />
</head>
<body>
  <header>
    <div class="header-content">
      <div class="logo">CALABARZON Harvest Hub</div>
      <nav>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="aboutus.php">About</a></li>
          <li><a href="contactpage.php">Contact</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <div class="container">
    <h1 class="project-name">About Us</h1>

    <div class="team">
      <?php foreach ($members as $member): ?>
        <div class="team-member">
          <img src="images/<?php echo htmlspecialchars($member['img']); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" />
          <h3><?php echo htmlspecialchars($member['name']); ?></h3>
          <p><strong>Role:</strong> <?php echo htmlspecialchars($member['role']); ?></p>
          <p><strong>Tasks:</strong> <?php echo htmlspecialchars($member['tasks']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>

    <section class="about-description">
      <h2>About Us</h2>
      <p>We are the team behind CALABARZON Harvest Hub, a platform that connects farmers in CALABARZON with buyers to address food security. Together, we can help grow the region's agricultural success.</p>
    </section>
  </div>
</body>
</html>