<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLRT TANJUNGBALAI</title>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="theme-color" content="#f8fafc" />
    <link rel="manifest" href="manifest.json">
    <link rel="apple-touch-icon" href="icons/slrt1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQ00pDgOa6rfB6nU0Q8bZZ4p8Fz8aPtaa1Yk2xkXzY6p0t5P6T5t5T5t5" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/jpg/png" href="logo/slrt.png">

    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>


    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body,
input {
  font-family: "Poppins", sans-serif;
}

.container {
  position: relative;
  width: 100%;
  background-color: #fff;
  min-height: 100vh;
  overflow: hidden;
}

.forms-container {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
}

.signin-signup {
  position: absolute;
  top: 50%;
  transform: translate(-50%, -50%);
  left: 75%;
  width: 50%;
  transition: 1s 0.7s ease-in-out;
  display: grid;
  grid-template-columns: 1fr;
  z-index: 5;
}

form {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  padding: 0rem 5rem;
  transition: all 0.2s 0.7s;
  overflow: hidden;
  grid-column: 1 / 2;
  grid-row: 1 / 2;
}

form.sign-up-form {
  opacity: 0;
  z-index: 1;
}

form.sign-in-form {
  z-index: 2;
}

.title {
  font-size: 1.5rem;
  color: #444;
  margin-bottom: 10px;
}

.input-field {
  max-width: 380px;
  width: 100%;
  background-color: #f0f0f0;
  margin: 10px 0;
  height: 55px;
  border-radius: 55px;
  display: grid;
  grid-template-columns: 15% 85%;
  padding: 0 0.4rem;
  position: relative;
}

.input-field i {
  text-align: center;
  line-height: 55px;
  color: #71a3fa;
  transition: 0.5s;
  font-size: 1.1rem;
}

.input-field input {
  background: none;
  outline: none;
  border: none;
  line-height: 1;
  font-weight: 600;
  font-size: 1.1rem;
  color: #333;
}

.input-field input::placeholder {
  color: #aaa;
  font-weight: 500;
}


.btn {
  width: 150px;
  background-color: #5995fd;
  border: none;
  outline: none;
  height: 49px;
  border-radius: 49px;
  color: #fff;
  text-transform: uppercase;
  font-weight: 600;
  margin: 10px 0;
  cursor: pointer;
  transition: 0.5s;
}

.btn:hover {
  background-color: #4d84e2;
}
.panels-container {
  position: absolute;
  height: 100%;
  width: 100%;
  top: 0;
  left: 0;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
}

