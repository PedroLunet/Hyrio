// JavaScript for handling favorite button functionality
document.addEventListener('DOMContentLoaded', function() {
  const favoriteBtn = document.getElementById('favoriteBtn');
  
  if (favoriteBtn) {
    // Check if this service was previously favorited
    const serviceId = new URLSearchParams(window.location.search).get('id');
    const favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
    
    // Set initial state based on localStorage
    if (favorites.includes(parseInt(serviceId))) {
      favoriteBtn.classList.add('active');
    }
    
    // Add click event listener
    favoriteBtn.addEventListener('click', function() {
      this.classList.toggle('active');
      
      // Update localStorage
      const serviceId = new URLSearchParams(window.location.search).get('id');
      let favorites = JSON.parse(localStorage.getItem('favorites') || '[]');
      
      if (this.classList.contains('active')) {
        // Add to favorites if not already included
        if (!favorites.includes(parseInt(serviceId))) {
          favorites.push(parseInt(serviceId));
        }
      } else {
        // Remove from favorites
        favorites = favorites.filter(id => id !== parseInt(serviceId));
      }
      
      localStorage.setItem('favorites', JSON.stringify(favorites));
    });
  }
});
