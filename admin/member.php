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

// Handle form submit
 if (isset($_POST['submit'])) {
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name      = $_POST['name'];
    $email     = $_POST['email'];
    $phone     = $_POST['phone'];
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address   = $_POST['address'];
    $role      = $_POST['role'];
    $Approved_By  = $_POST['Approved_By'];

    // Handle file upload
    $profile_pic = "";
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $upload_dir = "uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $profile_pic = $upload_dir . basename($_FILES["profile_pic"]["name"]);
        move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $profile_pic);
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO members (name, email, phone, password, address, role, Approved_By, profile_pic) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $email, $phone, $password, $address, $role, $Approved_By, $profile_pic);

    if ($stmt->execute()) {
    $_SESSION['message']= "<div class='alert alert-success'>User added successfully!</div>";
    
  
    } else {
        $_SESSION['message']= "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
 header("Location: member.php#add"); 
   exit();
    $stmt->close();
}
}
 // Handle delete
 if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // safely cast to integer
    $deleteQuery = "DELETE FROM members WHERE id = $id";

    if ($conn->query($deleteQuery)) {
        // Redirect back with tab=view
        header("Location: member.php?tab=view&deleted=1");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}


    // Fetch members
   

?>
<!DOCTYPE html>
<html>
<head>
    <title>Members Management</title>
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
    <div class="container mt-5">
        <h3 class="mb-4 text-center">User Management</h3>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="memberTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="add-tab" data-bs-toggle="tab" data-bs-target="#add" type="button" role="tab">Add Member</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="view-tab" data-bs-toggle="tab" data-bs-target="#view" type="button" role="tab">View Members</button>
        </li>
    </ul>

    <!-- Tab content -->
    <div class="tab-content pt-3" id="memberTabsContent">
        <!-- Add Member Tab -->
        <div class="tab-pane fade show active" id="add" role="tabpanel">
            <div class="card shadow">
                <div class="card-body">
                  <?php
                      if (isset($_SESSION['message'])) {
                       echo $_SESSION['message'];
                      unset($_SESSION['message']);
                           }
                     ?>  


                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="add_user" value="1">
                        <div class="row mb-2">
                            <div class="col">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="col">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Address</label>
                            <textarea name="address" class="form-control" required></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label>User Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="admin">Admin</option>
                                    <option value="member">Member</option>
                                    <option value="volunteer">Volunteer</option>
                                </select>
                            </div>
                            <div class="col">
                                <label>Added By</label>
                                <input type="text" name="Approved_By" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Profile Picture</label>
                            <input type="file" name="profile_pic" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Add Member</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Members Tab -->
        <div class="tab-pane fade" id="view" role="tabpanel">
            <div class="card shadow">
                <div class="card-body">
             <?php if (isset($_GET['deleted'])): ?>
              <div class="alert alert-success">Member deleted successfully!</div>
              <?php endif; ?>

                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Added By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $members = $conn->query("SELECT * FROM members ORDER BY Id DESC");
                            if ($members && $members->num_rows > 0): ?>
                                <?php while($row = $members->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['Id']; ?></td>
                                        <td>
                                            <?php if ($row['profile_pic']): ?>
                                                <img src="<?php echo $row['profile_pic']; ?>" width="50" height="50" class="rounded-circle">
                                            <?php else: ?>
                                                <span>No Image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        <td><?php echo ucfirst($row['role']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Approved_By']); ?></td>
                                        <td>
                                            <a href="edit_user.php?Id=<?php echo $row['Id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="?delete=<?php echo $row['Id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>

                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="8" class="text-center">No members found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                        

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);

    // Handle tab switch
    if (urlParams.get('tab') === 'view') {
        const viewTab = new bootstrap.Tab(document.querySelector('#view-tab'));
        viewTab.show();
    }

    // Remove 'deleted' param after showing message
    if (urlParams.get('deleted')) {
        const url = new URL(window.location.href);
        url.searchParams.delete('deleted');
        window.history.replaceState({}, document.title, url.toString());
    }
});
</script>
</body>
</html>

