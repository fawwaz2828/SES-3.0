// Ambil elemen yang perlu dipantau
const lampuImage = document.getElementById("lampuImage");
const gambarBlank = document.getElementById("gambarBlank");
const backgroundWrapper = document.getElementById("backgroundWrapper");

// Fungsi untuk mengubah gambar latar belakang dan menyembunyikan gambar lampu
function changeImageAndHideLampu() {
  // Tambahkan kelas fade pada lampuImage untuk memulai transisi
  lampuImage.classList.add("fade");

  // Ubah warna latar belakang menjadi putih dengan efek fade
  backgroundWrapper.style.backgroundColor = "#ffffff"; // Warna latar belakang baru

  // Tunggu beberapa waktu untuk transisi selesai sebelum menyembunyikan gambar
  setTimeout(function () {
    // Ganti src gambar dengan path yang baru
    gambarBlank.src = "Image/Frame 14.svg"; // Ganti dengan path gambar yang diinginkan

    // Menambahkan efek fading pada gambar baru
    gambarBlank.classList.add("fade-in");

    // Tunggu gambar baru muncul, kemudian tampilkan dengan efek fading
    setTimeout(() => {
      gambarBlank.classList.add("visible");
    }, 50); // Delay kecil agar transisi fading dapat terlihat

    // Menyembunyikan gambar lampu dengan efek fading
    lampuImage.classList.add("hidden");
  }, 500); // 500ms sesuai dengan durasi transisi
}

// Tambahkan event listener saat gambar overlay diklik
lampuImage.addEventListener("click", changeImageAndHideLampu);

document.querySelector(".cta-button").addEventListener("click", function () {
  // Replace this with your actual login check
  const isLoggedIn = false; // Example: Replace with actual login status check

  if (!isLoggedIn) {
    window.location.href = "php/Login1.php"; // Redirect to login page
  } else {
    // Proceed with the intended action if logged in
    alert("Proceeding to book a consultation...");
  }
});
