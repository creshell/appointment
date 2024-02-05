<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title >Dashboard</title>
  
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #c6f8ff;
            background: linear-gradient(to top, #c6f8ff 0%, #595CFF 100%);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center; /* Center the text horizontally */
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
            padding: 20px;
            background: #fff;
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
            background-color: #595CFF;
            color: white;
        }

        a.button {
            display: block;
            margin-top: 20px;
            padding: 10px;
            background-color: #595CFF;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .right-side-notes {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Left side: Appointments Table -->
    <div class="left-side">
        <h2>Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient Name</th>
                    <th>Schedule Date</th>
                    <th>Schedule Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include the database connection file
                include 'include/db_connection.php';

                // Fetch appointments
                $sqlAppointments = "SELECT appointments.id, patients.name AS patient_name, schedule.schedule_date, schedule.schedule_time, appointments.status FROM appointments
                INNER JOIN patients ON appointments.patient_id = patients.patient_id
                INNER JOIN schedule ON appointments.schedule_id = schedule.schedule_id";

                $resultAppointments = $db->query($sqlAppointments);

                if ($resultAppointments === FALSE) {
                    die("Error executing the query: " . $db->error);
                }

                if ($resultAppointments->num_rows > 0) {
                    while ($row = $resultAppointments->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['patient_name']}</td>";
                        echo "<td>{$row['schedule_date']}</td>";
                        echo "<td>{$row['schedule_time']}</td>";
                        echo "<td>{$row['status']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No appointments found</td></tr>";
                }
                ?>
            </tbody>
        </table>

       
    </div>

    <!-- Right side: Notes -->
    <div class="right-side">
         <!-- Buttons to Access Forms -->
         <a href="patient_form.php" class="button">Manage Patients</a>
        <a href="schedule_form.php" class="button">Manage Schedules</a>
        <a href="appointment_form.php" class="button">Manage Appointments</a>
    </div>
</div>

</body>
</html>
