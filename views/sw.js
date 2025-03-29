// Nombre de la caché
const CACHE_NAME = 'biometrico-cache-v1';

// Recursos que quieres que estén disponibles sin conexión
const urlsToCache = [
  '/',
  '/index.php',
  '/views/template.php',
  '/css/tailwind.css', // Asumiendo que usas el archivo generado de Tailwind CSS
  '/manifest.json',
  '/icons/icon-72x72.png',
  '/icons/icon-96x96.png',
  '/icons/icon-128x128.png',
  '/icons/icon-144x144.png',
  '/icons/icon-152x152.png',
  '/icons/icon-192x192.png',
  '/icons/icon-384x384.png',
  '/icons/icon-512x512.png'
];

// Instalación del Service Worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Abriendo caché');
        return cache.addAll(urlsToCache);
      })
  );
});

// Interceptando requests
self.addEventListener('fetch', event => {
  // Para solicitudes POST (como tu API de registro biométrico)
  if (event.request.method === 'POST') {
    // Si no hay internet, guarda los datos para enviarlos más tarde
    if (!navigator.onLine) {
      // Aquí podrías guardar los datos en IndexedDB para sincronizarlos más tarde
      // Por ahora simplemente pasamos la solicitud
      return fetch(event.request.clone())
        .catch(err => {
          console.error('Error en solicitud POST:', err);
        });
    } else {
      // Si hay internet, simplemente pasa la solicitud
      return fetch(event.request);
    }
  }

  // Para solicitudes GET (recursos estáticos, páginas, etc.)
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Si el recurso está en caché, lo devolvemos
        if (response) {
          return response;
        }
        
        // Si no está en caché, hacemos la solicitud a la red
        return fetch(event.request)
          .then(response => {
            // Si la respuesta no es válida, simplemente la devolvemos
            if (!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }
            
            // Clonamos la respuesta porque ya se va a consumir
            const responseToCache = response.clone();
            
            // Añadimos la respuesta a la caché
            caches.open(CACHE_NAME)
              .then(cache => {
                cache.put(event.request, responseToCache);
              });
            
            return response;
          })
          .catch(error => {
            // Si falla la solicitud y tenemos una página offline, la mostramos
            console.error('Error en fetch:', error);
            
            // Aquí podrías devolver una página offline personalizada
            // return caches.match('/offline.html');
          });
      })
  );
});

// Activación del Service Worker (para limpiar cachés antiguas)
self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            // Eliminamos cachés antiguas
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// Manejo de sincronización en segundo plano para enviar datos pendientes
self.addEventListener('sync', event => {
  if (event.tag === 'sync-biometrico') {
    // Aquí implementarías la lógica para enviar datos pendientes
    // cuando se recupere la conexión a internet
    event.waitUntil(
      // Código para obtener datos de IndexedDB y enviarlos al servidor
      console.log('Sincronizando datos pendientes')
    );
  }
});