const OverlaySystem = {
    open: function (overlayId) {
        const overlay = document.getElementById(overlayId);
        if (overlay) {
            overlay.style.display = 'block';
            document.body.style.overflow = 'hidden';

            overlay.offsetHeight;

            overlay.classList.add('active');
            const content = overlay.querySelector('.overlay-content');
            if (content) content.classList.add('active');
        }
    },

    close: function (overlayId) {
        const overlay = document.getElementById(overlayId);
        if (overlay) {
            overlay.classList.remove('active');
            const content = overlay.querySelector('.overlay-content');
            if (content) content.classList.remove('active');

            // Clear any error messages when the overlay is closed
            const errorMessages = overlay.querySelectorAll('.error-message');
            errorMessages.forEach(errorMsg => {
                errorMsg.style.display = 'none';
            });

            setTimeout(() => {
                overlay.style.display = 'none';
                document.body.style.overflow = 'auto';
            }, 300);
        }
    },

    init: function () {
        const overlays = document.querySelectorAll('.overlay');

        overlays.forEach(overlay => {
            overlay.addEventListener('click', function (event) {
                if (event.target === overlay) {
                    OverlaySystem.close(overlay.id);
                }
            });
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                overlays.forEach(overlay => {
                    if (overlay.style.display === 'block') {
                        OverlaySystem.close(overlay.id);
                    }
                });
            }
        });

        const closeButtons = document.querySelectorAll('.close-btn');
        closeButtons.forEach(button => {
            button.addEventListener('click', function () {
                const overlay = button.closest('.overlay');
                if (overlay) {
                    OverlaySystem.close(overlay.id);
                }
            });
        });

        const cancelButtons = document.querySelectorAll('.overlay [data-action="close"]');
        cancelButtons.forEach(button => {
            button.addEventListener('click', function () {
                const overlay = button.closest('.overlay');
                if (overlay) {
                    OverlaySystem.close(overlay.id);
                }
            });
        });
    }
};

document.addEventListener('DOMContentLoaded', function () {
    OverlaySystem.init();
});
