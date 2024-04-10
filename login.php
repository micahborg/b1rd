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

        <?php
        $username = isset($_GET['username']) ? $_GET['username'] : ''; // check if 'username' is present in query parameters
        ?>

        <form action="index.php" method="post" style="padding: 20px 500px;">
            <div class="form-group">
                <input class="form-control" type="text" placeholder="Username" aria-label="Username" name="username" value="<?php echo htmlspecialchars($username); ?>">
            </div>
            <div class="form-group">
            <input class="form-control" type="password" placeholder="Password" aria-label="Password" name="password">
            </div>
        <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">Login</button>
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