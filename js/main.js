/* Arodx Agency - Main JavaScript File */
/* Author: Asiful Islam */
/* Version: 7.4.32 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initNavigation();
    initAnimations();
    initPortfolioFilter();
    initContactForms();
    initCounters();
    initScrollEffects();
    initModals();
    initServiceLinks();
    initProjectCards();
    initFooterInteractions();
});

// Navigation functionality
function initNavigation() {
    const navbar = document.querySelector('.navbar');
    const navToggler = document.querySelector('.navbar-toggler');
    const navCollapse = document.querySelector('.navbar-collapse');
    
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    
    // Close mobile menu when clicking on links
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (navCollapse.classList.contains('show')) {
                navToggler.click();
            }
        });
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Animation effects
function initAnimations() {
    // Fade in animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    document.querySelectorAll('.service-card, .team-card, .testimonial-card, .portfolio-card, .premade-card').forEach(el => {
        observer.observe(el);
    });
}

// Portfolio filter functionality
function initPortfolioFilter() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const portfolioItems = document.querySelectorAll('.portfolio-item');
    
    if (filterBtns.length === 0) return;
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const filterValue = this.getAttribute('data-filter');
            
            portfolioItems.forEach(item => {
                if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 100);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
}

// Contact forms functionality
function initContactForms() {
    // Main contact form
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', handleContactForm);
    }
    
    // Service inquiry form
    const inquiryForm = document.getElementById('inquiryForm');
    if (inquiryForm) {
        inquiryForm.addEventListener('submit', handleInquiryForm);
    }
    
    // Purchase form
    const purchaseForm = document.getElementById('purchaseForm');
    if (purchaseForm) {
        purchaseForm.addEventListener('submit', handlePurchaseForm);
    }
}

// Handle contact form submission
async function handleContactForm(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('php/contact-handler.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('Success!', 'Your message has been sent successfully. We\'ll get back to you soon!', 'success');
            e.target.reset();
        } else {
            showMessage('Error', result.message || 'There was an error sending your message. Please try again.', 'error');
        }
    } catch (error) {
        showMessage('Error', 'There was an error sending your message. Please try again.', 'error');
    } finally {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

// Handle service inquiry form submission
async function handleInquiryForm(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('php/service-inquiry.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('Inquiry Sent!', 'Your service inquiry has been sent successfully. We\'ll contact you soon with a detailed quote.', 'success');
            e.target.reset();
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('inquiryModal'));
            modal.hide();
        } else {
            showMessage('Error', result.message || 'There was an error sending your inquiry. Please try again.', 'error');
        }
    } catch (error) {
        showMessage('Error', 'There was an error sending your inquiry. Please try again.', 'error');
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

// Handle purchase form submission
async function handlePurchaseForm(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const submitBtn = e.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('php/contact-handler.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('Purchase Request Sent!', 'Your purchase request has been sent successfully. We\'ll contact you with payment details and next steps.', 'success');
            e.target.reset();
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('purchaseModal'));
            modal.hide();
        } else {
            showMessage('Error', result.message || 'There was an error processing your request. Please try again.', 'error');
        }
    } catch (error) {
        showMessage('Error', 'There was an error processing your request. Please try again.', 'error');
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

// Counter animation
function initCounters() {
    const counters = document.querySelectorAll('.stat-number');
    
    const animateCounter = (counter) => {
        const target = parseInt(counter.getAttribute('data-count'));
        const duration = 2500; // 2.5 seconds for smoother animation
        const increment = target / (duration / 16); // 60fps
        let current = 0;
        
        // Add loading class for visual feedback
        counter.closest('.stat-item').classList.add('counting');
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
                // Remove loading class and add completed class
                counter.closest('.stat-item').classList.remove('counting');
                counter.closest('.stat-item').classList.add('count-completed');
                
                // Add sparkle effect
                createSparkleEffect(counter);
            }
            counter.textContent = Math.floor(current);
        }, 16);
    };
    
    // Create sparkle effect when counting completes
    const createSparkleEffect = (counter) => {
        const sparkle = document.createElement('div');
        sparkle.className = 'counter-sparkle';
        sparkle.innerHTML = '✨';
        counter.appendChild(sparkle);
        
        setTimeout(() => {
            if (sparkle.parentNode) {
                sparkle.parentNode.removeChild(sparkle);
            }
        }, 1000);
    };
    
    // Intersection observer for counters
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Add stagger delay for multiple counters
                const delay = Array.from(entry.target.closest('.row').querySelectorAll('.stat-number')).indexOf(entry.target) * 200;
                setTimeout(() => {
                    animateCounter(entry.target);
                }, delay);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });
    
    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
}

// Scroll effects
function initScrollEffects() {
    // Parallax effect for hero section
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            heroSection.style.transform = `translateY(${rate}px)`;
        });
    }
    
    // Progress bar on scroll
    const progressBar = document.createElement('div');
    progressBar.className = 'scroll-progress';
    progressBar.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: linear-gradient(135deg, #6B46C1 0%, #9333EA 100%);
        z-index: 9999;
        transition: width 0.1s ease;
    `;
    document.body.appendChild(progressBar);
    
    window.addEventListener('scroll', () => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        progressBar.style.width = scrolled + '%';
    });
}

// Modal functionality
function initModals() {
    // Add CSS for modals
    const modalStyles = `
        .modal-content {
            background: var(--card-bg);
            border: 1px solid rgba(107, 70, 193, 0.2);
        }
        .modal-header {
            border-bottom: 1px solid rgba(107, 70, 193, 0.2);
        }
        .modal-footer {
            border-top: 1px solid rgba(107, 70, 193, 0.2);
        }
        .modal-title {
            color: var(--text-light);
        }
        .btn-close {
            filter: invert(1);
        }
    `;
    
    const styleSheet = document.createElement('style');
    styleSheet.textContent = modalStyles;
    document.head.appendChild(styleSheet);
}

// Service inquiry modal
function openInquiryModal(serviceName) {
    const modal = new bootstrap.Modal(document.getElementById('inquiryModal'));
    document.getElementById('inquiryService').value = serviceName;
    modal.show();
}

// Project detail modal
function openProjectModal(projectName, category) {
    const modal = new bootstrap.Modal(document.getElementById('projectModal'));
    
    // Update modal content
    document.getElementById('projectModalTitle').textContent = projectName;
    document.getElementById('projectModalName').textContent = projectName;
    document.getElementById('projectModalCategory').textContent = category.charAt(0).toUpperCase() + category.slice(1);
    
    // Set project-specific content
    const projectData = getProjectData(projectName, category);
    document.getElementById('projectModalDescription').textContent = projectData.description;
    document.getElementById('projectModalTech').textContent = projectData.technologies;
    document.getElementById('projectModalDuration').textContent = projectData.duration;
    
    // Update features list
    const featuresList = document.getElementById('projectModalFeatures');
    featuresList.innerHTML = '';
    projectData.features.forEach(feature => {
        const li = document.createElement('li');
        li.textContent = feature;
        featuresList.appendChild(li);
    });
    
    modal.show();
}

// Get project data based on name and category
function getProjectData(name, category) {
    const projectData = {
        'Fashion E-commerce Store': {
            description: 'A modern e-commerce platform for fashion retailers with advanced inventory management, multiple payment options, and responsive design.',
            technologies: 'WordPress, WooCommerce, Custom CSS, JavaScript, PHP',
            duration: '4-6 weeks',
            features: ['Custom Theme Design', 'Payment Gateway Integration', 'Inventory Management', 'Mobile Responsive', 'SEO Optimized']
        },
        'Corporate Business Website': {
            description: 'Professional corporate website with modern design, SEO optimization, and comprehensive business information presentation.',
            technologies: 'WordPress, Custom Theme, SEO Plugins, Contact Forms',
            duration: '2-3 weeks',
            features: ['Responsive Design', 'SEO Optimization', 'Contact Forms', 'Blog Integration', 'Performance Optimized']
        },
        'Creative Portfolio Website': {
            description: 'Interactive portfolio website showcasing creative work with modern design elements and smooth animations.',
            technologies: 'WordPress, Custom Theme, JavaScript, CSS Animations',
            duration: '3-4 weeks',
            features: ['Portfolio Gallery', 'Interactive Design', 'Animation Effects', 'Contact Integration', 'Blog Support']
        },
        'Restaurant WordPress Site': {
            description: 'Custom restaurant website with online ordering capabilities, menu management, and reservation system.',
            technologies: 'WordPress, WooCommerce, Booking Plugin, Custom CSS',
            duration: '3-5 weeks',
            features: ['Online Ordering', 'Menu Management', 'Reservation System', 'Location Integration', 'Mobile Optimized']
        },
        'Tech Products Store': {
            description: 'Advanced e-commerce platform for technology products with detailed specifications, reviews, and inventory tracking.',
            technologies: 'WordPress, WooCommerce, Custom Plugins, Advanced Search',
            duration: '5-7 weeks',
            features: ['Advanced Search', 'Product Specifications', 'Review System', 'Inventory Tracking', 'Multi-currency Support']
        },
        'Medical Practice Website': {
            description: 'Professional healthcare website with appointment booking, patient portal, and medical information management.',
            technologies: 'WordPress, Booking System, Custom Forms, Security Features',
            duration: '4-6 weeks',
            features: ['Appointment Booking', 'Patient Portal', 'HIPAA Compliance', 'Contact Forms', 'Service Pages']
        }
    };
    
    return projectData[name] || {
        description: 'Professional website development with modern design and advanced functionality.',
        technologies: 'WordPress, Custom CSS, JavaScript, PHP',
        duration: '2-4 weeks',
        features: ['Responsive Design', 'SEO Optimization', 'Custom Functionality', 'Performance Optimization']
    };
}

// Purchase modal
function openPurchaseModal(templateName, price) {
    const modal = new bootstrap.Modal(document.getElementById('purchaseModal'));
    
    document.getElementById('purchaseItemName').textContent = templateName;
    document.getElementById('purchaseItemPrice').textContent = `Price: ${price}`;
    document.getElementById('purchaseTemplate').value = templateName;
    document.getElementById('purchaseTemplatePrice').value = price;
    
    modal.show();
}

// Show message modal
function showMessage(title, message, type) {
    const modal = new bootstrap.Modal(document.getElementById('messageModal'));
    const titleElement = document.getElementById('messageModalTitle');
    const textElement = document.getElementById('messageModalText');
    const iconElement = document.getElementById('messageModalIcon');
    
    titleElement.textContent = title;
    textElement.textContent = message;
    
    // Update icon based on type
    if (type === 'success') {
        iconElement.innerHTML = '<i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>';
    } else {
        iconElement.innerHTML = '<i class="fas fa-exclamation-circle text-danger" style="font-size: 3rem;"></i>';
    }
    
    modal.show();
}

// Live chat functionality
function startLiveChat() {
    // This would integrate with a live chat service
    alert('Live chat feature will be available soon! Please use the contact form or email us directly.');
}

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Back to top button
function initBackToTop() {
    const backToTopBtn = document.createElement('button');
    backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    backToTopBtn.className = 'back-to-top';
    backToTopBtn.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #6B46C1 0%, #9333EA 100%);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1000;
        box-shadow: 0 4px 15px rgba(107, 70, 193, 0.3);
    `;
    
    document.body.appendChild(backToTopBtn);
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTopBtn.style.opacity = '1';
            backToTopBtn.style.visibility = 'visible';
        } else {
            backToTopBtn.style.opacity = '0';
            backToTopBtn.style.visibility = 'hidden';
        }
    });
    
    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Initialize back to top button
initBackToTop();

// Service Links functionality - Make "Learn More" buttons work
function initServiceLinks() {
    // Handle service "Learn More" links
    document.querySelectorAll('.service-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            
            // If it's an anchor link (like #wordpress), navigate to services page with hash
            if (href.startsWith('services.html#')) {
                window.location.href = href;
            } else {
                // Otherwise, smooth scroll to the target if it exists on current page
                const targetId = href.replace('services.html#', '#');
                const target = document.querySelector(targetId);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                } else {
                    // Navigate to services page
                    window.location.href = 'services.html';
                }
            }
        });
    });
}

// Modern Project Cards functionality
function initProjectCards() {
    // Add hover effects and animations to project cards
    document.querySelectorAll('.modern-project-card').forEach(card => {
        // Add smooth transitions and effects
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-15px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
        
        // Handle project link clicks
        const projectLinks = card.querySelectorAll('.project-link');
        projectLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                
                if (href.startsWith('portfolio.html#')) {
                    // Navigate to portfolio page with specific section
                    window.location.href = href;
                } else if (href === '#') {
                    // Handle live preview or other actions
                    showMessage(
                        'Live Preview', 
                        'This feature will redirect to the live project preview. Contact us for demo access.',
                        'success'
                    );
                }
            });
        });
    });
    
    // Animate project stats on scroll
    const projectStats = document.querySelectorAll('.stat');
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.5 });
    
    projectStats.forEach(stat => {
        stat.style.opacity = '0';
        stat.style.transform = 'translateY(20px)';
        stat.style.transition = 'all 0.6s ease';
        statsObserver.observe(stat);
    });
}

// Footer interactions and animations
function initFooterInteractions() {
    // Animate footer sections on scroll
    const footerSections = document.querySelectorAll('.footer-section, .footer-brand');
    const footerObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 100); // Stagger animation
            }
        });
    }, { threshold: 0.2 });
    
    footerSections.forEach(section => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(30px)';
        section.style.transition = 'all 0.8s ease';
        footerObserver.observe(section);
    });
    
    // Add hover effects to footer links
    document.querySelectorAll('.footer-links a').forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
            this.style.color = 'var(--primary-color)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
            this.style.color = 'var(--text-muted)';
        });
    });
    
    // Social links hover effects
    document.querySelectorAll('.social-link').forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.1)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add typing effect to footer stats
    const footerStats = document.querySelectorAll('.footer-stats .stat-number');
    const footerStatsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateFooterStat(entry.target);
                footerStatsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    footerStats.forEach(stat => {
        footerStatsObserver.observe(stat);
    });
}

// Animate footer statistics
function animateFooterStat(element) {
    const finalText = element.textContent;
    const isNumber = /^\d+\+?$/.test(finalText);
    
    if (isNumber) {
        const target = parseInt(finalText.replace('+', ''));
        const suffix = finalText.includes('+') ? '+' : '';
        const duration = 1500;
        const increment = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current) + suffix;
        }, 16);
    } else {
        // For percentage values like "98%"
        if (finalText.includes('%')) {
            const target = parseInt(finalText.replace('%', ''));
            const duration = 1500;
            const increment = target / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current) + '%';
            }, 16);
        }
    }
}

// Enhanced smooth scrolling for all internal links
document.querySelectorAll('a[href^="#"], a[href*=".html#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        
        // Handle same-page anchors
        if (href.startsWith('#')) {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
        // Handle cross-page anchors - let them navigate normally
        // The target page will handle the anchor scrolling
    });
});

// Add loading animations
function addLoadingAnimations() {
    // Add fade-in class to elements as they become visible
    const animatedElements = document.querySelectorAll('.service-card, .modern-project-card, .testimonial-card, .team-card');
    
    const loadingObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('animate-fade-in');
                }, index * 100);
                loadingObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    animatedElements.forEach(el => {
        loadingObserver.observe(el);
    });
}

// Initialize loading animations
addLoadingAnimations();

// Add page-specific styles
const pageStyles = `
    .page-header {
        padding: 150px 0 100px;
        background: linear-gradient(135deg, var(--dark-bg) 0%, var(--darker-bg) 100%);
        position: relative;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(ellipse at center, rgba(107, 70, 193, 0.1) 0%, transparent 70%);
    }
    
    .page-title {
        font-size: 3rem;
        font-weight: 700;
        color: var(--text-light);
        margin-bottom: 1rem;
    }
    
    .page-subtitle {
        font-size: 1.25rem;
        color: var(--text-muted);
        max-width: 600px;
        margin: 0 auto;
    }
    
    .bg-dark-alt {
        background-color: var(--darker-bg) !important;
    }
    
    .service-detail {
        padding: 80px 0;
    }
    
    .service-icon-large {
        width: 100px;
        height: 100px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2rem;
    }
    
    .service-icon-large i {
        font-size: 2.5rem;
        color: white;
    }
    
    .service-price {
        margin: 2rem 0;
    }
    
    .price-label {
        color: var(--text-gray);
        font-size: 0.9rem;
        display: block;
    }
    
    .price {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .service-features {
        list-style: none;
        padding: 0;
        margin: 2rem 0;
    }
    
    .service-features li {
        color: var(--text-muted);
        margin-bottom: 0.75rem;
        padding-left: 2rem;
        position: relative;
    }
    
    .service-features li i {
        position: absolute;
        left: 0;
        top: 0.25rem;
        color: var(--primary-color);
    }
    
    .premade-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        overflow: hidden;
        transition: var(--transition);
        border: 1px solid rgba(107, 70, 193, 0.1);
        height: 100%;
    }
    
    .premade-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--box-shadow-hover);
        border-color: var(--primary-color);
    }
    
    .premade-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }
    
    .premade-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }
    
    .premade-card:hover .premade-image img {
        transform: scale(1.1);
    }
    
    .premade-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: var(--gradient-primary);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: var(--border-radius);
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .premade-content {
        padding: 2rem;
    }
    
    .premade-features {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
    }
    
    .premade-features li {
        color: var(--text-muted);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .premade-features li i {
        color: var(--primary-color);
        margin-right: 0.5rem;
    }
    
    .premade-price {
        margin: 1.5rem 0;
    }
    
    .original-price {
        text-decoration: line-through;
        color: var(--text-gray);
        margin-right: 0.5rem;
    }
    
    .sale-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .contact-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 2rem;
    }
    
    .contact-icon {
        width: 60px;
        height: 60px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1.5rem;
        flex-shrink: 0;
    }
    
    .contact-icon i {
        font-size: 1.25rem;
        color: white;
    }
    
    .contact-details h4 {
        color: var(--text-light);
        margin-bottom: 0.5rem;
    }
    
    .contact-details p {
        color: var(--text-muted);
        margin: 0;
    }
    
    .contact-social {
        margin-top: 2rem;
    }
    
    .contact-social h4 {
        color: var(--text-light);
        margin-bottom: 1rem;
    }
    
    .quick-contact-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 2rem;
        text-align: center;
        transition: var(--transition);
        border: 1px solid rgba(107, 70, 193, 0.1);
        height: 100%;
    }
    
    .quick-contact-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--box-shadow-hover);
        border-color: var(--primary-color);
    }
    
    .quick-contact-icon {
        width: 80px;
        height: 80px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .quick-contact-icon i {
        font-size: 2rem;
        color: white;
    }
    
    .accordion-item {
        background: var(--card-bg);
        border: 1px solid rgba(107, 70, 193, 0.2);
        margin-bottom: 1rem;
    }
    
    .accordion-button {
        background: var(--card-bg);
        color: var(--text-light);
        border: none;
        padding: 1.5rem;
    }
    
    .accordion-button:not(.collapsed) {
        background: var(--gradient-primary);
        color: white;
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: var(--primary-color);
    }
    
    .accordion-body {
        background: var(--darker-bg);
        color: var(--text-muted);
        padding: 1.5rem;
    }
    
    .team-intro {
        padding: 80px 0;
    }
    
    .team-stats {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
    }
    
    .team-stats .stat-item {
        text-align: center;
    }
    
    .team-stats .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        display: block;
    }
    
    .team-stats .stat-label {
        color: var(--text-gray);
        font-size: 0.9rem;
    }
    
    .team-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(107, 70, 193, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: var(--transition);
    }
    
    .team-card:hover .team-overlay {
        opacity: 1;
    }
    
    .team-social {
        display: flex;
        gap: 1rem;
    }
    
    .team-social a {
        width: 40px;
        height: 40px;
        background: white;
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }
    
    .team-social a:hover {
        background: var(--primary-color);
        color: white;
    }
    
    .skill-tag {
        display: inline-block;
        background: rgba(107, 70, 193, 0.2);
        color: var(--primary-color);
        padding: 0.25rem 0.75rem;
        border-radius: var(--border-radius);
        font-size: 0.75rem;
        font-weight: 500;
        margin: 0 0.25rem 0.25rem 0;
    }
    
    .value-card {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 2rem;
        text-align: center;
        transition: var(--transition);
        border: 1px solid rgba(107, 70, 193, 0.1);
        height: 100%;
    }
    
    .value-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--box-shadow-hover);
        border-color: var(--primary-color);
    }
    
    .value-icon {
        width: 80px;
        height: 80px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }
    
    .value-icon i {
        font-size: 2rem;
        color: white;
    }
    
    .benefit-item {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .benefit-item i {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
        display: block;
    }
    
    .benefit-item h4 {
        color: var(--text-light);
        margin-bottom: 0.5rem;
    }
    
    .benefit-item p {
        color: var(--text-gray);
        margin: 0;
    }
    
    .contact-form {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        padding: 3rem;
        border: 1px solid rgba(107, 70, 193, 0.1);
        box-shadow: var(--box-shadow);
    }
    
    .stars {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 1rem;
        justify-content: center;
    }
    
    .stars i {
        color: #FFD700;
        font-size: 1.125rem;
    }
    
    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .author-avatar {
        width: 50px;
        height: 50px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }
    
    .author-info h4 {
        color: var(--text-light);
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }
    
    .author-info span {
        color: var(--text-gray);
        font-size: 0.875rem;
    }
    
    @media (max-width: 768px) {
        .page-title {
            font-size: 2.5rem;
        }
        
        .page-subtitle {
            font-size: 1rem;
        }
        
        .service-icon-large {
            width: 80px;
            height: 80px;
        }
        
        .service-icon-large i {
            font-size: 2rem;
        }
        
        .team-stats {
            flex-direction: column;
            gap: 1rem;
        }
        
        .contact-form {
            padding: 2rem;
        }
        
        .contact-icon {
            width: 50px;
            height: 50px;
        }
        
        .contact-icon i {
            font-size: 1rem;
        }
    }
`;

// Apply page-specific styles
const pageStyleSheet = document.createElement('style');
pageStyleSheet.textContent = pageStyles;
document.head.appendChild(pageStyleSheet);
