<?php
/* Arodx Agency - Configuration File */
/* Author: Asiful Islam */
/* Version: 7.4.32 */

// Prevent direct access
if (!defined('ARODX_AGENCY_INIT')) {
    define('ARODX_AGENCY_INIT', true);
}

// Site Configuration
define('SITE_NAME', 'Arodx Agency');
define('SITE_URL', getenv('SITE_URL') ?: 'https://arodx.com');
define('SITE_VERSION', '7.4.32');
define('SITE_AUTHOR', 'Asiful Islam');

// Email Configuration
define('ADMIN_EMAIL', getenv('ADMIN_EMAIL') ?: 'info@arodx.com');
define('FROM_EMAIL', getenv('FROM_EMAIL') ?: 'noreply@arodx.com');
define('FROM_NAME', getenv('FROM_NAME') ?: 'Arodx Agency');

// SMTP Configuration (optional)
define('SMTP_HOST', getenv('SMTP_HOST') ?: '');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
define('SMTP_USERNAME', getenv('SMTP_USERNAME') ?: '');
define('SMTP_PASSWORD', getenv('SMTP_PASSWORD') ?: '');
define('SMTP_ENCRYPTION', getenv('SMTP_ENCRYPTION') ?: 'tls');

// Database Configuration (if needed for future features)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'arodx_agency');
define('DB_USER', getenv('DB_USER') ?: '');
define('DB_PASS', getenv('DB_PASS') ?: '');

// Security Settings
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);
define('RATE_LIMIT_REQUESTS', 10); // Max requests per minute
define('RATE_LIMIT_WINDOW', 60); // Time window in seconds

// Service Pricing Configuration
$SERVICE_PRICING = [
    'WordPress Development' => [
        'min_price' => 799,
        'max_price' => 2500,
        'timeline' => '2-4 weeks',
        'description' => 'Custom WordPress websites with modern design and functionality',
        'features' => [
            'Custom Theme Development',
            'Plugin Integration',
            'Responsive Design', 
            'Performance Optimization',
            'Content Management Training'
        ]
    ],
    'WooCommerce Development' => [
        'min_price' => 1299,
        'max_price' => 5000,
        'timeline' => '3-6 weeks',
        'description' => 'Complete e-commerce solutions with secure payment gateways',
        'features' => [
            'Custom Store Design',
            'Payment Gateway Integration',
            'Inventory Management',
            'Order Management System',
            'Mobile Commerce Optimization'
        ]
    ],
    'SEO Optimization' => [
        'min_price' => 599,
        'max_price' => 1500,
        'timeline' => '1-3 months',
        'description' => 'Comprehensive SEO strategies to improve search rankings',
        'features' => [
            'Keyword Research & Analysis',
            'On-Page SEO Optimization',
            'Technical SEO Audit',
            'Content Optimization',
            'Local SEO Setup'
        ]
    ],
    'Website Security' => [
        'min_price' => 399,
        'max_price' => 999,
        'timeline' => '1-2 weeks',
        'description' => 'Advanced security measures to protect your website',
        'features' => [
            'Security Audit & Assessment',
            'Malware Scanning & Removal',
            'Firewall Configuration',
            'SSL Certificate Setup',
            'Regular Security Updates'
        ]
    ],
    'Website Recovery' => [
        'min_price' => 299,
        'max_price' => 799,
        'timeline' => '1-3 days',
        'description' => 'Expert recovery services for hacked WordPress websites',
        'features' => [
            'Malware Detection & Removal',
            'Website Backup & Restoration',
            'Security Vulnerability Patching',
            'Google Blacklist Removal',
            'Prevention Measures Setup'
        ]
    ],
    'Hosting Management' => [
        'min_price' => 199,
        'max_price' => 999,
        'timeline' => 'Ongoing',
        'description' => 'Complete hosting solutions including VPS setup and management',
        'features' => [
            'VPS Setup & Configuration',
            'Server Monitoring & Maintenance',
            'Game Server Hosting',
            'Performance Optimization',
            '24/7 Technical Support'
        ]
    ],
    'Portfolio Website' => [
        'min_price' => 599,
        'max_price' => 1999,
        'timeline' => '2-3 weeks',
        'description' => 'Professional portfolio websites for creative professionals',
        'features' => [
            'Custom Portfolio Design',
            'Gallery Management',
            'Contact Integration',
            'Blog Support',
            'Social Media Integration'
        ]
    ]
];

