
<?php
// download.php
require 'config.php';

function htmlWrap($content) {
    echo "<!DOCTYPE html><html lang='ja'><head><meta charset='UTF-8'><title>ダウンロード</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'></head><body class='bg-light'>
    <div class='container mt-5'>";
    echo $content;
    echo "</div><script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script></body></html>";
}

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM files WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($file && file_exists($file['filepath'])) {
        if (!empty($file['password'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (password_verify($_POST['password'], $file['password'])) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . basename($file['filename']) . '"');
                    header('Content-Length: ' . filesize($file['filepath']));
                    readfile($file['filepath']);
                    exit;
                } else {
                    htmlWrap("<div class='alert alert-danger'>パスワードが間違っています。</div>");
                }
            } else {
                htmlWrap("<form method='post'><div class='mb-3'><label for='pw'>パスワード:</label>
                          <input type='password' class='form-control' name='password' id='pw'></div>
                          <button type='submit' class='btn btn-primary'>ダウンロード</button></form>");
            }
        } else {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file['filename']) . '"');
            header('Content-Length: ' . filesize($file['filepath']));
            readfile($file['filepath']);
            exit;
        }
    } else {
        htmlWrap("<div class='alert alert-warning'>ファイルが存在しません。</div>");
    }
} else {
    htmlWrap("<div class='alert alert-warning'>IDが指定されていません。</div>");
}
?>
