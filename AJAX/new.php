<script>
$(document).ready(function(){
    function toggleNextButton() {
        // Enable the button only if both email and username are valid
        const isEmailValid = $('#email').hasClass('is-valid');
        const isUsernameValid = $('#username').hasClass('is-valid');
        $('#nextButton').prop('disabled', !(isEmailValid && isUsernameValid));
    }

    // AJAX for checking email
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
                        $('#email').removeClass('is-valid').addClass('is-invalid');
                        $('#emailFeedback').text('Email is already taken.').show();
                        $('#email')[0].setCustomValidity('Email is already taken.');
                    } else {
                        $('#email').removeClass('is-invalid').addClass('is-valid');
                        $('#emailFeedback').text('').hide();
                        $('#email')[0].setCustomValidity('');
                    }
                    toggleNextButton(); // Check button state
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        } else {
            $('#email').removeClass('is-valid is-invalid');
            $('#emailFeedback').text('').hide();
            $('#email')[0].setCustomValidity('');
            toggleNextButton(); // Check button state
        }
    });

    // AJAX for checking username
    $('#username').on('input', function(){
        var username = $(this).val();
        if (username.length > 0) {
            $.ajax({
                url: 'AJAX/check_username.php',
                method: 'POST',
                data: { username: username },
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        $('#username').removeClass('is-valid').addClass('is-invalid');
                        $('#usernameFeedback').text('Username is already taken.').show();
                        $('#username')[0].setCustomValidity('Username is already taken.');
                    } else {
                        $('#username').removeClass('is-invalid').addClass('is-valid');
                        $('#usernameFeedback').text('').hide();
                        $('#username')[0].setCustomValidity('');
                    }
                    toggleNextButton(); // Check button state
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        } else {
            $('#username').removeClass('is-valid is-invalid');
            $('#usernameFeedback').text('').hide();
            $('#username')[0].setCustomValidity('');
            toggleNextButton(); // Check button state
        }
    });
});
</script>