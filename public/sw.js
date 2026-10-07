self.addEventListener('install', (event) => {
    // Force the waiting service worker to become the active service worker.
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    // Claim the clients to ensure the service worker controls the page immediately.
    event.waitUntil(clients.claim());
});

self.addEventListener('fetch', (event) => {
    // Simple fetch listener to pass the PWA install check.
    // In a real offline app, you would use Caches API here.
    event.respondWith(
        fetch(event.request).catch(error => {
            return new Response('Offline mode');
        })
    );
});
