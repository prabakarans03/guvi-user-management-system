$(function () {
  $('#loginForm').on('submit', function (event) {
    event.preventDefault();

    const email = $('#email').val().trim();
    const password = $('#password').val().trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!email || !password) {
      showAlert('Email and password are required.', 'danger');
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
      url: 'php/login.php',
      method: 'POST',
      dataType: 'json',
      data: { email, password },
      success(response) {
        if (response.success && response.token) {
          localStorage.setItem('authToken', response.token);
          localStorage.setItem('userEmail', email);
          window.location.href = 'profile.html';
        } else {
          showAlert(response.error || 'Login failed.', 'danger');
        }
      },
      error() {
        showAlert('Unable to login. Try again later.', 'danger');
      },
    });
  });

  function showAlert(message, type) {
    $('#alertPlaceholder').html(` <div class="alert alert-${type}" role="alert">${message}</div>`);
  }
});
