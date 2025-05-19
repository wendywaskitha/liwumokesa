// resources/js/tourist-dashboard.js

// Handle foto preview
function previewPhoto(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Handle konfirmasi hapus
function confirmDelete(event, message = 'Apakah Anda yakin ingin menghapus item ini?') {
    if (!confirm(message)) {
        event.preventDefault();
        return false;
    }
    return true;
}

// Handle toggle wishlist
async function toggleWishlist(destinationId) {
    try {
        const response = await fetch(`/tourist/wishlist/toggle/${destinationId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            const button = document.querySelector(`#wishlist-btn-${destinationId}`);
            button.classList.toggle('active');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Initialize tooltips and popovers
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize Bootstrap popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Mobile sidebar toggle
    const sidebarToggle = document.querySelector('#sidebarToggle');
    const sidebar = document.querySelector('.sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', function(event) {
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Dynamic content loading
    function loadContent(url, targetId) {
        const target = document.getElementById(targetId);
        if (target) {
            target.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div></div>';
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    target.innerHTML = html;
                })
                .catch(error => {
                    target.innerHTML = '<div class="alert alert-danger">Error loading content</div>';
                });
        }
    }

    // Image preview before upload
    const imageInputs = document.querySelectorAll('input[type="file"][accept^="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const preview = document.querySelector(this.dataset.preview);
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => preview.src = e.target.result;
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // Infinite scroll for listings
    let loading = false;
    window.addEventListener('scroll', function() {
        const infiniteScroll = document.querySelector('.infinite-scroll');
        if (infiniteScroll && !loading) {
            const rect = infiniteScroll.getBoundingClientRect();
            if (rect.bottom <= window.innerHeight + 100) {
                loading = true;
                loadMoreContent();
            }
        }
    });

    function loadMoreContent() {
        // Implementation for loading more content
    }
});

// Export functions for use in other files
export {
    loadContent,
    confirmDelete,
    toggleWishlist
};
