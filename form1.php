<?php

class Users {

    private $con;

    public function __construct() {
        $this->con = mysqli_connect('localhost', 'root', '', 'ghar');
        if (mysqli_connect_error()) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    public function addRecord($data) {
        $id = mysqli_real_escape_string($this->con, $data['id']);
        $name = mysqli_real_escape_string($this->con, $data['name']);
        $email = mysqli_real_escape_string($this->con, $data['email']);
        $mobile = mysqli_real_escape_string($this->con, $data['mobile']);

        $query = "INSERT INTO crud (id, name, email, mobile) VALUES ('$id', '$name', '$email', '$mobile')";
        $runQuery = mysqli_query($this->con, $query);

        if ($runQuery) {
            echo "Record inserted successfully.<br>";
        } else {
            echo "Error: " . mysqli_error($this->con) . "<br>";
        }
    }

    public function fetchRecords() {
        $query = "SELECT * FROM crud";
        $runQuery = mysqli_query($this->con, $query);

        if ($runQuery) {
            echo "<table class='styled-table'>";
            echo "<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Mobile</th><th>Update</th><th>Delete</th></tr></thead>";
            echo "<tbody>";
            while ($row = mysqli_fetch_assoc($runQuery)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['mobile'] . "</td>";
                echo "<td><a class='btn update' href='?update_id=" . $row['id'] . "'>Update</a></td>";
                echo "<td><a class='btn delete' href='?delete_id=" . $row['id'] . "'>Delete</a></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "Error fetching records: " . mysqli_error($this->con);
        }
    }

    public function deleteRecord($id) {
        $id = mysqli_real_escape_string($this->con, $id);
        $query = "DELETE FROM crud WHERE id = '$id'";
        $runQuery = mysqli_query($this->con, $query);

        if ($runQuery) {
            echo "Record deleted successfully.<br>";
        } else {
            echo "Error deleting record: " . mysqli_error($this->con) . "<br>";
        }
    }

    public function updateRecord($data) {
        $id = mysqli_real_escape_string($this->con, $data['id']);
        $name = mysqli_real_escape_string($this->con, $data['name']);
        $email = mysqli_real_escape_string($this->con, $data['email']);
        $mobile = mysqli_real_escape_string($this->con, $data['mobile']);

        $query = "UPDATE crud SET name = '$name', email = '$email', mobile = '$mobile' WHERE id = '$id'";
        $runQuery = mysqli_query($this->con, $query);

        if ($runQuery) {
            echo "Record updated successfully.<br>";
        } else {
            echo "Error updating record: " . mysqli_error($this->con) . "<br>";
        }
    }

    public function getRecordById($id) {
        $id = mysqli_real_escape_string($this->con, $id);
        $query = "SELECT * FROM crud WHERE id = '$id'";
        $runQuery = mysqli_query($this->con, $query);

        return mysqli_fetch_assoc($runQuery);
    }
}

$user = new Users();

if (isset($_POST['submit'])) {
    $user->addRecord($_POST);
}

if (isset($_POST['update'])) {
    $user->updateRecord($_POST);
}

if (isset($_GET['delete_id'])) {
    $user->deleteRecord($_GET['delete_id']);
}

$updateData = [];
if (isset($_GET['update_id'])) {
    $updateData = $user->getRecordById($_GET['update_id']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Operations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }
        form {
            margin-bottom: 20px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        form input[type="text"],
        form input[type="email"],
        form input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form input[type="submit"] {
            background: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            
        }
        form input[type="submit"]:hover {
            background: #0056b3;
        }
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 18px;
            text-align: left;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .styled-table thead tr {
            background-color: #007BFF;
            color: #ffffff;
            text-align: left;
        }
        .styled-table th, .styled-table td {
            padding: 12px 15px;
        }
        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }
        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }
        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #007BFF;
        }
        .btn {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            color: white;
        }
        .btn.update {
            background-color: #28a745;
        }
        .btn.update:hover {
            background-color: #218838;
        }
        .btn.delete {
            background-color: #dc3545;
        }
        .btn.delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<h2><?php echo isset($_GET['update_id']) ? 'Update' : 'Insert'; ?> Record</h2>
<form method="post" action="">
    ID: <input type="text" name="id" value="<?php echo isset($updateData['id']) ? $updateData['id'] : ''; ?>" <?php echo isset($_GET['update_id']) ? 'readonly' : ''; ?>><br/>
    Name: <input type="text" name="name" value="<?php echo isset($updateData['name']) ? $updateData['name'] : ''; ?>"><br/>
    Email: <input type="email" name="email" value="<?php echo isset($updateData['email']) ? $updateData['email'] : ''; ?>"><br/>
    Mobile: <input type="text" name="mobile" value="<?php echo isset($updateData['mobile']) ? $updateData['mobile'] : ''; ?>"><br/>
    <input type="submit" name="<?php echo isset($_GET['update_id']) ? 'update' : 'submit'; ?>" value="<?php echo isset($_GET['update_id']) ? 'Update' : 'Insert'; ?>">
</form>

<h2>Records</h2>
<form method="post" action="">
    <input type="submit" name="fetch" value="Fetch Records">
</form>

<?php
if (isset($_POST['fetch'])) {
    $user->fetchRecords();
}
?>

</body>
</html>
