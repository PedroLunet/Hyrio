// JavaScript for handling favorite button functionality
document.addEventListener('DOMContentLoaded', function() {
  console.log('DOM loaded - favorite.js running');
  const favoriteBtn = document.getElementById('favoriteBtn');
  
  if (favoriteBtn) {
    console.log('Found favorite button');
    // Get service ID from URL
    const serviceId = new URLSearchParams(window.location.search).get('id');
    console.log('Service ID from URL:', serviceId);
    
    if (!serviceId) {
      console.error('No service ID found in the URL');
      return;
    }
    
    // Check if this service is favorited (server-side)
    checkFavoriteStatus(serviceId);
    
    // Add click event listener
    favoriteBtn.addEventListener('click', function(e) {
      // Prevent navigating away
      e.preventDefault();
      e.stopPropagation();
      
      console.log('Favorite button clicked');
      this.classList.toggle('active');
      
      const isActive = this.classList.contains('active');
      const action = isActive ? 'add' : 'remove';
      console.log('Action:', action);
      
      // Send request to server
      updateFavorite(serviceId, action);
    });
  } else {
    console.log('No favorite button found');
  }
});

// Function to check favorite status from server
function checkFavoriteStatus(serviceId) {
  console.log('Checking favorite status for service ID:', serviceId);
  fetch('/actions/favorite_action.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      serviceId: serviceId,
      action: 'check'
    })
  })
  .then(response => response.json())
  .then(data => {
    console.log('Favorite status check response:', data);
    if (data.success && data.isFavorite) {
      const favoriteBtn = document.getElementById('favoriteBtn');
      if (favoriteBtn) {
        favoriteBtn.classList.add('active');
      }
    }
  })
  .catch(error => {
    console.error('Error checking favorite status:', error);
  });
}

// Function to update favorite status on server
function updateFavorite(serviceId, action) {
  console.log('Updating favorite status:', serviceId, action);
  fetch('/actions/favorite_action.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      serviceId: serviceId,
      action: action
    })
  })
  .then(response => response.json())
  .then(data => {
    console.log('Update favorite response:', data);
    if (!data.success) {
      // If user is not logged in or another error occurred, show message
      if (data.error === 'User not logged in') {
        alert('Please log in to save favorites');
        // Revert the button state since the operation failed
        const favoriteBtn = document.getElementById('favoriteBtn');
        if (favoriteBtn) {
          favoriteBtn.classList.toggle('active');
        }
      } else {
        // Other errors
        alert('Error updating favorite: ' + (data.error || 'Unknown error'));
        // Revert the button state since the operation failed
        const favoriteBtn = document.getElementById('favoriteBtn');
        if (favoriteBtn) {
          favoriteBtn.classList.toggle('active');
        }
      }
    }
      
  })
  .catch(error => {
    console.error('Error updating favorite:', error);
    // Show error and revert button state
    alert('Network error when updating favorites. Please try again.');
    const favoriteBtn = document.getElementById('favoriteBtn');
    if (favoriteBtn) {
      favoriteBtn.classList.toggle('active');
    }
  });
}
