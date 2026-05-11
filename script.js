// Navigation Toggle
const burger = document.querySelector('.burger');
const nav = document.querySelector('.nav-links');
const navLinks = document.querySelectorAll('.nav-links li');

burger.addEventListener('click', () => {
    // Toggle Nav
    nav.classList.toggle('nav-active');
    
    // Animate Links
    navLinks.forEach((link, index) => {
        if (link.style.animation) {
            link.style.animation = '';
        } else {
            link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.3}s`;
        }
    });
    
    // Burger Animation
    burger.classList.toggle('toggle');
});

// Smooth Scrolling for Navigation Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
            
            // Close mobile menu if open
            if (nav.classList.contains('nav-active')) {
                nav.classList.remove('nav-active');
                burger.classList.remove('toggle');
            }
        }
    });
});

// Form Submission with Enhanced Validation
const contactForm = document.querySelector('.contact-form form');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(this);
        const inputs = this.querySelectorAll('input, textarea');
        let isValid = true;
        
        // Enhanced validation
        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                showError(input, 'Field ini harus diisi');
            } else if (input.type === 'email' && !isValidEmail(input.value)) {
                isValid = false;
                showError(input, 'Format email tidak valid');
            } else {
                clearError(input);
            }
        });
        
        if (isValid) {
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            submitBtn.disabled = true;
            
            // Simulate form submission
            setTimeout(() => {
                showNotification('Pesan Anda telah berhasil dikirim!', 'success');
                this.reset();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        } else {
            showNotification('Harap perbaiki kesalahan di form.', 'error');
        }
    });
}

// Email validation function
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Show error message
function showError(input, message) {
    clearError(input);
    input.style.borderColor = 'var(--secondary-color)';
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.cssText = `
        color: var(--secondary-color);
        font-size: 0.8rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    `;
    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    
    input.parentNode.appendChild(errorDiv);
}

// Clear error message
function clearError(input) {
    input.style.borderColor = '';
    const errorDiv = input.parentNode.querySelector('.error-message');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'var(--success-color)' : 
                    type === 'error' ? 'var(--secondary-color)' : 'var(--primary-color)';
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${bgColor};
        color: white;
        padding: 1rem 2rem;
        border-radius: 10px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        z-index: 10000;
        animation: slideInRight 0.3s ease;
        max-width: 400px;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        font-weight: 500;
    `;
    
    const icon = type === 'success' ? 'fa-check-circle' : 
                type === 'error' ? 'fa-exclamation-triangle' : 'fa-info-circle';
    
    notification.innerHTML = `<i class="fas ${icon}"></i> ${message}`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 4000);
}

// Add animation on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in');
        }
    });
}, observerOptions);

// Observe sections for animation
document.querySelectorAll('section').forEach(section => {
    observer.observe(section);
});

// Profile Image Handler with Enhanced Features
function initProfileImage() {
    const profileImage = document.querySelector('.profile-image');
    const imageContainer = document.querySelector('.image-container');
    
    if (!profileImage || !imageContainer) return;
    
    // Preload image with better error handling
    const img = new Image();
    img.src = profileImage.src;
    
    img.onload = function() {
        console.log('Profile image loaded successfully');
        // Image exists, ensure fallback is hidden
        imageContainer.classList.remove('no-image');
        
        // Add loaded class for additional styling options
        profileImage.classList.add('loaded');
        
        // Optional: Add subtle animation when image loads
        setTimeout(() => {
            profileImage.style.opacity = '1';
            profileImage.style.transform = 'scale(1)';
        }, 100);
    };
    
    img.onerror = function() {
        console.log('Profile image failed to load, showing fallback');
        // Image doesn't exist, show fallback
        imageContainer.classList.add('no-image');
        
        // Try alternative image names
        tryAlternativeImages();
    };
    
    // Handle direct image error as well
    profileImage.addEventListener('error', function() {
        console.log('Direct image error detected');
        imageContainer.classList.add('no-image');
        tryAlternativeImages();
    });
    
    // Add hover effects
    imageContainer.addEventListener('mouseenter', function() {
        if (!imageContainer.classList.contains('no-image')) {
            this.style.transform = 'scale(1.05)';
        }
    });
    
    imageContainer.addEventListener('mouseleave', function() {
        if (!imageContainer.classList.contains('no-image')) {
            this.style.transform = 'scale(1)';
        }
    });
    
    // Click to enlarge feature (optional)
    imageContainer.addEventListener('click', function() {
        if (!imageContainer.classList.contains('no-image')) {
            createImageModal(profileImage.src);
        }
    });
}

