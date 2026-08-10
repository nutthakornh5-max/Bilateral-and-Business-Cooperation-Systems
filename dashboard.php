<?php
// เปิดแสดง Error ชั่วคราวเพื่อตรวจสอบสาเหตุ
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db.php';

// 1. ตรวจสอบการเข้าสู่ระบบ หากยังไม่ได้ล็อกอิน ให้เด้งกลับหน้า login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// กำหนดชื่อผู้ใช้งานสำหรับแสดงผล
$userFullname = $_SESSION['fullname'] ?? 'ผู้ใช้งาน';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก - Dashboard BBCS</title>
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

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Prompt', sans-serif;
        }

        body {
            margin: 0;
            padding: 30px 20px;
            overflow-x: hidden;
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

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 10px 0 15px 0;
            color: #cbd5e1;
            font-size: 14px;
        }
        .feature-list li {
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }
        .feature-list li::before {
            content: "•";
            color: #00f2fe;
            position: absolute;
            left: 0;
            font-weight: bold;
            font-size: 16px;
        }

        .top-bar-announcement {
            width: 100%;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #cbd5e1;
            backdrop-filter: blur(25px) saturate(180%);
            -webkit-backdrop-filter: blur(25px) saturate(180%);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            transition: all 0.4s ease;
        }
        .top-bar-announcement:hover {
            border-color: var(--glass-border-hover);
            box-shadow: 0 20px 40px rgba(0, 242, 254, 0.15);
            transform: translateY(-2px);
        }
        .top-bar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .top-bar-badge {
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            color: #090d16;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
        }

        .navbar {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--glass-bg);
            backdrop-filter: blur(25px) saturate(180%);
            -webkit-backdrop-filter: blur(25px) saturate(180%);
            padding: 15px 30px;
            border-radius: 16px;
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
            font-size: 15px;
            text-shadow: 0 0 15px rgba(0, 242, 254, 0.5);
        }

        .navbar .nav-links {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        /* เอฟเฟค Apple Liquid Glass สำหรับปุ่มแถบเครื่องมือ */
        .liquid-glass-btn {
            color: #f8fafc !important;
            text-decoration: none !important;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 99px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px) saturate(200%);
            -webkit-backdrop-filter: blur(16px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 
                0 4px 24px -1px rgba(0, 0, 0, 0.2),
                inset 0 1px 1px rgba(255, 255, 255, 0.25),
                inset 0 -1px 1px rgba(0, 0, 0, 0.2);
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
            top: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.22), rgba(255, 255, 255, 0));
            border-radius: 99px 99px 0 0;
            pointer-events: none;
        }

        .liquid-glass-btn:hover, 
        .liquid-glass-btn:active {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.35);
            transform: translateY(-2px) scale(1.02);
            box-shadow: 
                0 8px 32px 0 rgba(0, 242, 254, 0.25),
                inset 0 1px 2px rgba(255, 255, 255, 0.4),
                inset 0 -1px 2px rgba(0, 0, 0, 0.3);
        }

        .liquid-glass-btn.logout {
            background: rgba(248, 113, 113, 0.08);
            border-color: rgba(248, 113, 113, 0.2);
        }

        .liquid-glass-btn.logout:hover {
            background: rgba(248, 113, 113, 0.2);
            border-color: rgba(248, 113, 113, 0.4);
            box-shadow: 
                0 8px 32px 0 rgba(248, 113, 113, 0.3),
                inset 0 1px 2px rgba(255, 255, 255, 0.3),
                inset 0 -1px 2px rgba(0, 0, 0, 0.3);
            color: #fca5a5 !important;
        }

        .glass-container {
            width: 100%;
            max-width: 100%;
            background: var(--glass-bg);
            backdrop-filter: blur(25px) saturate(180%);
            -webkit-backdrop-filter: blur(25px) saturate(180%);
            border-radius: 24px;
            border: 1px solid var(--glass-border);
            padding: 35px;
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

        .info-card, .grid-card {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        .info-card:hover, .grid-card:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: var(--glass-border-hover);
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5), inset 0 0 15px rgba(0, 242, 254, 0.15);
        }

        .chatbot-toggler {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            color: #090d16;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(0, 242, 254, 0.4), inset 0 2px 3px rgba(255, 255, 255, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            z-index: 1000;
            transition: transform 0.3s ease, background 0.3s ease, box-shadow 0.3s ease;
        }
        .chatbot-toggler:hover {
            transform: scale(1.1) rotate(5deg);
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            box-shadow: 0 15px 30px rgba(0, 242, 254, 0.6);
        }

        .chatbot-container {
            position: fixed;
            bottom: 95px;
            right: 25px;
            width: 380px;
            max-width: calc(100vw - 40px);
            height: 500px;
            max-height: calc(100vh - 120px);
            background: rgba(9, 13, 22, 0.92);
            backdrop-filter: blur(30px) saturate(180%);
            -webkit-backdrop-filter: blur(30px) saturate(180%);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.7);
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index: 1000;
        }
        .chatbot-container.active {
            display: flex;
        }

        .chat-header {
            padding: 15px 20px;
            background: rgba(0, 242, 254, 0.08);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #00f2fe;
            font-weight: 600;
        }
        .chat-close {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 18px;
            padding: 5px;
            transition: color 0.2s;
        }
        .chat-close:hover {
            color: #f87171;
        }
        .chat-body {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            font-size: 14px;
        }
        .chat-message-wrapper {
            display: flex;
            flex-direction: column;
            max-width: 85%;
        }
        .chat-message-wrapper.bot {
            align-self: flex-start;
        }
        .chat-message-wrapper.user {
            align-self: flex-end;
        }
        .chat-message {
            padding: 10px 14px;
            border-radius: 12px;
            line-height: 1.4;
            word-break: break-word;
        }
        .chat-message.bot {
            background: rgba(255, 255, 255, 0.07);
            color: #cbd5e1;
            border-bottom-left-radius: 3px;
        }
        .chat-message.user {
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            color: #090d16;
            border-bottom-right-radius: 3px;
            font-weight: 600;
        }
        
        .speak-btn {
            background: none;
            border: none;
            color: #00f2fe;
            font-size: 12px;
            cursor: pointer;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 0;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        .speak-btn:hover {
            opacity: 1;
            text-decoration: underline;
        }

        .chat-suggestions {
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.2);
            max-height: 180px;
            overflow-y: auto;
        }
        .suggestion-chip {
            background: rgba(0, 242, 254, 0.08);
            border: 1px solid rgba(0, 242, 254, 0.25);
            color: #00f2fe;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 13px;
            cursor: pointer;
            text-align: left;
            transition: all 0.25s ease;
        }
        .suggestion-chip:hover {
            background: #00f2fe;
            color: #090d16;
            transform: translateX(4px);
            font-weight: 500;
        }

        .developer-credit {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
            color: #94a3b8;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            .top-bar-announcement {
                flex-direction: column;
                gap: 8px;
                text-align: center;
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
            .chatbot-container {
                bottom: 0;
                right: 0;
                left: 0;
                width: 100%;
                max-width: 100%;
                height: 85vh;
                border-radius: 24px 24px 0 0;
            }
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        
        <div class="top-bar-announcement">
            <div class="top-bar-left">
                <span class="top-bar-badge">การแจ้งเตือน</span>
                <span>ยินดีต้อนรับเข้าสู่ระบบจัดการข้อมูลทวิภาคี (BBCS)</span>
            </div>
            <div style="color: #00f2fe; font-weight: 500;">
                สถานะระบบ: ปกติ 🟢
            </div>
        </div>

        <div class="navbar">
            <div class="logo">🌐 ระบบความร่วมมือทวิภาคี BBCS</div>
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
            <h2 style="color: #ffffff; text-align: center; margin-top: 0; font-size: clamp(18px, 4vw, 24px); text-shadow: 0 0 15px rgba(0, 242, 254, 0.4);">ยินดีต้อนรับสู่ระบบความร่วมมือทวิภาคี, คุณ <?= htmlspecialchars($userFullname) ?> 👋</h2>
            
            <p style="color: #cbd5e1; text-align: center; margin-bottom: 5px; font-size: 14px;">
                ระบบบริหารจัดการข้อมูลความร่วมมือระหว่างสถานศึกษาและสถานประกอบการด้วยเทคโนโลยีสมัยใหม่
            </p>
            <p style="color: #cbd5e1; text-align: center; margin-bottom: 25px; font-size: 14px;">
                (Bilateral and Business Cooperation Systems)
            </p>

            <div class="info-card" style="padding: 25px; margin-bottom: 25px;">
                <h3 style="color: #00f2fe; margin-bottom: 12px; font-size: 17px; margin-top: 0;">🏛️ เกี่ยวกับระบบกลาง BBCS</h3>
                <p style="color: #cbd5e1; font-size: 14px; line-height: 1.6; margin-bottom: 15px;">
                    <strong>BBCS (Bilateral and Business Cooperation Systems)</strong> คือระบบฐานข้อมูลกลางที่พัฒนาขึ้นเพื่อใช้ในการบริหารจัดการและขับเคลื่อนงานความร่วมมือระหว่างสถานศึกษาและสถานประกอบการ (ภาคธุรกิจ/อุตสาหกรรม) โดยเฉพาะอย่างยิ่งในรูปแบบการจัดการศึกษาด้านอาชีวศึกษาระบบทวิภาคี ทำหน้าที่เป็นศูนย์กลาง (Centralized Database) ในการบูรณาการข้อมูลทั้งหมด เพื่อให้เกิดความสะดวก รวดเร็ว โปร่งใส และนำไปใช้ประโยชน์ในการวางแผน วิเคราะห์ และรายงานผลได้อย่างมีประสิทธิภาพ
                </p>

                <h3 style="color: #00f2fe; margin-bottom: 10px; font-size: 16px;">🔑 องค์ประกอบหลักและฟังก์ชันการทำงานของระบบ BBCS</h3>
                
                <div style="margin-bottom: 12px;">
                    <strong style="color: #00f2fe; font-size: 14px;">1. การบริหารจัดการข้อมูลสถานประกอบการ (Enterprise Management)</strong>
                    <ul class="feature-list">
                        <li><strong>ทำเนียบสถานประกอบการ:</strong> รวบรวมข้อมูลรายละเอียด เช่น ที่ตั้ง ประเภทธุรกิจ ขนาดกิจการ และผู้ประสานงาน</li>
                        <li><strong>การประเมินความพร้อม:</strong> บันทึกผลการประเมินมาตรฐานและความพร้อม เช่น ความปลอดภัย เครื่องมืออุปกรณ์ และพี่เลี้ยง (Mentor)</li>
                        <li><strong>โควต้ารับนักเรียน/นักศึกษา:</strong> จัดการข้อมูลจำนวนอัตราว่างที่สถานประกอบการสามารถรองรับนักศึกษาฝึกงานในแต่ละสาขาวิชา</li>
                    </ul>
                </div>

                <div style="margin-bottom: 12px;">
                    <strong style="color: #00f2fe; font-size: 14px;">2. การบริหารจัดการบันทึกข้อตกลงความร่วมมือ (MOU Management)</strong>
                    <ul class="feature-list">
                        <li><strong>ฐานข้อมูล MOU:</strong> จัดเก็บข้อมูลสถานะของบันทึกข้อตกลงความร่วมมือระหว่างสถานศึกษากับสถานประกอบการ</li>
                        <li><strong>การติดตามอายุสัญญา:</strong> แจ้งเตือนสถานะความร่วมมือ เช่น สัญญาที่กำลังจะหมดอายุ เพื่อให้เจ้าหน้าที่ดำเนินการต่ออายุ</li>
                        <li><strong>การจับคู่วิชาชีพ:</strong> เชื่อมโยงข้อมูล MOU กับสาขาวิชาที่เกี่ยวข้องเพื่อให้เห็นภาพความร่วมมือในแต่ละด้าน</li>
                    </ul>
                </div>

                <div style="margin-bottom: 12px;">
                    <strong style="color: #00f2fe; font-size: 14px;">3. การจัดการและการรายงานผลการดำเนินงานทวิภาคี (Dual Vocational Education Reporting)</strong>
                    <ul class="feature-list">
                        <li><strong>การบันทึกข้อมูลการฝึกอาชีพ:</strong> ติดตามข้อมูลการส่งนักเรียน/นักศึกษาเข้าฝึกประสบการณ์วิชาชีพ</li>
                        <li><strong>การประเมินผลการฝึก:</strong> บันทึกผลการเรียน คะแนน หรือสมรรถนะ โดยความร่วมมือระหว่างครูนิเทศก์และพี่เลี้ยง</li>
                        <li><strong>การออกรายงานสรุป (Reports):</strong> สร้างรายงานสำหรับผู้บริหาร สถานศึกษา หน่วยงานต้นสังกัด (เช่น สอศ.) หรือการประกันคุณภาพ</li>
                    </ul>
                </div>

                <div style="margin-bottom: 15px;">
                    <strong style="color: #00f2fe; font-size: 14px;">4. ระบบสิทธิ์และการรักษาความปลอดภัย (Access Control)</strong>
                    <ul class="feature-list">
                        <li>กำหนดระดับการใช้งานตามบทบาทหน้าที่ เช่น ผู้ดูแลระบบ (Admin), ผู้บริหาร, ครูนิเทศก์, เจ้าหน้าที่สถานประกอบการ และนักศึกษา</li>
                    </ul>
                </div>

                <h3 style="color: #00f2fe; margin-bottom: 10px; font-size: 16px;">🌟 ประโยชน์สำคัญของระบบ BBCS</h3>
                <ul class="feature-list" style="margin-bottom: 0;">
                    <li><strong>ลดความซ้ำซ้อนของข้อมูล:</strong> รวมข้อมูลการทำความร่วมมือไว้ในที่เดียว ไม่ต้องแยกเก็บเอกสารกระดาษ</li>
                    <li><strong>ความโปร่งใสและตรวจสอบได้:</strong> เรียกดูสถิติจำนวนสถานประกอบการ นักศึกษาฝึกงาน และสถานะ MOU ได้แบบเรียลไทม์</li>
                    <li><strong>สนับสนุนการตัดสินใจ:</strong> นำข้อมูลสถิติไปวางแผนขยายความร่วมมือกับภาคเอกชน หรือปรับปรุงหลักสูตรให้ตรงกับตลาดแรงงาน</li>
                </ul>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px;">
                <div class="grid-card" style="padding: 20px;">
                    <h3 style="color: #00f2fe; margin-bottom: 8px; font-size: 15px; margin-top: 0;">🏢 สถานประกอบการ</h3>
                    <p style="font-size: 13px; color: #cbd5e1; margin: 0;">จัดการข้อมูลสถานประกอบการคู่ความร่วมมือ และติดตามสถานะการฝึกอาชีพ</p>
                </div>
                <div class="grid-card" style="padding: 20px;">
                    <h3 style="color: #00f2fe; margin-bottom: 8px; font-size: 15px; margin-top: 0;">🏫 สถานศึกษา</h3>
                    <p style="font-size: 13px; color: #cbd5e1; margin: 0;">ประสานงานและอัปเดตข้อมูลหลักสูตร ทักษะ และอาจารย์นิเทศก์</p>
                </div>
                <div class="grid-card" style="padding: 20px;">
                    <h3 style="color: #00f2fe; margin-bottom: 8px; font-size: 15px; margin-top: 0;">⚙️ เกี่ยวกับระบบ</h3>
                    <p style="font-size: 13px; color: #cbd5e1; margin: 0;">ระบบทวิภาคีอัจฉริยะช่วยยกระดับมาตรฐานคุณภาพการจัดการอาชีวศึกษา</p>
                </div>
            </div>

            <!-- ส่วนเครดิตผู้พัฒนา -->
            <div class="developer-credit">
                พัฒนาโดย: นายณัฎฐากร สันทัดงาม nutthakornh5@gmail.com
            </div>
        </div>
    </div>

    <button class="chatbot-toggler" id="chatbotToggler" aria-label="เปิดแชทบอท">💬</button>

    <div class="chatbot-container" id="chatbotContainer">
        <div class="chat-header">
            <span>🤖 BBCS Assistant</span>
            <button class="chat-close" id="chatClose">✕</button>
        </div>
        <div class="chat-body" id="chatBody">
            <div class="chat-message-wrapper bot">
                <div class="chat-message bot">สวัสดีครับคุณ <?= htmlspecialchars($userFullname) ?> มีอะไรให้ระบบ BBCS ช่วยเหลือวันนี้ไหมครับ? สามารถเลือกหัวข้อคำถามด้านล่างได้เลยครับ</div>
                <button class="speak-btn" onclick="speakText(this)">🔊 ฟังเสียง</button>
            </div>
        </div>
        
        <div class="chat-suggestions" id="chatSuggestions">
            <button class="suggestion-chip" data-question="BBCS คืออะไร?">📌 BBCS คืออะไร?</button>
            <button class="suggestion-chip" data-question="วิธีเพิ่มข้อมูล">➕ วิธีเพิ่มข้อมูล</button>
            <button class="suggestion-chip" data-question="ระบบจับคู่ทำงานอย่างไร?">🤝 ระบบจับคู่ทำงานอย่างไร?</button>
            <button class="suggestion-chip" data-question="การติดตามผลฝึกงาน">📊 การติดตามผลฝึกงาน</button>
            <button class="suggestion-chip" data-question="แก้ไขโปรไฟล์อย่างไร">👤 แก้ไขโปรไฟล์อย่างไร</button>
            <button class="suggestion-chip" data-question="ตรวจสอบสถานะ MOU">📄 ตรวจสอบสถานะ MOU</button>
        </div>
    </div>

    <script>
        const toggler = document.getElementById('chatbotToggler');
        const container = document.getElementById('chatbotContainer');
        const closeBtn = document.getElementById('chatClose');
        const chatBody = document.getElementById('chatBody');
        const suggestionChips = document.querySelectorAll('.suggestion-chip');

        toggler.addEventListener('click', () => {
            container.classList.toggle('active');
        });

        closeBtn.addEventListener('click', () => {
            container.classList.remove('active');
        });

        function speakText(buttonElement) {
            const messageBox = buttonElement.previousElementSibling;
            const textToSpeak = messageBox.textContent;

            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const utterance = new SpeechSynthesisUtterance(textToSpeak);
                utterance.lang = 'th-TH';
                utterance.rate = 1.0;
                utterance.pitch = 1.0;
                window.speechSynthesis.speak(utterance);
            } else {
                alert('เบราว์เซอร์ของคุณไม่รองรับระบบการออกเสียง');
            }
        }

        function handleUserMessage(text) {
            if (!text.trim()) return;

            const userWrapper = document.createElement('div');
            userWrapper.className = 'chat-message-wrapper user';
            
            const userMsg = document.createElement('div');
            userMsg.className = 'chat-message user';
            userMsg.textContent = text;
            userWrapper.appendChild(userMsg);
            
            chatBody.appendChild(userWrapper);
            chatBody.scrollTop = chatBody.scrollHeight;

            setTimeout(() => {
                let botReply = '';
                const lowerText = text.toLowerCase();

                if (lowerText.includes('bbcs')) {
                    botReply = 'BBCS (Bilateral and Business Cooperation Systems) คือระบบฐานข้อมูลกลางในการบริหารจัดการงานความร่วมมือระหว่างสถานศึกษาและสถานประกอบการ อาชีวศึกษาระบบทวิภาคีครับ';
                } else if (lowerText.includes('เพิ่มข้อมูล') || lowerText.includes('add')) {
                    botReply = 'คุณสามารถเพิ่มข้อมูลใหม่ได้โดยคลิกที่เมนู เพิ่ม บนแถบเมนูด้านบน เพื่อกรอกรายละเอียดสถานประกอบการหรือข้อมูลความร่วมมือครับ';
                } else if (lowerText.includes('จับคู่') || lowerText.includes('matching')) {
                    botReply = 'ระบบจับคู่ จะช่วยเชื่อมโยงข้อมูล MOU กับสาขาวิชาที่เกี่ยวข้อง เพื่อให้เห็นภาพความร่วมมือและรองรับโควตานักศึกษาฝึกงานครับ';
                } else if (lowerText.includes('ติดตาม') || lowerText.includes('ฝึกงาน')) {
                    botReply = 'การติดตามผลฝึกงานรองรับการบันทึกข้อมูลและประเมินสมรรถนะของนักศึกษาจากการปฏิบัติงานจริงโดยครูนิเทศก์และพี่เลี้ยงครับ';
                } else if (lowerText.includes('โปรไฟล์') || lowerText.includes('แก้ไข')) {
                    botReply = 'คุณสามารถแก้ไขข้อมูลส่วนตัว รหัสผ่าน หรือตั้งค่าระบบได้ที่เมนู โปรไฟล์ หรือ การตั้งค่า ที่แถบเมนูด้านบนครับ';
                } else if (lowerText.includes('mou')) {
                    botReply = 'คุณสามารถตรวจสอบสถานะและติดตามอายุสัญญาของบันทึกข้อตกลงความร่วมมือ MOU ได้ที่เมนู รายการ ครับ';
                } else {
                    botReply = 'รับทราบครับ ระบบกำลังประมวลผลคำขอของคุณเกี่ยวกับ "' + text + '" สามารถเลือกหัวข้ออื่นเพิ่มเติมได้เลยครับ';
                }

                const botWrapper = document.createElement('div');
                botWrapper.className = 'chat-message-wrapper bot';

                const botMsg = document.createElement('div');
                botMsg.className = 'chat-message bot';
                botMsg.textContent = botReply;

                const speakButton = document.createElement('button');
                speakButton.className = 'speak-btn';
                speakButton.innerHTML = '🔊 ฟังเสียง';
                speakButton.onclick = function() { speakText(this); };

                botWrapper.appendChild(botMsg);
                botWrapper.appendChild(speakButton);
                chatBody.appendChild(botWrapper);

                chatBody.scrollTop = chatBody.scrollHeight;
            }, 500);
        }

        suggestionChips.forEach(chip => {
            chip.addEventListener('click', () => {
                const questionText = chip.getAttribute('data-question');
                handleUserMessage(questionText);
            });
        });
    </script>
</body>
</html>