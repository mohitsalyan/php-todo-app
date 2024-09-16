<?php 
// Database connection
$connection = mysqli_connect('10.96.200.23', 'root', 'rootpassword', 'timeseries_schema');

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = '';

// Handle form submissions
if (isset($_POST['AAPL']) && empty($_POST['id'])) {
    $AAPL = $_POST['AAPL'];
    if (!empty($AAPL)) {
        if (insertAAPL($AAPL, $connection)) {
            echo "<script>alert('AAPL Inserted');</script>";
        } else {
            echo "<script>alert('Error inserting AAPL');</script>";
        }
    } else {
        echo "<script>alert('Please enter a AAPL');</script>";
    }
}

if (isset($_POST['AAPL']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    $AAPL = $_POST['AAPL'];
    if (updateAAPL($id, $AAPL, $connection)) {
        echo "<script>alert('AAPL Updated');</script>";
    } else {
        echo "<script>alert('Error updating AAPL');</script>";
    }
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    if (deleteAAPL($id, $connection)) {
        echo "<script>alert('AAPL Deleted');</script>";
    } else {
        echo "<script>alert('Error deleting AAPL');</script>";
    }
}

if (isset($_GET['marking_id'])) {
    $id = $_GET['marking_id'];
    if (completeAAPL($id, $connection)) {
        echo "<script>alert('AAPL marked as complete');</script>";
    } else {
        echo "<script>alert('Error marking AAPL as complete');</script>";
    }
}

function insertAAPL($AAPL, $connection) {
    $query = "INSERT INTO AAPLs (AAPL) VALUES ('$AAPL')";
    return mysqli_query($connection, $query);
}

function deleteAAPL($id, $connection) {
    $query = "DELETE FROM AAPLs WHERE id = '$id'";
    return mysqli_query($connection, $query);
}

function completeAAPL($id, $connection) {
    $query = "UPDATE AAPLs SET completed = 1 WHERE id = '$id'";
    return mysqli_query($connection, $query);
}

if (isset($_POST['update'])) {
    $id = $_POST['update_id'];
    $data = getAAPL($id, $connection);
}

function getAAPL($id, $connection) {
    $query = "SELECT * FROM AAPLs WHERE id = '$id'";
    $result = mysqli_query($connection, $query);
    return $result ? mysqli_fetch_assoc($result) : false;
}

function updateAAPL($id, $AAPL, $connection) {
    $query = "UPDATE AAPLs SET AAPL = '$AAPL' WHERE id = '$id'";
    return mysqli_query($connection, $query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>3-Tier PHP AAPL App</title>
    <style type="text/css">
        body {
            background-color: #f0f8ff; /* Light blue background */
            color: #333; /* Text color */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #4CAF50; /* Green background */
            color: white; /* White text */
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .wrapper {
            width: 40%;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px #aaa;
            background-color: #fafafa;
        }
        input[type="text"] {
            padding: 10px 20px;
            width: 80%;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
        .form {
            display: inline-block;
        }
        a {
            text-decoration: none;
            color: #3e3e3e;
        }
        .AAPL-item {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .AAPL-item:last-child {
            border-bottom: none;
        }
        hr {
            margin-top: 20px;
            border: 0;
            border-top: 1px solid #ddd;
        }
        .footer {
            background-color: #4CAF50; /* Green background */
            color: white; /* White text */
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 14px;
        }
        .footer .banner {
            background-color: #FFD700; /* Gold background */
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">Welcome to SenDevOps - 3-Tier PHP AAPL App</div>
    <div class="wrapper">
        <form method="post" action="index.php">
            <div>
                <center>
                    <input type="text" name="AAPL" placeholder="Create new AAPL" value="<?php if (isset($_POST['update_id'])) { echo htmlspecialchars($data['AAPL']); } ?>" required>
                    <input type="hidden" name="id" value="<?php if (isset($_POST['update_id'])) { echo htmlspecialchars($data['id']); } ?>">
                    <input class="button" type="submit" value="Submit">
                </center>
            </div>
        </form>
        <br><br>

        <?php 
        $query = "SELECT * FROM AAPLs ORDER BY id DESC";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($row as $AAPLs) { ?>
            <div class="AAPL-item">
                <center>
                    <?php echo htmlspecialchars($AAPLs['AAPL']); ?>
                    <button><a href='index.php?delete_id=<?php echo htmlspecialchars($AAPLs['id']); ?>'>Delete</a></button>
                    <form class="form" method="post" action="">
                        <input type="hidden" name="update_id" value="<?php echo htmlspecialchars($AAPLs['id']); ?>">
                        <input type="submit" value="Update" name="update">
                    </form>
                    <?php if ($AAPLs['completed'] == 1) {
                        echo "Completed";
                    } else { ?>
                        <button><a href='index.php?marking_id=<?php echo htmlspecialchars($AAPLs['id']); ?>'>Mark complete</a></button>
                    <?php } ?>
                </center>
            </div>
            <hr>
        <?php } ?>
    </div>
    <div class="footer">
        <div class="banner">Welcome to SenDevOps</div>
        All rights reserved by SenDevOps
    </div>
</body>
</html>