.container:before {
  content: "";
  position: absolute;
  height: 2000px;
  width: 2000px;
  top: -10%;
  right: 48%;
  transform: translateY(-50%);
  background-image: linear-gradient(-45deg, #71a3fa 0%, #71a3fa 100%);
  transition: 1.8s ease-in-out;
  border-radius: 50%;
  z-index: 6;
}

.image {
  width: 100%;
  transition: transform 1.1s ease-in-out;
  transition-delay: 0.4s;
}

.panel {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  justify-content: space-around;
  text-align: center;
  z-index: 6;
}

.left-panel {
  pointer-events: all;
  padding: 3rem 17% 2rem 12%;
}

.right-panel {
  pointer-events: none;
  padding: 3rem 12% 2rem 17%;
}

.panel .content {
  color: #fff;
  transition: transform 0.9s ease-in-out;
  transition-delay: 0.6s;
  position: relative;
  left: 0px;
}

.panel h3 {
  font-weight: 600;
  line-height: 1;
  font-size: 1.5rem;
}

.panel p {
  font-size: 0.95rem;
  padding: 0.7rem 0;
}

.btn.transparent {
  margin: 0;
  background: none;
  border: 2px solid #fff;
  width: 130px;
  height: 41px;
  font-weight: 600;
  font-size: 0.8rem;
}

.right-panel .image,
.right-panel .content {
  transform: translateX(800px);
}

/* ANIMATION */

.container.sign-up-mode:before {
  transform: translate(100%, -50%);
  right: 52%;
}

.container.sign-up-mode .left-panel .image,
.container.sign-up-mode .left-panel .content {
  transform: translateX(-800px);
}

.container.sign-up-mode .signin-signup {
  left: 25%;
}

.container.sign-up-mode form.sign-up-form {
  opacity: 1;
  z-index: 2;
}

.container.sign-up-mode form.sign-in-form {
  opacity: 0;
  z-index: 1;
}

.container.sign-up-mode .right-panel .image,
.container.sign-up-mode .right-panel .content {
  transform: translateX(0%);
}

.container.sign-up-mode .left-panel {
  pointer-events: none;
}

.container.sign-up-mode .right-panel {
  pointer-events: all;
}

@media (max-width: 870px) {
  .container {
    min-height: 800px;
    height: 100vh;
  }
  .signin-signup {
    width: 100%;
    top: 95%;
    transform: translate(-50%, -100%);
    transition: 1s 0.8s ease-in-out;
  }

  .signin-signup,
  .container.sign-up-mode .signin-signup {
    left: 50%;
  }

  .panels-container {
    grid-template-columns: 1fr;
    grid-template-rows: 1fr 2fr 1fr;
  }

  .panel {
    flex-direction: row;
    justify-content: space-around;
    align-items: center;
    padding: 2.5rem 8%;
    grid-column: 1 / 2;
  }

  .right-panel {
    grid-row: 3 / 4;
  }

  .left-panel {
    grid-row: 1 / 2;
  }

  .image {
    width: 200px;
    transition: transform 0.9s ease-in-out;
    transition-delay: 0.6s;
  }

  .panel .content {
    padding-right: 15%;
    transition: transform 0.9s ease-in-out;
    transition-delay: 0.8s;
  }

  .panel h3 {
    font-size: 1.2rem;
  }

  .panel p {
    font-size: 0.7rem;
    padding: 0.5rem 0;
  }

  .btn.transparent {
    width: 110px;
    height: 35px;
    font-size: 0.7rem;
  }

  .container:before {
    width: 1500px;
    height: 1500px;
    transform: translateX(-50%);
    left: 30%;
    bottom: 68%;
    right: initial;
    top: initial;
    transition: 2s ease-in-out;
  }

  .container.sign-up-mode:before {
    transform: translate(-50%, 100%);
    bottom: 32%;
    right: initial;
  }

  .container.sign-up-mode .left-panel .image,
  .container.sign-up-mode .left-panel .content {
    transform: translateY(-300px);
  }

  .container.sign-up-mode .right-panel .image,
  .container.sign-up-mode .right-panel .content {
    transform: translateY(0px);
  }

  .right-panel .image,
  .right-panel .content {
    transform: translateY(300px);
  }

  .container.sign-up-mode .signin-signup {
    top: 5%;
    transform: translate(-50%, 0);
  }
}

@media (max-width: 570px) {
  form {
    padding: 0 1.5rem;
  }

  .image {
    display: none;
  }
  .panel .content {
    padding: 0.5rem 1rem;
  }
  .container {
    padding: 1.5rem;
  }

  .container:before {
    bottom: 72%;
    left: 50%;
  }

  .container.sign-up-mode:before {
    bottom: 28%;
    left: 50%;
  }
}



.passwor {
  position: relative;
  display: flex;
  top: -55px;
  left: 320px;
}

.main-footer {
    width: 100%;
    text-align: center;
    position: relative; /* Agar footer tetap di bawah */
    bottom: 5px;
    left: 0;

    /* background: #f8f9fa; */
}
/* Responsif untuk layar kecil */
@media (max-width: 768px) {
    .main-footer {
        font-size: 12px;
        /* padding: 10px 0; */
    }
}


/* Responsif untuk ukuran mobile */
@media (max-width: 480px) {
    .password #togglePassword {
    position: relative;
    display: flex;
    top: -55px;
    left: 275px;
  }
}

/* Untuk layar tablet dan iPad (portrait & landscape) */
@media (min-width: 872px) {
  .password #togglePassword {
    position: relative;
    display: flex;
    top: -55px;
    left: 320px;
  }
}

