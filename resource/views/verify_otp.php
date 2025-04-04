<?php
// Pastikan session hanya dimulai jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menampilkan notifikasi sukses atau error
if (isset($_SESSION['success_message'])) {
    echo "<div class='alert alert-success' role='alert'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger' role='alert'>" . $_SESSION['error_message'] . "</div>";
    unset($_SESSION['error_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/otp-style.css">
    <script src="../js/timerOtp.js"></script>
    <script>
        function collectOtp() {
            const otpInputs = document.querySelectorAll('.otp-input');
            let otp = '';
            otpInputs.forEach(input => {
                otp += input.value;
            });
            document.getElementById('otp-hidden').value = otp;
        }

        function moveToNext(current, nextId) {
            if (current.value.length === current.maxLength && nextId) {
                document.getElementById(nextId).focus();
            }
        }
    </script>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="otp-box text-center">
            <h2>Cek Inbox Pada Email</h2>
            <p>Masukan 6 digit kode OTP yang dikirimkan ke Email</p>
            <p id="countdown" class="countdown">2:00</p>

            <!-- Form untuk verifikasi OTP -->
            <form action="../../controllers/verifyOtpController.php" method="POST" onsubmit="collectOtp()">
                <div class="d-flex justify-content-center mb-3">
                    <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 'otp2')" id="otp1" required>
                    <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 'otp3')" id="otp2" required>
                    <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 'otp4')" id="otp3" required>
                    <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 'otp5')" id="otp4" required>
                    <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 'otp6')" id="otp5" required>
                    <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, '')" id="otp6" required>
                </div>

                <!-- Input hidden untuk menggabungkan OTP -->
                <input type="hidden" id="otp-hidden" name="otp" value="">

                <button type="submit" id="submit-btn" class="btn btn-primary w-100">Verify OTP</button>
            </form>

            <a href="../../controllers/resendOtpController.php">Resend OTP</a>
        </div>
    </div>
</body>
</html>
