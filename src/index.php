<?php 
// Database connection
$connection = mysqli_connect('10.96.104.194', 'root', 'rootpassword', 'mydb');

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = '';

// Handle form submissions
if (isset($_POST['todo']) && empty($_POST['id'])) {
    $todo = $_POST['todo'];
    if (!empty($todo)) {
        if (insertTodo($todo, $connection)) {
            echo "<script>alert('Todo Inserted');</script>";
        } else {
            echo "<script>alert('Error inserting Todo');</script>";
        }
    } else {
        echo "<script>alert('Please enter a Todo');</script>";
    }
}

if (isset($_POST['todo']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    $todo = $_POST['todo'];
    if (updateTodo($id, $todo, $connection)) {
        echo "<script>alert('Todo Updated');</script>";
    } else {
        echo "<script>alert('Error updating Todo');</script>";
    }
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    if (deleteTodo($id, $connection)) {
        echo "<script>alert('Todo Deleted');</script>";
    } else {
        echo "<script>alert('Error deleting Todo');</script>";
    }
}

if (isset($_GET['marking_id'])) {
    $id = $_GET['marking_id'];
    if (completeTodo($id, $connection)) {
        echo "<script>alert('Todo marked as complete');</script>";
    } else {
        echo "<script>alert('Error marking Todo as complete');</script>";
    }
}

function insertTodo($todo, $connection) {
    $query = "INSERT INTO todos (todo) VALUES ('$todo')";
    return mysqli_query($connection, $query);
}

function deleteTodo($id, $connection) {
    $query = "DELETE FROM todos WHERE id = '$id'";
    return mysqli_query($connection, $query);
}

function completeTodo($id, $connection) {
    $query = "UPDATE todos SET completed = 1 WHERE id = '$id'";
    return mysqli_query($connection, $query);
}

if (isset($_POST['update'])) {
    $id = $_POST['update_id'];
    $data = getTodo($id, $connection);
}

function getTodo($id, $connection) {
    $query = "SELECT * FROM todos WHERE id = '$id'";
    $result = mysqli_query($connection, $query);
    return $result ? mysqli_fetch_assoc($result) : false;
}

function updateTodo($id, $todo, $connection) {
    $query = "UPDATE todos SET todo = '$todo' WHERE id = '$id'";
    return mysqli_query($connection, $query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>3-Tier PHP Todo App</title>
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
        .todo-item {
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .todo-item:last-child {
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
    <div class="header">Welcome to SenDevOps - 3-Tier PHP Todo App</div>
    <div class="wrapper">
        <form method="post" action="index.php">
            <div>
                <center>
                    <input type="text" name="todo" placeholder="Create new todo" value="<?php if (isset($_POST['update_id'])) { echo htmlspecialchars($data['todo']); } ?>" required>
                    <input type="hidden" name="id" value="<?php if (isset($_POST['update_id'])) { echo htmlspecialchars($data['id']); } ?>">
                    <input class="button" type="submit" value="Submit">
                </center>
            </div>
        </form>
        <br><br>

        <?php 
        $query = "SELECT * FROM todos ORDER BY id DESC";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
        foreach ($row as $todos) { ?>
            <div class="todo-item">
                <center>
                    <?php echo htmlspecialchars($todos['todo']); ?>
                    <button><a href='index.php?delete_id=<?php echo htmlspecialchars($todos['id']); ?>'>Delete</a></button>
                    <form class="form" method="post" action="">
                        <input type="hidden" name="update_id" value="<?php echo htmlspecialchars($todos['id']); ?>">
                        <input type="submit" value="Update" name="update">
                    </form>
                    <?php if ($todos['completed'] == 1) {
                        echo "Completed";
                    } else { ?>
                        <button><a href='index.php?marking_id=<?php echo htmlspecialchars($todos['id']); ?>'>Mark complete</a></button>
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
