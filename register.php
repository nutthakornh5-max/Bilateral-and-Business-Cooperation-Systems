<?php
require 'db.php';
$success = '';
$error = '';

// เก็บค่าเดิมไว้แสดงในฟอร์มกรณีเกิดข้อผิดพลาด
$username = '';
$fullname = '';
$email = '';
$role = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'establishment';

    // ตรวจสอบความถูกต้องเบื้องต้น
    if (empty($username) || empty($password) || empty($fullname) || empty($email)) {
        $error = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
    } else {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, fullname, email, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $hashed_password, $fullname, $email, $role]);
            
            // เปลี่ยนเส้นทางทันทีไปยังหน้าเข้าสู่ระบบ
            header("Location: login.php?success=register");
            exit();
            
        } catch (PDOException $e) {
            $error = "ชื่อผู้ใช้งานนี้ถูกใช้งานแล้ว หรือเกิดข้อผิดพลาดในการบันทึกข้อมูล";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียน - ระบบความร่วมมือทวิภาคี</title>
    <link rel="stylesheet" href="style.css">
    <style>
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

        /* เอฟเฟกต์เมื่อเอาเมาส์ชี้ที่กล่องสมัครสมาชิก */
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
            color: #cbd5e1;
            font-size: 14px;
            margin-bottom: 6px;
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
            font-size: 16px;
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

        /* แก้ไขสีพื้นหลังของตัวเลือก dropdown ให้ตัดกับข้อความ */
        .form-group select option {
            background-color: #0f172a;
            color: #ffffff;
            padding: 10px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #38bdf8;
            color: #0f172a;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(56, 189, 248, 0.2);
        }

        button[type="submit"]:hover {
            background: #0ea5e9;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.4);
        }

        button[type="submit"]:active {
            transform: translateY(-1px);
        }

        .link-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #94a3b8;
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
            transform: translateX(3px);
        }

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
        <h2>ลงทะเบียนผู้ใช้งาน</h2>
        
        <?php if(!empty($error)): ?>
            <p style="color: #f87171; background: rgba(248, 113, 113, 0.1); border: 1px solid rgba(248, 113, 113, 0.2); padding: 10px; border-radius: 8px; text-align: center; margin-bottom: 15px; font-size: 14px;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="form-group">
                <label>ชื่อผู้ใช้งาน (Username)</label>
                <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required>
            </div>
            <div class="form-group">
                <label>รหัสผ่าน (Password)</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($fullname) ?>" required>
            </div>
            <div class="form-group">
                <label>อีเมล</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
            </div>
            <div class="form-group">
                <label>ประเภทผู้ใช้</label>
                <select name="role">
                    <option value="establishment" <?= ($role == 'establishment') ? 'selected' : '' ?>>สถานประกอบการ</option>
                    <option value="institution" <?= ($role == 'institution') ? 'selected' : '' ?>>สถานศึกษา</option>
                    <option value="student" <?= ($role == 'student') ? 'selected' : '' ?>>นักศึกษา</option>
                </select>
            </div>
            <button type="submit">สมัครสมาชิก</button>
        </form>
        
        <div class="link-text">
            มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a>
        </div>
    </div>
</body>
</html>