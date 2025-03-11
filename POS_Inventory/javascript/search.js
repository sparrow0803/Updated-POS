document.getElementById("searchForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission
  
    const formData = new FormData(this);
  
    fetch('search.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      displaySearchResults(data);
    })
    .catch(error => {
      console.error('Error:', error);
    });
  });
  
  function displaySearchResults(results) {
    const searchResultsDiv = document.getElementById('searchResults');
    searchResultsDiv.innerHTML = ''; // Clear previous results
  
    if (results.length === 0) {
      searchResultsDiv.innerHTML = 'No results found.';
      return;
    }
  
    const ul = document.createElement('ul');
    results.forEach(result => {
      const li = document.createElement('li');
      li.textContent = result.item + ' - ' + result.stock;
      ul.appendChild(li);
    });
  
    searchResultsDiv.appendChild(ul);
  }