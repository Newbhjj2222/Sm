<?php
require __DIR__.'/dbcon.php'; // Firebase setup
session_start();

/* ================= CONFIG ================= */
$nhostProjectId = 'hheouvehxbgetjbbvchu';  // shyiramo project id yawe
$nhostAdminSecret = 'Sm'; // cyangwa token y'umukoresha
$cloudName = "dilowy3fd";
$uploadPreset = "Newtalents";

/* ================= Nhost PDF Upload ================= */
function uploadToNhost($fileTmp, $fileName, $token, $projectId) {
    $url = "https://backend-$projectId.nhost.app/v1/storage/files";

    $postFields = [
        'file' => new CURLFile($fileTmp, mime_content_type($fileTmp), $fileName)
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postFields,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token"
        ]
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
        return ['error' => "HTTP Error $httpCode", 'raw' => $response];
    }

    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => "JSON Decode Error: " . json_last_error_msg(), 'raw' => $response];
    }

    return $data;
}

/* ================= CLOUDINARY IMAGE UPLOAD ================= */
function uploadToCloudinary($fileTmp, $fileType, $cloudName, $uploadPreset)
{
    $endpoint = str_starts_with($fileType, 'image/')
        ? "https://api.cloudinary.com/v1_1/$cloudName/image/upload"
        : "https://api.cloudinary.com/v1_1/$cloudName/raw/upload";

    if (!file_exists($fileTmp)) {
        return ['error' => 'Temporary file not found.'];
    }

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
        return ['error' => "JSON Decode Error: ".json_last_error_msg(), 'raw_response'=>$response];
    }

    if (!isset($data['secure_url'])) {
        return ['error' => 'Upload succeeded but secure_url missing', 'response'=>$data];
    }

    return ['url' => $data['secure_url']];
}

/* ================= ADD BOOK HANDLER ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_book'])) {

    $id = uniqid();
    $name = $_POST['name'] ?? '';
    $level = $_POST['level'] ?? '';
    $class = $_POST['class'] ?? '';

    $fileUrl = '';
    $imageUrl = '';

    /* ===== FILE SIZE VALIDATION (10MB) ===== */
    if (!empty($_FILES['file']['size']) && $_FILES['file']['size'] > 10000000) {
        die("File too large. Maximum 10MB.");
    }

    /* ===== UPLOAD PDF TO NHOST ===== */
    if (!empty($_FILES['file']['tmp_name'])) {
        $res = uploadToNhost($_FILES['file']['tmp_name'], $_FILES['file']['name'], $nhostAdminSecret, $nhostProjectId);
        if (isset($res['error'])) {
            echo "<pre>"; print_r($res); echo "</pre>"; exit;
        }
        $fileUrl = $res['url'] ?? '';
    }

    /* ===== UPLOAD IMAGE TO CLOUDINARY ===== */
    if (!empty($_FILES['image']['tmp_name'])) {
        $res = uploadToCloudinary($_FILES['image']['tmp_name'], $_FILES['image']['type'], $cloudName, $uploadPreset);
        if (isset($res['error'])) {
            echo "<pre>"; print_r($res); echo "</pre>"; exit;
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
