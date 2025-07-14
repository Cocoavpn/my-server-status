<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานะระบบทั้งหมด</title>
    
    <!-- ส่วนของ CSS สำหรับตกแต่งหน้าเว็บ -->
    <style>
        body {
            font-family: 'Sarabun', sans-serif; /* ใช้ฟอนต์ที่สวยงาม (ถ้าเครื่องมี) */
            background-color: #f4f7f6;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* เปลี่ยนเป็น flex-start เพื่อให้เริ่มจากด้านบน */
            min-height: 100vh;
            margin: 0;
            padding: 2rem 0; /* เพิ่มช่องว่างบนล่าง */
        }
        .status-container {
            width: 90%;
            max-width: 800px;
            background-color: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 1rem;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e8f4fd;
        }
        .status-online {
            color: #27ae60;
            font-weight: bold;
        }
        .status-offline {
            color: #c0392b;
            font-weight: bold;
        }
        .status-icon {
            margin-right: 8px;
        }
        td.center, th.center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #2c3e50;
            color: white;
            font-size: 1.2rem;
        }
    </style>
    
    <!-- ส่วนของ Google Fonts (ไม่จำเป็น แต่ทำให้สวยขึ้น) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<?php
// --- ส่วนของ PHP Logic ---

$domains = [
    'true.dem-home.uk', '3bb.dem-home.uk', 'th-15.twins-app.uk', 'th-14.twins-app.uk',
    'th-10.twins-app.uk', 'th-07.twins-app.uk', 'th-08.twins-app.uk', 'th-09.twins-app.uk',
    'game02.twins-app.uk', 'game01.twins-app.uk', 'th-03.twins-app.uk', 'th-05.twins-app.uk',
    'th-04.twins-app.uk', 'dtac-slow.twins-app.uk', 'th-02.twins-app.uk', 'sg-03.twins-app.uk',
    'sg-02.twins-app.uk',
];

$total_online = 0;
$total_limit = 0;
?>

<!-- ส่วนของ HTML ที่จะแสดงผล -->
<div class="status-container">
    <h2>📡 สถานะระบบทั้งหมด</h2>
    <table>
        <thead>
            <tr>
                <th>🌐 โดเมน</th>
                <th class="center">👥 ออนไลน์</th>
                <th class="center">🚫 จำกัด</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // วนลูปเพื่อดึงข้อมูลและแสดงผลทีละแถว
            foreach ($domains as $domain) {
                $proxy = 'https://bismirobbee.com/Online-nuwa/proxy.php?url=' . urlencode("http://$domain:82/server/online_app.json");
                $context = stream_context_create(['http' => ['timeout' => 5]]); // ป้องกันเว็บค้าง
                $json = @file_get_contents($proxy, false, $context);
                
                $online_display = '<span class="status-offline"><span class="status-icon">❌</span>ออฟไลน์</span>';
                $limit_display = '<span class="status-offline">N/A</span>';

                if ($json !== false) {
                    $data = json_decode($json, true);
                    if ($data && isset($data[0]['onlines'])) {
                        $online = $data[0]['onlines'];
                        $limit = $data[0]['limite'];

                        $online_display = '<span class="status-online"><span class="status-icon">✔️</span>' . htmlspecialchars($online) . '</span>';
                        $limit_display = htmlspecialchars($limit);
                        
                        if (is_numeric($online)) $total_online += $online;
                        if (is_numeric($limit)) $total_limit += $limit;
                    }
                }

                // แสดงผลแถวของตารางทันที
                echo "<tr>
                        <td>" . htmlspecialchars($domain) . "</td>
                        <td class='center'>$online_display</td>
                        <td class='center'>$limit_display</td>
                      </tr>";
            }
            ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>รวมทั้งหมด</td>
                <td class="center"><?= $total_online ?></td>
                <td class="center"><?= $total_limit ?></td>
            </tr>
        </tfoot>
    </table>
</div>

</body>
</html>
