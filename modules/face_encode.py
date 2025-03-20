from face_recognition import face_encodings
from face_recognition import face_distance

import numpy as np

"""
# Get encoding photo
def encode_photo(photo) -> np.array:
    image_bytes = base64.b64decode(image)
    # Crear un objeto BytesIO a partir de los bytes
    image_io = BytesIO(image_bytes)
    # Abrir la imagen utilizando la biblioteca PIL (Pillow)
    image = Image.open(image_io)
    # Convertir la imagen a una matriz numpy y encodear
    img_array = np.array(image)
    return {
        "image_encode": face_encodings(img_array)[0],
    }
"""