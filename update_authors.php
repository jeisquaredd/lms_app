<?php
require_once('classes/database.php');
$con = new database();
session_start();

// Redirect if ID is not present
if (!isset($_POST['id']) && !isset($_GET['id'])) {
    header('location:index.php');
    exit();
}

// Determine ID source
$id = $_POST['id'] ?? $_GET['id'];

// Get current author data
$data = $con->viewAuthorsID($id);

// Handle form submission
if (isset($_POST['update_author'])) {
    $id = $_POST['id'];
    $authorFirstName = $_POST['authorFirstName'];
    $authorLastName = $_POST['authorLastName'];
    $authorBirthYear = $_POST['authorBirthYear'];
    $authorNationality = $_POST['authorNationality'];

    if ($con->updateAuthor($id, $authorFirstName, $authorLastName, $authorBirthYear, $authorNationality)) {
        header('location:admin_homepage.php?status=author_updated');
        exit();
    } else {
        echo "<script>alert('Failed to update author.');</script>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <title>Update Author</title>
</head>
<body>
  <!-- NAVBAR (unchanged) -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="admin_homepage.php">Library Management System (Admin)</a>
      <a class="btn btn-outline-light ms-auto active" href="add_authors.html">Add Authors</a>
      <a class="btn btn-outline-light ms-2" href="add_genres.html">Add Genres</a>
      <a class="btn btn-outline-light ms-2" href="add_books.html">Add Books</a>
      <div class="dropdown ms-2">
        <button class="btn btn-outline-light dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <li><a class="dropdown-item" href="profile.html"><i class="bi bi-person-circle me-2"></i> See Profile Information</a></li>
          <li><button class="dropdown-item" onclick="updatePersonalInfo()"><i class="bi bi-pencil-square me-2"></i> Update Personal Info</button></li>
          <li><button class="dropdown-item" onclick="updatePassword()"><i class="bi bi-key me-2"></i> Update Password</button></li>
          <li><button class="dropdown-item text-danger" onclick="logout()"><i class="bi bi-box-arrow-right me-2"></i> Logout</button></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- FORM -->
  <div class="container my-5 border border-2 rounded-3 shadow p-4 bg-light">
    <h4 class="mb-4">Update Existing Author</h4>
    <form method="POST">
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['author_id']); ?>">
      <div class="mb-3">
        <label for="authorFirstName" class="form-label">First Name</label>
        <input type="text" name="authorFirstName" class="form-control" id="authorFirstName" value="<?php echo htmlspecialchars($data['author_FN']); ?>" required>
      </div>
      <div class="mb-3">
        <label for="authorLastName" class="form-label">Last Name</label>
        <input type="text" name="authorLastName" class="form-control" id="authorLastName" value="<?php echo htmlspecialchars($data['author_LN']); ?>" required>
      </div>
      <div class="mb-3">
        <label for="authorBirthYear" class="form-label">Birth Date</label>
        <input type="date" name="authorBirthYear" class="form-control" id="authorBirthYear"
          value="<?php echo isset($data['author_birthday']) ? date('Y-m-d', strtotime($data['author_birthday'])) : ''; ?>"
          max="<?= date('Y-m-d') ?>" required>
      </div>
      <div class="mb-3">
        <label for="authorNationality" class="form-label">Nationality</label>
        <select class="form-select" name="authorNationality" id="authorNationality" required>
          <?php
          $nationalities = ["Filipino", "American", "British", "Canadian", "Chinese", "French", "German", "Indian", "Japanese", "Mexican", "Russian", "South African", "Spanish", "Other"];
          foreach ($nationalities as $nat) {
              $selected = ($data['author_nat'] == $nat) ? 'selected' : '';
              echo "<option value=\"$nat\" $selected>$nat</option>";
          }
          ?>
        </select>
      </div>
      <button type="submit" name="update_author" class="btn btn-primary">Update Author</button>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="./bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>