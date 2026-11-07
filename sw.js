const cacheName = "SLRT-TANJUNGBALAI-v1";
const preCache = [
    "/.", "/index.php", "/styles.css",
    "/approve.php", "/cek_login.php", "/crud_member.php",
    "/daftar_bansos_kd.php", "/daftar_bansos_s.php", "/data_bpjs.php",
    "/data_bpnt.php", "/data_dtks.php", "/data_pkh.php", "/fetch_data.php",
    "/fs_dashboard.php", "/fs_layanan.php", "/fs_registrasi.php",
    "/fs_tugas_tambahan.php", "/fs_update_data.php", "/fs_usulan_baru.php",
    "/history.php", "/kd_dashboard.php", "/laporan_approv_kd.php",
    "/laporan_approv_m.php", "/laporan_approv_o.php", "/laporan_approv_s.php",
    "/laporan_manajer.php", "/laporan_operator.php", "/laporan_sekretaris.php",
    "/logout.php", "/manajer.php", "/member_status.php", "/member.php",
    "/menu.php", "/op_dashboard.php", "/profile.php", "/register.php",
    "/sekretaris.php", 
    "/sidebar/fasilitator.php", "/sidebar/operator.php", "/sidebar/koordinator.php",
    "/sidebar/sekretaris.php", "/sidebar/manajer.php",
    "/manifest.json", "/sw.js",
    "/icons/slrt.png", "/image/dinsos.png", "/logo/bpjs.png",
    "/logo/bpnt.jpg", "/logo/pkh.jpg", "/logo/slrt.png", "/logo/slrtori.png",
    "/header/header.php", "/header/loader.php", "/navbar/fs_navbar.php",
    "/navbar/kd_navbar.php", "/navbar/m_navbar.php", "/navbar/op_navbar.php",
    "/navbar/sk_navbar.php", "/footer/fasilitator/", "/footer/koordinator/",
    "/footer/manajer/", "/footer/operator/", "/footer/sekretaris/",
    "/assets/plugins/bootstrap/", "/assets/plugins/chart.js/",
    "/assets/plugins/datatables/", "/assets/plugins/daterangepicker/",
    "/assets/plugins/ekko-lightbox/",
];

self.addEventListener("install", (e) => {
    console.log("Service Worker Installed");
    e.waitUntil(
        (async () => {
            const cache = await caches.open(cacheName);
            await cache.addAll(preCache);
        })()
    );
    self.skipWaiting();
});

self.addEventListener("fetch", (e) => {
    e.respondWith(
        (async () => {
            const cache = await caches.open(cacheName);
            const cachedResponse = await cache.match(e.request);
            if (cachedResponse) return cachedResponse;

            try {
                const networkResponse = await fetch(e.request);
                cache.put(e.request, networkResponse.clone());
                return networkResponse;
            } catch (error) {
                console.log("Fetch failed; returning offline page instead.", error);
            }
        })()
    );
});

self.addEventListener("activate", (e) => {
    console.log("Service Worker Activated");
    e.waitUntil(
        (async () => {
            const cacheKeys = await caches.keys();
            await Promise.all(
                cacheKeys.map((key) => {
                    if (key !== cacheName) {
                        return caches.delete(key);
                    }
                })
            );
        })()
    );
    self.clients.claim();
});

let deferredPrompt;

self.addEventListener("beforeinstallprompt", (e) => {
    e.preventDefault();
    deferredPrompt = e;
    self.registration.showNotification("Pasang Aplikasi", {
        body: "Install aplikasi SLRT Tanjungbalai.",
        icon: "/icons/slrt.png",
        actions: [{ action: "install", title: "Pasang Sekarang" }],
    });
});

self.addEventListener("notificationclick", (e) => {
    if (e.action === "install" && deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === "accepted") {
                console.log("User accepted the install prompt");
            }
            deferredPrompt = null;
        });
    }
    e.notification.close();
});
