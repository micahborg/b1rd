<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connection.php");

// Login functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user_name = $_POST['user_name'];
    $password = $_POST['user_password'];

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        $query = "SELECT * FROM user WHERE user_name = ? LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $user_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            if ($user_data['user_password'] === $password) {
                $_SESSION['user_id'] = $user_data['user_id'];
                header("Location: index.php");
                exit;
            } else {
                echo "Wrong username or password!";
            }
        } else {
            echo "User does not exist!";
        }
    } else {
        echo "Invalid username or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | B1RD</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body> 
    <!-- NAVBAR/FOOTER -->  
    <?php include 'objects/nav.php'; ?>

    <!-- MAIN CONTENT -->
    <div class="container-fluid">
        <div class="row justify-content-center" style="padding: 100px 0px 0px 0px;">
            <h1>Login</h1>
        </div>

        <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div>
                <label for="user_name">Username:</label>
                <input type="text" id="user_name" name="user_name" required>
            </div>
            <div>
                <label for="user_password">Password:</label>
                <input type="password" id="user_password" name="user_password" required>
            </div>
            <div>
                <button type="submit" name="login">Login</button>
            </div>
        </form>

        <p style="padding: 20px 500px;">Don't have an account? <a href="#" data-toggle="modal" data-target="#signupModal">Sign up</a>.</p>

        <!-- Sign up Modal -->
        <div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="signupModalLabel">Sign Up</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Sign up form -->
                        <form action="signup.php" method="post">
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Username" aria-label="Username" name="username">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" placeholder="Password" aria-label="Password" name="password">
                            </div>
                            <button class="btn btn-primary" type="submit">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>