// Contact Form Fields Configuration
$CONTACT_FORM_FIELDS = [
    'required' => ['first_name', 'last_name', 'email', 'message'],
    'optional' => ['phone', 'company', 'service', 'budget', 'timeline', 'newsletter'],
    'validation' => [
        'first_name' => ['min_length' => 2, 'max_length' => 50],
        'last_name' => ['min_length' => 2, 'max_length' => 50],
        'email' => ['format' => 'email'],
        'phone' => ['format' => 'phone', 'optional' => true],
        'company' => ['max_length' => 100],
        'message' => ['min_length' => 10, 'max_length' => 2000]
    ]
];

// Email Templates
$EMAIL_TEMPLATES = [
    'contact_notification' => [
        'subject' => 'New Contact Form Submission from {first_name} {last_name}',
        'template' => 'contact_admin_notification.html'
    ],
    'contact_auto_reply' => [
        'subject' => 'Thank you for contacting Arodx Agency',
        'template' => 'contact_auto_reply.html'
    ],
    'service_inquiry_notification' => [
        'subject' => 'New Service Inquiry - {service}',
        'template' => 'service_inquiry_notification.html'
    ],
    'service_inquiry_auto_reply' => [
        'subject' => 'Thank you for your {service} inquiry - Arodx Agency',
        'template' => 'service_inquiry_auto_reply.html'
    ],
    'template_purchase_notification' => [
        'subject' => 'New Template Purchase Request - {template}',
        'template' => 'template_purchase_notification.html'
    ],
    'template_purchase_auto_reply' => [
        'subject' => 'Thank you for your template purchase request',
        'template' => 'template_purchase_auto_reply.html'
    ]
];

// API Rate Limiting
$RATE_LIMITS = [
    'contact_form' => ['requests' => 5, 'window' => 3600], // 5 per hour
    'service_inquiry' => ['requests' => 3, 'window' => 3600], // 3 per hour
    'template_purchase' => ['requests' => 2, 'window' => 3600] // 2 per hour
];

// Error Messages
$ERROR_MESSAGES = [
    'required_field' => 'This field is required.',
    'invalid_email' => 'Please enter a valid email address.',
    'invalid_phone' => 'Please enter a valid phone number.',
    'min_length' => 'This field must be at least {min} characters long.',
    'max_length' => 'This field must be no more than {max} characters long.',
    'rate_limit' => 'Too many requests. Please try again later.',
    'server_error' => 'Server error occurred. Please try again.',
    'spam_detected' => 'Spam detected. Please try again.',
    'file_too_large' => 'File size exceeds maximum allowed size.',
    'file_type_not_allowed' => 'File type not allowed.'
];

// Success Messages
$SUCCESS_MESSAGES = [
    'contact_sent' => 'Your message has been sent successfully. We\'ll get back to you soon!',
    'inquiry_sent' => 'Your service inquiry has been sent successfully. We\'ll contact you with a detailed quote within 24 hours.',
    'purchase_request_sent' => 'Your purchase request has been sent successfully. We\'ll contact you with payment details and next steps.'
];

// Utility Functions
function getServicePricing($serviceName) {
    global $SERVICE_PRICING;
    return $SERVICE_PRICING[$serviceName] ?? null;
}

function getAllServices() {
    global $SERVICE_PRICING;
    return array_keys($SERVICE_PRICING);
}

function formatPrice($price) {
    return '$' . number_format($price);
}

