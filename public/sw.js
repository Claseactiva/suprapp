// Service worker minimo: solo habilita la instalacion de la PWA.
// No cachea nada todavia, todas las requests pasan directo a la red.
self.addEventListener('install', function (event) {
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    self.clients.claim();
});

self.addEventListener('fetch', function (event) {
    event.respondWith(fetch(event.request));
});
