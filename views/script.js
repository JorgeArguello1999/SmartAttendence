document.addEventListener("DOMContentLoaded", () => {
    const video = document.getElementById("video");
    const captureButton = document.getElementById("capture");
    const canvas = document.getElementById("canvas");
    const capturedImage = document.getElementById("capturedImage");
    const sendDataButton = document.getElementById("sendData");
    const tipoRegistro = document.getElementById("tipo_registro");
    const cedulaInput = document.getElementById("cedula");
    const requestPermissionsButton = document.getElementById("requestPermissions");
    const responseMessage = document.getElementById("responseMessage");

    let latitud = "";
    let longitud = "";
    let imageData = null;
    let stream = null;

    async function requestPermissions() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;

            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition( 
                    (position) => {
                        latitud = position.coords.latitude;
                        longitud = position.coords.longitude;
                    }, 
                    () => alert("No se pudo obtener la ubicación.")
                );
            }
        } catch (error) {
            alert("Error al obtener permisos de cámara o ubicación.");
        }
    }

    captureButton.addEventListener("click", () => {
        if (!cedulaInput.value.match(/^\d{10}$/)) {
            alert("Ingrese una cédula válida de 10 dígitos.");
            return;
        }

        const context = canvas.getContext("2d");
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        imageData = canvas.toDataURL("image/jpeg");

        // Mostrar la imagen capturada sobre el video
        capturedImage.src = imageData;
        capturedImage.classList.remove("hidden");

        // Detener la cámara
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            video.srcObject = null;
        }
    });

    sendDataButton.addEventListener("click", async () => {
        if (!imageData) {
            alert("Debes capturar una imagen antes de enviar.");
            return;
        }

        if (!cedulaInput.value.match(/^\d{10}$/)) {
            alert("Ingrese una cédula válida de 10 dígitos.");
            return;
        }

        const formData = new FormData();
        formData.append("cedula", cedulaInput.value);
        formData.append("image", dataURLtoBlob(imageData), "photo.jpg");
        formData.append("tipo_registro", tipoRegistro.value);
        formData.append("latitud", latitud);
        formData.append("longitud", longitud);

        try {
            const response = await fetch("http://localhost:82/index.php", { 
                method: "POST",
                body: formData
            });

            const result = await response.json();
                    
            if (result.success && result.data) {
                responseMessage.textContent = `Nombre: ${result.data.nombres}\nEstatus: ${result.data.estatus}\nConfianza: ${result.data.confianza_reconocimiento.toFixed(2)}`;
                responseMessage.classList.remove("hidden");
                responseMessage.classList.add("border-gray-500", "bg-gray-200", "text-gray-800");
            } else {
                throw new Error("Respuesta inesperada del servidor.");
            }

        } catch (error) {
            responseMessage.textContent = "Error al enviar los datos.";
            responseMessage.classList.remove("hidden");
            responseMessage.classList.add("border-red-500", "bg-red-200", "text-red-800");
        }
    });

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

    requestPermissionsButton.addEventListener("click", requestPermissions);
});