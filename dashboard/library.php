<?php
require __DIR__.'/dbcon.php';
session_start();

/* ================= CLOUDINARY UPLOAD FUNCTION ================= */
function uploadToCloudinary($fileTmp, $fileType)
{
    $cloudName = "dilowy3fd";
    $uploadPreset = "Newtalents";

    $endpoint = str_starts_with($fileType, 'image/')
        ? "https://api.cloudinary.com/v1_1/$cloudName/image/upload"
        : "https://api.cloudinary.com/v1_1/$cloudName/raw/upload";

    if (!file_exists($fileTmp)) {
        return ['error' => 'Temporary file not found.'];
    }

    $postFields = [
        'file' => new CURLFile($fileTmp, $fileType),
        'upload_preset' => $uploadPreset
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
        return ['error' => "cURL Error: $error"];
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        return [
            'error' => "HTTP Error Code: $httpCode",
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

    if (isset($data['error'])) {
        return [
            'error' => "Cloudinary Error: " . $data['error']['message'],
            'details' => $data
        ];
    }

    if (!isset($data['secure_url'])) {
        return [
            'error' => "Upload succeeded but secure_url missing.",
            'response' => $data
        ];
    }

    return ['url' => $data['secure_url']];
}

/* ================= ADD BOOK ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {

    $id = uniqid();
    $name = $_POST['name'] ?? '';
    $level = $_POST['level'] ?? '';
    $class = $_POST['class'] ?? '';

    $fileUrl = '';
    $imageUrl = '';

    /* ===== FILE SIZE VALIDATION (10MB limit) ===== */
    if (!empty($_FILES['file']['size']) && $_FILES['file']['size'] > 10000000) {
        die("File too large. Maximum allowed is 10MB.");
    }

    /* ===== UPLOAD FILE ===== */
    if (!empty($_FILES['file']['tmp_name'])) {
        $res = uploadToCloudinary($_FILES['file']['tmp_name'], $_FILES['file']['type']);

        if (isset($res['error'])) {
            echo "<pre>";
            print_r($res);
            echo "</pre>";
            exit;
        }

        $fileUrl = $res['url'];
    }

    /* ===== UPLOAD IMAGE ===== */
    if (!empty($_FILES['image']['tmp_name'])) {
        $res = uploadToCloudinary($_FILES['image']['tmp_name'], $_FILES['image']['type']);

        if (isset($res['error'])) {
            echo "<pre>";
            print_r($res);
            echo "</pre>";
            exit;
        }

        $imageUrl = $res['url'];
    }

    /* ===== SAVE TO FIREBASE ===== */
    $realtimeDatabase->getReference("library/$id")->set([
        "id" => $id,
        "name" => $name,
        "level" => $level,
        "class" => $class,
        "file" => $fileUrl,
        "image" => $imageUrl
    ]);

    echo "Book added successfully.";
    exit;
}
?>
