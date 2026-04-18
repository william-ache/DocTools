const CACHE_NAME = 'consultia-v2';
const ASSETS_TO_CACHE = [
    '/admin/dashboard',
    '/css/design-tokens.css',
    '/js/jquery.min.js',
    '/js/tailwindcss.js',
    '/assets/libs/sweetalert2/style.css',
    '/assets/libs/sweetalert2/script.js',
    '/assets/img/icon-192x192.png',
    '/assets/img/icon-512x512.png'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            // Usamos un loop para cachear individualmente y no fallar si uno falta
            return Promise.allSettled(
                ASSETS_TO_CACHE.map(url => 
                    cache.add(url).catch(err => console.warn(`SW: Failed to cache ${url}`, err))
                )
            );
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
            );
        })
    );
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;

    // Estrategia Network-First con Fallback al Cache (Ideal para SaaS Dinámicos)
    event.respondWith(
        fetch(event.request)
            .then((networkResponse) => {
                // Opcional: Actualizar el cache con la nueva respuesta si es un asset estático
                return networkResponse;
            })
            .catch(() => {
                // Si falla la red (Modo Offline), intentar devolver del Cache
                return caches.match(event.request);
            })
    );
});

self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'KEEP_ALIVE') {
        // Heartbeat log
    }
});
