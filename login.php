<?php
session_start();
require 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบกรณีคลิกปุ่ม Try Mode
    if (isset($_POST['try_mode'])) {
        $_SESSION['user_id'] = 0;
        $_SESSION['username'] = 'try_mode_user';
        $_SESSION['fullname'] = 'ผู้ใช้งานทดลอง (Try Mode)';
        $_SESSION['is_try_mode'] = true; // กำหนดสถานะโหมดทดลองให้ชัดเจน
        header("Location: dashboard.php");
        exit;
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['is_try_mode'] = false; // ผู้ใช้จริงไม่ใช่ Try Mode
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง!";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบความร่วมมือทวิภาคี BBCS</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #0f172a;
            overflow: hidden;
        }

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
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        h2 {
            color: #ffffff;
            text-align: center;
            font-size: clamp(18px, 4vw, 22px);
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
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            color: #ffffff;
            font-size: 16px;
            outline: none;
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
            transition: all 0.3s ease;
        }

        button[type="submit"]:hover {
            background: #0ea5e9;
            transform: translateY(-2px);
        }

        .btn-try-mode {
            width: 100%;
            padding: 12px;
            background: transparent;
            color: #38bdf8;
            border: 1px solid #38bdf8;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .btn-try-mode:hover {
            background: rgba(56, 189, 248, 0.1);
            transform: translateY(-2px);
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
        }
    </style>
</head>
<body>
    <div class="glass-container">
        <h2>เข้าสู่ระบบความร่วมมือทวิภาคี BBCS</h2>
        
        <?php if($error): ?>
            <p style="color: #f87171; background: rgba(248, 113, 113, 0.1); border: 1px solid rgba(248, 113, 113, 0.2); padding: 10px; border-radius: 8px; text-align: center; margin-bottom: 15px; font-size: 14px;"><?= $error ?></p>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="form-group">
                <label>ชื่อผู้ใช้งาน (Username)</label>
                <input type="text" name="username">
            </div>
            <div class="form-group">
                <label>รหัสผ่าน (Password)</label>
                <input type="password" name="password">
            </div>
            
            <button type="submit">เข้าสู่ระบบ</button>
        </form>

        <!-- ฟอร์มแยกสำหรับปุ่ม Try Mode เพื่อป้องกันการติด Validate ของช่องกรอกข้อมูล -->
        <form action="" method="POST">
            <button type="submit" name="try_mode" value="1" class="btn-try-mode">ทดลองใช้งาน (Try Mode)</button>
        </form>
        
        <div class="link-text">
            ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a>
        </div>
    </div>
</body>
</html>