function getPriceRange($serviceName) {
    $service = getServicePricing($serviceName);
    if (!$service) return 'Contact for pricing';
    
    return formatPrice($service['min_price']) . ' - ' . formatPrice($service['max_price']);
}

// Security Functions
function generateCSRFToken() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function sanitizeInput($data, $type = 'string') {
    $data = trim($data);
    
    switch ($type) {
        case 'email':
            return filter_var($data, FILTER_SANITIZE_EMAIL);
        case 'phone':
            return preg_replace('/[^0-9+\-\(\)\s]/', '', $data);
        case 'number':
            return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
        case 'url':
            return filter_var($data, FILTER_SANITIZE_URL);
        default:
            return htmlspecialchars(strip_tags($data), ENT_QUOTES, 'UTF-8');
    }
}

function validateInput($data, $rules) {
    $errors = [];
    
    foreach ($rules as $field => $rule) {
        $value = $data[$field] ?? '';
        
        // Check required fields
        if (isset($rule['required']) && $rule['required'] && empty($value)) {
            $errors[$field] = 'This field is required.';
            continue;
        }
        
        // Skip validation if field is optional and empty
        if (empty($value) && !isset($rule['required'])) {
            continue;
        }
        
        // Validate email format
        if (isset($rule['format']) && $rule['format'] === 'email') {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = 'Please enter a valid email address.';
            }
        }
        
        // Validate phone format
        if (isset($rule['format']) && $rule['format'] === 'phone') {
            if (!preg_match('/^[\+]?[0-9\-\(\)\s]{10,}$/', $value)) {
                $errors[$field] = 'Please enter a valid phone number.';
            }
        }
        
        // Validate minimum length
        if (isset($rule['min_length']) && strlen($value) < $rule['min_length']) {
            $errors[$field] = "This field must be at least {$rule['min_length']} characters long.";
        }
        
        // Validate maximum length
        if (isset($rule['max_length']) && strlen($value) > $rule['max_length']) {
            $errors[$field] = "This field must be no more than {$rule['max_length']} characters long.";
        }
    }
    
    return $errors;
}

// Rate Limiting Functions
function checkRateLimit($type, $identifier = null) {
    global $RATE_LIMITS;
    
    if (!isset($RATE_LIMITS[$type])) {
        return true; // No rate limit configured
    }
    
    $limit = $RATE_LIMITS[$type];
    $identifier = $identifier ?: $_SERVER['REMOTE_ADDR'];
    $key = $type . '_' . md5($identifier);
    $file = sys_get_temp_dir() . '/arodx_rate_' . $key . '.txt';
    
    $now = time();
    $window_start = $now - $limit['window'];
    
    // Read existing requests
    $requests = [];
    if (file_exists($file)) {
        $data = file_get_contents($file);
        $requests = $data ? json_decode($data, true) : [];
    }
    
    // Filter out old requests
    $requests = array_filter($requests, function($timestamp) use ($window_start) {
        return $timestamp > $window_start;
    });
    
    // Check if limit exceeded
    if (count($requests) >= $limit['requests']) {
        return false;
    }
    
    // Add current request
    $requests[] = $now;
    
    // Save updated requests
    file_put_contents($file, json_encode($requests));
    
    return true;
}

// Logging Functions
function logError($message, $context = []) {
    $log_entry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'level' => 'ERROR',
        'message' => $message,
        'context' => $context,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ];
    
    $log_file = sys_get_temp_dir() . '/arodx_error.log';
    file_put_contents($log_file, json_encode($log_entry) . "\n", FILE_APPEND | LOCK_EX);
}

function logActivity($message, $context = []) {
    $log_entry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'level' => 'INFO',
        'message' => $message,
        'context' => $context,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ];
    
    $log_file = sys_get_temp_dir() . '/arodx_activity.log';
    file_put_contents($log_file, json_encode($log_entry) . "\n", FILE_APPEND | LOCK_EX);
}

// Initialize session for CSRF protection
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
