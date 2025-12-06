<?php
include 'db.php';
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Handle form submission
if (isset($_POST['submit'])) {
    $stmt = $conn->prepare("INSERT INTO patients (
        name, phone, email, doctor_prescription_doc, blood_group, any_other_help,
        raised_by_user_id, relationship_with_patient, description, amount_needed,
        amount_provided, blood_provided_units, images_after_treatment, message_from_patient,
        campaign_id, approved_by_admin_id, hospital_name, doctor_name, is_approved
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$isApproved = isset($_POST['is_approved']) ? 1 : 0;

    $stmt->bind_param("sssssssssddisssissi",
        $_POST['name'], $_POST['phone'], $_POST['email'], $_POST['doctor_prescription_doc'],
        $_POST['blood_group'], $_POST['any_other_help'], $_POST['raised_by_user_id'],
        $_POST['relationship_with_patient'], $_POST['description'], $_POST['amount_needed'],
        $_POST['amount_provided'], $_POST['blood_provided_units'], $_POST['images_after_treatment'],
        $_POST['message_from_patient'], $_POST['campaign_id'], $_POST['approved_by_admin_id'],
        $_POST['hospital_name'], $_POST['doctor_name']
        , $isApproved);

    if ($stmt->execute()) {
        header("Location: patients.php?success=1");
        exit;
    } else {
        header("Location: patients.php?error=" . urlencode($stmt->error));
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Campaigns</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <style>
        body {
            display: flex;
            height: 100vh;
            background-color: #f8f9fa;
        }
        form { max-width: 700px; margin-bottom: 40px; }
        input, select { width: 100%; padding: 8px; margin: 6px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f4f4f4; }

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
    <h2>Patient Campaigns</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Patient added successfully!</div>
    <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger">Error: <?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <ul class="nav nav-tabs" id="tabMenu">
        <li class="nav-item">
            <a class="nav-link active" href="#add" data-bs-toggle="tab">Add Patient</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#view" data-bs-toggle="tab">View Patients</a>
        </li>
    </ul>

    <div class="tab-content border p-3">
        <!-- Add Patient Tab -->
        <div class="tab-pane fade show active" id="add">
            <form action="patients.php" method="POST">
                <input type="text" name="name" placeholder="Patient Name" required>
                <input type="text" name="phone" placeholder="Phone">
                <input type="email" name="email" placeholder="Email">
                <input type="text" name="doctor_prescription_doc" placeholder="Doctor Prescription File Path">
                <input type="text" name="blood_group" placeholder="Blood Group">
                <textarea name="any_other_help" placeholder="Other Help Needed"></textarea>
                <input type="number" name="raised_by_user_id" placeholder="Raised By User ID">
                <input type="text" name="relationship_with_patient" placeholder="Relationship With Patient">
                <textarea name="description" placeholder="Description"></textarea>
                <input type="number" step="0.01" name="amount_needed" placeholder="Amount Needed">
                <input type="number" step="0.01" name="amount_provided" placeholder="Amount Provided">
                <input type="number" name="blood_provided_units" placeholder="Blood Provided Units">
                <textarea name="images_after_treatment" placeholder="Images After Treatment (comma-separated paths)"></textarea>
                <textarea name="message_from_patient" placeholder="Message from Patient"></textarea>
                <input type="number" name="campaign_id" placeholder="Campaign ID">
                <input type="number" name="approved_by_admin_id" placeholder="Approved By Admin ID (optional)">
                <input type="text" name="hospital_name" placeholder="Hospital Name">
                <input type="text" name="doctor_name" placeholder="Doctor Name">
                <label>Approved: <input type="checkbox" name="is_approved" value="1"></label><br><br>
                <button type="submit" name="submit" class="btn btn-primary">Add Patient</button>
            </form>
        </div>

        <!-- View Patients Tab -->
        <div class="tab-pane fade" id="view">
            <h4 class="mt-3">Patient List</h4>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th><th>Name</th><th>Phone</th><th>Email</th>
                    <th>Approved</th><th>Amount Needed</th><th>Amount Provided</th><th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $result = $conn->query("SELECT * FROM patients ORDER BY id DESC");
                while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= $row['is_approved'] ? 'Yes' : 'No' ?></td>
                        <td><?= $row['amount_needed'] ?></td>
                        <td><?= $row['amount_provided'] ?></td>
                          <td>
                            <a href="campaigns.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="campaigns.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this campaign?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
