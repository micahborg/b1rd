<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connection.php");

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];
$shelter_id = $_SESSION['shelter_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];

    $query = "UPDATE adoptionapplication SET application_status = ? WHERE application_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("si", $status, $application_id);
    $stmt->execute();

    if ($status === 'Accepted') {
        $query = "UPDATE bird SET adoption_status = 'Not Available' WHERE bird_id = (SELECT bird_id FROM adoptionapplication WHERE application_id = ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $application_id);
        $stmt->execute();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Applications | B1RD</title>
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
            <h1>Applications</h1>
        </div>
        <?php
        if ($user_type == 'shelter') {
            $query = "SELECT * FROM adoptionapplication 
                      JOIN bird ON adoptionapplication.bird_id = bird.bird_id 
                      JOIN user ON adoptionapplication.user_id = user.user_id
                      JOIN shelter ON bird.shelter_id = shelter.shelter_id
                      WHERE bird.shelter_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $shelter_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="card">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">Application ID: ' . $row['application_id'] . '</h5>';
                    echo '<h5 class="card-text">Applicant Name: ' . $row['user_name'] . '</h5>';
                    echo '<p class="card-text">Shelter Name: ' . $row['shelter_name'] . '</p>';
                    echo '<p class="card-text">Bird Name: ' . $row['bird_name'] . '</p>';
                    echo '<p class="card-text">Reason: ' . $row['reason_for_adoption'] . '</p>';
                    if ($row['application_status'] == 'Pending') {
                        echo '<div class="alert alert-primary" role="alert">Adoption Status: ' . $row['application_status'] . '</div>';
                        echo '<form action="" method="post">';
                        echo '<input type="hidden" name="application_id" value="' . $row['application_id'] . '">';
                        echo '<div class="form-group">';
                        echo '<label for="status">Update Status:</label>';
                        echo '<select class="form-control" id="status" name="status">';
                        echo '<option value="Accepted">Accepted</option>';
                        echo '<option value="Denied">Denied</option>';
                        echo '</select>';
                        echo '</div>';
                        echo '<button type="submit" name="update_status" class="btn btn-primary">Submit</button>';
                        echo '</form>';
                    } elseif ($row['application_status'] == 'Denied') {
                        echo '<div class="alert alert-danger" role="alert">Adoption Status: ' . $row['application_status'] . '</div>';
                    } elseif ($row['application_status'] == 'Accepted') {
                        echo '<div class="alert alert-success" role="alert">Adoption Status: ' . $row['application_status'] . '</div>';
                    }
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No applications found.</p>';
            }
        } elseif ($user_type == 'adopter') {
            // Display applications with corresponding user id
            $query = "SELECT * FROM adoptionapplication 
                      JOIN bird ON adoptionapplication.bird_id = bird.bird_id 
                      JOIN shelter ON bird.shelter_id = shelter.shelter_id 
                      WHERE adoptionapplication.user_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="card">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">Application ID: ' . $row['application_id'] . '</h5>';
                    echo '<p class="card-text">Shelter Name: ' . $row['shelter_name'] . '</p>';
                    echo '<p class="card-text">Bird Name: ' . $row['bird_name'] . '</p>';
                    if ($row['application_status'] == 'Pending') {
                        echo '<div class="alert alert-primary" role="alert">Adoption Status: ' . $row['application_status'] . '</div>';
                    } elseif ($row['application_status'] == 'Denied') {
                        echo '<div class="alert alert-danger" role="alert">Adoption Status: ' . $row['application_status'] . '</div>';
                    } elseif ($row['application_status'] == 'Accepted') {
                        echo '<div class="alert alert-success" role="alert">Adoption Status: ' . $row['application_status'] . '</div>';
                    }
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No applications found.</p>';
            }
        }
        ?>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>