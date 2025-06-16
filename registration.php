<?php

require_once('classes/database.php');
require_once('classes/functions.php'); 

$con = new database();
$sweetAlertConfig = ""; // Initialize SweetAlert script variable

if (isset($_POST['multisave'])) {

    // Getting the account information
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Hash the password

    // Getting the personal information
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $birthday = $_POST['birthday'];
    $sex = $_POST['sex'];  
    $phone = $_POST['phone']; 
    
    // Handle file upload for profile picture
    $profile_picture_path = handleFileUpload($_FILES["profile_picture"]);
    if ($profile_picture_path === false) {
        $_SESSION['error'] = "Sorry, there was an error uploading your file or the file is invalid.";
    } else {

        // Save the user data in the Users table
        $userID = $con->signupUser($firstname, $lastname, $birthday, $email, $sex, $phone, $username, $password, $profile_picture_path);



        if ($userID) {
            // Save the address information if user was successfully inserted
            $street = $_POST['user_street'];
            $barangay = $_POST['user_barangay'];
            $city = $_POST['user_city'];
            $province = $_POST['user_province'];

            if ($con->insertAddress($userID, $street, $barangay, $city, $province)) {
                // Address insertion successful, set SweetAlert script
                $sweetAlertConfig = "
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Registration Successful',
                            text: 'Your account has been created successfully!',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'login.php';
                            }
                        });
                    </script>";
            } else {
                $_SESSION['error'] = "Error occurred while inserting address. Please try again.";
            }
        } else {
            $_SESSION['error'] = "Sorry, there was an error signing up.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="./bootstrap-4.5.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <!-- JQuery for Address Selector -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
 <link rel="stylesheet" href="package/dist/sweetalert2.css">

  <title>LMS | Registration</title>
  <style>
    .form-step {
      display: none;
    }
    .form-step-active {
      display: block;
    }
  </style>
</head>
<body>

<script src="package/dist/sweetalert2.js"></script>
<?php
// Output SweetAlert script if set
if (!empty($sweetAlertConfig)) {
    echo $sweetAlertConfig;
    exit; // Stop further execution
}
?>
<div class="container custom-container rounded-3 shadow my-5 p-3 px-5">
  <h3 class="text-center mt-4">Registration Form</h3>
  <form method="post" action="" enctype="multipart/form-data" novalidate>
    <!-- Step 1 -->
    <div class="form-step form-step-active" id="step-1">
      <div class="card mt-4">
        <div class="card-header bg-info text-white">Account Information</div>
        <div class="card-body">

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" name="username" id="username" placeholder="Enter username" required>
            <div class="valid-feedback">Looks good!</div>
            <div class="invalid-feedback">Please enter a valid username.</div>
            <div id="usernameFeedback" class="invalid-feedback"></div> 
        </div>

          <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
            <div class="valid-feedback">Looks good!</div>
            <div class="invalid-feedback">Please enter a valid email.</div>
            <div id="emailFeedback" class="invalid-feedback"></div> <!-- Custom feedback for AJAX -->
          </div>

          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" placeholder="Enter password" required>
            <div class="valid-feedback">Looks good!</div>
            <div class="invalid-feedback">Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one special character.</div>
          </div>

          <div class="form-group">
            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" class="form-control" name="confirmPassword" placeholder="Re-enter your password" required>
            <div class="valid-feedback">Looks good!</div>
            <div class="invalid-feedback">Please confirm your password.</div>
          </div>
        </div>
      </div>
      <button type="button" id="nextButton" class="btn btn-primary mt-3" onclick="nextStep()">Next</button>
    </div>

    <!-- Step 2 -->
    <div class="form-step" id="step-2">
      <div class="card mt-4">
        <div class="card-header bg-info text-white">Personal Information</div>
        <div class="card-body">
          <div class="form-row">
            <div class="form-group col-md-6 col-sm-12">
              <label for="firstName">First Name:</label>
              <input type="text" class="form-control" name="firstname" placeholder="Enter first name" required>
              <div class="valid-feedback">Looks good!</div>
              <div class="invalid-feedback">Please enter a valid first name.</div>
            </div>
            <div class="form-group col-md-6 col-sm-12">
              <label for="lastName">Last Name:</label>
              <input type="text" class="form-control" name="lastname" placeholder="Enter last name" required>
              <div class="valid-feedback">Looks good!</div>
              <div class="invalid-feedback">Please enter a valid last name.</div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="birthday">Birthday:</label>
              <input type="date" class="form-control" name="birthday" id="birthday" required>
              <div class="valid-feedback">Great!</div>
              <div class="invalid-feedback">Please enter a valid birthday.</div>
            </div>
            <div class="form-group col-md-6">
              <label for="sex">Sex:</label>
              <select class="form-control" name="sex" required>
                <option selected disabled value="">Select Sex</option>
                <option>Male</option>
                <option>Female</option>
              </select>
              <div class="valid-feedback">Looks good.</div>
              <div class="invalid-feedback">Please select a sex.</div>
            </div>

              <!-- New Phone Number field -->
                <div class="form-group col-md-6">
        <label for="phone">Phone Number:</label>
        <input type="text" class="form-control" name="phone" placeholder="Enter phone number" required>
      </div>
      <!-- New Profile Picture field -->
      <div class="form-group col-md-6">
        <label for="profile_picture">Profile Picture:</label>
        <input type="file" class="form-control" name="profile_picture" accept="image/*" required>
      </div>
          </div>
        </div>
      </div>
      <button type="button" class="btn btn-secondary mt-3" onclick="prevStep()">Previous</button>
      <button type="button" class="btn btn-primary mt-3" onclick="nextStep()">Next</button>
    </div>

    <!-- Step 3 -->
    <div class="form-step" id="step-3">
      <div class="card mt-4">
        <div class="card-header bg-info text-white">Address Information</div>
        <div class="card-body">
          <div class="form-group">
            <label class="form-label">Region<span class="text-danger"> *</span></label>
            <select name="user_region" class="form-control form-control-md" id="region"></select>
            <input type="hidden" class="form-control form-control-md" name="user_province" id="region-text">
            <div class="valid-feedback">Looks good!</div>
            <div class="invalid-feedback">Please select a region.</div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label class="form-label">Province<span class="text-danger"> *</span></label>
              <select name="user_province" class="form-control form-control-md" id="province"></select>
              <input type="hidden" class="form-control form-control-md" name="user_province" id="province-text" required>
              <div class="valid-feedback">Looks good!</div>
              <div class="invalid-feedback">Please select your province.</div>
            </div>
            <div class="form-group col-md-6">
              <label class="form-label">City / Municipality<span class="text-danger"> *</span></label>
              <select name="user_city" class="form-control form-control-md" id="city"></select>
              <input type="hidden" class="form-control form-control-md" name="user_city" id="city-text" required>
              <div class="valid-feedback">Looks good!</div>
              <div class="invalid-feedback">Please select your city/municipality.</div>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Barangay<span class="text-danger"> *</span></label>
            <select name="user_barangay" class="form-control form-control-md" id="barangay"></select>
            <input type="hidden" class="form-control form-control-md" name="user_barangay" id="barangay-text" required>
            <div class="valid-feedback">Looks good!</div>
            <div class="invalid-feedback">Please select your barangay.</div>
          </div>
          <div class="form-group">
            <label class="form-label">Street <span class="text-danger"> *</span></label>
            <input type="text" class="form-control form-control-md" name="user_street" id="street-text" required>
            <div class="valid-feedback">Looks good!</div>
            <div class="invalid-feedback">Please select your street.</div>
          </div>
        </div>
      </div>
      <button type="button" class="btn btn-secondary mt-3" onclick="prevStep()">Previous</button>
      <button type="submit" name="multisave" class="btn btn-primary mt-3">Sign Up</button>
      <a class="btn btn-outline-danger mt-3" href="index.php">Go Back</a>
    </div>
  </form>
</div>
<script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
<!-- Script for Address Selector -->
<script src="ph-address-selector.js"></script>

<!-- Script for Form Validation -->
<script>
  
    document.addEventListener("DOMContentLoaded", () => {
      const form = document.querySelector("form");
      const birthdayInput = document.getElementById("birthday");
      const steps = document.querySelectorAll(".form-step");
      let currentStep = 0;
  
      // Set the max attribute of the birthday input to today's date
      const today = new Date().toISOString().split('T')[0];    
      birthdayInput.setAttribute('max', today);

      // Add event listeners for real-time validation
      const inputs = form.querySelectorAll("input, select");
      inputs.forEach(input => {
        input.addEventListener("input", () => validateInput(input));
        input.addEventListener("change", () => validateInput(input));
      });

      //MultiStep Logic 
  // Add an event listener to the form's submit event
  form.addEventListener("submit", (event) => {
  // Prevent form submission if the current step is not valid
  if (!validateStep(currentStep)) {
    event.preventDefault();
    event.stopPropagation();
  }

  // Add the 'was-validated' class to the form for Bootstrap styling
  form.classList.add("was-validated");
}, false);

// Function to move to the next step
window.nextStep = () => {
  // Only proceed to the next step if the current step is valid
  if (validateStep(currentStep)) {
    steps[currentStep].classList.remove("form-step-active"); // Hide the current step
    currentStep++; // Increment the current step index
    steps[currentStep].classList.add("form-step-active"); // Show the next step
  }
};

// Function to move to the previous step
window.prevStep = () => {
  steps[currentStep].classList.remove("form-step-active"); // Hide the current step
  currentStep--; // Decrement the current step index
  steps[currentStep].classList.add("form-step-active"); // Show the previous step
};

// Function to validate all inputs in the current step
function validateStep(step) {
  let valid = true;
  // Select all input and select elements in the current step
  const stepInputs = steps[step].querySelectorAll("input, select");

  // Validate each input element
  stepInputs.forEach(input => {
    if (!validateInput(input)) {
      valid = false; // If any input is invalid, set valid to false
    }
  });

  return valid; // Return the overall validity of the step
}

  
      function validateInput(input) {
        if (input.name === 'password') {
          return validatePassword(input);
        } else if (input.name === 'confirmPassword') {
          return validateConfirmPassword(input);
        } else {
          if (input.checkValidity()) {
            input.classList.remove("is-invalid");
            input.classList.add("is-valid");
            return true;
          } else {
            input.classList.remove("is-valid");
            input.classList.add("is-invalid");
            return false;
          }
        }
      }
  
      function validatePassword(passwordInput) {
        const password = passwordInput.value;
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        if (regex.test(password)) {
          passwordInput.classList.remove("is-invalid");
          passwordInput.classList.add("is-valid");
          return true;
        } else {
          passwordInput.classList.remove("is-valid");
          passwordInput.classList.add("is-invalid");
          return false;
        }
      }
  
      function validateConfirmPassword(confirmPasswordInput) {
        const passwordInput = form.querySelector("input[name='password']");
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
      
        if (password === confirmPassword && password !== '') {
          confirmPasswordInput.classList.remove("is-invalid");
          confirmPasswordInput.classList.add("is-valid");
          return true;
        } else {
          confirmPasswordInput.classList.remove("is-valid");
          confirmPasswordInput.classList.add("is-invalid");
          return false;
        }
      }
      
    });
  </script>
 <!-- AJAX for live checking of existing emails -->
 <script>
$(document).ready(function(){
    function toggleNextButton(isEnabled) {
        $('#nextButton').prop('disabled', !isEnabled);
    }

    $('#email').on('input', function(){
        var email = $(this).val();
        if (email.length > 0) {
            $.ajax({
                url: 'AJAX/check_email.php',
                method: 'POST',
                data: { email: email },
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        // Email is already taken
                        $('#email').removeClass('is-valid').addClass('is-invalid');
                        $('#emailFeedback').text('Email is already taken.').show();
                        $('#email')[0].setCustomValidity('Email is already taken.');
                        $('#email').siblings('.invalid-feedback').not('#emailFeedback').hide();
                        toggleNextButton(false); // ❌ Disable next button
                    } else {
                        // Email is valid and available
                        $('#email').removeClass('is-invalid').addClass('is-valid');
                        $('#emailFeedback').text('').hide();
                        $('#email')[0].setCustomValidity('');
                        $('#email').siblings('.valid-feedback').show();
                        toggleNextButton(true); // ✅ Enable next button
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        } else {
            // Empty input reset
            $('#email').removeClass('is-valid is-invalid');
            $('#emailFeedback').text('').hide();
            $('#email')[0].setCustomValidity('');
            toggleNextButton(false); // ❌ Disable next button
        }
    });

    $('#email').on('invalid', function() {
        if ($('#email')[0].validity.valueMissing) {
            $('#email')[0].setCustomValidity('Please enter a valid email.');
            $('#emailFeedback').hide();
            toggleNextButton(false); // ❌ Disable next button
        }
    });
});
</script>

<script>
  function toggleNextButton() {
        // Enable the button only if both email and username are valid
        const isEmailValid = $('#email').hasClass('is-valid');
        const isUsernameValid = $('#username').hasClass('is-valid');
        $('#nextButton').prop('disabled', !(isEmailValid && isUsernameValid));
    }
</script>

 <!-- AJAX for live checking of existing emails -->
 <script>
$(document).ready(function(){
    function toggleNextButton(isEnabled) {
        $('#nextButton').prop('disabled', !isEnabled);
    }

    $('#username').on('input', function(){
        var email = $(this).val();
        if (email.length > 0) {
            $.ajax({
                url: 'AJAX/check_username.php',
                method: 'POST',
                data: { username: username },
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        // Email is already taken
                        $('#username').removeClass('is-valid').addClass('is-invalid');
                        $('#usernameFeedback').text('Username is already taken.').show();
                        $('#username')[0].setCustomValidity('Username is already taken.');
                        $('#username').siblings('.invalid-feedback').not('#usernameFeedback').hide();
                        toggleNextButton(); // ❌ Disable next button
                    } else {
                        // Email is valid and available
                        $('#username').removeClass('is-invalid').addClass('is-valid');
                        $('#usernameFeedback').text('').hide();
                        $('#username')[0].setCustomValidity('');
                        $('#emausernameil').siblings('.valid-feedback').show();
                        toggleNextButton(); // ✅ Enable next button
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        } else {
            // Empty input reset
            $('#username').removeClass('is-valid is-invalid');
            $('#usernameFeedback').text('').hide();
            $('#username')[0].setCustomValidity('');
            toggleNextButton(); // ❌ Disable next button
        }
    });

    $('#username').on('invalid', function() {
        if ($('#username')[0].validity.valueMissing) {
            $('#username')[0].setCustomValidity('Please enter a valid email.');
            $('#usernameFeedback').hide();
            toggleNextButton(); // ❌ Disable next button
        }
    });
});
</script>




 
</body>
  </html>
