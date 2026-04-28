<?php
require_once 'db.php';

// Search
$search = isset($_GET['search']) ? trim($conn->real_escape_string($_GET['search'])) : '';

if ($search !== '') {
    $sql = "SELECT * FROM students
            WHERE firstname LIKE '%$search%'
               OR lastname  LIKE '%$search%'
               OR email     LIKE '%$search%'
               OR course    LIKE '%$search%'
            ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM students ORDER BY id DESC";
}

$result = $conn->query($sql);

// Success / error messages from redirects
$message = '';
$msgType = 'success';
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'added':    $message = 'Student added successfully.';   break;
        case 'updated':  $message = 'Student updated successfully.'; break;
        case 'deleted':  $message = 'Student deleted successfully.'; break;
        case 'error':    $message = 'An error occurred. Please try again.'; $msgType = 'danger'; break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student CRUD</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Student Management</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add.php">Add Student</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main>
<div class="container mt-4">

    <?php if ($message): ?>
    <div class="alert alert-<?= $msgType ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between gap-2 py-3">
            <h5 class="mb-0">Student List</h5>
            <div class="d-flex gap-2 flex-wrap">
                <!-- Search form -->
                <form method="GET" action="index.php" class="d-flex gap-2">
                    <input
                        type="text"
                        name="search"
                        class="form-control form-control-sm"
                        placeholder="Search students..."
                        value="<?= htmlspecialchars($search) ?>"
                        style="min-width: 220px;"
                    >
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Search</button>
                    <?php if ($search): ?>
                    <a href="index.php" class="btn btn-sm btn-outline-danger">Clear</a>
                    <?php endif; ?>
                </form>
                <!-- Add button -->
                <a href="add.php" class="btn btn-sm btn-primary">+ Add Student</a>
            </div>
        </div>

        <div class="card-body p-0">
            <?php if ($search): ?>
            <div class="px-3 pt-2 text-muted small">
                Showing results for: <strong><?= htmlspecialchars($search) ?></strong>
            </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Course</th>
                            <th>Year</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0):
                            $counter = 1;
                            while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $counter++ ?></td>
                            <td><?= htmlspecialchars($row['firstname']) ?></td>
                            <td><?= htmlspecialchars($row['lastname']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['course']) ?></td>
                            <td><?= htmlspecialchars($row['year']) ?></td>
                            <td class="text-center">
                                <a href="update.php?id=<?= $row['id'] ?>"
                                   class="btn btn-sm btn-warning me-1">Edit</a>
                                <a href="delete.php?id=<?= $row['id'] ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No students found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer text-muted small">
            Total records: <?= ($result ? $result->num_rows : 0) ?>
        </div>
    </div>
</div>
</main>

<footer class="bg-light border-top mt-4 py-3">
    <div class="container text-center text-muted small">
        &copy; <?= date('Y') ?> Student Management System
    </div>
</footer>

<!-- Bootstrap JS (bundle includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
