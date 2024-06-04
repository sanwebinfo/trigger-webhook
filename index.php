<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);

header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000');
header('X-Robots-Tag: noindex, nofollow', true);

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: auth.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="color-scheme" content="light only">
  <meta name="theme-color" content="#c7ecee">
  <link rel="shortcut icon" href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABqklEQVQ4jZ2Tv0scURDHP7P7SGWh14mkuXJZEH8cgqUWcklAsLBbCEEJSprkD7hD/4BUISHEkMBBiivs5LhCwRQBuWgQji2vT7NeYeF7GxwLd7nl4knMwMDMfL8z876P94TMLt+8D0U0EggQSsAjwMvga8ChJAqxqjTG3m53AQTg4tXHDRH9ABj+zf6oytbEu5d78nvzcyiivx7QXBwy46XOi5z1jbM+Be+nqVfP8yzuD3FM6rzIs9YE1hqGvDf15cVunmdx7w5eYJw1pcGptC9CD4gBUuef5Ujq/BhAlTLIeFYuyfmTZgeYv+2nPt1a371P+Hm1WUPYydKf0lnePwVmh3hnlcO1uc7yvgJUDtdG8oy98kduK2KjeHI0fzCQINSXOk/vlXBUOaihAwnGWd8V5r1uhe1VIK52V6JW2D4FqHZX5lphuwEE7ooyaN7gjLMmKSwYL+pMnV+MA/6+g8RYa2Lg2RBQbj4+rll7uymLy3coiuXb5PdQVf7rKYvojAB8Lf3YUJUHfSYR3XqeLO5JXvk0dhKqSqQQoCO+s5AIxCLa2Lxc6ALcAPwS26XFskWbAAAAAElFTkSuQmCC" />
  
  <title>Trigger n8n URLs</title>
  <meta name="description" content="Trigger n8n URLs: List all n8n webhook URLS with Name and Trigger Buttons."/>

  <link rel="preconnect" href="https://cdnjs.cloudflare.com">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css" integrity="sha512-IgmDkwzs96t4SrChW29No3NXBIBv8baW490zk5aXvhCD8vuZM3yUSkbyTBcXohkySecyzIrUwiF/qV0cuPcL3Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">

  <style>
    html, body {
        min-height: 100vh;
    }
    body {
        font-family: "Roboto Mono", monospace;
        background-color: #fcf837;
        padding-bottom: 20px;
        font-weight: 600;
        line-height: 1.6;
        word-wrap: break-word;
        -moz-osx-font-smoothing: grayscale;
        -webkit-font-smoothing: antialiased !important;
        -moz-font-smoothing: antialiased !important;
        text-rendering: optimizeLegibility !important;
    }
    .is-loading .button {
      pointer-events: none;
    }
    .empty-message {
      font-family: "Roboto Mono", monospace;
      text-align: center;
      font-size: 1.2em;
      color: black;
    }
    input {
      font-family: "Roboto Mono", monospace;
    }
    button {
      font-family: "Roboto Mono", monospace;
      font-weight: 700;
    }
    table {
      font-family: "Roboto Mono", monospace;
      font-weight: 700;
    }
    .model {
      font-family: "Roboto Mono", monospace;
    }
    .btn-box {
        font-weight: 600;
        font-size: 14px;
        font-family: "Roboto Mono", monospace;
        text-transform: uppercase;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        border-radius: 32px;
        padding: 10px 20px;
        -moz-osx-font-smoothing: grayscale;
        -webkit-font-smoothing: antialiased !important;
        -moz-font-smoothing: antialiased !important;
        text-rendering: optimizelegibility !important;
    }
    .table-container {
      overflow: hidden;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1), 0 2px 10px rgba(0, 0, 0, 0.06);
      margin-top: 20px;
    }
    .table {
      border-radius: 8px;
      overflow: hidden;
      border-spacing: 0;
      width: 100%;
    }
    .table th,
    .table td {
      padding: 16px;
      text-align: left;
      border-bottom: 1px solid #e0e0e0;
    }
    .table thead {
      background-color: #f5f5f5;
    }
    .table tbody tr {
      background-color: #fff;
      transition: background-color 0.3s;
    }
    .table tbody tr:hover {
      background-color: #f1f1f1;
    }
    .pagination button {
      border: none;
      background-color: #fff;
      color: #007bff;
      cursor: pointer;
      transition: background-color 0.3s, color 0.3s;
    }
    .pagination button:hover {
      background-color: #007bff;
      color: #fff;
    }
    .pagination button:disabled {
      color: #ccc;
      cursor: not-allowed;
    }
  </style>
