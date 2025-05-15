 <!-- AJAX for live checking of existing emails (inside the registration.php) (CODE STARTS HERE) -->
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

 <!-- AJAX for live checking of existing emails (should be pasted in the registration.php) (CODE ENDS HERE) -->

 <!-- SQL query that checks if a user is existing or not (should be pasted in the check_email.php) (CODE STARTS HERE) -->
<?php
 require_once '../classes/database.php';

header('Content-Type: application/json'); // Ensure JSON response

if (isset($_POST['email'])) {
    $email = $_POST['email']; 
    $con = new database();

    $db = $con->opencon();
    if (!$db) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }

    $query = $db->prepare("SELECT user_email FROM Users WHERE user_email = ?");
    $query->execute([$email]);
    $existingUser = $query->fetch();

    if ($existingUser) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
}

?>
 <!-- SQL query that checks if a user is existing or not (should be pasted in the check_email.php) (CODE ENDS HERE) -->