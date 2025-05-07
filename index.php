<?php
require 'config.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

$messageSent = false;
$error = '';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    if ($stmt->execute()) {
        // Send email using PHPMailer
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($email, $name);
        $mail->addAddress('arrifat40@gmail.com');
        $mail->Subject = 'নতুন বার্তা প্রাপ্তি';
        $mail->Body = "নাম: $name\nইমেল: $email\nবার্তা:\n$message";
        if ($mail->send()) {
            $messageSent = true;
        } else {
            $error = 'মেইল পাঠাতে ব্যর্থ!';
        }
    } else {
        $error = 'ডাটাবেসে তথ্য সংরক্ষণে সমস্যা হয়েছে!';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>যোগাযোগ ফর্ম</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>যোগাযোগ করুন</h2>
    <?php if($messageSent): ?>
        <div class="alert alert-success">আপনার বার্তা সফলভাবে প্রেরিত হয়েছে!</div>
    <?php elseif($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <div class="mb-3">
            <label class="form-label">নাম</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">ইমেল</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">বার্তা</label>
            <textarea name="message" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">জমা দিন</button>
    </form>
</div>
</body>
</html>