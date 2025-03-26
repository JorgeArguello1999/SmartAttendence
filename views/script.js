document.addEventListener("DOMContentLoaded", () => {
    const video = document.getElementById("video");
    const captureButton = document.getElementById("capture");
    const canvas = document.getElementById("canvas");
    const sendDataButton = document.getElementById("sendData");
    const tipoRegistro = document.getElementById("tipo_registro");
    const cedulaInput = document.getElementById("cedula");
    const requestPermissionsButton = document.getElementById("requestPermissions");

    let latitud = "";
    let longitud = "";
    let imageData = null;

    // Solicitar permisos de cámara y ubicación
    async function requestPermissions() {
        try {
            // Permiso para la cámara
            await navigator.mediaDevices.getUserMedia({ video: true });
            startCamera();

            // Permiso para la ubicación
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        latitud = position.coords.latitude;
                        longitud = position.coords.longitude;
                        console.log(`Latitud: ${latitud}\nLogitud: ${longitud}`);
                    },
                    (error) => {
                        console.error("Error obteniendo la ubicación:", error);
                        alert("No se pudo obtener la ubicación.");
                    }
                );
            } else {
                alert("Geolocalización no soportada en este navegador.");
            }
        } catch (error) {
            console.error("Error al solicitar permisos:", error);
            alert("Error al obtener permisos de cámara o ubicación.");
        }
    }

    // Iniciar la cámara
    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
        } catch (error) {
            console.error("Error al acceder a la cámara:", error);
            alert("No se pudo acceder a la cámara.");
        }
    }

    // Capturar imagen
    captureButton.addEventListener("click", () => {
        if (!cedulaInput.value.match(/^\d{10}$/)) {
            alert("Ingrese una cédula válida de 10 dígitos.");
            return;
        }

        const context = canvas.getContext("2d");
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        imageData = canvas.toDataURL("image/jpeg"); // Guardar la imagen en base64
    });

    // Enviar datos al servidor
    sendDataButton.addEventListener("click", async () => {
        if (!imageData) {
            alert("Debes capturar una imagen antes de enviar.");
            return;
        }

        if (!cedulaInput.value.match(/^\d{10}$/)) {
            alert("Ingrese una cédula válida de 10 dígitos.");
            return;
        }

        const tipo = tipoRegistro.value;
        const cedula = cedulaInput.value;

        const formData = new FormData();
        formData.append("cedula", cedula);
        formData.append("image", dataURLtoBlob(imageData), "photo.jpg");
        formData.append("tipo_registro", tipo);
        formData.append("latitud", latitud);
        formData.append("longitud", longitud);
        formData.append("info", "hola");

        try {
            const response = await fetch("http://localhost:82/index.php", {
                method: "POST",
                body: formData
            });

            const result = await response.text();
            alert("Respuesta del servidor: " + result);
        } catch (error) {
            console.error("Error enviando los datos:", error);
            alert("Error al enviar los datos.");
        }
    });

    // Convertir Base64 a Blob para enviarlo como archivo
    function dataURLtoBlob(dataURL) {
        const byteString = atob(dataURL.split(",")[1]);
        const mimeString = dataURL.split(",")[0].split(":")[1].split(";")[0];

        const arrayBuffer = new ArrayBuffer(byteString.length);
        const uint8Array = new Uint8Array(arrayBuffer);

        for (let i = 0; i < byteString.length; i++) {
            uint8Array[i] = byteString.charCodeAt(i);
        }

        return new Blob([arrayBuffer], { type: mimeString });
    }

    // Botón para solicitar permisos antes de empezar
    requestPermissionsButton.addEventListener("click", requestPermissions);
});
