<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก - Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* ตั้งค่าพื้นฐานและการรองรับทุกอุปกรณ์ */
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 15px 0;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* เพิ่มเอฟเฟกต์แสงพื้นหลังแบบเรืองแสงขยับได้ */
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

        /* จัดโครงสร้างหน้าจอหลักให้อยู่กึ่งกลางและยืดหยุ่น */
        .main-wrapper {
            width: 100%;
            max-width: 1000px;
            margin: auto;
            padding: 0 15px;
            box-sizing: border-box;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 15px 0 25px 0;
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
            color: #38bdf8;
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        /* ปรับแต่ง Navbar แบบ Glassmorphism และมีเอฟเฟกต์ Hover */
        .navbar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        .navbar:hover {
            border-color: rgba(56, 189, 248, 0.3);
            box-shadow: 0 15px 35px rgba(56, 189, 248, 0.1);
        }
        .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }
        .nav-links a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s, text-shadow 0.3s;
        }
        .nav-links a:hover {
            color: #38bdf8;
            text-shadow: 0 0 8px rgba(56, 189, 248, 0.4);
        }

        /* กล่องเนื้อหาหลัก (Glass Container) พร้อมเอฟเฟกต์ Hover */
        .glass-container {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            transition: transform 0.4s cubic-bezier(0.03, 0.98, 0.52, 0.99), box-shadow 0.4s ease, border-color 0.4s ease;
        }
        .glass-container:hover {
            transform: translateY(-4px);
            box-shadow: 0 25px 50px rgba(56, 189, 248, 0.15);
            border-color: rgba(56, 189, 248, 0.3);
        }

        /* การ์ดเนื้อหาย่อยด้านใน ให้ขยับรับเมาส์ได้ */
        .info-card, .grid-card {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }
        .info-card:hover, .grid-card:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(56, 189, 248, 0.3);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        /* สไตล์สำหรับแชทบอท รองรับทุกขนาดหน้าจอ */
        .chatbot-toggler {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 55px;
            height: 55px;
            background: #38bdf8;
            color: #0f172a;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(56, 189, 248, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            z-index: 1000;
            transition: transform 0.3s ease, background 0.3s ease, box-shadow 0.3s ease;
        }
        .chatbot-toggler:hover {
            transform: scale(1.1) rotate(5deg);
            background: #0ea5e9;
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.6);
        }

        .chatbot-container {
            position: fixed;
            bottom: 85px;
            right: 20px;
            width: 380px;
            max-width: calc(100vw - 40px);
            height: 500px;
            max-height: calc(100vh - 120px);
            background: rgba(15, 23, 42, 0.96);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            z-index: 1000;
            display: none; /* ซ่อนไว้ก่อนเริ่มต้น */
        }
        .chatbot-container.active {
            display: flex;
        }

        .chat-header {
            padding: 12px 15px;
            background: rgba(56, 189, 248, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #38bdf8;
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
            padding: 12px;
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
            border-radius: 10px;
            line-height: 1.4;
            word-break: break-word;
        }
        .chat-message.bot {
            background: rgba(255, 255, 255, 0.05);
            color: #cbd5e1;
            border-bottom-left-radius: 2px;
        }
        .chat-message.user {
            background: #38bdf8;
            color: #0f172a;
            border-bottom-right-radius: 2px;
            font-weight: 500;
        }
        
        /* สไตล์ปุ่มกดฟังเสียง */
        .speak-btn {
            background: none;
            border: none;
            color: #38bdf8;
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

        /* สไตล์สำหรับกล่องคำถามแนะนำ (Quick Questions) */
        .chat-suggestions {
            padding: 8px 12px;
            display: flex;
            gap: 6px;
            overflow-x: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            background: rgba(255, 255, 255, 0.01);
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
        .chat-suggestions::-webkit-scrollbar {
            height: 4px;
        }
        .chat-suggestions::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
        }
        .suggestion-chip {
            background: rgba(56, 189, 248, 0.08);
            border: 1px solid rgba(56, 189, 248, 0.25);
            color: #38bdf8;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.25s ease;
        }
        .suggestion-chip:hover {
            background: #38bdf8;
            color: #0f172a;
            transform: translateY(-2px);
        }

        .chat-footer {
            padding: 10px 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            gap: 8px;
        }
        .chat-input {
            flex: 1;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 8px 12px;
            border-radius: 8px;
            color: #fff;
            outline: none;
            font-size: 14px;
            min-width: 0;
            transition: border-color 0.3s;
        }
        .chat-input:focus {
            border-color: #38bdf8;
        }
        .chat-send {
            background: #38bdf8;
            border: none;
            color: #0f172a;
            padding: 0 14px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            flex-shrink: 0;
            transition: background 0.3s, transform 0.2s;
        }
        .chat-send:hover {
            background: #0ea5e9;
            transform: translateY(-1px);
        }

        /* Responsive สำหรับมือถือขนาดเล็กเป็นพิเศษ */
        @media (max-width: 480px) {
            body {
                padding: 8px;
            }
            .navbar {
                padding: 10px 12px;
            }
            .chatbot-container {
                bottom: 0;
                right: 0;
                left: 0;
                width: 100%;
                max-width: 100%;
                height: 85vh;
                border-radius: 16px 16px 0 0;
                border-left: none;
                border-right: none;
            }
            .chatbot-toggler {
                bottom: 15px;
                right: 15px;
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <!-- Navbar -->
        <div class="navbar">
            <div style="font-weight: 600; color: #38bdf8; font-size: 14px;">🌐 BBCS</div>
            <div class="nav-links">
                <a href="dashboard.php">หน้าหลัก</a>
                <a href="list.php">รายการ</a>
                <a href="add.php">เพิ่ม</a>
                <a href="profile.php">โปรไฟล์</a>
                <a href="settings.php">ตั้งค่า</a>
                <a href="logout.php" style="color: #f87171;">ออก</a>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="glass-container wide" style="padding: 25px; max-width: 100%;">
            <h2 style="color: #ffffff; text-align: center; margin-top: 0; font-size: clamp(18px, 4vw, 24px);">ยินดีต้อนรับสู่ระบบความร่วมมือทวิภาคี, คุณ <?= htmlspecialchars($_SESSION['fullname']) ?> 👋</h2>
            
            <p style="color: #94a3b8; text-align: center; margin-bottom: 25px; font-size: 14px;">
                ระบบบริหารจัดการข้อมูลความร่วมมือระหว่างสถานศึกษาและสถานประกอบการด้วยเทคโนโลยีสมัยใหม่
            </p>

            <!-- ข้อมูลระบบ BBCS -->
            <div class="info-card" style="padding: 20px; margin-bottom: 25px;">
                <h3 style="color: #38bdf8; margin-bottom: 12px; font-size: 16px; margin-top: 0;">🏛️ เกี่ยวกับระบบหลัก BBCS</h3>
                <p style="color: #cbd5e1; font-size: 14px; line-height: 1.6; margin-bottom: 15px;">
                    ระบบบริหารจัดการข้อมูลความร่วมมือระหว่างสถานศึกษาและสถานประกอบการหลักคือ <strong>BBCS</strong> (Bilateral and Business Cooperation Systems) ซึ่งเป็นระบบฐานข้อมูลกลาง สำหรับใช้จัดการข้อมูลสถานประกอบการ บันทึกข้อตกลงความร่วมมือ (MOU) และรายงานผลการดำเนินงานทวิภาคี
                </p>

                <h3 style="color: #38bdf8; margin-bottom: 12px; font-size: 16px;">⭐ คุณสมบัติหลักของระบบ BBCS</h3>
                <ul class="feature-list" style="margin-bottom: 0;">
                    <li><strong>ทะเบียนสถานประกอบการ:</strong> จัดเก็บประวัติ ข้อมูลติดต่อ และประเภทกิจการ</li>
                    <li><strong>บันทึกความร่วมมือ (MOU):</strong> บันทึกและติดตามสถานะการทำข้อตกลงความร่วมมือ</li>
                    <li><strong>รายงานผลการดำเนินงาน:</strong> สรุปสถิติและข้อมูลการฝึกประสบการณ์วิชาชีพ</li>
                </ul>
            </div>
            
            <!-- การ์ดเนื้อหาด้านล่าง -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px;">
                <div class="grid-card" style="padding: 18px;">
                    <h3 style="color: #38bdf8; margin-bottom: 8px; font-size: 15px; margin-top: 0;">🏢 สถานประกอบการ</h3>
                    <p style="font-size: 13px; color: #cbd5e1; margin: 0;">จัดการข้อมูลสถานประกอบการคู่ความร่วมมือ และติดตามสถานะการฝึกอาชีพ</p>
                </div>
                <div class="grid-card" style="padding: 18px;">
                    <h3 style="color: #38bdf8; margin-bottom: 8px; font-size: 15px; margin-top: 0;">🏫 สถานศึกษา</h3>
                    <p style="font-size: 13px; color: #cbd5e1; margin: 0;">ประสานงานและอัปเดตข้อมูลหลักสูตร ทักษะ และอาจารย์นิเทศก์</p>
                </div>
                <div class="grid-card" style="padding: 18px;">
                    <h3 style="color: #38bdf8; margin-bottom: 8px; font-size: 15px; margin-top: 0;">⚙️ เกี่ยวกับระบบ</h3>
                    <p style="font-size: 13px; color: #cbd5e1; margin: 0;">ระบบทวิภาคีอัจฉริยะช่วยยกระดับมาตรฐานคุณภาพการจัดการอาชีวศึกษา</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ปุ่มเปิด-ปิด Chatbot -->
    <button class="chatbot-toggler" id="chatbotToggler" aria-label="เปิดแชทบอท">💬</button>

    <!-- หน้าต่าง Chatbot Widget -->
    <div class="chatbot-container" id="chatbotContainer">
        <div class="chat-header">
            <span>🤖 BBCS Assistant</span>
            <button class="chat-close" id="chatClose">✕</button>
        </div>
        <div class="chat-body" id="chatBody">
            <div class="chat-message-wrapper bot">
                <div class="chat-message bot">สวัสดีครับคุณ <?= htmlspecialchars($_SESSION['fullname']) ?> มีอะไรให้ระบบ BBCS ช่วยเหลือวันนี้ไหมครับ? สามารถเลือกคำถามด้านล่างหรือพิมพ์สอบถามได้เลยครับ</div>
                <button class="speak-btn" onclick="speakText(this)">🔊 ฟังเสียง</button>
            </div>
        </div>
        
        <!-- แถบรวมคำถามอัตโนมัติ (Quick Questions) -->
        <div class="chat-suggestions" id="chatSuggestions">
            <button class="suggestion-chip" data-question="BBCS คืออะไร?">BBCS คืออะไร?</button>
            <button class="suggestion-chip" data-question="วิธีเพิ่มข้อมูล">วิธีเพิ่มข้อมูล</button>
            <button class="suggestion-chip" data-question="ระบบจับคู่ทำงานอย่างไร?">ระบบจับคู่ทำงานอย่างไร?</button>
            <button class="suggestion-chip" data-question="การติดตามผลฝึกงาน">การติดตามผลฝึกงาน</button>
            <button class="suggestion-chip" data-question="แก้ไขโปรไฟล์อย่างไร">แก้ไขโปรไฟล์อย่างไร</button>
        </div>

        <div class="chat-footer">
            <input type="text" class="chat-input" id="chatInput" placeholder="พิมพ์ข้อความที่นี่...">
            <button class="chat-send" id="chatSend">ส่ง</button>
        </div>
    </div>

    <!-- JavaScript สำหรับควบคุม Chatbot และการออกเสียงภาษาไทย -->
    <script>
        const toggler = document.getElementById('chatbotToggler');
        const container = document.getElementById('chatbotContainer');
        const closeBtn = document.getElementById('chatClose');
        const chatBody = document.getElementById('chatBody');
        const chatInput = document.getElementById('chatInput');
        const chatSend = document.getElementById('chatSend');
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
            chatInput.value = '';
            chatBody.scrollTop = chatBody.scrollHeight;

            setTimeout(() => {
                let botReply = '';
                const lowerText = text.toLowerCase();

                if (lowerText.includes('bbcs')) {
                    botReply = 'BBCS (Bilateral and Business Cooperation Systems) คือระบบบริหารจัดการข้อมูลความร่วมมือระหว่างสถานศึกษาและสถานประกอบการ ใช้จัดการข้อมูลสถานประกอบการและบันทึกข้อตกลงความร่วมมือ MOU ครับ';
                } else if (lowerText.includes('เพิ่มข้อมูล') || lowerText.includes('add')) {
                    botReply = 'คุณสามารถเพิ่มข้อมูลใหม่ได้โดยคลิกที่เมนู เพิ่ม บนแถบเมนูด้านบน เพื่อกรอกรายละเอียดสถานประกอบการหรือข้อมูลความร่วมมือครับ';
                } else if (lowerText.includes('จับคู่') || lowerText.includes('matching')) {
                    botReply = 'ระบบจับคู่ จะช่วยคัดเลือกนักเรียนให้นักศึกษาฝึกงานตรงกับความต้องการและโควตาของสถานประกอบการโดยอัตโนมัติครับ';
                } else if (lowerText.includes('ติดตาม') || lowerText.includes('ฝึกงาน')) {
                    botReply = 'การติดตามและประเมินผล รองรับการบันทึกเวลาฝึกงาน และประเมินสมรรถนะของนักเรียนแบบเรียลไทม์ครับ';
                } else if (lowerText.includes('โปรไฟล์') || lowerText.includes('แก้ไข')) {
                    botReply = 'คุณสามารถแก้ไขข้อมูลส่วนตัว รหัสผ่าน หรือตั้งค่าระบบได้ที่เมนู โปรไฟล์ หรือ ตั้งค่า ที่แถบเมนูด้านบนครับ';
                } else if (lowerText.includes('mou')) {
                    botReply = 'คุณสามารถตรวจสอบสถานะการทำข้อตกลงความร่วมมือ MOU ได้ที่เมนู รายการ ครับ';
                } else {
                    botReply = 'รับทราบครับ ระบบกำลังประมวลผลคำขอของคุณเกี่ยวกับ ' + text + ' หากต้องการความช่วยเหลือเร่งด่วนสามารถติดต่อผู้ดูแลระบบได้เลยครับ';
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

        chatSend.addEventListener('click', () => {
            handleUserMessage(chatInput.value);
        });

        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                handleUserMessage(chatInput.value);
            }
        });

        suggestionChips.forEach(chip => {
            chip.addEventListener('click', () => {
                const questionText = chip.getAttribute('data-question');
                handleUserMessage(questionText);
            });
        });
    </script>
</body>
</html>