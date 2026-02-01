<?php
/**
 * TEST PERMISSION - Laravel Hosting
 * 
 * File ini untuk mengecek apakah folder storage dan session bisa ditulis
 * Upload file ini ke folder public/ di hosting
 * Akses: https://yourdomain.com/test-permission.php
 * 
 * ⚠️ HAPUS FILE INI SETELAH TESTING!
 */

// Enable error display
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permission Test - Laravel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .test-item {
            background: #f8f9fa;
            border-left: 4px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .test-item.success {
            border-left-color: #28a745;
            background: #d4edda;
        }
        .test-item.error {
            border-left-color: #dc3545;
            background: #f8d7da;
        }
        .test-item.warning {
            border-left-color: #ffc107;
            background: #fff3cd;
        }
        .test-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 16px;
        }
        .test-result {
            font-size: 14px;
            color: #555;
        }
        .icon {
            display: inline-block;
            margin-right: 8px;
            font-size: 18px;
        }
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .code {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
            margin-top: 10px;
        }
        .warning-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Laravel Permission Test</h1>
        <p class="subtitle">Testing storage, session, and file permissions</p>

        <?php
        $allPassed = true;
        $basePath = dirname(__DIR__);

        // Test 1: Storage Write Permission
        echo '<div class="test-item ';
        $storageTestFile = $basePath . '/storage/logs/permission-test.txt';
        $storageWritable = @file_put_contents($storageTestFile, 'Test write: ' . date('Y-m-d H:i:s'));
        
        if ($storageWritable !== false) {
            echo 'success">';
            echo '<div class="test-title"><span class="icon">✅</span>Storage Writable</div>';
            echo '<div class="test-result">Successfully wrote to: ' . $storageTestFile . '</div>';
            @unlink($storageTestFile); // Clean up
        } else {
            echo 'error">';
            echo '<div class="test-title"><span class="icon">❌</span>Storage NOT Writable</div>';
            echo '<div class="test-result">Failed to write to: ' . $storageTestFile . '</div>';
            if ($error = error_get_last()) {
                echo '<div class="test-result">Error: ' . htmlspecialchars($error['message']) . '</div>';
            }
            $allPassed = false;
        }
        echo '</div>';

        // Test 2: Session
        echo '<div class="test-item ';
        try {
            session_start();
            $_SESSION['test'] = 'OK';
            if (isset($_SESSION['test']) && $_SESSION['test'] === 'OK') {
                echo 'success">';
                echo '<div class="test-title"><span class="icon">✅</span>Session Working</div>';
                echo '<div class="test-result">Session save path: ' . session_save_path() . '</div>';
                echo '<div class="test-result">Session handler: ' . ini_get('session.save_handler') . '</div>';
            } else {
                throw new Exception('Session test failed');
            }
        } catch (Exception $e) {
            echo 'error">';
            echo '<div class="test-title"><span class="icon">❌</span>Session NOT Working</div>';
            echo '<div class="test-result">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
            $allPassed = false;
        }
        echo '</div>';

        // Test 3: PHP Version
        echo '<div class="test-item ';
        $phpVersion = phpversion();
        if (version_compare($phpVersion, '8.1.0', '>=')) {
            echo 'success">';
            echo '<div class="test-title"><span class="icon">✅</span>PHP Version OK</div>';
            echo '<div class="test-result">PHP ' . $phpVersion . ' (Laravel 10+ requires PHP 8.1+)</div>';
        } else {
            echo 'warning">';
            echo '<div class="test-title"><span class="icon">⚠️</span>PHP Version Low</div>';
            echo '<div class="test-result">PHP ' . $phpVersion . ' - Laravel 10+ requires PHP 8.1+</div>';
            $allPassed = false;
        }
        echo '</div>';

        // Test 4: Required Extensions
        echo '<div class="test-item ';
        $requiredExtensions = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath'];
        $missingExtensions = [];
        foreach ($requiredExtensions as $ext) {
            if (!extension_loaded($ext)) {
                $missingExtensions[] = $ext;
            }
        }
        
        if (empty($missingExtensions)) {
            echo 'success">';
            echo '<div class="test-title"><span class="icon">✅</span>PHP Extensions OK</div>';
            echo '<div class="test-result">All required extensions are loaded</div>';
        } else {
            echo 'error">';
            echo '<div class="test-title"><span class="icon">❌</span>Missing PHP Extensions</div>';
            echo '<div class="test-result">Missing: ' . implode(', ', $missingExtensions) . '</div>';
            $allPassed = false;
        }
        echo '</div>';

        // Test 5: Framework Directories
        echo '<div class="test-item ';
        $frameworkDirs = [
            'storage/framework/sessions',
            'storage/framework/views',
            'storage/framework/cache',
            'storage/logs',
            'bootstrap/cache'
        ];
        $missingDirs = [];
        foreach ($frameworkDirs as $dir) {
            if (!is_dir($basePath . '/' . $dir)) {
                $missingDirs[] = $dir;
            }
        }
        
        if (empty($missingDirs)) {
            echo 'success">';
            echo '<div class="test-title"><span class="icon">✅</span>Framework Directories OK</div>';
            echo '<div class="test-result">All required directories exist</div>';
        } else {
            echo 'error">';
            echo '<div class="test-title"><span class="icon">❌</span>Missing Directories</div>';
            echo '<div class="test-result">Missing: ' . implode(', ', $missingDirs) . '</div>';
            $allPassed = false;
        }
        echo '</div>';

        // Test 6: .env file
        echo '<div class="test-item ';
        $envFile = $basePath . '/.env';
        if (file_exists($envFile) && is_readable($envFile)) {
            echo 'success">';
            echo '<div class="test-title"><span class="icon">✅</span>.env File OK</div>';
            echo '<div class="test-result">File exists and is readable</div>';
        } else {
            echo 'error">';
            echo '<div class="test-title"><span class="icon">❌</span>.env File Missing</div>';
            echo '<div class="test-result">File not found or not readable: ' . $envFile . '</div>';
            $allPassed = false;
        }
        echo '</div>';

        // Summary
        if ($allPassed) {
            echo '<div class="info-box" style="background: #d4edda; border-color: #28a745;">';
            echo '<strong style="color: #155724;">🎉 All Tests Passed!</strong><br>';
            echo 'Your hosting environment is properly configured for Laravel.';
            echo '</div>';
        } else {
            echo '<div class="info-box" style="background: #f8d7da; border-color: #dc3545;">';
            echo '<strong style="color: #721c24;">⚠️ Some Tests Failed</strong><br>';
            echo 'Please fix the issues above before running Laravel.';
            echo '</div>';
        }

        // System Info
        echo '<div class="info-box">';
        echo '<strong>📊 System Information:</strong>';
        echo '<div class="code">';
        echo 'PHP Version: ' . phpversion() . "\n";
        echo 'Server Software: ' . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
        echo 'Document Root: ' . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "\n";
        echo 'Base Path: ' . $basePath . "\n";
        echo 'Current User: ' . get_current_user() . "\n";
        echo 'Session Save Path: ' . session_save_path() . "\n";
        echo '</div>';
        echo '</div>';

        // Warning to delete
        echo '<div class="warning-box">';
        echo '⚠️ PENTING: Hapus file test-permission.php setelah testing selesai!';
        echo '</div>';
        ?>
    </div>
</body>
</html>
