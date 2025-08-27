
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('https://img.freepik.com/free-photo/flat-lay-desk-arrangement-with-copy-space_23-2148928165.jpg');
            background-size: cover;
        }
        form {
            width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        label {
            display: inline-block;
            width: 100px;
            text-align: right;
            margin-right: 15px;
        }
        input[type="text"],
        input[type="email"],
        textarea {
            width: 250px;
            padding: 5px;
            margin-bottom: 10px;
        }
        textarea {
            height: 100px;
        }
        input[type="submit"] {
            width: auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>

<?php
   
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $feedbacks = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $first_name = sanitize_input($_POST["first_name"]);
        $last_name = sanitize_input($_POST["last_name"]);
        $address = sanitize_input($_POST["address"]);
        $email = sanitize_input($_POST["email"]);
        $feedback = sanitize_input($_POST["feedback"]);
        $mobile = sanitize_input($_POST["mobile"]);

        // Save form data to CSV file
        $data = array($first_name, $last_name, $address, $email, $feedback, $mobile);
        $file = fopen("feedback.csv", "a");
        fputcsv($file, $data);
        fclose($file);

        echo "<p>Feedback submitted successfully!</p>";
    }

    // Read feedback from CSV file
    if (($handle = fopen("feedback.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $feedbacks[] = $data;
        }
        fclose($handle);
    }

    if (!empty($feedbacks)) {
        echo "<h2>Previous Feedback</h2>";
        echo "<table>";
        echo "<tr><th>First Name</th><th>Last Name</th><th>Address</th><th>Email</th><th>Feedback</th><th>Mobile</th></tr>";
        foreach ($feedbacks as $feedback) {
            echo "<tr>";
            foreach ($feedback as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" required><br><br>

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" required><br><br>

    <label for="address">Address:</label>
    <input type="text" id="address" name="address" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="feedback">Feedback:</label>
    <textarea id="feedback" name="feedback" rows="4" required></textarea><br><br>

    <label for="mobile">Mobile:</label>
    <input type="text" id="mobile" name="mobile" required><br><br>

    <input type="submit" value="Submit">
</form>

</body>
</html>
