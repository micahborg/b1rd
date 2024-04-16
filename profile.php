<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connection.php");
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
            <h1>My Profile</h1>
        </div>
        <div class="container">
            <?php 
            $query = "SELECT * FROM user WHERE user_id = $user_id";
            $result = $con->query($query);
            if ($result->num_rows > 0):    ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Username: <?= htmlspecialchars($row['user_name']) ?></h5>
                            <p class="card-text">Email: <?= htmlspecialchars($row['email']) ?></p>
                            <p>Phone Number: <?= htmlspecialchars($row['phone_number']) ?></p>
                            <p>User Type: <?= htmlspecialchars($row['user_type']) ?></p>

                            <?php if ($row['user_type'] == 'shelter'): ?>
                                <a href="list.php" class="btn btn-outline-secondary">My Birds</a>
                            <?php endif; ?>

                            <a href="edit_profile.php" class="btn btn-outline-secondary">Edit Profile</a>

                            <form action="delete_profile.php" method="POST" style="display: inline;">
                                <button type="submit" class="btn btn-outline-secondary">Delete Profile</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No profile found.</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>