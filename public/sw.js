/* Huru SMS service worker: app shell cached, API never cached, offline fallback page. */
const VERSION = 'huru-v1';
const SHELL = ['/', '/chat', '/offline', '/logo.svg', '/manifest.json'];

self.addEventListener('install', (event) => {
    event.waitUntil(caches.open(VERSION).then((c) => c.addAll(SHELL)).then(() => self.skipWaiting()));
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => Promise.all(keys.filter((k) => k !== VERSION).map((k) => caches.delete(k)))).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const req = event.request;
    const url = new URL(req.url);

    if (req.method !== 'GET' || url.origin !== self.location.origin) return;
    // Never cache dynamic data or the admin panel.
    if (url.pathname.startsWith('/chat/') || url.pathname.startsWith('/admin') || url.pathname.startsWith('/api') || url.pathname.startsWith('/community')) return;

    if (req.mode === 'navigate') {
        event.respondWith(
            fetch(req)
                .then((res) => { const copy = res.clone(); caches.open(VERSION).then((c) => c.put(req, copy)); return res; })
                .catch(() => caches.match(req).then((hit) => hit || caches.match('/offline')))
        );
        return;
    }

    event.respondWith(
        caches.match(req).then((hit) => hit || fetch(req).then((res) => {
            if (res.ok && (url.pathname.startsWith('/build/') || /\.(svg|png|ico|css|js|woff2?)$/.test(url.pathname))) {
                const copy = res.clone(); caches.open(VERSION).then((c) => c.put(req, copy));
            }
            return res;
        }))
    );
});
