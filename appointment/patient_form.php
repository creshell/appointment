<?php
// Include the database connection file
include 'include/db_connection.php';

// Initialize variables for the edit form
$editMode = false;
$editId = '';
$editName = '';
$editEmail = '';

// Check if edit button is clicked
if (isset($_GET['edit'])) {
    $editId = $_GET['edit'];
    $editMode = true;

    // Fetch data of the selected patient for editing
    $result = $db->query("SELECT * FROM patients WHERE patient_id = $editId");

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $editName = $row['name'];
        $editEmail = $row['email'];
    }
}

// Process form submission for adding new patient
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $db->prepare("INSERT INTO patients (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the patient form
    header("Location: patient_form.php");
    exit();
}

// Process form submission for editing existing patient
if (isset($_POST['edit'])) {
    $id = $_POST['patient_id']; // Change 'id' to 'patient_id'
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $db->prepare("UPDATE patients SET name=?, email=? WHERE patient_id=?");
    $stmt->bind_param("ssi", $name, $email, $id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the patient form
    header("Location: patient_form.php");
    exit();
}

// Fetch and display patient data from the database
$result = $db->query("SELECT * FROM patients");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Patient Form</title>

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            display: flex;
            max-width: 1200px;
            margin: 20px;
            padding: 20px;
            background: #c6f8ff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
        }

        .left-side {
            flex: 1;
            margin-right: 20px;
        }

        .right-side {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #49cfe1;
            color: white;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            background-color: #595CFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover,.backbutton:hover {
            background-color: #49cfe1;
        }

        .backbutton{
            background-color: #595CFF;
            color: white; 
            padding: 8px 16px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer;
            margin-left: 311px;
        }

        a{
            text-decoration:none;
        }
        h2{
            text-align:center;
        }
       
    </style>
</head>
<body>
    <div class="container">
        <!-- Left side: Patient Form -->
        <div class="left-side">
            <?php if (!$editMode): ?>
                <h2>Patient Form</h2>
                <!-- Form to add new patient -->
                <form action="patient_form.php" method="post">
                    <label for="name">Name:</label>
                    <input type="text" name="name" required>
                    <label for="email">Email:</label>
                    <input type="email" name="email" required>

                    <!-- Add button and Back link in the same form -->
                    <button type="submit" name="add">Add</button>
                </form>
            <?php endif; ?>

            <?php if ($editMode): ?>
            <!-- Form to edit existing patient -->
            <h2>Edit Patient</h2>
            <form action="patient_form.php" method="post">
                <input type="hidden" name="edit" value="1"> <!-- Add this line -->
                <input type="hidden" name="patient_id" value="<?php echo $editId; ?>">
                <label for="name">Name:</label>
                <input type="text" name="name" value="<?php echo $editName; ?>" required>
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo $editEmail; ?>" required>
                <button type="submit">Save</button>
            </form>
            <?php endif; ?>
        </div>

        <!-- Right side: Patient Table -->
        <div class="right-side">
            <h2>Patients</h2>

            <?php
            if ($result->num_rows > 0) {
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Name</th>';
                echo '<th>Email</th>';
                echo '<th>Action</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "<td><a href='patient_form.php?edit={$row['patient_id']}' class='button edit'>Edit</a>  <a href='include/patient_process.php?delete={$row['patient_id']}' class='button delete'>Delete</a>";
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>No patients found.</p>';
            }

            $result->free(); // Free the result set
            ?>
            <!-- Back button -->
            <a  href="dashboard.php" class="backbutton">Back</a>
        </div>
    </div>

    
</body>
</html>

<?php
$db->close();
?>
