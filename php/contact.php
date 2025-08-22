<?php
/* Arodx Agency - Dedicated Contact Form Processor */
/* Author: Asiful Islam */
/* Version: 7.4.32 */

// Include configuration
require_once 'config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Rate limiting check
if (!checkRateLimit('contact_form')) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many requests. Please try again in an hour.']);
    logError('Rate limit exceeded for contact form', ['ip' => $_SERVER['REMOTE_ADDR']]);
    exit;
}

/**
 * Enhanced email sending function with better error handling
 */
function sendEnhancedEmail($to, $subject, $htmlMessage, $replyTo = null) {
    // Prepare headers
    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . FROM_NAME . ' <' . FROM_EMAIL . '>',
        'X-Mailer: Arodx Agency Contact System v' . SITE_VERSION
    ];
    
    if ($replyTo) {
        $headers[] = 'Reply-To: ' . $replyTo;
    }
    
    // Add anti-spam headers
    $headers[] = 'X-Priority: 3';
    $headers[] = 'X-MSMail-Priority: Normal';
    $headers[] = 'Importance: Normal';
    
    $headerString = implode("\r\n", $headers);
    
    // Log email attempt
    logActivity('Attempting to send email', [
        'to' => $to,
        'subject' => $subject,
        'from' => FROM_EMAIL
    ]);
    
    // Send email
    $result = mail($to, $subject, $htmlMessage, $headerString);
    
    if (!$result) {
        logError('Failed to send email', [
            'to' => $to,
            'subject' => $subject,
            'error' => error_get_last()
        ]);
    }
    
    return $result;
}

/**
 * Generate HTML email template
 */
