// Rating functionality for service pages
document.addEventListener('DOMContentLoaded', function () {
	initializeRatingForms();
	initializeRatingDisplay();
});

function initializeRatingForms() {
	const ratingForm = document.querySelector('.rating-form');
	if (ratingForm) {
		// Handle star rating input
		const starInputs = ratingForm.querySelectorAll('input[name="rating"]');
		const starLabels = ratingForm.querySelectorAll('.star-label');

		// Add hover effects for better UX
		starLabels.forEach((label, index) => {
			label.addEventListener('mouseenter', function () {
				highlightStars(index + 1);
			});

			label.addEventListener('mouseleave', function () {
				const checkedInput = ratingForm.querySelector(
					'input[name="rating"]:checked'
				);
				const checkedValue = checkedInput ? parseInt(checkedInput.value) : 0;
				highlightStars(checkedValue);
			});

			label.addEventListener('click', function () {
				const input = label.previousElementSibling;
				input.checked = true;
				highlightStars(parseInt(input.value));
			});
		});

		// Handle form submission
		ratingForm.addEventListener('submit', function (e) {
			e.preventDefault();
			submitRating(this);
		});
	}
}

function highlightStars(count) {
	// Only target star labels within the current rating form, not all stars on the page
	const ratingForm = document.querySelector('.rating-form');
	if (!ratingForm) return;

	const starLabels = ratingForm.querySelectorAll('.star-label');
	starLabels.forEach((label, index) => {
		const star = label.querySelector('i');
		if (index < count) {
			star.style.color = '#fbbf24';
		} else {
			star.style.color = '#d1d5db';
		}
	});
}

function submitRating(form) {
	const formData = new FormData(form);
	const submitButton = form.querySelector('button[type="submit"]');
	const originalText = submitButton.textContent;

	// Disable button and show loading state
	submitButton.disabled = true;
	submitButton.textContent = 'Submitting...';

	fetch('/actions/rating_action.php', {
		method: 'POST',
		body: formData,
		headers: {
			'X-Requested-With': 'XMLHttpRequest',
		},
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				showMessage(data.message, 'success');

				// Update rating display if stats are provided
				if (data.stats) {
					updateRatingDisplay(data.stats);
				}

				// Refresh the page to show updated ratings
				setTimeout(() => {
					window.location.reload();
				}, 1500);
			} else {
				showMessage(data.message, 'error');
			}
		})
		.catch((error) => {
			console.error('Error submitting rating:', error);
			showMessage(
				'An error occurred while submitting your rating. Please try again.',
				'error'
			);
		})
		.finally(() => {
			// Re-enable button
			submitButton.disabled = false;
			submitButton.textContent = originalText;
		});
}

function deleteRating(serviceId) {
	if (
		!confirm(
			'Are you sure you want to delete your rating? This action cannot be undone.'
		)
	) {
		return;
	}

	const formData = new FormData();
	formData.append('service_id', serviceId);
	formData.append('action', 'delete');

	fetch('/actions/rating_action.php', {
		method: 'POST',
		body: formData,
		headers: {
			'X-Requested-With': 'XMLHttpRequest',
		},
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				showMessage(data.message, 'success');

				// Update rating display if stats are provided
				if (data.stats) {
					updateRatingDisplay(data.stats);
				}

				// Refresh the page to show updated state
				setTimeout(() => {
					window.location.reload();
				}, 1500);
			} else {
				showMessage(data.message, 'error');
			}
		})
		.catch((error) => {
			console.error('Error deleting rating:', error);
			showMessage(
				'An error occurred while deleting your rating. Please try again.',
				'error'
			);
		});
}

function updateRatingDisplay(stats) {
	// Update average rating if displayed
	const ratingValue = document.querySelector('.rating-value');
	if (ratingValue && stats.average_rating) {
		ratingValue.textContent = parseFloat(stats.average_rating).toFixed(1);
	}

	// Update rating count
	const ratingText = document.querySelector('.rating-text');
	if (ratingText && stats.total_ratings !== undefined) {
		const averageRating = parseFloat(stats.average_rating || 0).toFixed(1);
		const totalRatings = parseInt(stats.total_ratings);
		ratingText.textContent = `${averageRating} (${totalRatings} rating${
			totalRatings !== 1 ? 's' : ''
		})`;
	}
}

function initializeRatingDisplay() {
	// Add click handlers for rating toggles
	const rateButtons = document.querySelectorAll('.rate-service-btn');
	rateButtons.forEach((button) => {
		button.addEventListener('click', function () {
			const ratingForm = document.querySelector('.rating-form-container');
			if (ratingForm) {
				ratingForm.scrollIntoView({ behavior: 'smooth' });
			}
		});
	});
}

function showMessage(message, type = 'info') {
	// Create message element
	const messageDiv = document.createElement('div');
	messageDiv.className = `message message-${type}`;
	messageDiv.textContent = message;

	// Style the message
	messageDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 24px;
        border-radius: 4px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        max-width: 400px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: opacity 0.3s ease;
    `;

	// Set background color based on type
	switch (type) {
		case 'success':
			messageDiv.style.backgroundColor = '#10b981';
			break;
		case 'error':
			messageDiv.style.backgroundColor = '#ef4444';
			break;
		case 'warning':
			messageDiv.style.backgroundColor = '#f59e0b';
			break;
		default:
			messageDiv.style.backgroundColor = '#3b82f6';
	}

	// Add to page
	document.body.appendChild(messageDiv);

	// Auto-remove after 5 seconds
	setTimeout(() => {
		messageDiv.style.opacity = '0';
		setTimeout(() => {
			if (messageDiv.parentNode) {
				messageDiv.parentNode.removeChild(messageDiv);
			}
		}, 300);
	}, 5000);
}

// Make deleteRating function available globally
window.deleteRating = deleteRating;