@media (min-width: 900px) {
  .password #togglePassword {
    position: relative;
    display: flex;
    top: -55px;
    left: 320px;
  }
}

#togglePass {
  position: relative;
  top: -52px;
  left: 280px;
}




.form-group {
    width: 320px;
  }

/* Ukuran default (Mobile) */
.label {
    width: 90px;
    height: 56px;
    background-color: #f1f1f1;
    border-radius: 40% 0 0 40%;
    left: 0px;
    position: relative;
    bottom: 65px;
    z-index: -1;
}

.label i {
    font-size: 18px;
    position: relative;
    top: 15px;
    left: 18px;
    color: #71a3fa;
}

#level {
    border: none;
    left: 45px;
    width: 280px;
    appearance: none; /* Untuk browser modern */
    -webkit-appearance: none; /* Untuk Safari dan Chrome */
    -moz-appearance: none; /* Untuk Firefox */
}

#btn {
  position: relative;
  margin-bottom: 40px;
}



/* Ukuran untuk Tablet (768px - 1024px) */
@media (min-width: 768px) {
    .label {
        width: 100px; /* Lebih lebar di tablet */
        height: 56px;
        position: relative;
        left: -30px;
    }

    .label i {
        font-size: 20px;
        top: 17px;
        left: 25px;
    }

    #level {
        left: 20px;
        width: 328px;
    }

    
    #togglePass {
      position: relative;
      top: -52px;
      left: 315px;
    }
}

/* Ukuran untuk Laptop/Desktop (1025px ke atas) */
@media (min-width: 1025px) {
    .label {
        width: 100px;
        height: 56px;
        position: relative;
        left: -30px;
    }

    .label i {
        font-size: 17px;
        top: 18px;
        left: 25px;
        color:#71a3fa;
    }

    #level {
        left: 23px;
        width: 325px;
    }
}


/* password animasi */

        .strength-indicator {
            margin-top: 5px;
            height: 5px;
            width: 340px;
            border-radius: 5px;
            transition: 0.3s;
        }
        .low { background: red; }
        .medium { background: orange; }
        .high { background: green; }
        .strength-text {
            margin-top: 5px;
            font-weight: bold;
        }


        @media (max-width: 400px) {
            .strength-indicator {
                width: 300px;
            }
        }



        .main-footer {
          position: relative;
          top: 0px;
        }

        .main-footer span a {
          text-decoration: none;
          color: #000;
        }

    </style>

<script>
  // Simpan sesi user saat login
  function setUserSession(username) {
    document.cookie = "user_session=" + username + "; path=/; expires=Fri, 31 Dec 9999 23:59:59 GMT";
    sessionStorage.setItem("user_session", username);
  }

  // Hapus sesi user saat logout
  function clearUserSession() {
    document.cookie = "user_session=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC";
    sessionStorage.removeItem("user_session");
  }

  // Cek apakah cookie masih ada setelah reload atau aplikasi ditutup
  function checkUserSession() {
    let cookies = document.cookie.split("; ");
    let sessionUser = cookies.find(row => row.startsWith("user_session="));
    if (sessionUser) {
      let username = sessionUser.split("=")[1];
      sessionStorage.setItem("user_session", username);
      console.log("User tetap login:", username);
    }
  }

  window.onload = checkUserSession;
</script>

  
</head>

<?php 
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $username = $_POST["username"];
      $password = $_POST["password"];
  
      // Contoh pengecekan login (gantilah dengan validasi database)
      if ($username == "admin" && $password == "admin123") {
          $_SESSION["user"] = $username;
          
          // Set Cookie agar user tetap login setelah aplikasi ditutup
          setcookie("user_session", $username, time() + (86400 * 30), "/"); // 30 hari
          setcookie("username", $username, time() + (86400 * 30), "/"); // 30 hari
          setcookie("password", $password, time() + (86400 * 30), "/"); // 30 hari

  
          echo json_encode(["status" => "success", "username" => $username]);
      } else {
          echo json_encode(["status" => "error", "message" => "Login gagal!"]);
      }
  }
