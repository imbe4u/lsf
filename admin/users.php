<?php
session_start();
include 'db.php';



// Handle form submission
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $blood_group = $_POST['blood_group'];
    $user_type = $_POST['user_type'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];

    // Upload files
    $blood_group_proof = $_FILES['blood_group_proof']['name'];
    $profile_image = $_FILES['profile_image']['name'];

    $blood_group_proof_tmp = $_FILES['blood_group_proof']['tmp_name'];
    $profile_image_tmp = $_FILES['profile_image']['tmp_name'];

    move_uploaded_file($blood_group_proof_tmp, "uploads/" . $blood_group_proof);
    move_uploaded_file($profile_image_tmp, "uploads/" . $profile_image);

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, blood_group, blood_group_proof, profile_image, user_type, gender, age) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssi", $name, $email, $phone, $blood_group, $blood_group_proof, $profile_image, $user_type, $gender, $age);

    if ($stmt->execute()) {
        $_SESSION['message'] = "<div class='alert alert-success'>User added successfully!</div>";
        header("Location: users.php#view");
        exit;
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        header("Location: users.php#add");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #343a40;
            color: white;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .content {
            flex-grow: 1;
            padding: 30px;
        }
        .profile {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h4>Admin Panel</h4>
    <a href="users.php"><i class="fas fa-user-plus"></i> Add User</a>
    <a href="member.php"><i class="fas fa-user"></i> Members</a>
    <a href="campaigns.php"><i class="fas fa-bullhorn"></i> Campaigns</a>
    <a href="patients.php"><i class="fas fa-file-medical"></i> Patients</a>
    <a href="donors.php"><i class="fas fa-hand-holding-medical"></i> Donors</a>
    <a href="blogs.php"><i class="fas fa-blog"></i> Blogs</a>
    <a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <form method="POST" style="margin-top: 20px;">
        <button type="submit" name="logout" class="btn btn-danger w-100">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </form>
    <!-- // Handle logout -->
    <?php
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    echo '<script>window.location.replace("index.php");</script>';
    exit;
}
?>
</div>
<div class="content">
    <ul class="nav nav-tabs" id="tabMenu" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="add-tab" data-bs-toggle="tab" data-bs-target="#add" type="button" role="tab" aria-controls="add" aria-selected="true">
                Add User
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="view-tab" data-bs-toggle="tab" data-bs-target="#view" type="button" role="tab" aria-controls="view" aria-selected="false">
                View Users
            </button>
        </li>
    </ul>

    <div class="tab-content mt-4" id="userTabContent">
        <!-- Add User -->
        <div class="tab-pane fade show active" id="add" role="tabpanel" aria-labelledby="add-tab">
            <h3>Add New User</h3>
            <?php
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            }
            ?>
            <form action="users.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="name" class="form-control mb-2" placeholder="Full Name" required>
                <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                <input type="text" name="phone" class="form-control mb-2" placeholder="Phone">
                <input type="text" name="blood_group" class="form-control mb-2" placeholder="Blood Group">

                <label>Blood Group Proof (Image/PDF)</label>
                <input type="file" name="blood_group_proof" class="form-control mb-2">

                <label>Profile Image</label>
                <input type="file" name="profile_image" class="form-control mb-2">

                <select name="user_type" class="form-control mb-2" required>
                    <option value="">--Select User Type--</option>
                    <option value="donor">Donor</option>
                    <option value="admin">Admin</option>
                    <option value="patient">Patient</option>
                    <option value="member">Member</option>
                </select>

                <select name="gender" class="form-control mb-2">
                    <option value="">--Select Gender--</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>

                <input type="number" name="age" class="form-control mb-3" placeholder="Age" min="0" max="120">
                <button type="submit" name="submit" class="btn btn-primary">Add User</button>
            </form>
        </div>

        <!-- View Users -->
        <div class="tab-pane fade" id="view" role="tabpanel" aria-labelledby="view-tab">
            <h3>User List</h3>
            <?php
            $result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
            if ($result->num_rows > 0) {
                echo "<table class='table table-bordered table-striped mt-3'>
                        <thead class='table-dark'>
                            <tr>
                                <th>ID</th>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>User Type</th>
                                <th>Gender</th>
                                <th>Age</th>
                                <th>Created At</th>
                            </tr>
                        </thead><tbody>";
                while ($row = $result->fetch_assoc()) {
                    $img = $row['profile_image'] ? 'uploads/' . $row['profile_image'] : 'https://via.placeholder.com/50';
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td><img class='profile' src='{$img}'></td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['user_type']}</td>
                            <td>{$row['gender']}</td>
                            <td>{$row['age']}</td>
                            <td>{$row['created_at']}</td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No users found.</p>";
            }
            ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
