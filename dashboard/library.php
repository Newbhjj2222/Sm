<?php
require __DIR__.'/dbcon.php'; 
session_start();

/* ================= ERROR REPORTING ================= */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* ================= CONFIG ================= */
$nhostProjectId = 'hheouvehxbgetjbbvchu'; // Reba neza ko ari Project ID nyayo
$nhostAdminSecret = 'SHYIRAMO_ADMIN_SECRET_NYAYO'; // HASURA_GRAPHQL_ADMIN_SECRET
$cloudName = "dilowy3fd";
$uploadPreset = "Newtalents";

/* ================= UPLOAD PDF TO NHOST ================= */
function uploadToNhost($fileTmp, $fileName, $adminSecret, $projectId) {

    if (!file_exists($fileTmp)) {
        return ['error' => 'PDF temporary file not found'];
    }

    $url = "https://backend-$projectId.nhost.app/v1/storage/files";

    $postFields = [
        'file' => new CURLFile(
            $fileTmp,
            mime_content_type($fileTmp),
            $fileName
        )
    ];

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postFields,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "x-hasura-admin-secret: $adminSecret"
        ],
        CURLOPT_TIMEOUT => 60,
        CURLOPT_SSL_VERIFYPEER => true
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['error' => "cURL Error: $error"];
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 && $httpCode !== 201) {
        return [
            'error' => "Nhost HTTP Error: $httpCode",
            'raw_response' => $response
        ];
    }

    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            'error' => "JSON Decode Error: " . json_last_error_msg(),
            'raw_response' => $response
        ];
    }

    if (!isset($data['fileMetadata']['id'])) {
        return [
            'error' => "Upload succeeded but fileMetadata missing",
            'response' => $data
        ];
    }

    return $data;
}

/* ================= UPLOAD IMAGE TO CLOUDINARY ================= */
function uploadToCloudinary($fileTmp, $fileType, $cloudName, $uploadPreset)
{
    if (!file_exists($fileTmp)) {
        return ['error' => 'Image temporary file not found'];
    }

    $endpoint = "https://api.cloudinary.com/v1_1/$cloudName/image/upload";

    $postFields = [
        'file' => new CURLFile($fileTmp, $fileType),
        'upload_preset' => $uploadPreset,
        'folder' => 'books/images'
    ];

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $endpoint,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postFields,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_SSL_VERIFYPEER => true
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['error' => "Cloudinary cURL Error: $error"];
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        return [
            'error' => "Cloudinary HTTP Error: $httpCode",
            'raw_response' => $response
        ];
    }

    $data = json_decode($response, true);

    if (!isset($data['secure_url'])) {
        return [
            'error' => "Cloudinary upload failed",
            'response' => $data
        ];
    }

    return ['url' => $data['secure_url']];
}

/* ================= HANDLE FORM ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {

    $id = uniqid();
    $name = $_POST['name'] ?? '';
    $level = $_POST['level'] ?? '';
    $class = $_POST['class'] ?? '';

    $fileUrl = '';
    $imageUrl = '';

    /* ===== VALIDATE PDF SIZE ===== */
    if (!empty($_FILES['file']['size']) && $_FILES['file']['size'] > 10000000) {
        die("PDF too large. Max 10MB.");
    }

    /* ===== UPLOAD PDF ===== */
    if (!empty($_FILES['file']['tmp_name'])) {

        $pdfUpload = uploadToNhost(
            $_FILES['file']['tmp_name'],
            $_FILES['file']['name'],
            $nhostAdminSecret,
            $nhostProjectId
        );

        if (isset($pdfUpload['error'])) {
            echo "<pre>";
            print_r($pdfUpload);
            echo "</pre>";
            exit;
        }

        // Kubona public URL
        $fileId = $pdfUpload['fileMetadata']['id'];
        $fileUrl = "https://backend-$nhostProjectId.nhost.app/v1/storage/files/$fileId";
    }

    /* ===== UPLOAD IMAGE ===== */
    if (!empty($_FILES['image']['tmp_name'])) {

        $imageUpload = uploadToCloudinary(
            $_FILES['image']['tmp_name'],
            $_FILES['image']['type'],
            $cloudName,
            $uploadPreset
        );

        if (isset($imageUpload['error'])) {
            echo "<pre>";
            print_r($imageUpload);
            echo "</pre>";
            exit;
        }

        $imageUrl = $imageUpload['url'];
    }

    /* ===== SAVE TO FIREBASE ===== */
    $realtimeDatabase->getReference("library/$id")->set([
        "id" => $id,
        "name" => $name,
        "level" => $level,
        "class" => $class,
        "file" => $fileUrl,
        "image" => $imageUrl,
        "created_at" => date('Y-m-d H:i:s')
    ]);

    echo "Book added successfully.";
    exit;
}
?>
