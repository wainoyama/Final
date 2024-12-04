<?php
$settingsFile = 'settings.json';

$settings = [];
if (file_exists($settingsFile)) {
    $settings = json_decode(file_get_contents($settingsFile), true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings['site_name'] = htmlspecialchars($_POST['site_name'] ?? '');
    $settings['admin_email'] = htmlspecialchars($_POST['admin_email'] ?? '');
    $settings['theme'] = htmlspecialchars($_POST['theme'] ?? 'light');

    file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT));
    echo "<p style='color:green;'>Settings saved successfully!</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings Page</title>
</head>
<body>
    <h1>Settings</h1>
    <form method="POST" action="">
        <label for="site_name">Site Name:</label><br>
        <input type="text" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>"><br><br>
        
        <label for="admin_email">Admin Email:</label><br>
        <input type="email" id="admin_email" name="admin_email" value="<?php echo htmlspecialchars($settings['admin_email'] ?? ''); ?>"><br><br>
        
        <label for="theme">Theme:</label><br>
        <select id="theme" name="theme">
            <option value="light" <?php echo ($settings['theme'] ?? '') === 'light' ? 'selected' : ''; ?>>Light</option>
            <option value="dark" <?php echo ($settings['theme'] ?? '') === 'dark' ? 'selected' : ''; ?>>Dark</option>
        </select><br><br>
        
        <button type="submit">Save Settings</button>
    </form>
</body>
</html>