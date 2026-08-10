<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM partnerships WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if(!$item) { die("ไม่พบข้อมูล"); }
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <!-- เพิ่ม Viewport สำหรับรองรับมือถือและทุกอุปกรณ์ -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดข้อมูล</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            background-color: #0f172a;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* เพิ่มเอฟเฟกต์แสงพื้นหลัง */
        body::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #38bdf8 0%, #3b82f6 100%);
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.15;
            z-index: -1;
            animation: floatGlow 8s ease-in-out infinite alternate;
        }

        @keyframes floatGlow {
            0% { transform: translate(-100px, -100px); }
            100% { transform: translate(100px, 100px); }
        }

        .main-wrapper {
            width: 100%;
            max-width: 700px;
            box-sizing: border-box;
            padding: 15px;
        }

        .glass-container.wide {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 30px;
            box-sizing: border-box;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            transition: transform 0.4s cubic-bezier(0.03, 0.98, 0.52, 0.99), box-shadow 0.4s ease, border-color 0.4s ease;
        }

        /* เอฟเฟกต์เมื่อเอาเมาส์ชี้ที่กล่องแสดงข้อมูล */
        .glass-container.wide:hover {
            transform: translateY(-6px) scale(1.005);
            box-shadow: 0 25px 50px rgba(56, 189, 248, 0.15);
            border-color: rgba(56, 189, 248, 0.3);
        }

        h2 {
            font-size: clamp(20px, 4vw, 24px);
            text-align: center;
            margin-top: 0;
            margin-bottom: 25px;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .detail-item {
            margin-bottom: 18px;
            font-size: 15px;
            color: #cbd5e1;
            line-height: 1.6;
            word-break: break-word; /* ป้องกันไม่ให้ข้อความยาวล้นขอบจอ */
            transition: color 0.3s;
        }

        .detail-item:hover {
            color: #ffffff;
        }

        .detail-item strong {
            color: #38bdf8;
            display: inline-block;
            min-width: 110px;
            transition: color 0.3s;
        }

        .detail-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 15px;
            border-radius: 10px;
            margin-top: 8px;
            color: #cbd5e1;
            transition: all 0.3s ease;
        }

        /* เอฟเฟกต์เมื่อเอาเมาส์ชี้กล่องรายละเอียดด้านใน */
        .detail-box:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(56, 189, 248, 0.3);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .link-back {
            display: inline-block;
            margin-top: 15px;
            color: #38bdf8;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s, text-shadow 0.3s, transform 0.2s;
        }

        .link-back:hover {
            color: #7dd3fc;
            text-decoration: underline;
            text-shadow: 0 0 8px rgba(56, 189, 248, 0.5);
            transform: translateX(-3px);
        }

        /* รองรับหน้าจอขนาดเล็กพิเศษ */
        @media (max-width: 480px) {
            .glass-container.wide {
                padding: 20px;
            }
            .detail-item strong {
                display: block;
                margin-bottom: 3px;
            }
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <div class="glass-container wide">
            <h2>รายละเอียดความร่วมมือ</h2>
            
            <div class="detail-item">
                <strong>หัวข้อ:</strong> <?= htmlspecialchars($item['title']) ?>
            </div>
            <div class="detail-item">
                <strong>คู่สัญญา:</strong> <?= htmlspecialchars($item['partner_name']) ?>
            </div>
            <div class="detail-item">
                <strong>ประเภท:</strong> <?= htmlspecialchars($item['type']) ?>
            </div>
            <div class="detail-item">
                <strong>สถานะ:</strong> <?= htmlspecialchars($item['status']) ?>
            </div>
            
            <div class="detail-item">
                <strong>รายละเอียด:</strong>
                <div class="detail-box">
                    <?= nl2br(htmlspecialchars($item['details'])) ?>
                </div>
            </div>

            <a href="list.php" class="link-back">← กลับสู่หน้ารายการ</a>
        </div>
    </div>
</body>
</html>