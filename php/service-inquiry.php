<?php
/* Arodx Agency - Service Inquiry Handler */
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
$SITE_NAME = 'Arodx Agency';

// Sanitize input function
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Validate email function
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Simple email function
function sendEmail($to, $subject, $htmlMessage) {
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Arodx Agency <noreply@arodx.com>\r\n";
    $headers .= "Reply-To: " . $to . "\r\n";
    
    return mail($to, $subject, $htmlMessage, $headers);
}

// Get service pricing
function getServicePricing($service) {
    $pricing = [
        'WordPress Development' => ['min' => 799, 'max' => 2500, 'timeline' => '2-4 weeks'],
        'WooCommerce Development' => ['min' => 1299, 'max' => 5000, 'timeline' => '3-6 weeks'],
        'SEO Optimization' => ['min' => 599, 'max' => 1500, 'timeline' => '1-3 months'],
        'Website Security' => ['min' => 399, 'max' => 999, 'timeline' => '1-2 weeks'],
        'Website Recovery' => ['min' => 299, 'max' => 799, 'timeline' => '1-3 days'],
        'Hosting Management' => ['min' => 199, 'max' => 999, 'timeline' => 'Ongoing']
    ];
    
    return $pricing[$service] ?? ['min' => 299, 'max' => 2500, 'timeline' => '1-4 weeks'];
}

// Main processing
try {
    // Get and validate form data
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $service = sanitizeInput($_POST['service'] ?? '');
    $budget = sanitizeInput($_POST['budget'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');
    
    // Validation
    if (empty($name) || empty($email) || empty($service) || empty($message)) {
        throw new Exception('Please fill in all required fields.');
    }
    
    if (!validateEmail($email)) {
        throw new Exception('Please enter a valid email address.');
    }
    
    // Get service pricing info
    $serviceInfo = getServicePricing($service);
    
    // Prepare email content for admin
    $subject = "New Service Inquiry - $service";
    
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
            .service-info { background: white; padding: 15px; border: 2px solid #6B46C1; border-radius: 8px; margin: 15px 0; }
            .priority { background: #ffebee; border-left: 4px solid #f44336; padding: 10px; margin: 15px 0; }
            .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Service Inquiry</h2>
                <p>$service Request</p>
            </div>
            <div class='content'>
                <div class='service-info'>
                    <h3>Service: $service</h3>
                    <p><strong>Estimated Price Range:</strong> $" . number_format($serviceInfo['min']) . " - $" . number_format($serviceInfo['max']) . "</p>
                    <p><strong>Typical Timeline:</strong> " . $serviceInfo['timeline'] . "</p>
                </div>
                
                <div class='field'>
                    <span class='label'>Client Name:</span>
                    <span class='value'>$name</span>
                </div>
                <div class='field'>
                    <span class='label'>Email:</span>
                    <span class='value'>$email</span>
                </div>
                " . (!empty($phone) ? "<div class='field'><span class='label'>Phone:</span><span class='value'>$phone</span></div>" : "") . "
                " . (!empty($budget) ? "<div class='field'><span class='label'>Budget Range:</span><span class='value'>$budget</span></div>" : "") . "
                
                <div class='field'>
                    <span class='label'>Project Details:</span>
                    <div style='background: white; padding: 10px; border-left: 4px solid #6B46C1; margin-top: 5px;'>
                        " . nl2br($message) . "
                    </div>
                </div>
                
                " . (in_array($service, ['Website Recovery', 'Website Security']) ? 
                "<div class='priority'>
                    <strong>Priority Service:</strong> This is a " . strtolower($service) . " request which may require immediate attention.
                </div>" : "") . "
            </div>
            <div class='footer'>
                <p>Service inquiry received on " . date('Y-m-d H:i:s') . "</p>
                <p>Please respond within 4 hours for best client experience</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Send email to admin
    if (sendEmail($ADMIN_EMAIL, $subject, $htmlMessage)) {
        // Send auto-reply to client
        $autoReplySubject = "Thank you for your $service inquiry - Arodx Agency";
        
        $autoReplyMessage = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #6B46C1 0%, #9333EA 100%); color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; }
                .service-details { background: white; padding: 15px; border-left: 4px solid #6B46C1; margin: 15px 0; }
                .next-steps { background: #e8f5e8; padding: 15px; border-radius: 8px; margin: 15px 0; }
                .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Thank You for Your Inquiry!</h2>
                    <p>Arodx Agency</p>
                </div>
                <div class='content'>
                    <p>Dear $name,</p>
                    
                    <p>Thank you for your interest in our <strong>$service</strong> services. We have received your inquiry and our team is already reviewing your requirements.</p>
                    
                    <div class='service-details'>
                        <h3>Service Overview: $service</h3>
                        <p><strong>Estimated Investment:</strong> $" . number_format($serviceInfo['min']) . " - $" . number_format($serviceInfo['max']) . "</p>
                        <p><strong>Typical Timeline:</strong> " . $serviceInfo['timeline'] . "</p>
                    </div>
                    
                    <div class='next-steps'>
                        <h3>Next Steps:</h3>
                        <ol>
                            <li>Our expert will review your project details within 4 hours</li>
                            <li>We'll prepare a customized proposal based on your requirements</li>
                            <li>You'll receive a detailed quote via email within 24 hours</li>
                            <li>We can schedule a consultation call to discuss your project in detail</li>
                        </ol>
                    </div>
                    
                    " . (in_array($service, ['Website Recovery', 'Website Security']) ? 
                    "<div style='background: #fff3cd; padding: 15px; border-radius: 8px; margin: 15px 0;'>
                        <strong>Urgent Request Noted:</strong> We understand that " . strtolower($service) . " issues need immediate attention. 
                        Our team will prioritize your request and contact you within 2 hours.
                    </div>" : "") . "
                    
                    <p>If you have any immediate questions or need to discuss your project urgently, please don't hesitate to contact us:</p>
                    <ul>
                        <li><strong>Phone:</strong> +1 (555) 123-4567</li>
                        <li><strong>Email:</strong> info@arodx.com</li>
                        <li><strong>WhatsApp:</strong> +1 (555) 123-4567</li>
                    </ul>
                    
                    <p>We look forward to working with you and bringing your vision to life!</p>
                    
                    <p>Best regards,<br>
                    Asiful Islam<br>
                    Founder & Lead Developer<br>
                    Arodx Agency</p>
                </div>
                <div class='footer'>
                    <p>Arodx Agency - Professional Web Development Services</p>
                    <p>Specializing in WordPress, WooCommerce, SEO, Security & Hosting</p>
                    <p>Website: arodx.com | Email: info@arodx.com</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        sendEmail($email, $autoReplySubject, $autoReplyMessage);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Your inquiry has been sent successfully! We will contact you within 4 hours with a detailed quote.'
        ]);
    } else {
        throw new Exception('Failed to send inquiry. Please try again.');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
