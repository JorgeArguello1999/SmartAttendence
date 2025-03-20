from django.shortcuts import render

# Models
from attendance.models import RegistroAsistencia

# Create your views here.
def home(request):
    registros = RegistroAsistencia.objects.select_related('id_empleado', 'id_sede').values(
        'id_empleado_id', 'id_empleado__nombres', 'id_empleado__apellidos',
        'id_empleado__email', 'id_empleado__telefono',
        'fecha_hora', 'dispositivo_info', 'estatus', 'imagen_verificacion',
        'id_sede__nombre', 'latitud', 'longitud'
    )
    print(registros)
    return render(request, 'home.html', {'registros': registros})