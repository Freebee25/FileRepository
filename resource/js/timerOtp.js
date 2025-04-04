function startCountdown() {
    const countdownElement = document.getElementById('countdown');
    let timeLeft = 120; // 2 menit dalam detik

    const timer = setInterval(() => {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;

        countdownElement.textContent = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
        timeLeft--;

        if (timeLeft < 0) {
            clearInterval(timer);
            countdownElement.textContent = "Waktu habis!";
            document.querySelectorAll('.otp-input').forEach(input => input.disabled = true);
            document.getElementById('submit-btn').disabled = true; // Disable tombol Verifikasi
        }
    }, 1000);
}

function moveToNext(current, nextFieldId) {
    if (current.value.length === 1 && nextFieldId) {
        document.getElementById(nextFieldId).focus();
    }
}

// Mulai countdown ketika halaman dimuat
window.onload = startCountdown;