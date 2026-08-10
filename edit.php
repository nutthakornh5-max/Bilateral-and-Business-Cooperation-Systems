<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM partnerships WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();
if(!$item) die("ไม่พบข้อมูล");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $partner_name = trim($_POST['partner_name']);
    $type = $_POST['type'];
    $details = trim($_POST['details']);
    $status = trim($_POST['status']);

    $stmt = $pdo->prepare("UPDATE partnerships SET title=?, partner_name=?, type=?, details=?, status=? WHERE id=?");
    $stmt->execute([$title, $partner_name, $type, $details, $status, $id]);
    header("Location: list.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <!-- เพิ่ม Viewport เพื่อรองรับการแสดงผลบนมือถือและทุกอุปกรณ์ -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูล</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            padding: 20px 0;
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

        .glass-container.wide {
            width: 100%;
            max-width: 600px;
            margin: 20px;
            padding: 30px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            transition: transform 0.4s cubic-bezier(0.03, 0.98, 0.52, 0.99), box-shadow 0.4s ease, border-color 0.4s ease;
        }

        /* เอฟเฟกต์เมื่อเอาเมาส์ชี้ที่กล่องฟอร์มแก้ไขข้อมูล */
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

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #cbd5e1;
            transition: color 0.3s;
        }

        .form-group:hover label {
            color: #38bdf8;
        }

        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            color: #ffffff;
            font-size: 16px; /* ป้องกันไม่ให้ iOS ซูมหน้าจออัตโนมัติ */
            outline: none;
            transition: all 0.3s ease;
            font-family: sans-serif;
        }

        .form-group input:hover, 
        .form-group select:hover, 
        .form-group textarea:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .form-group input:focus, 
        .form-group select:focus, 
        .form-group textarea:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: #38bdf8;
            box-shadow: 0 0 12px rgba(56, 189, 248, 0.25);
        }

        /* ปรับแต่ง Dropdown (Select) ให้เข้ากับธีมมืด */
        .form-group select option {
            background-color: #0f172a;
            color: #ffffff;
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

        /* ปรับแต่งเพิ่มเติมสำหรับหน้าจอขนาดเล็ก */
        @media (max-width: 480px) {
            .glass-container.wide {
                margin: 10px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="glass-container wide">
        <h2>แก้ไขข้อมูลความร่วมมือ</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>หัวข้อความร่วมมือ</label>
                <input type="text" name="title" value="<?= htmlspecialchars($item['title']) ?>" required>
            </div>
            <div class="form-group">
                <label>ชื่อสถานประกอบการ / สถานศึกษา</label>
                <input type="text" name="partner_name" value="<?= htmlspecialchars($item['partner_name']) ?>" required>
            </div>
            <div class="form-group">
                <label>ประเภทความร่วมมือ</label>
                <select name="type">
                    <option value="สถานประกอบการ" <?= $item['type']=='สถานประกอบการ'?'selected':'' ?>>สถานประกอบการ</option>
                    <option value="สถานศึกษา" <?= $item['type']=='สถานศึกษา'?'selected':'' ?>>สถานศึกษา</option>
                </select>
            </div>
            <div class="form-group">
                <label>รายละเอียดเพิ่มเติม</label>
                <textarea name="details" rows="4"><?= htmlspecialchars($item['details']) ?></textarea>
            </div>
            <div class="form-group">
                <label>สถานะ</label>
                <input type="text" name="status" value="<?= htmlspecialchars($item['status']) ?>" required>
            </div>
            <button type="submit">บันทึกการแก้ไข</button>
        </form>
        <div class="link-text"><a href="list.php">← ย้อนกลับ</a></div>
    </div>
</body>
</html>