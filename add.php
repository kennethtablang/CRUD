<?php
require_once 'db.php';

$errors = [];
$firstname = $lastname = $email = $course = $year = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($conn->real_escape_string($_POST['firstname']));
    $lastname  = trim($conn->real_escape_string($_POST['lastname']));
    $email     = trim($conn->real_escape_string($_POST['email']));
    $course    = trim($conn->real_escape_string($_POST['course']));
    $year      = intval($_POST['year']);

    // Validation
    if ($firstname === '') $errors[] = 'First name is required.';
    if ($lastname  === '') $errors[] = 'Last name is required.';
    if ($email     === '') $errors[] = 'Email is required.';
    elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.';
    if ($course    === '') $errors[] = 'Course is required.';
    if ($year < 1 || $year > 5) $errors[] = 'Year must be between 1 and 5.';

    if (empty($errors)) {
        $sql = "INSERT INTO students (firstname, lastname, email, course, year)
                VALUES ('$firstname', '$lastname', '$email', '$course', $year)";
        if ($conn->query($sql)) {
            header('Location: index.php?msg=added');
            exit;
        } else {
            $errors[] = 'Database error: ' . $conn->error;
        }
    }

    // Re-populate with raw POST data for redisplay
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname  = htmlspecialchars($_POST['lastname']);
    $email     = htmlspecialchars($_POST['email']);
    $course    = htmlspecialchars($_POST['course']);
    $year      = htmlspecialchars($_POST['year']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student | Student CRUD</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">&#127891; Student Management</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="add.php">Add Student</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Add New Student</h5>
                </div>
                <div class="card-body">

                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $err): ?>
                            <li><?= $err ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="add.php">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="firstname" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" id="firstname" name="firstname"
                                       class="form-control" value="<?= $firstname ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" id="lastname" name="lastname"
                                       class="form-control" value="<?= $lastname ?>" required>
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email"
                                       class="form-control" value="<?= $email ?>" required>
                            </div>

                            <div class="col-md-8">
                                <label for="course" class="form-label">Course <span class="text-danger">*</span></label>
                                <input type="text" id="course" name="course"
                                       class="form-control" value="<?= $course ?>"
                                       placeholder="e.g. BSCS, BSIT, BSECE" required>
                            </div>

                            <div class="col-md-4">
                                <label for="year" class="form-label">Year Level <span class="text-danger">*</span></label>
                                <select id="year" name="year" class="form-select" required>
                                    <option value="">-- Select --</option>
                                    <?php for ($y = 1; $y <= 5; $y++): ?>
                                    <option value="<?= $y ?>" <?= ($year == $y) ? 'selected' : '' ?>>
                                        Year <?= $y ?>
                                    </option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div class="col-12 d-flex gap-2 justify-content-end mt-2">
                                <a href="index.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Student</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
</main>

<footer class="bg-light border-top mt-4 py-3">
    <div class="container text-center text-muted small">
        &copy; <?= date('Y') ?> Student Management System
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
