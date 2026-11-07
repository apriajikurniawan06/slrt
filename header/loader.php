<!-- Preloader -->


<div class="preloader flex-column justify-content-center align-items-center bg-transparent">
    <img class="animation__shake" src="logo/slrt.png" height="100" width="100"> <br>
</div>

<style>
        /* CSS untuk Preloader */
       
        .preloader {
            position: fixed; /* Memastikan preloader tetap di posisi tetap */
            top: 0;
            left: 0;
            width: 100%; /* Lebar penuh */
            height: 100%; /* Tinggi penuh */
            display: flex; /* Menggunakan flexbox */
            justify-content: center; /* Memusatkan secara horizontal */
            align-items: center; /* Memusatkan secara vertikal */
            background-color: transparent; /* Latar belakang semi-transparan */
            z-index: 9999; /* Menempatkan di atas konten lain */
        }

        .animation__shake {
            animation: shake 20s infinite; 
        }

        @keyframes shake {
            0% { transform: translate(1px, 0); }
            25% { transform: translate(-1px, 0); }
            50% { transform: translate(1px, 0); }
            75% { transform: translate(-1px, 0); }
            100% { transform: translate(0, 0); }
        }
    </style>