<?php
require 'db.php';
// หากใน db.php ยังไม่มีการเรียก session_start() ให้เปิดใช้บรรทัดล่างนี้
// if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <!-- เพิ่ม Viewport สำหรับรองรับมือถือและทุกอุปกรณ์ -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตั้งค่าและแจ้งเตือน</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* จัดการโครงสร้างพื้นฐานให้ Responsive และมีเอฟเฟกต์แสง */
        body {
            margin: 0;
            padding: 20px 0;
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
            max-width: 450px;
            margin: 20px;
            padding: 30px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
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
            font-size: clamp(20px, 4vw, 24px); /* ปรับขนาดตัวอักษรตามหน้าจออัตโนมัติ */
            margin-top: 0;
            margin-bottom: 25px;
            letter-spacing: 0.5px;
        }

        .settings-link {
            display: block;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px 20px;
            border-radius: 12px;
            color: #38bdf8;
            text-decoration: none;
            text-align: center;
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
        }
        
        .settings-link:hover {
            background: rgba(56, 189, 248, 0.12);
            border-color: #38bdf8;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(56, 189, 248, 0.2);
        }
        
        .settings-link.danger {
            color: #f87171;
            border-color: rgba(248, 113, 113, 0.2);
        }
        
        .settings-link.danger:hover {
            background: rgba(248, 113, 113, 0.12);
            border-color: #f87171;
            box-shadow: 0 8px 20px rgba(248, 113, 113, 0.2);
        }

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

        /* รองรับหน้าจอขนาดเล็กพิเศษ (มือถือจอแคบ) */
        @media (max-width: 480px) {
            .glass-container {
                margin: 10px;
                padding: 20px;
            }
            .settings-link {
                padding: 12px 15px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="glass-container">
        <h2>การตั้งค่าและแจ้งเตือน</h2>
        
        <a href="notification.php" class="settings-link">
            🔔 หน้าแจ้งเตือน
        </a>
        
        <a href="logout.php" class="settings-link danger">
            🚪 ออกจากระบบ
        </a>

        <div class="link-text">
            <a href="dashboard.php">← กลับสู่หน้าหลัก</a>
        </div>
    </div>
</body>
</html>