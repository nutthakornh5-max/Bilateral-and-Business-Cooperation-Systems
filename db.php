<?php
$host = 'sql112.infinityfree.com'; // เปลี่ยนเป็น Host ของฐานข้อมูลจริงจาก Control Panel ของคุณ
$dbname = 'if0_42590629_dual_system_db'; // ใช้ชื่อฐานข้อมูลที่มีprefix ของโฮสต์
$username = 'if0_42590629';             // ชื่อผู้ใช้งานฐานข้อมูลของคุณ
$password = '2f6AcIGO1KBt';            // รหัสผ่านฐานข้อมูลของคุณ

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>