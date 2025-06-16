<?php

require_once('classes/database.php');
require_once('classes/functions.php'); 

$con = new database();
$sweetAlertConfig = ""; // Initialize SweetAlert script variable

if (isset($_POST['multisave'])) {

    // Getting the account information
    $email = $_POST['email'];
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
        $userID = $con->signupUser($firstname, $lastname, $birthday, $email, $sex, $phone, $password, $profile_picture_path);


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
                                window.location.href = 'index.php';
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