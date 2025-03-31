<?php
function getHTML($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
    ]);
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

function getExamSchedule() {
    $url = 'https://pdaotao.duytan.edu.vn/EXAM_LIST/?lang=VN';
    $baseDetailUrl = 'https://pdaotao.duytan.edu.vn';

    $html = getHTML($url);
    if (!$html) return [];

    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $links = $xpath->query('//a[contains(@id, "txt_l4")]');
    $data = [];

    foreach ($links as $link) {
        $maThi = trim($link->nodeValue);
        $href = $link->getAttribute('href');

        if (preg_match('/ID=(\d+)/', $href, $matches)) {
            $id = $matches[1];
            $detailUrl = "https://pdaotao.duytan.edu.vn/EXAM_LIST_Detail/?ID=$id&lang=VN";
            $detailHTML = getHTML($detailUrl);
            if (!$detailHTML) continue;

            $detailDOM = new DOMDocument();
            @$detailDOM->loadHTML($detailHTML);
            $detailXPath = new DOMXPath($detailDOM);

            $pdfLinks = $detailXPath->query('//a[contains(@href, ".pdf")]');

            foreach ($pdfLinks as $pdfLink) {
                $pdfHref = $pdfLink->getAttribute('href');
                $fileUrl = $baseDetailUrl . $pdfHref;

                $fileName = basename($pdfHref);
                if (preg_match('/(\d{2})(\d{2})(\d{4})/', $fileName, $d)) {
                    $ngayTaiLen = "$d[1]/$d[2]/$d[3]";
                } else {
                    $ngayTaiLen = 'Không rõ';
                }

                $data[] = [
                    'ngay_tai_len' => $ngayTaiLen,
                    'so_trang'     => 'Không rõ', // nếu muốn chính xác phải tải PDF và đếm trang
                    'ma_thi'       => $maThi,
                    'link_tai'     => $fileUrl,
                ];
            }
        }
    }

    return $data;
}

// Chạy và lưu dữ liệu
$data = getExamSchedule();
if (!is_dir('data')) mkdir('data');
file_put_contents('data/exams.json', json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

echo "Đã cập nhật " . count($data) . " mục vào data/exams.json\n";