</head>
<body>
  <section class="section">
    <div class="container">
      <h1 class="title is-size-5">🎛 n8n Trigger URLs</h1>
      <br>
      <div class="field">
        <label class="label">🔎 Search Actions</label>
        <div class="control">
          <input class="input is-rounded" type="text" id="searchInput" placeholder="Search by name">
        </div>
      </div>
      <div class="table-container">
      <table class="table is-fullwidth is-striped is-hoverable">
        <thead>
          <tr>
            <th>Name</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="urlTableBody">
        </tbody>
      </table>
      </div>
      <p id="emptyMessage" class="empty-message" style="display: none;">No actions found</p>
      <br>
      <nav class="pagination is-centered" role="navigation" aria-label="pagination">
        <button class="pagination-previous button is-link is-rounded btn-box" id="prevPage">Previous</button>
        <button class="pagination-next button is-link is-rounded btn-box" id="nextPage">Next</button>
      </nav>
      <br>
    </div>
  </section>

  <div class="modal" id="notificationModal">
    <div class="modal-background"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Notification</p>
        <button class="delete" aria-label="close"></button>
      </header>
      <section class="modal-card-body">
        <p id="modalMessage"></p>
      </section>
      <footer class="modal-card-foot">
        <button class="button is-success" id="modalClose">OK</button>
      </footer>
    </div>
  </div>

<script>

    document.addEventListener('DOMContentLoaded', async () => {
      const urlTableBody = document.getElementById('urlTableBody');
      const searchInput = document.getElementById('searchInput');
      const emptyMessage = document.getElementById('emptyMessage');
      const modal = document.getElementById('notificationModal');
      const modalMessage = document.getElementById('modalMessage');
      const modalClose = document.getElementById('modalClose');
      const modalDelete = modal.querySelector('.delete');
      const prevPageButton = document.getElementById('prevPage');
      const nextPageButton = document.getElementById('nextPage');
      const itemsPerPage = 5;
      let currentPage = 1;
      let urlData = [];
      let filteredData = [];

      async function fetchUrlData() {
        try {
          const response = await fetch('/api/url.php');
          if (!response.ok) throw new Error('Network response was not ok');
          return await response.json();
        } catch (error) {
          showModal(`Error! ${error.message}`);
          return [];
        }
      }

      function createTableRows(data) {
        urlTableBody.innerHTML = '';
        data.forEach(item => {
          const row = document.createElement('tr');
          const nameCell = document.createElement('td');
          nameCell.textContent = '➡ ' + item.name;
          const actionCell = document.createElement('td');
          const button = document.createElement('button');
          button.classList.add('button', 'is-primary');
          button.classList.add('button', 'btn-box');
          button.classList.add('button', 'is-rounded');
          button.textContent = '▶ Trigger';
          button.setAttribute('data-url', item.url);
          button.addEventListener('click', handleButtonClick);
          actionCell.appendChild(button);
          row.appendChild(nameCell);
          row.appendChild(actionCell);
          urlTableBody.appendChild(row);
        });
      }

      async function handleButtonClick(event) {
        const button = event.target;
        const url = button.getAttribute('data-url');
        if (!url) {
          showModal('Error! Invalid URL.');
          return;
        }
        button.classList.add('is-loading');

        try {
          await new Promise(resolve => setTimeout(resolve, 2000));
          const response = await fetch(url);
          if (response.ok) {
            showModal('Success! URL triggered successfully.');
          } else {
            showModal('Error! Failed to trigger the URL.');
          }
        } catch (error) {
          showModal(`Error! ${error.message}`);
        } finally {
          button.classList.remove('is-loading');
        }
      }

      function showModal(message) {
        modalMessage.textContent = message;
        modal.classList.add('is-active');
        setTimeout(closeModal, 3000);
      }

      function closeModal() {
        modal.classList.remove('is-active');
      }

      function displayPage(page, data) {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const pageData = data.slice(start, end);
        createTableRows(pageData);
        updatePaginationButtons(page, data.length);
      }

      function updatePaginationButtons(page, dataLength) {
        prevPageButton.disabled = page === 1;
        nextPageButton.disabled = page * itemsPerPage >= dataLength;
      }

      prevPageButton.addEventListener('click', () => {
        if (currentPage > 1) {
          currentPage--;
          displayPage(currentPage, filteredData);
        }
      });

      nextPageButton.addEventListener('click', () => {
        if (currentPage * itemsPerPage < filteredData.length) {
          currentPage++;
          displayPage(currentPage, filteredData);
        }
      });

      function filterData() {
        const query = searchInput.value.toLowerCase();
        filteredData = urlData.filter(item => item.name.toLowerCase().includes(query));
        if (filteredData.length === 0) {
          urlTableBody.innerHTML = '';
          emptyMessage.style.display = 'block';
        } else {
          emptyMessage.style.display = 'none';
          currentPage = 1;
          displayPage(currentPage, filteredData);
        }
        updatePaginationButtons(currentPage, filteredData.length);
      }

      searchInput.addEventListener('input', filterData);

      urlData = await fetchUrlData();
      filteredData = urlData;
      if (urlData.length === 0) {
        prevPageButton.disabled = true;
        nextPageButton.disabled = true;
      } else {
        displayPage(currentPage, filteredData);
      }

      modalClose.addEventListener('click', closeModal);
      modalDelete.addEventListener('click', closeModal);
    });

</script>

</body>
</html>