    <?php
    $validUsername = 'BSIT Student';
    $validPassword = '1234';

    $message = '';

    if (isset($_POST['submit'])) { 
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === $validUsername && $password === $validPassword) {
header('Location: index.php'); 
exit;
} else {
            $message = 'Invalid username or password.';
        }
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <h2>Login</h2>
        <form method='post'>
            <p>Username: <input type='text' name='username' required></p>
            <p>Password: <input type='password' name='password' required></p>
            <p><input type='submit' name='submit' value='Login'></p> 
        </form>

        <p>
            <?php echo $message; ?>
        </p>

        <p>
            Dont have an account? <a href="signup.php">Click here to sign up</a>
        </p>
    </body>
    </html>
