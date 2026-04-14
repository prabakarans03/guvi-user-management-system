$(function () {
  const authToken = localStorage.getItem('authToken');
  if (!authToken || authToken.length < 10) {
    redirectToLogin();
    return;
  }

  fetchProfile();

  $('#profileForm').on('submit', function (event) {
    event.preventDefault();
    updateProfile();
  });

  $('#logoutBtn').on('click', function () {
    const storedToken = localStorage.getItem('authToken');
    $.ajax({
      url: 'php/profile.php',
      method: 'POST',
      dataType: 'json',
      data: { token: storedToken, action: 'logout' },
      complete() {
        localStorage.removeItem('authToken');
        localStorage.removeItem('userEmail');
        window.location.href = 'login.html';
      },
    });
  });

  function fetchProfile() {
    $.ajax({
      url: 'php/profile.php',
      method: 'GET',
      dataType: 'json',
      data: { token: authToken },
      success(response) {
        if (response.success && response.profile) {
          $('#profileName').text(response.profile.name || '—');
          $('#profileEmail').text(response.profile.email || localStorage.getItem('userEmail') || '—');
          $('#age').val(response.profile.age || '');
          $('#dob').val(response.profile.dob || '');
          $('#contact').val(response.profile.contact || '');
          $('#address').val(response.profile.address || '');
        } else {
          redirectToLogin();
        }
      },
      error() {
        redirectToLogin();
      },
    });
  }

  function updateProfile() {
    const payload = {
      token: authToken,
      age: $('#age').val().trim(),
      dob: $('#dob').val(),
      contact: $('#contact').val().trim(),
      address: $('#address').val().trim(),
    };

    if (!payload.age && !payload.dob && !payload.contact && !payload.address) {
      showAlert('Nothing to update', 'warning');
      return;
    }

    $.ajax({
      url: 'php/profile.php',
      method: 'POST',
      dataType: 'json',
      data: payload,
      success(response) {
        if (response.success) {
          showAlert('Profile updated successfully.', 'success');
        } else {
          showAlert(response.error || 'Unable to update profile.', 'danger');
        }
      },
      error() {
        showAlert('Server error while updating profile.', 'danger');
      },
    });
  }

  function redirectToLogin() {
    localStorage.removeItem('authToken');
    localStorage.removeItem('userEmail');
    window.location.href = 'login.html';
  }

  function showAlert(message, type) {
    $('#alertPlaceholder').html(` <div class="alert alert-${type}" role="alert">${message}</div>`);
  }
});