function generateEmailTemplate($templateType, $data) {
    $baseStyle = "
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .header { background: linear-gradient(135deg, #6B46C1 0%, #9333EA 100%); color: white; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; font-weight: 700; }
        .header p { margin: 10px 0 0 0; opacity: 0.9; }
        .content { padding: 30px 20px; background: #f8fafc; }
        .field { margin-bottom: 20px; padding: 15px; background: white; border-radius: 8px; border-left: 4px solid #6B46C1; }
        .field-label { font-weight: 600; color: #6B46C1; margin-bottom: 5px; display: block; }
        .field-value { color: #1f2937; }
        .message-content { background: white; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb; margin: 15px 0; }
        .priority-notice { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 15px; margin: 20px 0; }
        .priority-notice strong { color: #dc2626; }
        .footer { background: #1f2937; color: #9ca3af; padding: 20px; text-align: center; font-size: 14px; }
        .footer a { color: #6B46C1; text-decoration: none; }
        .btn { display: inline-block; background: #6B46C1; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500; margin: 10px 0; }
        .stats { display: flex; justify-content: space-around; margin: 20px 0; }
        .stat { text-align: center; }
        .stat-number { font-size: 24px; font-weight: 700; color: #6B46C1; }
        .stat-label { font-size: 12px; color: #6b7280; text-transform: uppercase; }
    </style>
    ";
    
    if ($templateType === 'admin_notification') {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <title>New Contact Form Submission</title>
            {$baseStyle}
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>📧 New Contact Submission</h1>
                    <p>Arodx Agency Contact System</p>
                </div>
                <div class='content'>
                    <div class='field'>
                        <span class='field-label'>👤 Full Name</span>
                        <div class='field-value'>{$data['first_name']} {$data['last_name']}</div>
                    </div>
                    <div class='field'>
                        <span class='field-label'>📧 Email Address</span>
                        <div class='field-value'>{$data['email']}</div>
                    </div>
                    " . (!empty($data['phone']) ? "
                    <div class='field'>
                        <span class='field-label'>📱 Phone Number</span>
                        <div class='field-value'>{$data['phone']}</div>
                    </div>
                    " : "") . "
                    " . (!empty($data['company']) ? "
                    <div class='field'>
                        <span class='field-label'>🏢 Company</span>
                        <div class='field-value'>{$data['company']}</div>
                    </div>
                    " : "") . "
                    " . (!empty($data['service']) ? "
                    <div class='field'>
                        <span class='field-label'>🛠️ Service Interest</span>
                        <div class='field-value'>{$data['service']}</div>
                    </div>
                    " : "") . "
                    " . (!empty($data['budget']) ? "
                    <div class='field'>
                        <span class='field-label'>💰 Budget Range</span>
                        <div class='field-value'>{$data['budget']}</div>
                    </div>
                    " : "") . "
                    " . (!empty($data['timeline']) ? "
                    <div class='field'>
                        <span class='field-label'>⏰ Timeline</span>
                        <div class='field-value'>{$data['timeline']}</div>
                    </div>
                    " : "") . "
                    <div class='field'>
                        <span class='field-label'>📝 Message</span>
                        <div class='message-content'>" . nl2br(htmlspecialchars($data['message'])) . "</div>
                    </div>
                    <div class='field'>
                        <span class='field-label'>📬 Newsletter Subscription</span>
                        <div class='field-value'>" . ($data['newsletter'] ? 'Yes' : 'No') . "</div>
                    </div>
                </div>
                <div class='footer'>
                    <p><strong>Contact received:</strong> " . date('F j, Y \a\t g:i A T') . "</p>
                    <p><strong>IP Address:</strong> {$_SERVER['REMOTE_ADDR']}</p>
                    <p><strong>User Agent:</strong> " . htmlspecialchars($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown') . "</p>
                    <p style='margin-top: 20px;'>
                        <a href='mailto:{$data['email']}' class='btn'>Reply to Customer</a>
                    </p>
                </div>
            </div>
        </body>
        </html>
        ";
    } elseif ($templateType === 'customer_auto_reply') {
        $serviceInfo = !empty($data['service']) ? getServicePricing($data['service']) : null;
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <title>Thank you for contacting Arodx Agency</title>
            {$baseStyle}
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🎉 Thank You!</h1>
                    <p>We've received your message</p>
                </div>
                <div class='content'>
                    <p>Dear {$data['first_name']},</p>
                    
                    <p>Thank you for reaching out to <strong>Arodx Agency</strong>! We have received your message and our team is excited to help bring your vision to life.</p>
                    
                    " . ($serviceInfo ? "
                    <div class='field'>
                        <span class='field-label'>🛠️ Service: {$data['service']}</span>
                        <div class='field-value'>
                            <strong>Investment Range:</strong> " . formatPrice($serviceInfo['min_price']) . " - " . formatPrice($serviceInfo['max_price']) . "<br>
                            <strong>Timeline:</strong> {$serviceInfo['timeline']}<br>
                            <strong>Description:</strong> {$serviceInfo['description']}
                        </div>
                    </div>
                    " : "") . "
                    
                    <h3>🚀 What happens next?</h3>
                    <ol>
                        <li><strong>Review:</strong> Our expert team will carefully review your requirements</li>
                        <li><strong>Analysis:</strong> We'll analyze your project scope and prepare a customized proposal</li>
                        <li><strong>Response:</strong> You'll receive a detailed quote within 24 hours</li>
                        <li><strong>Consultation:</strong> We'll schedule a free consultation call to discuss details</li>
                    </ol>
                    
                    <div class='stats'>
                        <div class='stat'>
                            <div class='stat-number'>150+</div>
                            <div class='stat-label'>Projects Completed</div>
                        </div>
                        <div class='stat'>
                            <div class='stat-number'>98%</div>
                            <div class='stat-label'>Client Satisfaction</div>
                        </div>
                        <div class='stat'>
                            <div class='stat-number'>24hr</div>
                            <div class='stat-label'>Response Time</div>
                        </div>
                    </div>
                    
                    <h3>📞 Need immediate assistance?</h3>
                    <ul>
                        <li><strong>Phone:</strong> +1 (555) 123-4567</li>
                        <li><strong>Email:</strong> info@arodx.com</li>
                        <li><strong>WhatsApp:</strong> +1 (555) 123-4567</li>
                    </ul>
                    
                    <p>We look forward to working with you!</p>
                    
                    <p>Best regards,<br>
                    <strong>Asiful Islam</strong><br>
                    Founder & Lead Developer<br>
                    Arodx Agency</p>
                </div>
                <div class='footer'>
                    <p><strong>Arodx Agency</strong> - Professional Web Development Services</p>
                    <p>Specializing in WordPress, WooCommerce, SEO, Security & Hosting</p>
                    <p><a href='" . SITE_URL . "'>Visit our website</a> | <a href='" . SITE_URL . "/portfolio.html'>View our portfolio</a></p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    return '';
}

/**
 * Detect and prevent spam submissions
 */
function detectSpam($data) {
    $spamKeywords = [
        'viagra', 'casino', 'lottery', 'winner', 'congratulations',
        'bitcoin', 'cryptocurrency', 'investment opportunity',
        'make money fast', 'get rich quick', 'free money'
    ];
    
    $message = strtolower($data['message'] ?? '');
    $email = strtolower($data['email'] ?? '');
    
    // Check for spam keywords
    foreach ($spamKeywords as $keyword) {
        if (strpos($message, $keyword) !== false) {
            return true;
        }
    }
    
    // Check for suspicious email patterns
    if (preg_match('/[0-9]{4,}@/', $email)) {
        return true;
    }
    
    // Check for excessive links
    if (substr_count($message, 'http') > 2) {
        return true;
    }
    
    // Check for excessive capital letters
    if (strlen($message) > 50 && (strlen($message) - strlen(preg_replace('/[A-Z]/', '', $message))) / strlen($message) > 0.6) {
        return true;
    }
    
    return false;
}

// Main processing
try {
    // Collect and sanitize form data
    $formData = [
        'first_name' => sanitizeInput($_POST['first_name'] ?? ''),
        'last_name' => sanitizeInput($_POST['last_name'] ?? ''),
        'email' => sanitizeInput($_POST['email'] ?? '', 'email'),
        'phone' => sanitizeInput($_POST['phone'] ?? '', 'phone'),
        'company' => sanitizeInput($_POST['company'] ?? ''),
        'service' => sanitizeInput($_POST['service'] ?? ''),
        'budget' => sanitizeInput($_POST['budget'] ?? ''),
        'timeline' => sanitizeInput($_POST['timeline'] ?? ''),
        'message' => sanitizeInput($_POST['message'] ?? ''),
        'newsletter' => !empty($_POST['newsletter'])
    ];
    
    // Validation rules
    $validationRules = [
        'first_name' => ['required' => true, 'min_length' => 2, 'max_length' => 50],
        'last_name' => ['required' => true, 'min_length' => 2, 'max_length' => 50],
        'email' => ['required' => true, 'format' => 'email'],
        'phone' => ['format' => 'phone'],
        'company' => ['max_length' => 100],
        'message' => ['required' => true, 'min_length' => 10, 'max_length' => 2000]
    ];
    
    // Validate input
    $errors = validateInput($formData, $validationRules);
    if (!empty($errors)) {
        throw new Exception('Validation failed: ' . implode(', ', $errors));
    }
    
    // Spam detection
    if (detectSpam($formData)) {
        logError('Spam detected in contact form', $formData);
        throw new Exception('Your message appears to be spam. Please try again with a genuine inquiry.');
    }
    
    // Log the contact attempt
    logActivity('New contact form submission', [
        'name' => $formData['first_name'] . ' ' . $formData['last_name'],
        'email' => $formData['email'],
        'service' => $formData['service'],
        'ip' => $_SERVER['REMOTE_ADDR']
    ]);
    
    // Generate and send admin notification
    $adminSubject = "New Contact: {$formData['first_name']} {$formData['last_name']}" . 
                   (!empty($formData['service']) ? " - {$formData['service']}" : "");
    
    $adminEmail = generateEmailTemplate('admin_notification', $formData);
    
    if (!sendEnhancedEmail(ADMIN_EMAIL, $adminSubject, $adminEmail, $formData['email'])) {
        throw new Exception('Failed to send notification email to admin.');
    }
    
    // Generate and send customer auto-reply
    $customerSubject = "Thank you for contacting Arodx Agency, {$formData['first_name']}!";
    $customerEmail = generateEmailTemplate('customer_auto_reply', $formData);
    
    if (!sendEnhancedEmail($formData['email'], $customerSubject, $customerEmail)) {
        // Log warning but don't fail the request
        logError('Failed to send auto-reply email to customer', [
            'customer_email' => $formData['email'],
            'name' => $formData['first_name'] . ' ' . $formData['last_name']
        ]);
    }
    
    // Success response
    $responseMessage = !empty($formData['service']) ? 
        "Thank you for your interest in our {$formData['service']} services! We'll send you a detailed quote within 24 hours." :
        "Thank you for your message! We'll get back to you within 24 hours.";
    
    echo json_encode([
        'success' => true,
        'message' => $responseMessage,
        'data' => [
            'reference_id' => md5($formData['email'] . time()),
            'estimated_response' => '24 hours'
        ]
    ]);
    
    // Log successful submission
    logActivity('Contact form processed successfully', [
        'name' => $formData['first_name'] . ' ' . $formData['last_name'],
        'email' => $formData['email']
    ]);
    
} catch (Exception $e) {
    // Log the error
    logError('Contact form processing failed', [
        'error' => $e->getMessage(),
        'data' => $_POST ?? [],
        'trace' => $e->getTraceAsString()
    ]);
    
    // Return error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => 'CONTACT_FORM_ERROR'
    ]);
}
?>
