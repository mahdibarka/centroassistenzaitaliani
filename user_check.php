<?php
$servername = "localhost";
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "centro_assistenza_italiani";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize and sanitize variables from POST data
$username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';
$password = isset($_POST['password']) ? htmlspecialchars(trim($_POST['password'])) : '';

// Validate input data
if (!empty($username) && !empty($password)) {
    // Prepare SQL statement to check user
    $sql = $conn->prepare("SELECT id FROM users WHERE username = ? AND password = ?");
    $sql->bind_param("ss", $username, $password);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        // User exists, fetch user_id
        $user_id = $result->fetch_assoc()['id'];

        // Fetch contact information for this user
        $contact_sql = $conn->prepare("
            SELECT * 
            FROM contacts 
            WHERE user_id = ?
        ");
        $contact_sql->bind_param("i", $user_id);
        $contact_sql->execute();
        $contact_result = $contact_sql->get_result();

        if ($contact_result->num_rows > 0) {
            // Display contact information
            echo "<table class='table'>";
            echo "<thead><tr><th>ID</th><th>Full Name</th><th>Email</th><th>Phone Number</th><th>Message</th><th>Created At</th></tr></thead><tbody>";
            while ($row = $contact_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "No contact information found for this user.";
        }
    } else {
        echo "Invalid username or password.";
    }

    $sql->close();
    $contact_sql->close();
} else {
    echo "Please provide both username and password.";
}

$conn->close();
?>
