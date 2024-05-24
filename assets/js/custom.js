function logout() {
  fetch('../../logout.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    }
  })
  .then(response => {
    if (response.ok) {
      window.location.href = '../../login.php';
    } else {
      console.error('Logout failed');
    }
  })
  .catch(error => {
    console.error('Error:', error);
  });
}
