<?php
include 'db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Handle Add/Edit Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $patient = $_POST['patient_name'];
    $title = $_POST['title'];
    $disease = $_POST['disease'];
    $money = $_POST['money_required'];

    if (!empty($_POST['campaign_id'])) {
        // Edit mode
        $id = $_POST['campaign_id'];
        $stmt = $conn->prepare("UPDATE campaigns SET name=?, patient_name=?, title=?, disease=?, money_required=? WHERE id=?");
        $stmt->bind_param("ssssdi", $name, $patient, $title, $disease, $money, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: campaigns.php?status=updated");
        exit;
    } else {
        // Add mode
        $stmt = $conn->prepare("INSERT INTO campaigns (name, patient_name, title, disease, money_required) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssd", $name, $patient, $title, $disease, $money);
        $stmt->execute();
        $stmt->close();
        header("Location: campaigns.php?status=added");
        exit;
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM campaigns WHERE id=$id");
    header("Location: campaigns.php?status=deleted");
    exit;
}

// Handle Edit Mode
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM campaigns WHERE id=$id");
    $editData = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Campaign Management</title>
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
  
<!-- Sidebar -->
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
<div class="container mt-4">
    <h2 class="text-center">Campaign Management</h2>

    <?php if (isset($_GET['status'])): ?>
        <div class="alert alert-success mt-2">
            <?php
            if ($_GET['status'] == 'added') echo "Campaign added successfully!";
            elseif ($_GET['status'] == 'updated') echo "Campaign updated successfully!";
            elseif ($_GET['status'] == 'deleted') echo "Campaign deleted successfully!";
           
            ?>
        </div>
    <?php endif; ?>

    <ul class="nav nav-tabs mt-3" id="tabMenu">
        <li class="nav-item">
            <a class="nav-link <?= isset($_GET['edit']) ? '' : 'active' ?>" href="#add" data-bs-toggle="tab">Add Campaign</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isset($_GET['edit']) ? 'active' : '' ?>" href="#view" data-bs-toggle="tab">View Campaigns</a>
        </li>
    </ul>

    <div class="tab-content border p-3">
        <!-- ADD TAB -->
        <div class="tab-pane fade <?= isset($_GET['edit']) ? '' : 'show active' ?>" id="add">
            <form method="POST" action="campaigns.php">
                <?php if ($editData): ?>
                    <input type="hidden" name="campaign_id" value="<?= $editData['id'] ?>">
                <?php endif; ?>
                <div class="row mb-2">
                    <div class="col">
                        <input type="text" class="form-control" name="name" placeholder="Campaign Name" required value="<?= $editData['name'] ?? '' ?>">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="patient_name" placeholder="Patient Name" required value="<?= $editData['patient_name'] ?? '' ?>">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <input type="text" class="form-control" name="title" placeholder="Title" required value="<?= $editData['title'] ?? '' ?>">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="disease" placeholder="Disease" required value="<?= $editData['disease'] ?? '' ?>">
                    </div>
                </div>
                <div class="mb-2">
                    <input type="number" step="0.01" class="form-control" name="money_required" placeholder="Money Required" required value="<?= $editData['money_required'] ?? '' ?>">
                </div>
                <button type="submit" class="btn btn-primary"><?= $editData ? 'Update Campaign' : 'Add Campaign' ?></button>
                <?php if ($editData): ?>
                    <a href="campaigns.php" class="btn btn-secondary ms-2">Cancel Edit</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- VIEW TAB -->
        <div class="tab-pane fade <?= isset($_GET['edit']) ? 'show active' : '' ?>" id="view">
            <table class="table table-bordered mt-3">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Campaign</th>
                    <th>Patient</th>
                    <th>Title</th>
                    <th>Disease</th>
                    <th>Money</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $campaigns = $conn->query("SELECT * FROM campaigns ORDER BY created_at DESC");
                while ($row = $campaigns->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['patient_name'] ?></td>
                        <td><?= $row['title'] ?></td>
                        <td><?= $row['disease'] ?></td>
                        <td><?= $row['money_required'] ?></td>
                        <td><?= $row['created_at'] ?></td>
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php if (isset($_GET['edit'])): ?>
<script>
    const triggerEl = document.querySelector('a[href="#add"]');
    bootstrap.Tab.getOrCreateInstance(triggerEl).show();
</script>
<?php endif; ?>

</body>
</html>
