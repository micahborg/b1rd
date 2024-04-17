<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connection.php");
$user_id = $_SESSION['user_id'];

// handle edit profile form. only update fields that are not empty
if (isset($_POST['changes_submit'])) {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_phone_number = $_POST['phone_number'];
    $current_password = $_POST['password'];
    $new_user_type = $_POST['user_type'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($new_username)) {
        $stmt = $con->prepare("UPDATE user SET user_name = ? WHERE user_id = ?");
        $stmt->bind_param("si", $new_username, $user_id);
        $stmt->execute();
    }

    if (!empty($new_email)) {
        $stmt = $con->prepare("UPDATE user SET email = ? WHERE user_id = ?");
        $stmt->bind_param("si", $new_email, $user_id);
        $stmt->execute();
    }

    if (!empty($new_phone_number)) {
        $stmt = $con->prepare("UPDATE user SET phone_number = ? WHERE user_id = ?");
        $stmt->bind_param("si", $new_phone_number, $user_id);
        $stmt->execute();
    }

    if (!empty($current_password)) {
        $stmt = $con->prepare("SELECT * FROM user WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();

        if ($user_data['user_password'] === $current_password) {
            if ($new_password === $confirm_password) {
                $stmt = $con->prepare("UPDATE user SET user_password = ? WHERE user_id = ?");
                $stmt->bind_param("si", $new_password, $user_id);
                $stmt->execute();
            } else {
                echo "Passwords do not match.";
            }
        } else {
            echo "Incorrect password.";
        }
    }

    header("Location: profile.php");
}

// handle delete profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profile-delete'])) {
    $stmt = $con->prepare("DELETE FROM user WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    session_destroy();

    header("Location: index.php");
    exit;

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile | B1RD</title>
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
        <div class="row justify-content-center" style="padding: 50px 0px 0px 0px;">
            <h1><?= htmlspecialchars($row['user_name']) ?>'s Profile</h1>
        </div>
        <div class="container">
            <?php 
            $query = "SELECT * FROM user WHERE user_id = $user_id";
            $result = $con->query($query); ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Username: <?= htmlspecialchars($row['user_name']) ?></h5>
                    <p class="card-text">Email: <?= htmlspecialchars($row['email']) ?></p>
                    <p>Phone Number: <?= htmlspecialchars($row['phone_number']) ?></p>
                    <p>User Type: <?= htmlspecialchars($row['user_type']) ?></p>

                    <?php if ($row['user_type'] == 'shelter'): ?>
                        <a href="my_birds.php" class="btn btn-outline-secondary">My Birds</a>
                    <?php endif; ?>

                    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#editProfileModal">Edit Profile</button>

                    <!-- Edit Profile Modal -->
                    <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <!-- Add your form fields here -->
                                    <form action="" method="POST">
                                        <div class="form-group">
                                            <input class="form-control" type="text" placeholder="Username" aria-label="Username" name="username">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" type="email" placeholder="Email" aria-label="Email" name="email">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" type="text" placeholder="Phone Number" aria-label="Phone Number" name="phone_number">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" type="password" placeholder="Password" aria-label="Password" name="password" required>
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" type="password" placeholder="New Password" aria-label="New Password" name="new_password">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" type="password" placeholder="Confirm Password" aria-label="Confirm Password" name="confirm_password">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary" name="changes_submit">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="" method="POST" style="display: inline;">
                        <button type="submit" class="btn btn-outline-secondary" name="profile-delete">Delete Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>