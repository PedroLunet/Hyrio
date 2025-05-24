document.addEventListener('DOMContentLoaded', function () {
  const searchInputs = document.querySelectorAll('.search-input');

  searchInputs.forEach(input => {
    const suggestionsContainer = document.createElement('div');
    suggestionsContainer.className = 'search-suggestions';
    input.parentNode.appendChild(suggestionsContainer);

    const searchForm = input.closest('form');

    let debounceTimer;
    input.addEventListener('input', function () {
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

    document.addEventListener('click', function (event) {
      if (!input.contains(event.target) && !suggestionsContainer.contains(event.target)) {
        suggestionsContainer.classList.remove('active');
      }
    });

    input.addEventListener('keydown', function (event) {
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
        const serviceSuggestions = data.suggestions.filter(s => s.type === 'service');
        const userSuggestions = data.suggestions.filter(s => s.type === 'user');
        const categorySuggestions = data.suggestions.filter(s => s.type === 'category');

        if (serviceSuggestions.length > 0) {
          const sectionHeader = document.createElement('div');
          sectionHeader.className = 'suggestion-header';
          sectionHeader.textContent = 'Services';
          container.appendChild(sectionHeader);

          serviceSuggestions.forEach(suggestion => {
            addSuggestionItem(container, suggestion, query);
          });

          addSeeMoreLink(container, query, 'services');
        }

        if (userSuggestions.length > 0) {
          const sectionHeader = document.createElement('div');
          sectionHeader.className = 'suggestion-header';
          sectionHeader.textContent = 'Users';
          container.appendChild(sectionHeader);

          userSuggestions.forEach(suggestion => {
            addSuggestionItem(container, suggestion, query);
          });

          addSeeMoreLink(container, query, 'users');
        }

        if (categorySuggestions.length > 0) {
          const sectionHeader = document.createElement('div');
          sectionHeader.className = 'suggestion-header';
          sectionHeader.textContent = 'Categories';
          container.appendChild(sectionHeader);

          categorySuggestions.forEach(suggestion => {
            addSuggestionItem(container, suggestion, query);
          });
        }

        container.classList.add('active');
      } else {
        container.classList.remove('active');
      }
    })
    .catch(error => {
      console.error('Error fetching search suggestions:', error);
    });
}

function addSuggestionItem(container, suggestion, query) {
  const item = document.createElement('div');
  item.className = 'suggestion-item';
  if (suggestion.type === 'category') {
    item.classList.add('suggestion-category');
  }

  let iconClass;
  switch (suggestion.type) {
    case 'category':
      iconClass = 'fas fa-folder';
      break;
    case 'user':
      iconClass = 'fas fa-user';
      break;
    case 'service':
    default:
      iconClass = 'fas fa-tag';
      break;
  }

  const textContainer = document.createElement('span');
  textContainer.className = 'suggestion-text';

  if (suggestion.type === 'user' && suggestion.username) {
    textContainer.innerHTML = `${highlightMatch(suggestion.text, query)} <span class="username">@${suggestion.username}</span>`;
  } else {
    textContainer.innerHTML = highlightMatch(suggestion.text, query);
  }

  item.innerHTML = `<i class="${iconClass}"></i>`;
  item.appendChild(textContainer);

  item.addEventListener('click', function () {
    if (suggestion.type === 'service') {
      window.location.href = `/pages/service.php?id=${suggestion.id}`;
    } else if (suggestion.type === 'user') {
      window.location.href = `/pages/profile.php?username=${suggestion.username}`;
    } else {
      window.location.href = `/pages/search.php?category=${suggestion.id}`;
    }
  });

  container.appendChild(item);
}

function addSeeMoreLink(container, query, mode) {
  const seeMoreItem = document.createElement('div');
  seeMoreItem.className = 'suggestion-item see-more';

  const iconClass = 'fas fa-search';

  const textContainer = document.createElement('span');
  textContainer.className = 'suggestion-text';
  textContainer.textContent = `See all ${mode} for "${query}"`;

  seeMoreItem.innerHTML = `<i class="${iconClass}"></i>`;
  seeMoreItem.appendChild(textContainer);

  seeMoreItem.addEventListener('click', function () {
    window.location.href = `/pages/search.php?q=${encodeURIComponent(query)}&mode=${mode}`;
  });

  container.appendChild(seeMoreItem);
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
