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

		// Initialize the display based on any checked input
		const checkedInput = ratingForm.querySelector(
			'input[name="rating"]:checked'
		);
		if (checkedInput) {
			highlightStars(parseInt(checkedInput.value));
		}

		// Add hover effects for better UX
		starLabels.forEach((label, index) => {
			label.addEventListener('mouseenter', function () {
				highlightStars(index + 1);
				updateRatingFeedback(index + 1);
			});

			label.addEventListener('mouseleave', function () {
				const checkedInput = ratingForm.querySelector(
					'input[name="rating"]:checked'
				);
				const checkedValue = checkedInput ? parseInt(checkedInput.value) : 0;
				highlightStars(checkedValue);
				updateRatingFeedback(checkedValue);
			});

			label.addEventListener('click', function () {
				const input = label.previousElementSibling;
				input.checked = true;
				const rating = parseInt(input.value);
				highlightStars(rating);
				updateRatingFeedback(rating);
			});
		});

		// Handle form submission
		ratingForm.addEventListener('submit', function (e) {
			e.preventDefault();
			submitRating(this);
		});

		// Initialize character count for review textarea
		initializeCharacterCount();
	}
}

function initializeCharacterCount() {
	const reviewTextarea = document.getElementById('review');
	const reviewCount = document.getElementById('review-count');

	if (reviewTextarea && reviewCount) {
		function updateCharCount() {
			const count = reviewTextarea.value.length;
			reviewCount.textContent = count;

			// Update color and form validation state
			if (count > 500) {
				reviewCount.style.color = '#f87171';
				reviewTextarea.classList.add('over-limit');
				updateSubmitButtonState();
			} else {
				reviewCount.style.color = '#6b7280';
				reviewTextarea.classList.remove('over-limit');
				updateSubmitButtonState();
			}
		}

		function updateSubmitButtonState() {
			const submitButton = document.querySelector(
				'.rating-form button[type="submit"]'
			);
			const count = reviewTextarea.value.length;

			if (submitButton) {
				if (count > 500) {
					submitButton.disabled = true;
					submitButton.style.opacity = '0.5';
					submitButton.style.cursor = 'not-allowed';
				} else {
					submitButton.disabled = false;
					submitButton.style.opacity = '1';
					submitButton.style.cursor = 'pointer';
				}
			}
		}

		reviewTextarea.addEventListener('input', updateCharCount);
		updateCharCount(); // Initial count

		// Prevent typing when at 500 characters (but allow deletion)
		reviewTextarea.addEventListener('keydown', function (e) {
			if (
				this.value.length >= 500 &&
				e.key !== 'Backspace' &&
				e.key !== 'Delete' &&
				e.key !== 'ArrowLeft' &&
				e.key !== 'ArrowRight' &&
				e.key !== 'ArrowUp' &&
				e.key !== 'ArrowDown' &&
				!e.ctrlKey && // Allow Ctrl+A, Ctrl+C, etc.
				!e.metaKey // Allow Cmd+A, Cmd+C, etc. on Mac
			) {
				e.preventDefault();
			}
		});

		// Prevent pasting text that would exceed the limit
		reviewTextarea.addEventListener('paste', function (e) {
			const pastedData = e.clipboardData.getData('text');
			const currentText = this.value;
			const selectionStart = this.selectionStart;
			const selectionEnd = this.selectionEnd;
			const newText =
				currentText.substring(0, selectionStart) +
				pastedData +
				currentText.substring(selectionEnd);

			if (newText.length > 500) {
				e.preventDefault();
				// Allow partial paste if it doesn't exceed the limit
				const availableSpace =
					500 - (currentText.length - (selectionEnd - selectionStart));
				if (availableSpace > 0) {
					const truncatedPaste = pastedData.substring(0, availableSpace);
					const beforeSelection = currentText.substring(0, selectionStart);
					const afterSelection = currentText.substring(selectionEnd);
					this.value = beforeSelection + truncatedPaste + afterSelection;
					this.setSelectionRange(
						selectionStart + truncatedPaste.length,
						selectionStart + truncatedPaste.length
					);
					updateCharCount();
				}
			}
		});
	}
}

function updateRatingFeedback(rating) {
	const feedbackElement = document.getElementById('rating-feedback');
	if (!feedbackElement) return;

	const feedbackTexts = {
		0: 'Click to rate',
		1: 'Poor',
		2: 'Fair',
		3: 'Good',
		4: 'Very Good',
		5: 'Excellent',
	};

	feedbackElement.textContent = feedbackTexts[rating] || 'Click to rate';
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

	// Validate character count before submitting
	const reviewTextarea = form.querySelector('#review');
	if (reviewTextarea && reviewTextarea.value.length > 500) {
		showMessage('Review text cannot exceed 500 characters.', 'error');
		return;
	}

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

// Make functions available globally
window.deleteRating = deleteRating;
window.initializeRatingForms = initializeRatingForms;
