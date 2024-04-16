<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connection.php");

// login functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user_name = $_POST['username'];
    $password = $_POST['password'];

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
                $_SESSION['username'] = $user_data['username'];
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup_submit'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $password = trim($_POST['password']);
    $user_type = trim($_POST['user_type']);

    if (empty($username) || empty($email) || empty($phone_number) || empty($password) || empty($user_type)) {
        echo "Please fill in all fields.";
    } else {
        $stmt = $con->prepare("INSERT INTO user (user_name, email, phone_number, user_password, user_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $phone_number, $password, $user_type);

        if ($stmt->execute()) {
            echo "Registered successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
    $con->close();
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

        <?php
        $username = isset($_GET['username']) ? $_GET['username'] : ''; // check if 'username' is present in query parameters
        ?>

        <form action="" method="post" style="padding: 20px 700px;">
            <div class="form-group">
                <input class="form-control" type="text" placeholder="Username" aria-label="Username" name="username" value="<?php echo htmlspecialchars($username); ?>">
            </div>
            <div class="form-group">
            <input class="form-control" type="password" placeholder="Password" aria-label="Password" name="password">
            </div>
        <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit" name="login">Login</button>
        </form> 

        <p style="padding: 20px 700px;">Don't have an account? <a href="#" data-toggle="modal" data-target="#signupModal">Sign up</a>.</p>

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
                        <form action="" method="post">
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Username" aria-label="Username" name="username" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="email" placeholder="Email" aria-label="Email" name="email" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Phone Number" aria-label="Phone Number" name="phone_number" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" placeholder="Password" aria-label="Password" name="password" required>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="user_type" required>
                                    <option value="adopter">Adopter</option>
                                    <option value="shelter">Shelter</option>
                                </select>
                            </div>
                            <button class="btn btn-primary" type="submit" name="signup_submit">Sign Up</button>
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