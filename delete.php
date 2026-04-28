<?php
require_once 'db.php';

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = intval($_GET['id']);

// Check student exists
$checkSql    = "SELECT id FROM students WHERE id = $id LIMIT 1";
$checkResult = $conn->query($checkSql);

if (!$checkResult || $checkResult->num_rows === 0) {
    header('Location: index.php?msg=error');
    exit;
}

// Delete the record
$deleteSql = "DELETE FROM students WHERE id = $id";
if ($conn->query($deleteSql)) {
    header('Location: index.php?msg=deleted');
} else {
    header('Location: index.php?msg=error');
}

$conn->close();
exit;
?>
