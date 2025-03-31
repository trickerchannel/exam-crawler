<?php
$dataFile = 'data/exams.json';
$examData = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách đề thi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 40px;
        }
        h2 {
            margin-bottom: 30px;
            text-align: center;
            font-weight: bold;
        }
        footer {
            margin-top: 60px;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Danh Sách Đề Thi Đã Tải Lên</h2>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Ngày tải lên</th>
                        <th>Số trang</th>
                        <th>Mã Thi</th>
                        <th>Tải xuống</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($examData)): ?>
                        <?php foreach ($examData as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['ngay_tai_len']) ?></td>
                                <td><?= htmlspecialchars($row['so_trang']) ?></td>
                                <td><?= htmlspecialchars($row['ma_thi']) ?></td>
                                <td>
                                    <a href="<?= htmlspecialchars($row['link_tai']) ?>" class="btn btn-sm btn-primary" target="_blank">
                                        Tải xuống
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Không có dữ liệu để hiển thị.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <footer>
            &copy; <?= date("Y") ?> - Danh sách đề thi | Tự động cập nhật từ hệ thống Duy Tân
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
