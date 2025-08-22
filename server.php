<?php
/* Arodx Agency - Simple PHP Server */
/* Author: Asiful Islam */
/* Version: 7.4.32 */

// Get the requested URI
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remove query string from URI
$uri = parse_url($requestUri, PHP_URL_PATH);

// Define routes
$routes = [
    '/' => 'index.html',
    '/home' => 'index.html',
    '/services' => 'services.html',
    '/portfolio' => 'portfolio.html',
    '/team' => 'team.html',
    '/contact' => 'contact.html'
];

// Handle API requests
if (strpos($uri, '/php/') === 0) {
    // Remove /php/ prefix and add .php extension if needed
    $phpFile = '.' . $uri;
    if (!pathinfo($phpFile, PATHINFO_EXTENSION)) {
        $phpFile .= '.php';
    }
    
    if (file_exists($phpFile)) {
        include $phpFile;
        exit;
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'API endpoint not found']);
        exit;
    }
}

// Handle static files
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/', $uri)) {
    $filePath = '.' . $uri;
    if (file_exists($filePath)) {
        // Set appropriate content type
        $contentTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject'
        ];
        
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $contentType = $contentTypes[$extension] ?? 'application/octet-stream';
        
        header('Content-Type: ' . $contentType);
        readfile($filePath);
        exit;
    }
}

// Handle page routes
if (isset($routes[$uri])) {
    $filePath = $routes[$uri];
} else {
    // Default to index.html for unknown routes
    $filePath = 'index.html';
}

// Check if file exists
if (file_exists($filePath)) {
    // Set content type for HTML
    header('Content-Type: text/html; charset=UTF-8');
    
    // Read and serve the file
    readfile($filePath);
} else {
    // 404 error
    http_response_code(404);
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | Arodx Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--dark-bg);
            text-align: center;
        }
        .error-code {
            font-size: 8rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }
        .error-title {
            font-size: 2rem;
            color: var(--text-light);
            margin: 1rem 0;
        }
        .error-text {
            color: var(--text-muted);
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="error-code">404</div>
                    <h1 class="error-title">Page Not Found</h1>
                    <p class="error-text">The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
                    <a href="/" class="btn btn-primary btn-lg">Go Home</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>';
}
?>
