<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

require_once('classes/database.php');
$con = new database();
$sweetAlertConfig = ""; 

// Fetch genres and authors for the form
$genres = $con->viewGenres(); 
$authors = $con->viewAuthors();

if (isset($_POST['add_book'])) {
    $title = $_POST['bookTitle'];
    $isbn = $_POST['bookISBN'];
    $pubyear = $_POST['bookYear'];
    $quantity = $_POST['bookQuantity'];
    $genre_ids = isset($_POST['bookGenres']) ? $_POST['bookGenres'] : [];
    $author_ids = isset($_POST['bookAuthors']) ? $_POST['bookAuthors'] : [];

    $result = $con->addBook($title, $isbn, $pubyear, $quantity, $genre_ids, $author_ids);

        if ($result) {

          $sweetAlertConfig =  "<script>
        Swal.fire({
        icon: 'success',
        title: 'Book has been  succesfully added',
        text: 'A new book has been added to the library.',
        confirmButtonText: 'Continue'
        }).then(() => {
        window.location.href = 'admin_homepage.php';
        });
        </script>";
           
        } else {
            $sweetAlertConfig = "<script>
            Swal.fire({
                icon: 'error',
                title: 'Somehing went wrong',
                text: 'Please try again.'
            });
        </script>";
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>Add Book</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Library Management System (Admin)</a>
      <a class="btn btn-outline-light ms-auto" href="add_authors.php">Add Authors</a>
      <a class="btn btn-outline-light ms-2" href="add_genres.php">Add Genres</a>
      <a class="btn btn-outline-light ms-2 active" href="add_books.php">Add Books</a>
      <div class="dropdown ms-2">
        <button class="btn btn-outline-light dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <li>
              <a class="dropdown-item" href="profile.php">
                  <i class="bi bi-person-circle me-2"></i> See Profile Information
              </a>
            </li>
          <li>
            <button class="dropdown-item" onclick="updatePersonalInfo()">
              <i class="bi bi-pencil-square me-2"></i> Update Personal Information
            </button>
          </li>
          <li>
            <button class="dropdown-item" onclick="updatePassword()">
              <i class="bi bi-key me-2"></i> Update Password
            </button>
          </li>
          <li>
            <button class="dropdown-item text-danger" onclick="logout()">
              <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>
<div class="container my-5 border border-2 rounded-3 shadow p-4 bg-light">

  <h4 class="mt-5">Add New Book</h4>
  <form method="POST">
    <div class="mb-3">
      <label for="bookTitle" class="form-label">Book Title</label>
      <input type="text" class="form-control" id="bookTitle" name="bookTitle" required>
    </div>
    <div class="mb-3">
      <label for="bookISBN" class="form-label">ISBN</label>
      <input type="text" class="form-control" id="bookISBN" name="bookISBN" required>
    </div>
    <div class="mb-3">
      <label for="bookYear" class="form-label">Publication Year</label>
      <input type="number" class="form-control" id="bookYear" name="bookYear" required>
    </div>

    <div class="mb-3">
      <label for="bookGenres" class="form-label">Genres</label>
      
      <select class="form-select" id="bookGenres" name="bookGenres[]" multiple required>
        <?php foreach ($genres as $genre): ?>
          <option value="<?php echo $genre['genre_id']; ?>"><?php echo htmlspecialchars($genre['genre_name']); ?></option>
        <?php endforeach; ?>
      </select>

      <small class="form-text text-muted">Hold down the Ctrl (Windows) or Command (Mac) key to select multiple genres.</small>
    </div>

    <div class="mb-3">
      <label for="bookAuthors" class="form-label">Authors</label>

      <select class="form-select" id="bookAuthors" name="bookAuthors[]" multiple required>
        <?php foreach ($authors as $author): ?>
          <option value="<?php echo $author['author_id']; ?>">
            <?php echo htmlspecialchars($author['author_FN'] . ' ' . $author['author_LN']); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <small class="form-text text-muted">Hold down the Ctrl (Windows) or Command (Mac) key to select multiple authors.</small>
    </div>
    
    <div class="mb-3">
      <label for="bookQuantity" class="form-label">Quantity Available</label>
      <input type="number" class="form-control" id="bookQuantity" name="bookQuantity" required>
    </div>

    <button type="submit" name="add_book" class="btn btn-primary">Add Book</button>
    <a class="btn btn-danger" href="admin_homepage.php">Cancel</a>
    <?php echo $sweetAlertConfig ?>
  </form>
</div>
<script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>