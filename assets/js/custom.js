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

$(document).ready(function() {
    $('#search-input').on('keyup', function(event) {
        if (event.keyCode === 13) {
            var searchQuery = $(this).val();
            $.get('table.php', { search: searchQuery, page: 1 }, function(data) {
                $('#table-container').html(data);
            });
            $.get('./component/pagination.php', { search: searchQuery, page: 1 }, function(data) {
                $('#app-pagination').html(data);
            });
        }
    });
});
