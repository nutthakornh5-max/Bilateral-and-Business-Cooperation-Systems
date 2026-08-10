<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <!-- เพิ่ม Viewport สำหรับรองรับมือถือและทุกอุปกรณ์ -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การแจ้งเตือน</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* จัดหน้าให้อยู่ตรงกลางและเป็นระเบียบทุกขนาดหน้าจอ */
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #0f172a;
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

        .glass-container {
            width: 100%;
            max-width: 480px;
            margin: 20px;
            padding: 30px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            transition: transform 0.4s cubic-bezier(0.03, 0.98, 0.52, 0.99), box-shadow 0.4s ease, border-color 0.4s ease;
        }

        /* เอฟเฟกต์เมื่อเอาเมาส์ชี้ที่กล่องหลัก */
        .glass-container:hover {
            transform: translateY(-6px) scale(1.005);
            box-shadow: 0 25px 50px rgba(56, 189, 248, 0.15);
            border-color: rgba(56, 189, 248, 0.3);
        }

        h2 {
            color: #ffffff;
            text-align: center;
            font-size: clamp(20px, 4vw, 24px);
            margin-top: 0;
            margin-bottom: 25px;
            letter-spacing: 0.5px;
        }

        /* กล่องข้อความแจ้งเตือนพร้อมเอฟเฟกต์ Hover */
        .notification-card {
            background: rgba(255, 255, 255, 0.03);
            padding: 18px;
            border-radius: 12px;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .notification-card:hover {
            background: rgba(255, 255, 255, 0.07);
            border-color: rgba(56, 189, 248, 0.3);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .notification-card p {
            margin: 0 0 8px 0;
            font-size: 15px;
            color: #cbd5e1;
            line-height: 1.5;
            transition: color 0.3s;
        }

        .notification-card:hover p {
            color: #ffffff;
        }

        .notification-card span {
            font-size: 13px;
            color: #94a3b8;
        }

        /* ลิงก์กลับหน้าเดิม */
        .link-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .link-text a {
            color: #38bdf8;
            text-decoration: none;
            transition: color 0.3s, text-shadow 0.3s, transform 0.2s;
            display: inline-block;
        }

        .link-text a:hover {
            color: #7dd3fc;
            text-decoration: underline;
            text-shadow: 0 0 8px rgba(56, 189, 248, 0.5);
            transform: translateX(-3px);
        }

        /* รองรับหน้าจอขนาดเล็กพิเศษ */
        @media (max-width: 480px) {
            .glass-container {
                margin: 10px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="glass-container">
        <h2>การแจ้งเตือน</h2>
        
        <div class="notification-card">
            <p>📌 ยินดีต้อนรับเข้าสู่ระบบความร่วมมือทวิภาคี</p>
            <span>ระบบพร้อมใช้งานแล้ว</span>
        </div>

        <div class="link-text">
            <a href="settings.php">← กลับสู่หน้าตั้งค่า</a>
        </div>
    </div>
</body>
</html>