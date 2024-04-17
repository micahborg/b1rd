<!DOCTYPE html>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <img src="images/lil_peep.png" width="30" height="30" class="d-inline-block align-top" alt="">
        <a class="navbar-brand" href="index.php">B1RD</a>
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="discover.php">Discover</a>
            </li>
            <?php
            include("connection.php");
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                echo '<li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>';
                echo '<li class="nav-item">
                        <a class="nav-link" href="applications.php">Appplications</a>
                    </li>';
                $query = "SELECT * FROM user WHERE user_id = $user_id";
                $result = $con->query($query);
                $row = $result->fetch_assoc();
                if ($row['user_type'] == 'shelter') {
                    echo '<li class="nav-item">
                            <a class="nav-link" href="my_birds.php">My Birds</a>
                        </li>';
                }
            }
            ?>
        </ul>

        <?php
        // check if user is logged in
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            echo '<form action="logout.php" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-outline-secondary my-2 my-sm-0">Logout</button>
                </form>';
        } else {
            // display the login form if not logged in
            echo '<form class="form-inline" action="login.php" method="GET">
                    <input class="form-control mr-sm-2" type="text" placeholder="Username" aria-label="Username" style="padding-left: 10px; padding-right: 10px;" name="username">
                    <button class="btn btn-outline-secondary my-2 my-sm-0" type="submit">Login</button>
                </form>';
        }
        ?>
    </div>
</nav>
<footer style="position: fixed; bottom: 0; width: 100%;">
    <div class="container-fluid">
        <p>&copy; 2024 B1RD. Made with 💛 by Code M.</p>
    </div>
</footer>