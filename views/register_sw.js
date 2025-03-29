// script.js - Coloca este archivo en la raíz de tu proyecto o inclúyelo en tu template.php

// Registrar el Service Worker
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('sw.js')
      .then(registration => {
        console.log('Service Worker registrado con éxito:', registration.scope);
      })
      .catch(error => {
        console.error('Error al registrar el Service Worker:', error);
      });
  });
  
  // Solicitar permiso para notificaciones
  if ('Notification' in window) {
    Notification.requestPermission()
      .then(permission => {
        if (permission === 'granted') {
          console.log('Permiso de notificaciones concedido');
        }
      });
  }
  
  // Configurar sincronización en segundo plano
  navigator.serviceWorker.ready
    .then(registration => {
      // Registrar la sincronización en segundo plano
      if ('sync' in registration) {
        document.getElementById('sendData').addEventListener('click', event => {
          // Si no hay conexión, registrar una sincronización en segundo plano
          if (!navigator.onLine) {
            // Aquí guardarías los datos en IndexedDB primero
            registration.sync.register('sync-biometrico')
              .then(() => {
                console.log('Sincronización en segundo plano registrada');
                // Mostrar mensaje al usuario
                document.getElementById('responseMessage').textContent = 
                  'Sin conexión. Los datos se enviarán automáticamente cuando haya conexión.';
                document.getElementById('responseMessage').classList.remove('hidden');
              })
              .catch(error => {
                console.error('Error al registrar sincronización:', error);
              });
          }
        });
      }
    });
}

// Función para detectar si la app está instalada o no
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
  // Prevenir que Chrome muestre el prompt automáticamente
  e.preventDefault();
  // Guardar el evento para usarlo más tarde
  deferredPrompt = e;
  // Mostrar botón de instalación (puedes crear un botón en tu HTML)
  // Ejemplo: document.getElementById('installBtn').classList.remove('hidden');
});

// Función para detectar cuando la app ha sido instalada
window.addEventListener('appinstalled', (evt) => {
  console.log('Sistema Biométrico instalado correctamente');
  // Ocultar botón de instalación si existe
  // Ejemplo: document.getElementById('installBtn').classList.add('hidden');
});