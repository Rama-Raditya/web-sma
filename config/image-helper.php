<?php
/**
 * Helper functions untuk penanganan gambar di web-sma
 */

/**
 * Dapatkan URL gambar dengan fallback
 * @param string $filename Nama file gambar dari database
 * @param string $type Tipe gambar: 'content' atau 'gallery' (opsional)
 * @return string URL gambar atau fallback
 */
function getImageUrl($filename, $type = 'content') {
    if (empty($filename)) {
        return 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22200%22%3E%3Crect fill=%22%23e0e0e0%22 width=%22300%22 height=%22200%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 fill=%22%23999%22 text-anchor=%22middle%22 dy=%22.3em%22%3ENo Image Available%3C/text%3E%3C/svg%3E';
    }
    
    $basename = basename($filename);
    $path = '../assets/img/' . $basename;
    
    return htmlspecialchars($path);
}

/**
 * Generate img tag dengan fallback SVG
 * @param string $filename Nama file gambar
 * @param string $alt Alt text
 * @param array $attrs Atribut HTML tambahan (class, style, etc)
 * @return string HTML img tag
 */
function getImageTag($filename, $alt = 'Image', $attrs = []) {
    $url = getImageUrl($filename);
    $altText = htmlspecialchars($alt);
    $attrStr = '';
    
    foreach ($attrs as $key => $value) {
        $attrStr .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
    }
    
    return '<img src="' . $url . '" alt="' . $altText . '"' . $attrStr . ' onerror="this.src=\'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22200%22%3E%3Crect fill=%22%23f5f5f5%22 width=%22300%22 height=%22200%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 fill=%22%23ccc%22 text-anchor=%22middle%22 dy=%22.3em%22%3E' . $altText . ' Not Found%3C/text%3E%3C/svg%3E\'">';
}

/**
 * Handle image upload dengan validasi
 * @param array $file $_FILES['gambar']
 * @param string $targetDir Target directory (mis. '../assets/img/')
 * @param array $allowedExt Ekstensi yang diizinkan
 * @param int $maxSize Max size dalam bytes (default 5MB)
 * @return array ['success' => bool, 'filename' => string, 'error' => string]
 */
function handleImageUpload($file, $targetDir = '../assets/img/', $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'], $maxSize = 5242880) {
    $response = ['success' => false, 'filename' => '', 'error' => ''];
    
    // Validasi upload error
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['error'] = 'Upload gagal: ' . getUploadErrorMessage($file['error']);
        return $response;
    }
    
    // Validasi ukuran
    if ($file['size'] > $maxSize) {
        $response['error'] = 'Ukuran gambar terlalu besar (max ' . ($maxSize / 1048576) . 'MB)';
        return $response;
    }
    
    // Validasi ekstensi
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt)) {
        $response['error'] = 'Format file tidak didukung. Gunakan: ' . implode(', ', $allowedExt);
        return $response;
    }
    
    // Buat folder jika belum ada
    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0755, true)) {
            $response['error'] = 'Gagal membuat folder upload';
            return $response;
        }
    }
    
    // Generate nama file unik (untuk menghindari overwrite)
    $filename = time() . '_' . basename($file['name']);
    $targetPath = $targetDir . $filename;
    
    // Move file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        $response['success'] = true;
        $response['filename'] = $filename;
    } else {
        $response['error'] = 'Gagal menyimpan file';
    }
    
    return $response;
}

/**
 * Get upload error message
 * @param int $errorCode Error code dari $_FILES
 * @return string Error message
 */
function getUploadErrorMessage($errorCode) {
    $messages = [
        UPLOAD_ERR_OK => 'Tidak ada error',
        UPLOAD_ERR_INI_SIZE => 'File melebihi upload_max_filesize di php.ini',
        UPLOAD_ERR_FORM_SIZE => 'File melebihi MAX_FILE_SIZE di form',
        UPLOAD_ERR_PARTIAL => 'File hanya ter-upload sebagian',
        UPLOAD_ERR_NO_FILE => 'Tidak ada file yang ter-upload',
        UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ada',
        UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
        UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh PHP extension',
    ];
    return $messages[$errorCode] ?? 'Error tidak diketahui';
}

/**
 * Delete image file
 * @param string $filename Nama file gambar
 * @param string $imageDir Directory gambar
 * @return bool Success status
 */
function deleteImageFile($filename, $imageDir = '../assets/img/') {
    if (empty($filename)) {
        return false;
    }
    
    $filepath = $imageDir . basename($filename);
    
    if (file_exists($filepath)) {
        return @unlink($filepath);
    }
    
    return true; // File sudah tidak ada, dianggap sukses
}

/**
 * Check if image exists
 * @param string $filename Nama file gambar
 * @param string $imageDir Directory gambar
 * @return bool
 */
function imageExists($filename, $imageDir = '../assets/img/') {
    if (empty($filename)) {
        return false;
    }
    
    return file_exists($imageDir . basename($filename));
}
?>
