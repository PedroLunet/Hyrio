document.addEventListener('DOMContentLoaded', function() {
  const searchInputs = document.querySelectorAll('.search-input');
  
  searchInputs.forEach(input => {
    const suggestionsContainer = document.createElement('div');
    suggestionsContainer.className = 'search-suggestions';
    input.parentNode.appendChild(suggestionsContainer);
    
    let debounceTimer;
    input.addEventListener('input', function() {
      const query = this.value.trim();
      
      clearTimeout(debounceTimer);
      
      if (query.length < 2) {
        suggestionsContainer.innerHTML = '';
        suggestionsContainer.classList.remove('active');
        return;
      }
      
      debounceTimer = setTimeout(() => {
        fetchSuggestions(query, suggestionsContainer, input);
      }, 300);
    });
    
    document.addEventListener('click', function(event) {
      if (!input.contains(event.target) && !suggestionsContainer.contains(event.target)) {
        suggestionsContainer.classList.remove('active');
      }
    });
    
    input.addEventListener('keydown', function(event) {
      const suggestions = suggestionsContainer.querySelectorAll('.suggestion-item');
      if (!suggestions.length) return;
      
      let activeIndex = -1;
      suggestions.forEach((item, index) => {
        if (item.classList.contains('active')) {
          activeIndex = index;
        }
      });
      
      if (event.key === 'ArrowDown') {
        event.preventDefault();
        activeIndex = (activeIndex + 1) % suggestions.length;
        focusSuggestion(suggestions, activeIndex);
      }
      
      else if (event.key === 'ArrowUp') {
        event.preventDefault();
        activeIndex = activeIndex <= 0 ? suggestions.length - 1 : activeIndex - 1;
        focusSuggestion(suggestions, activeIndex);
      }
      
      else if (event.key === 'Enter' && activeIndex >= 0) {
        event.preventDefault();
        suggestions[activeIndex].click();
      }
    });
  });
});

function fetchSuggestions(query, container, input) {
  fetch(`/actions/search_suggestions_action.php?q=${encodeURIComponent(query)}`)
    .then(response => response.json())
    .then(data => {
      container.innerHTML = '';
      
      if (data.suggestions && data.suggestions.length > 0) {
        data.suggestions.forEach(suggestion => {
          const item = document.createElement('div');
          item.className = 'suggestion-item';
          if (suggestion.type === 'category') {
            item.classList.add('suggestion-category');
          }
          
          const iconClass = suggestion.type === 'category' ? 'fas fa-folder' : 'fas fa-tag';
          
          const textContainer = document.createElement('span');
          textContainer.className = 'suggestion-text';
          textContainer.innerHTML = highlightMatch(suggestion.text, query);
          
          item.innerHTML = `<i class="${iconClass}"></i>`;
          item.appendChild(textContainer);
          
          item.addEventListener('click', function() {
            if (suggestion.type === 'service') {
              window.location.href = `/pages/service.php?id=${suggestion.id}`;
            } else {
              window.location.href = `/pages/search.php?q=${encodeURIComponent(query)}&category=${suggestion.id}`;
            }
          });
          
          container.appendChild(item);
        });
        
        container.classList.add('active');
      } else {
        container.classList.remove('active');
      }
    })
    .catch(error => {
      console.error('Error fetching search suggestions:', error);
    });
}

function highlightMatch(text, query) {
  const cleanQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
  const regex = new RegExp(`(${cleanQuery})`, 'gi');
  
  return text.replace(regex, '<span class="highlight">$1</span>');
}

function focusSuggestion(suggestions, index) {
  suggestions.forEach((item, i) => {
    if (i === index) {
      item.classList.add('active');
    } else {
      item.classList.remove('active');
    }
  });
}
