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

$(document).ready(function () {
  $('#search-input').on('keyup', function (event) {
    if (event.keyCode === 13) {
      var searchQuery = $(this).val();
      $.get('table.php', {
        search: searchQuery,
        page: 1
      }, function (data) {
        $('#table-container').html(data);
      });
      $.get('./component/pagination.php', {
        search: searchQuery,
        page: 1
      }, function (data) {
        $('#app-pagination').html(data);
      });
    }
  });
});

function showDialog(button, additionalParam) {
  const dialogContainer = document.getElementById('dialogContainer');
  const rect = button.getBoundingClientRect();
  
  dialogContainer.style.display = 'block';
  dialogContainer.style.top = `${rect.top + window.scrollY}px`;
  dialogContainer.style.left = `${rect.left - dialogContainer.offsetWidth + window.scrollX}px`;
  dialogContainer.style.transform = 'none';

  console.log('Additional parameter:', additionalParam);
}

function hideDialog() {
  const dialogContainer = document.getElementById('dialogContainer');
  dialogContainer.style.display = 'none';
}

document.addEventListener('click', function (event) {
  const dialogContainer = document.getElementById('dialogContainer');
  const dialog = document.querySelector('.dialog');
  if (dialogContainer.style.display === 'block' && !dialog.contains(event.target) && !event.target.matches('button[onclick^="showDialog"]')) {
    hideDialog();
  }
});
