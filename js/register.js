$(function () {
  $('#registerForm').on('submit', function (event) {
    event.preventDefault();

    const name = $('#name').val().trim();
    const email = $('#email').val().trim();
    const password = $('#password').val().trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!name || !email || !password) {
      showAlert('Please fill all fields.', 'danger');
      return;
    }

    if (!emailPattern.test(email)) {
      showAlert('Invalid email format', 'danger');
      return;
    }

    if (password.length < 6) {
      showAlert('Password must be at least 6 characters', 'danger');
      return;
    }

    $.ajax({
      url: 'php/register.php',
      method: 'POST',
      dataType: 'json',
      data: { name, email, password },
      success(response) {
        if (response.success) {
          showAlert('Registration successful. Redirecting to login...', 'success');
          setTimeout(() => {
            window.location.href = 'login.html';
          }, 1200);
        } else {
          showAlert(response.error || 'Registration failed.', 'danger');
        }
      },
      error() {
        showAlert('Unable to register. Try again later.', 'danger');
      },
    });
  });

  function showAlert(message, type) {
    $('#alertPlaceholder').html(` <div class="alert alert-${type}" role="alert">${message}</div>`);
  }
});
