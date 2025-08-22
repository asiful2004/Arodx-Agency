<?php
/* Arodx Agency - Contact Form Handler */
/* Author: Asiful Islam */
/* Version: 7.4.32 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Configuration
$ADMIN_EMAIL = getenv('ADMIN_EMAIL') ?: 'info@arodx.com';
$SMTP_HOST = getenv('SMTP_HOST') ?: 'localhost';
$SMTP_PORT = getenv('SMTP_PORT') ?: 587;
$SMTP_USERNAME = getenv('SMTP_USERNAME') ?: '';
$SMTP_PASSWORD = getenv('SMTP_PASSWORD') ?: '';
$SITE_NAME = 'Arodx Agency';
$SITE_URL = getenv('SITE_URL') ?: 'https://arodx.com';

// Sanitize input function
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Validate email function
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Simple email function (fallback)
function sendSimpleEmail($to, $subject, $message, $headers) {
    return mail($to, $subject, $message, $headers);
}

// Enhanced email function with SMTP (if available)
function sendEmail($to, $subject, $htmlMessage, $textMessage = '') {
    global $SMTP_HOST, $SMTP_PORT, $SMTP_USERNAME, $SMTP_PASSWORD;
    
    // Try to use SMTP if configured
    if (!empty($SMTP_USERNAME) && !empty($SMTP_PASSWORD)) {
        // This would use PHPMailer or similar library in production
        // For now, fall back to simple mail
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Arodx Agency <noreply@arodx.com>\r\n";
        $headers .= "Reply-To: " . $to . "\r\n";
        
        return sendSimpleEmail($to, $subject, $htmlMessage, $headers);
    } else {
        // Simple mail fallback
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Arodx Agency <noreply@arodx.com>\r\n";
        
        return sendSimpleEmail($to, $subject, $htmlMessage, $headers);
    }
}

// Main processing
try {
    // Get form type
    $formType = sanitizeInput($_POST['type'] ?? 'contact');
    
    if ($formType === 'contact') {
        // Regular contact form
        $firstName = sanitizeInput($_POST['first_name'] ?? '');
        $lastName = sanitizeInput($_POST['last_name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $phone = sanitizeInput($_POST['phone'] ?? '');
        $company = sanitizeInput($_POST['company'] ?? '');
        $service = sanitizeInput($_POST['service'] ?? '');
        $budget = sanitizeInput($_POST['budget'] ?? '');
        $timeline = sanitizeInput($_POST['timeline'] ?? '');
        $message = sanitizeInput($_POST['message'] ?? '');
        $newsletter = isset($_POST['newsletter']) ? 'Yes' : 'No';
        
        // Validation
        if (empty($firstName) || empty($lastName) || empty($email) || empty($message)) {
            throw new Exception('Please fill in all required fields.');
        }
        
        if (!validateEmail($email)) {
            throw new Exception('Please enter a valid email address.');
        }
        
        // Prepare email content
        $subject = "New Contact Form Submission from $firstName $lastName";
        
        $htmlMessage = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #6B46C1 0%, #9333EA 100%); color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #6B46C1; }
                .value { margin-left: 10px; }
                .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Contact Form Submission</h2>
                    <p>Arodx Agency Website</p>
                </div>
                <div class='content'>
                    <div class='field'>
                        <span class='label'>Name:</span>
                        <span class='value'>$firstName $lastName</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Email:</span>
                        <span class='value'>$email</span>
                    </div>
                    " . (!empty($phone) ? "<div class='field'><span class='label'>Phone:</span><span class='value'>$phone</span></div>" : "") . "
                    " . (!empty($company) ? "<div class='field'><span class='label'>Company:</span><span class='value'>$company</span></div>" : "") . "
                    " . (!empty($service) ? "<div class='field'><span class='label'>Service:</span><span class='value'>$service</span></div>" : "") . "
                    " . (!empty($budget) ? "<div class='field'><span class='label'>Budget:</span><span class='value'>$budget</span></div>" : "") . "
                    " . (!empty($timeline) ? "<div class='field'><span class='label'>Timeline:</span><span class='value'>$timeline</span></div>" : "") . "
                    <div class='field'>
                        <span class='label'>Newsletter:</span>
                        <span class='value'>$newsletter</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Message:</span>
                        <div style='background: white; padding: 10px; border-left: 4px solid #6B46C1; margin-top: 5px;'>
                            " . nl2br($message) . "
                        </div>
                    </div>
                </div>
                <div class='footer'>
                    <p>This email was sent from the Arodx Agency contact form on " . date('Y-m-d H:i:s') . "</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
    } elseif ($formType === 'template_purchase') {
        // Template purchase form
        $name = sanitizeInput($_POST['name'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $phone = sanitizeInput($_POST['phone'] ?? '');
        $company = sanitizeInput($_POST['company'] ?? '');
        $template = sanitizeInput($_POST['template'] ?? '');
        $price = sanitizeInput($_POST['price'] ?? '');
        $message = sanitizeInput($_POST['message'] ?? '');
        
        // Validation
        if (empty($name) || empty($email) || empty($template)) {
            throw new Exception('Please fill in all required fields.');
        }
        
        if (!validateEmail($email)) {
            throw new Exception('Please enter a valid email address.');
        }
        
        // Prepare email content
        $subject = "New Template Purchase Request - $template";
        
        $htmlMessage = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #6B46C1 0%, #9333EA 100%); color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #6B46C1; }
                .value { margin-left: 10px; }
                .template-info { background: white; padding: 15px; border: 2px solid #6B46C1; border-radius: 8px; margin: 15px 0; }
                .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Template Purchase Request</h2>
                    <p>Arodx Agency Website</p>
                </div>
                <div class='content'>
                    <div class='template-info'>
                        <h3>Template: $template</h3>
                        <p><strong>Price: $price</strong></p>
                    </div>
                    <div class='field'>
                        <span class='label'>Customer Name:</span>
                        <span class='value'>$name</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Email:</span>
                        <span class='value'>$email</span>
                    </div>
                    " . (!empty($phone) ? "<div class='field'><span class='label'>Phone:</span><span class='value'>$phone</span></div>" : "") . "
                    " . (!empty($company) ? "<div class='field'><span class='label'>Company:</span><span class='value'>$company</span></div>" : "") . "
                    " . (!empty($message) ? "
                    <div class='field'>
                        <span class='label'>Additional Requirements:</span>
                        <div style='background: white; padding: 10px; border-left: 4px solid #6B46C1; margin-top: 5px;'>
                            " . nl2br($message) . "
                        </div>
                    </div>
                    " : "") . "
                </div>
                <div class='footer'>
                    <p>This purchase request was sent on " . date('Y-m-d H:i:s') . "</p>
                </div>
            </div>
        </body>
        </html>
        ";
    } else {
        throw new Exception('Invalid form type.');
    }
    
    // Send email
    if (sendEmail($ADMIN_EMAIL, $subject, $htmlMessage)) {
        // Send auto-reply to customer
        $autoReplySubject = "Thank you for contacting Arodx Agency";
        $autoReplyMessage = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #6B46C1 0%, #9333EA 100%); color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; }
                .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Thank You!</h2>
                    <p>Arodx Agency</p>
                </div>
                <div class='content'>
                    <p>Dear " . (isset($firstName) ? $firstName : $name) . ",</p>
                    <p>Thank you for contacting Arodx Agency. We have received your " . 
                    ($formType === 'template_purchase' ? 'purchase request' : 'message') . 
                    " and will get back to you within 24 hours.</p>
                    " . ($formType === 'template_purchase' ? 
                    "<p>We will send you payment details and setup instructions for <strong>$template</strong> shortly.</p>" : 
                    "<p>Our team will review your requirements and provide you with a detailed proposal.</p>") . "
                    <p>In the meantime, feel free to browse our services and portfolio on our website.</p>
                    <p>Best regards,<br>
                    The Arodx Agency Team</p>
                </div>
                <div class='footer'>
                    <p>Arodx Agency | Professional Web Development Services</p>
                    <p>Email: info@arodx.com | Phone: +1 (555) 123-4567</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        sendEmail($email, $autoReplySubject, $autoReplyMessage);
        
        echo json_encode([
            'success' => true, 
            'message' => $formType === 'template_purchase' ? 
                'Purchase request sent successfully! We will contact you with payment details.' :
                'Message sent successfully! We will get back to you soon.'
        ]);
    } else {
        throw new Exception('Failed to send message. Please try again.');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
