<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include './config/connection.php';
include_once './includes/header.php';

function sanitize($conn, $input)
{
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = $conn->query($user_query);
$user_data = $user_result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = sanitize($conn, $_POST['first_name']);
    $last_name = sanitize($conn, $_POST['last_name']);
    $email = sanitize($conn, $_POST['email']);
    $phone = sanitize($conn, $_POST['phone']);
    $dob = sanitize($conn, $_POST['dob']);
    $gender = sanitize($conn, $_POST['gender']);

    // Handle file upload
    $profile_image = $user_data['profileImage']; // Default to existing image
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $upload_file = $upload_dir . basename($_FILES['profile_image']['name']);
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_file)) {
            $profile_image = $_FILES['profile_image']['name'];
        } else {
            $_SESSION['error'] = "Error uploading profile image.";
        }
    }

    // Update user data
    $update_query = "UPDATE users SET 
                     firstName = '$first_name', 
                     lastName = '$last_name', 
                     email = '$email', 
                     phone = '$phone', 
                     dob = '$dob', 
                     gender = '$gender',
                     profileImage = '$profile_image'
                     WHERE id = $user_id";

    if ($conn->query($update_query)) {
        $_SESSION['message'] = "Account updated successfully!";
        header('Location: account.php');
        exit();
    } else {
        $_SESSION['error'] = "Error updating account: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <style>
        .profile-image-preview {
            width: 515px;
            /* height: 100px; */
            /* border-radius: 50%; */
            object-fit: cover;
        }

        .container {
            max-width: 1200px;
            margin: 35px auto;
        }

        .message {
            background: #588e58;
            color: white;
            padding: 10px;
            margin: 7px 0px;
            border-radius: 10px;
            width: 281px;
            height: 41px;
        }
    </style>
    <script>
        function previewProfileImage(event) {
            const input = event.target;
            const reader = new FileReader();
            reader.onload = function() {
                const dataURL = reader.result;
                const output = document.getElementById('profileImagePreview');
                output.src = dataURL;
            };
            reader.readAsDataURL(input.files[0]);
        }
    </script>
</head>

<body>
    <div class="container">
        <h2>My Account</h2>

        <?php if (isset($_SESSION['message'])) : ?>
            <p class="message"><?= $_SESSION['message']; ?></p>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])) : ?>
            <p class="error"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="account.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?= $user_data['firstName']; ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?= $user_data['lastName']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= $user_data['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" value="<?= $user_data['phone']; ?>" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" value="<?= $user_data['dob']; ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="male" <?= $user_data['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?= $user_data['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                    <option value="other" <?= $user_data['gender'] == 'other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="profile_image">Profile Image:</label>
                <input type="file" id="profile_image" name="profile_image" accept="image/*" onchange="previewProfileImage(event)">
                <br>
                <img id="profileImagePreview" src="uploads/<?= $user_data['profileImage']; ?>" alt="Profile Image" class="profile-image-preview">
            </div>
            <button type="submit">Update</button>
        </form>
    </div>
</body>

</html>