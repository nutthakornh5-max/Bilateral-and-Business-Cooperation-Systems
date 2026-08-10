<?php
// เปิดแสดง Error ชั่วคราวเพื่อตรวจสอบสาเหตุ
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

$stmt = $pdo->query("SELECT * FROM partnerships ORDER BY id DESC");
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้ารายการข้อมูลความร่วมมือทวิภาคี</title>
    <link rel="stylesheet" href="style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap');

        :root {
            --bg-deep: #090d16;
            --primary-glow: #00f2fe;
            --secondary-glow: #4facfe;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.12);
            --glass-border-hover: rgba(0, 242, 254, 0.4);
            --shadow-3d: 0 30px 60px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.15), inset 0 -1px 0 rgba(0, 0, 0, 0.5);
        }

        body {
            margin: 0;
            padding: 30px 20px;
            font-family: 'Prompt', sans-serif;
            background-color: var(--bg-deep);
            background-image: 
                radial-gradient(at 10% 20%, rgba(0, 242, 254, 0.2) 0px, transparent 50%),
                radial-gradient(at 90% 10%, rgba(79, 172, 254, 0.2) 0px, transparent 50%),
                radial-gradient(at 50% 90%, rgba(118, 75, 162, 0.2) 0px, transparent 50%);
            background-attachment: fixed;
            color: #f1f5f9;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            overflow-y: auto;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(9, 13, 22, 0.8);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(0, 242, 254, 0.3);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 242, 254, 0.6);
        }

        .main-wrapper {
            width: 100%;
            max-width: 1000px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        /* ====== Navbar Style ====== */
        .navbar {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--glass-bg);
            backdrop-filter: blur(25px) saturate(180%);
            -webkit-backdrop-filter: blur(25px) saturate(180%);
            padding: 15px 30px;
            border-radius: 20px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .navbar:hover {
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.5);
            transform: translateY(-2px);
            border-color: var(--glass-border-hover);
        }

        .navbar .logo {
            font-weight: 600;
            color: #00f2fe;
            font-size: 16px;
            text-shadow: 0 0 15px rgba(0, 242, 254, 0.5);
        }

        .navbar .nav-links {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        /* ====== Apple Liquid Glass & Liquid Drop Effect ====== */
        .liquid-glass-btn {
            color: #f8fafc !important;
            text-decoration: none !important;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 18px;
            border-radius: 99px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(20px) saturate(220%);
            -webkit-backdrop-filter: blur(20px) saturate(220%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 6px 20px rgba(0, 0, 0, 0.25),
                inset 0 1px 2px rgba(255, 255, 255, 0.4),
                inset 0 -1px 2px rgba(0, 0, 0, 0.3);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            white-space: nowrap;
            -webkit-tap-highlight-color: transparent;
        }

        .liquid-glass-btn::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 10%;
            right: 10%;
            height: 45%;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.45), rgba(255, 255, 255, 0.02));
            border-radius: 99px;
            pointer-events: none;
            filter: blur(0.5px);
        }

        .liquid-glass-btn::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
            opacity: 0;
            transform: scale(0.5);
            transition: opacity 0.5s ease, transform 0.5s ease;
            pointer-events: none;
        }

        .liquid-glass-btn:hover::after {
            opacity: 1;
            transform: scale(1);
        }

        .liquid-glass-btn:hover, 
        .liquid-glass-btn:active {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.45);
            transform: translateY(-3px) scale(1.03);
            box-shadow: 
                0 10px 30px rgba(0, 242, 254, 0.3),
                inset 0 2px 4px rgba(255, 255, 255, 0.6),
                inset 0 -2px 4px rgba(0, 0, 0, 0.4);
        }

        .liquid-glass-btn.logout {
            background: rgba(248, 113, 113, 0.1);
            border-color: rgba(248, 113, 113, 0.3);
        }

        .liquid-glass-btn.logout:hover {
            background: rgba(248, 113, 113, 0.25);
            border-color: rgba(248, 113, 113, 0.5);
            box-shadow: 
                0 10px 30px rgba(248, 113, 113, 0.35),
                inset 0 2px 4px rgba(255, 255, 255, 0.5),
                inset 0 -2px 4px rgba(0, 0, 0, 0.4);
            color: #fca5a5 !important;
        }

        /* ====== Glass Container หลัก ====== */
        .glass-container {
            width: 100%;
            max-width: 100%;
            background: var(--glass-bg);
            backdrop-filter: blur(25px) saturate(180%);
            -webkit-backdrop-filter: blur(25px) saturate(180%);
            border: 1px solid var(--glass-border);
            border-radius: 32px;
            padding: 40px;
            box-shadow: var(--shadow-3d);
            position: relative;
            overflow: hidden;
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.5s ease, border-color 0.5s ease;
        }

        .glass-container:hover {
            transform: translateY(-5px);
            border-color: var(--glass-border-hover);
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.7), inset 0 0 30px rgba(255, 255, 255, 0.08);
        }

        h2 {
            color: #ffffff;
            margin-bottom: 25px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 10px rgba(0, 242, 254, 0.4);
        }

        /* ====== Animated Add Button ====== */
        .btn-add {
            display: inline-block;
            margin-bottom: 25px;
            padding: 12px 22px;
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            color: #090d16;
            text-decoration: none;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(0, 242, 254, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.6);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-add:hover {
            transform: translateY(-4px) scale(1.05);
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            box-shadow: 0 15px 30px rgba(0, 242, 254, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.9);
            border-color: #00f2fe;
        }

        /* ====== Scrollable Table Container ====== */
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
            overflow-x: auto;
            margin-top: 5px;
            padding-right: 5px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        th {
            position: sticky;
            top: 0;
            background: rgba(9, 13, 22, 0.95) !important;
            backdrop-filter: blur(15px);
            z-index: 10;
            color: #00f2fe;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
            padding: 16px 15px;
            text-align: left;
            border-bottom: 2px solid rgba(0, 242, 254, 0.3);
        }

        td {
            padding: 16px 15px;
            text-align: left;
            border: none;
        }

        tbody tr td {
            background: rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-size: 14px;
            color: #e2e8f0;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        tbody tr td:first-child {
            border-left: 1px solid rgba(255, 255, 255, 0.05);
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
        }

        tbody tr td:last-child {
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        tbody tr {
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        tbody tr:hover td {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border-color: rgba(0, 242, 254, 0.3);
        }

        tbody tr:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.6);
        }

        /* ====== Action Links ====== */
        .action-link {
            display: inline-block;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-right: 5px;
        }

        .action-view {
            background: rgba(0, 242, 254, 0.1);
            color: #00f2fe;
            border: 1px solid rgba(0, 242, 254, 0.25);
        }

        .action-view:hover {
            background: #00f2fe;
            color: #090d16;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 242, 254, 0.4);
            font-weight: 600;
        }

        .action-edit {
            background: rgba(251, 191, 36, 0.1);
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, 0.25);
        }

        .action-edit:hover {
            background: #fbbf24;
            color: #090d16;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(251, 191, 36, 0.4);
            font-weight: 600;
        }

        /* รองรับมือถือ */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            .navbar {
                flex-direction: column;
                gap: 15px;
                padding: 15px !important;
            }
            .navbar .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 8px !important;
                width: 100%;
            }
            .liquid-glass-btn {
                padding: 6px 14px;
                font-size: 13px;
                flex: 1 1 auto;
                min-width: 100px;
            }
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <div class="navbar">
            <div class="logo">🌐 ระบบความร่วมมือทวิภาคี BBCS </div>
            <div class="nav-links">
                <a href="dashboard.php" class="liquid-glass-btn">หน้าหลัก</a>
                <a href="list.php" class="liquid-glass-btn">รายการข้อมูล</a>
                <a href="add.php" class="liquid-glass-btn">เพิ่มข้อมูล</a>
                <a href="profile.php" class="liquid-glass-btn">โปรไฟล์</a>
                <a href="settings.php" class="liquid-glass-btn">การตั้งค่า</a>
                <a href="logout.php" class="liquid-glass-btn logout">ออกจากระบบ</a>
            </div>
        </div>

        <div class="glass-container">
            <h2>รายการข้อมูลความร่วมมือ</h2>
            
            <a href="add.php" class="btn-add">✨ + เพิ่มข้อมูลใหม่</a>
            
            <!-- ส่วนตารางข้อมูล -->
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>หัวข้อความร่วมมือ</th>
                            <th>ชื่อคู่สัญญา</th>
                            <th>ประเภท</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($items as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['partner_name']) ?></td>
                            <td>
                                <span style="background: rgba(255,255,255,0.08); padding: 5px 12px; border-radius: 20px; font-size: 12px; border: 1px solid rgba(255,255,255,0.1);">
                                    <?= htmlspecialchars($row['type']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td>
                                <a href="detail.php?id=<?= $row['id'] ?>" class="action-link action-view">ดูรายละเอียด</a>
                                <a href="edit.php?id=<?= $row['id'] ?>" class="action-link action-edit">แก้ไข</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($items)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: #94a3b8;">
                                ไม่มีข้อมูลความร่วมมือในระบบ
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>