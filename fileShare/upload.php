
<?php
// upload.php
require 'config.php';

function htmlHeader($title = 'アップロード結果') {
    echo "<!DOCTYPE html><html lang='ja'><head><meta charset='UTF-8'><title>$title</title>";
    echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'></head><body class='bg-light'><div class='container mt-5'>";
}
function htmlFooter() {
    echo "</div><script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script></body></html>";
}

htmlHeader();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $filename = basename($file['name']);
    $unique = uniqid();
    $filepath = $storagePath . $unique . '_' . $filename;
    $relativePath = str_replace('\\', '/', $filepath);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $stmt = $pdo->prepare("INSERT INTO files (filename, filepath, password, uploaded_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$filename, $relativePath, $password]);

        $file_id = $pdo->lastInsertId();
        echo "<div class='alert alert-success'><strong>アップロード成功！</strong><br>
              <a href='download.php?id=$file_id'>ダウンロードリンク</a><br>
              <a href='delete.php?id=$file_id'>削除リンク</a></div>";
    } else {
        echo "<div class='alert alert-danger'>アップロード失敗</div>";
    }
}

htmlFooter();
?>