?>

<body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="cek_login.php" method="POST" class="sign-in-form mb-2 mt-2" style="position: relative; top: -30px;">
            <img src="logo/slrt.png" width="50px" height="50px" class="img-fluid text-center">
            <h2 class="title">LOGIN</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" placeholder="Username" name="username" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>" autofocus/>
            </div>


            <div class="input-field password">
                <i class="fas fa-lock"></i>
                <input type="password" placeholder="Password" name="password" id="password" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; ?>" />
                <i class="fas fa-eye" id="togglePassword" style="cursor: pointer; display:none;"></i>
            </div>

            <div class="form-group">
                <select id="level" name="level" class="input-field" required>
                    <option value="" selected disabled>Pilih Jabatan</option>
                    <option value="fasilitator">Fasilitator</option>
                    <option value="tksk">TKSK</option>
                    <option value="operator">Operator</option>
                    <option value="koordinator">Koordinator</option>
                    <option value="sekretaris">Sekretaris</option>
                    <option value="manajer">Manajer</option>
                </select>

                <div class="label d-flex justify-content-center align-items-center position-absolute">
                    <i class="fas fa-briefcase"></i>
                </div>
            </div>

                        
            <script>
                const togglePassword = document.getElementById("togglePassword");
                const passwordInput = document.getElementById("password");

                // Event untuk menampilkan ikon saat mulai mengetik
                passwordInput.addEventListener("input", function () {
                    if (passwordInput.value.length > 0) {
                        togglePassword.style.display = "inline"; // Tampilkan ikon mata
                    } else {
                        togglePassword.style.display = "none"; // Sembunyikan ikon mata
                    }
                });

                // Event untuk toggle visibility password
                togglePassword.addEventListener("click", function () {
                    if (passwordInput.type === "password") {
                        passwordInput.type = "text";
                        this.classList.remove("fa-eye");
                        this.classList.add("fa-eye-slash");
                    } else {
                        passwordInput.type = "password";
                        this.classList.remove("fa-eye-slash");
                        this.classList.add("fa-eye");
                    }
                });
            </script>
            <input type="submit" value="Login" class="btn btn-warning rounded" />
          </form>

          <footer class="main-footer">
              <span>&copy; <?= date('Y') ?> <a href="">SLRT Kito Kota Tanjungbalai</a>.</span>
              <p>All rights reserved.</p>
          </footer>


          <form action="register.php" method="POST" class="sign-up-form">
            <img src="logo/slrt.png" width="50px" height="50px" class="img-fluid text-center">
            <h2 class="title">REGISTRASI AKUN</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" placeholder="Username" name="username" autofocus/>
            </div>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" placeholder="Nama Lengkap" name="nama_lengkap" uppercase/>
            </div>

            <div class="input-field sandi">
                <i class="fas fa-lock"></i>
                <input type="password" id="passwordInput" name="password" oninput="checkStrength(this.value)" placeholder="Masukkan sandi">
                <i class="fas fa-eye" id="togglePass" style="cursor: pointer;"></i>
              </div>
                <p id="passwordWarning" style="color: red; display: none;">⚠ Password minimal 8 karakter</p>
                <div id="strengthIndicator"></div>
                <p id="strengthText"></p>

            <div class="input-field">
              <i class="fas fa-phone"></i>
              <input type="number" placeholder="Nomor Whatsapp" name="no_wa" />
            </div>
            <input type="submit" class="btn" id="btn" name="simpan" value="DAFTAR" />

          </form>
        </div>
      </div>




      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h2><b>E-<span>LAPORAN </E-span></b>SLRT</h2>
            <span>
              SISTEM LAYANAN DAN RUJUKAN TERPADU
            </span> <br>
            <button class="btn transparent" id="sign-up-btn">
              DAFTAR AKUN
            </button>
          </div>
          <img src="img/log.svg" class="image" alt="" />
        </div>
        <div class="panel right-panel">
          <div class="content">
            <h3>SLRT</h3>
            <p>
              Sistem Layanan dan Rujukan Terpadu
            </p>
            <button class="btn transparent" id="sign-in-btn">
              LOGIN
            </button>
          </div>
          <img src="img/register.svg" class="image" alt="" />
        </div>
      </div>
    </div>

    <script src="app.js"></script>
    <script>
      const sign_in_btn = document.querySelector("#sign-in-btn");
      const sign_up_btn = document.querySelector("#sign-up-btn");
      const container = document.querySelector(".container");

      sign_up_btn.addEventListener("click", () => {
        container.classList.add("sign-up-mode");
      });

      sign_in_btn.addEventListener("click", () => {
        container.classList.remove("sign-up-mode");
      });
    </script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const togglePass = document.getElementById("togglePass");
    const passInput = document.getElementById("passwordInput");
    const strengthText = document.getElementById("strengthText");
    const passwordWarning = document.getElementById("passwordWarning");
    const strengthIndicator = document.getElementById("strengthIndicator");
    const requirementsList = document.getElementById("passwordRequirements");

    // Show/hide eye icon based on input
    passInput.addEventListener("input", function() {
        togglePass.style.display = passInput.value.length > 0 ? "inline" : "none";
        checkPasswordStrength(passInput.value);
    });

    // Toggle password visibility
    togglePass.addEventListener("click", function() {
        const isPassword = passInput.type === "password";
        passInput.type = isPassword ? "text" : "password";
        this.classList.toggle("fa-eye-slash", isPassword);
        this.classList.toggle("fa-eye", !isPassword);
    });

    // Password strength checker with special character validation
    function checkPasswordStrength(password) {
        const hasMinLength = password.length >= 8;
        const hasLowercase = /[a-z]/.test(password);
        const hasUppercase = /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecialChar = /[!@#$%^&*]/.test(password);
        
        // Update requirements display
        updateRequirement("length", hasMinLength);
        updateRequirement("lowercase", hasLowercase);
        updateRequirement("uppercase", hasUppercase);
        updateRequirement("number", hasNumber);
        updateRequirement("special", hasSpecialChar);

        // Check password length first
        if (password.length === 0) {
            passwordWarning.style.display = "none";
            strengthIndicator.className = "strength-indicator";
            strengthText.textContent = "";
            return;
        }
        
        if (!hasMinLength) {
            passwordWarning.style.display = "block";
            strengthIndicator.className = "strength-indicator low";
            strengthText.textContent = "Terlalu pendek";
            strengthText.style.color = "red";
            return;
        } else {
            passwordWarning.style.display = "none";
        }

        // Calculate strength based on fulfilled requirements
        const strength = [hasLowercase, hasUppercase, hasNumber, hasSpecialChar]
                         .filter(Boolean).length;
        
        // Determine strength level
        if (strength <= 1) {
            strengthIndicator.className = "strength-indicator low";
            strengthText.textContent = "Lemah";
            strengthText.style.color = "#ff4444";
        } else if (strength <= 3) {
            strengthIndicator.className = "strength-indicator medium";
            strengthText.textContent = "Sedang";
            strengthText.style.color = "#ffbb33";
        } else {
            strengthIndicator.className = "strength-indicator high";
            strengthText.textContent = "Kuat";
            strengthText.style.color = "#00C851";
        }
    }

    function updateRequirement(type, isValid) {
        const element = document.getElementById(`req-${type}`);
        if (element) {
            element.style.color = isValid ? "#00C851" : "#ff4444";
            element.querySelector("i").className = isValid ? "fas fa-check" : "fas fa-times";
        }
    }
});
</script>


<style>
.password-requirements {
    margin: 10px 0;
    font-size: 0.8rem;
    color: #666;
}
.password-requirements ul {
    padding-left: 20px;
    margin-top: 5px;
}
.password-requirements li {
    margin-bottom: 3px;
}
.password-requirements i {
    margin-right: 5px;
    width: 15px;
}
</style>


    <script>
      window.addEventListener("load", () => {
        if ("serviceWorker" in navigator) {
          navigator.serviceWorker.register("sw.js")
        }
      })
    </script>





  </body>
</html>