// Try alternative image names if primary fails
function tryAlternativeImages() {
    const imageContainer = document.querySelector('.image-container');
    const profileImage = document.querySelector('.profile-image');
    
    if (!profileImage) return;
    
    const alternativeNames = [
        'profile.jpg',
        'profile.jpeg',
        'profile.png',
        'foto.jpg',
        'foto.jpeg',
        'foto.png',
        'haikal.jpg',
        'haikal.jpeg',
        'haikal.png',
        'image.jpg',
        'image.jpeg',
        'image.png'
    ];
    
    let triedCount = 0;
    
    function tryNextImage() {
        if (triedCount >= alternativeNames.length) return;
        
        const testImg = new Image();
        testImg.src = alternativeNames[triedCount];
        
        testImg.onload = function() {
            console.log(`Alternative image found: ${alternativeNames[triedCount]}`);
            profileImage.src = alternativeNames[triedCount];
            imageContainer.classList.remove('no-image');
            profileImage.classList.add('loaded');
        };
        
        testImg.onerror = function() {
            triedCount++;
            tryNextImage();
        };
    }
    
    tryNextImage();
}

// Create modal for image enlargement
function createImageModal(imageSrc) {
    // Remove existing modal if any
    const existingModal = document.querySelector('.image-modal');
    if (existingModal) {
        existingModal.remove();
    }
    
    const modal = document.createElement('div');
    modal.className = 'image-modal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;
    
    const modalImage = document.createElement('img');
    modalImage.src = imageSrc;
    modalImage.style.cssText = `
        max-width: 90%;
        max-height: 90%;
        border-radius: 10px;
        transform: scale(0.8);
        transition: transform 0.3s ease;
    `;
    
    const closeBtn = document.createElement('button');
    closeBtn.innerHTML = '<i class="fas fa-times"></i>';
    closeBtn.style.cssText = `
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        font-size: 1.5rem;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: background 0.3s ease;
    `;
    
    closeBtn.addEventListener('mouseenter', function() {
        this.style.background = 'rgba(255, 255, 255, 0.2)';
    });
    
    closeBtn.addEventListener('mouseleave', function() {
        this.style.background = 'rgba(255, 255, 255, 0.1)';
    });
    
    closeBtn.addEventListener('click', function() {
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.remove();
        }, 300);
    });
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.remove();
            }, 300);
        }
    });
    
    modal.appendChild(modalImage);
    modal.appendChild(closeBtn);
    document.body.appendChild(modal);
    
    // Trigger animation
    setTimeout(() => {
        modal.style.opacity = '1';
        modalImage.style.transform = 'scale(1)';
    }, 10);
    
    // Close on ESC key
    document.addEventListener('keydown', function closeOnEsc(e) {
        if (e.key === 'Escape') {
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.remove();
                document.removeEventListener('keydown', closeOnEsc);
            }, 300);
        }
    });
}

// Track CV Downloads
function initDownloadTracking() {
    const downloadBtn = document.querySelector('.download-btn');
    
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function(e) {
            // Track download event
            console.log('CV downloaded at: ', new Date().toLocaleString());
            
            // Show download confirmation
            setTimeout(() => {
                showNotification('CV berhasil diunduh! Terima kasih.', 'success');
            }, 1000);
        });
    }
}

// Scroll to top functionality
function initScrollToTop() {
    const scrollToTopBtn = document.createElement('div');
    scrollToTopBtn.className = 'scroll-to-top';
    scrollToTopBtn.innerHTML = '<i class="fas fa-chevron-up"></i>';
    scrollToTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    document.body.appendChild(scrollToTopBtn);
    
    // Show/hide scroll to top button
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.classList.add('visible');
        } else {
            scrollToTopBtn.classList.remove('visible');
        }
    });
}

// Initialize all features when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing portfolio features...');
    
    // Initialize profile image with fallback
    initProfileImage();
    
    // Initialize download tracking
    initDownloadTracking();
    
    // Initialize scroll to top
    initScrollToTop();
    
    // Add loading animation to profile image
    const profileImage = document.querySelector('.profile-image');
    if (profileImage && !profileImage.classList.contains('no-image')) {
        profileImage.style.opacity = '0';
        profileImage.style.transform = 'scale(0.8)';
        profileImage.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    }
});

// Add CSS for animations and modal
const style = document.createElement('style');
style.textContent = `
    section {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }
    
    section.fade-in {
        opacity: 1;
        transform: translateY(0);
    }
    
    @keyframes navLinkFade {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .fa-spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Profile image specific animations */
    .profile-image.loaded {
        animation: imageFadeIn 0.8s ease-out;
    }
    
    @keyframes imageFadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    /* Modal animations */
    .image-modal {
        animation: modalFadeIn 0.3s ease;
    }
    
    @keyframes modalFadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    /* Enhanced scroll to top button */
    .scroll-to-top {
        transition: all 0.3s ease;
    }
    
    .scroll-to-top.visible {
        animation: bounceIn 0.5s ease;
    }
    
    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    /* Error message styling */
    .error-message {
        color: var(--secondary-color);
        font-size: 0.8rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
`;
document.head.appendChild(style);

// Performance optimization: Lazy load images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// Add resize handler for better responsive behavior
window.addEventListener('resize', function() {
    // Close mobile menu on resize to larger screens
    if (window.innerWidth > 768 && nav.classList.contains('nav-active')) {
        nav.classList.remove('nav-active');
        burger.classList.remove('toggle');
    }
});

console.log('Portfolio JavaScript initialized successfully!');