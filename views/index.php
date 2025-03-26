<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Biométrico</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen p-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Registro Biométrico</h1>

    <input type="text" id="cedula" placeholder="Ingrese su cédula" maxlength="10" pattern="\d{10}" required
        class="border border-gray-300 rounded p-2 mb-2 w-64 text-center">

    <button id="requestPermissions" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Solicitar Permisos</button>

    <video id="video" autoplay class="mt-4 border-2 border-black w-80 h-60"></video>
    <canvas id="canvas" class="hidden"></canvas>
    <img id="capturedImage" class="hidden mt-4 border-2 border-gray-300 w-80 h-60" alt="Imagen capturada">

    <button id="capture" class="bg-green-500 text-white px-4 py-2 rounded mt-2 hover:bg-green-700">Capturar Foto</button>

    <select id="tipo_registro" class="border border-gray-300 rounded p-2 mt-2 w-64 text-center">
        <option value="Entrada">Entrada</option>
        <option value="Salida">Salida</option>
    </select>

    <button id="sendData" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 hover:bg-blue-700">Enviar Datos</button>

    <div id="responseMessage" class="hidden mt-4 p-4 border rounded w-80 text-center"></div>

    <script>
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

                // Mostrar la imagen capturada en la etiqueta <img>
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
                        responseMessage.textContent = `Estatus: ${result.data.estatus}, Confianza: ${result.data.confianza_reconocimiento.toFixed(2)}`;
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
    </script>
</body>
</html>
