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

$message = "";
if (isset($_POST['submit'])) {
    $image_path = "";

    if (isset($_FILES['member_pic']) && $_FILES['member_pic']['error'] == 0) {
        $upload_dir = "uploads/blogs/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $image_path = $upload_dir . basename($_FILES["member_pic"]["name"]);
        move_uploaded_file($_FILES["member_pic"]["tmp_name"], $image_path);
    }

    $stmt = $conn->prepare("INSERT INTO blogs (title, description, video, images, external_link, patient_name, age, location, helped_with) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssssssiss",
        $_POST['title'],
        $_POST['description'],
        $_POST['video'],
        $image_path,
        $_POST['external_link'],
        $_POST['patient_name'],
        $_POST['age'],
        $_POST['location'],
        $_POST['helped_with']
    );

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Blog added successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blogs Management</title>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            background: #343a40;
            color: white;
            padding: 20px;
        }
        .sidebar h4 {
            margin-bottom: 20px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .content {
            flex-grow: 1;
            padding: 30px;
        }
        img {
            max-width: 100px;
            max-height: 80px;
            object-fit: cover;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Admin Panel</h4>
    <a href="users.php"><i class="fas fa-user-plus"></i> Add User</a>
    <a href="member.php"><i class="fas fa-user-friends"></i> Members</a>
    <a href="campaigns.php"><i class="fas fa-bullhorn"></i> Campaigns</a>
    <a href="patients.php"><i class="fas fa-procedures"></i> Patients</a>
    <a href="donors.php"><i class="fas fa-hand-holding-heart"></i> Donors</a>
    <a href="blogs.php"><i class="fas fa-blog"></i> Blogs</a>
    <a href="admin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>

    <form method="POST" class="mt-4">
        <button type="submit" name="logout" class="btn btn-danger w-100">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </form>
    <?php
    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        echo '<script>window.location.replace("index.php");</script>';
        exit;
    }
    ?>
</div>

<!-- Main Content -->
<div class="content container">
    <h3 class="text-center mb-4">Blogs Management</h3>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="blogTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="add-tab" data-bs-toggle="tab" data-bs-target="#add" type="button">Add Blog</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="view-tab" data-bs-toggle="tab" data-bs-target="#view" type="button">View Blogs</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content pt-4">
        <!-- Add Blog Tab -->
        <div class="tab-pane fade show active" id="add">
            <div class="card">
                <div class="card-body">
                    <?php echo $message; ?>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label>Blog Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Video URL</label>
                            <input type="text" name="video" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" name="member_pic" accept="image/*" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>External Link</label>
                            <input type="url" name="external_link" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Patient Name</label>
                            <input type="text" name="patient_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Age</label>
                            <input type="number" name="age" class="form-control" min="0" max="120" required>
                        </div>
                        <div class="mb-3">
                            <label>Location</label>
                            <input type="text" name="location" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Helped With</label>
                            <textarea name="helped_with" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary w-100">Add Blog</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Blogs Tab -->
        <div class="tab-pane fade" id="view">
            <div class="card">
                <div class="card-body">
                    <h4>All Blogs</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Video</th>
                                    <th>Image</th>
                                    <th>Link</th>
                                    <th>Patient</th>
                                    <th>Age</th>
                                    <th>Location</th>
                                    <th>Helped With</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC");
                                while ($row = $result->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['title'] ?></td>
                                    <td style="max-width: 300px;"><?= $row['description'] ?></td>
                                    <td><?= $row['video'] ? "<a href='{$row['video']}' target='_blank'>Watch</a>" : "" ?></td>
                                    <td><img src="<?= $row['images'] ?>" class="img-thumbnail" width="70"></td>
                                    <td><?= $row['external_link'] ? "<a href='{$row['external_link']}' target='_blank'>Visit</a>" : "" ?></td>
                                    <td><?= $row['patient_name'] ?></td>
                                    <td><?= $row['age'] ?></td>
                                    <td><?= $row['location'] ?></td>
                                    <td><?= $row['helped_with'] ?></td>
                                    <td><?= $row['created_at'] ?></td>
                                </tr>
                                <?php endwhile; $conn->close(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('tab') === 'view') {
        const viewTab = new bootstrap.Tab(document.querySelector('#view-tab'));
        viewTab.show();
    }
});
</script>
</body>
</html>
