<?php
require 'db.php';
// หากใน db.php ยังไม่มีการเรียก session_start() ให้เปิดใช้บรรทัดล่างนี้
// if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <!-- เพิ่ม Viewport เพื่อรองรับการแสดงผลบนมือถือและทุกอุปกรณ์ -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ผู้ใช้งาน</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* จัดหน้าให้อยู่กึ่งกลางหน้าจอและเป็นระเบียบทุกขนาดอุปกรณ์ */
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

        /* เอฟเฟกต์เมื่อเอาเมาส์ชี้ที่กล่องโปรไฟล์ */
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

        .form-group { 
            margin-bottom: 18px; 
        }

        .form-group label { 
            display: block; 
            margin-bottom: 6px; 
            font-weight: 500; 
            color: #cbd5e1;
            font-size: 14px;
            transition: color 0.3s;
        }

        .form-group:hover label {
            color: #38bdf8;
        }

        .form-group input, 
        .form-group select { 
            width: 100%; 
            padding: 12px 15px; 
            box-sizing: border-box; 
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.15); 
            border-radius: 10px; 
            color: #ffffff;
            font-size: 16px; /* ป้องกันการซูมหน้าจออัตโนมัติบนอุปกรณ์ iOS */
            outline: none;
            transition: all 0.3s ease;
        }

        .form-group input:hover, 
        .form-group select:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .form-group input:focus, 
        .form-group select:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: #38bdf8;
            box-shadow: 0 0 12px rgba(56, 189, 248, 0.25);
        }

        .btn-submit { 
            width: 100%;
            background-color: #38bdf8; 
            color: #0f172a; 
            padding: 12px; 
            border: none; 
            border-radius: 10px; 
            cursor: pointer; 
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.2);
        }

        .btn-submit:hover { 
            background-color: #0ea5e9; 
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.4);
        }

        .btn-submit:active {
            transform: translateY(-1px);
        }

        .alert-success { 
            color: #4ade80; 
            background: rgba(74, 222, 128, 0.1);
            border: 1px solid rgba(74, 222, 128, 0.2);
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 15px; 
            font-size: 14px;
        }

        .alert-error { 
            color: #f87171; 
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.2);
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 15px; 
            font-size: 14px;
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

        /* ปรับแต่งเพิ่มเติมสำหรับมือถือหน้าจอขนาดเล็ก */
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
        <h2>แก้ไขโปรไฟล์ผู้ใช้งาน</h2>

        <!-- แสดงข้อความแจ้งเตือนสถานะการอัปเดต -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert-success">อัปเดตข้อมูลสำเร็จแล้ว!</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form action="update-profile.php" method="POST">
            <div class="form-group">
                <label>ชื่อผู้ใช้งาน (Username):</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>ชื่อ-นามสกุล:</label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>อีเมล:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>บทบาท (Role):</label>
                <input type="text" name="role" value="<?= htmlspecialchars($user['role'] ?? '') ?>" required>
            </div>
            
            <button type="submit" class="btn-submit">บันทึกการแก้ไข</button>
        </form>

        <div class="link-text">
            <a href="dashboard.php">← กลับสู่หน้าหลัก</a>
        </div>
    </div>
</body>
</html>