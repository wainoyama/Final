<?php
  $members = [
    ["name" => "Emman", "role" => "Role Placeholder", "tasks" => "Tasks Placeholder", "img" => "emman.jpg"],
    ["name" => "Paul", "role" => "Role Placeholder", "tasks" => "Tasks Placeholder", "img" => "paul.jpg"],
    ["name" => "Klyzza", "role" => "Role Placeholder", "tasks" => "Tasks Placeholder", "img" => "klyzza.jpg"]
  ]; 
?>
<!DOCTYPE html>
<head>
  <title>About Page - CALABARZON Harvest Hub</title>
  <link rel="stylesheet" href="aboutpage.css" />
</head>
<body>

  <header>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li><!-- or Kung saan man ang home page-->
        <li><a href="aboutpage.php">About</a></li>
        <li><a href="contactpage.php">Contact</a></li><!-- To be added laterr-->
      </ul>
    </nav>
    <h1 class="project-name">CALABARZON Harvest Hub</h1> 
  </header>

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
    <p>We are the team behind CALABARZON Harvest Hub, a platform that connects farmers in CALABARZON with buyers to address food security. Together, we can help grow the region’s agricultural success.</p>
  </section>

</body>
</html>
