from django.db import models

# Create your models here.
class Cargo(models.Model):
    id_cargo = models.AutoField(primary_key=True)
    nombre = models.CharField(max_length=50)
    descripcion = models.TextField()
    
    def __str__(self):
        return self.nombre

class Departamento(models.Model):
    id_departamento = models.AutoField(primary_key=True)
    nombre = models.CharField(max_length=50)
    descripcion = models.TextField()

    def __str__(self):
        return self.nombre

class Empleado(models.Model):
    id_empleado = models.AutoField(primary_key=True)
    cedula = models.CharField(max_length=10)
    nombres = models.CharField(max_length=200)
    apellidos = models.CharField(max_length=200)
    email = models.EmailField(max_length=100)
    telefono = models.CharField(max_length=15)
    fecha_contratacion = models.DateField()

    id_departamento = models.ForeignKey(Departamento, on_delete=models.CASCADE)
    id_cargo = models.ForeignKey(Cargo, on_delete=models.CASCADE)

    # Estados 
    ESTADOS = {
        "ACT": "ACTIVO",
        "INA": "INACTIVO",
        "SUS": "SUSPENDIDO"
    }
    estado = models.CharField(max_length=3, choices=ESTADOS)

    fecha_creacion = models.DateField(auto_now_add=True)
    fecha_actualizacion = models.DateField(auto_now=True)

    def __str__(self):
        return f"{self.cedula} - {self.nombres} {self.apellidos}"

class DatosBiometricos(models.Model):
    id_datos_biometricos = models.AutoField(primary_key=True)
    id_empleado = models.ForeignKey(Empleado, on_delete=models.CASCADE)
    imagen_rostro = models.ImageField()
    caracteristicas_facilales = models.TextField()
    fecha_registro = models.DateField(auto_now_add=True)
    fecha_actualizacion = models.DateField(auto_now=True)

    def __str__(self):
        return f"{self.id_empleado}"

class Usuario(models.Model):
    id_usuario = models.AutoField(primary_key=True)
    nombre_usuario = models.CharField(max_length=50, unique=True)
    password = models.CharField(max_length=255)
    
    id_empleado = models.ForeignKey(Empleado, on_delete=models.CASCADE)

    ultimo_acceso = models.DateField(auto_now=True)
    activo = models.BooleanField(default=True)

    # Roles
    ROLES = {
        "ADM": "Admin",
        "SUP": "Supervisor",
        "RRH": "Recursos Humanos",
        "CON": "Consulta"
    }
    rol = models.CharField(max_length=3, choices=ROLES)

    def __str__(self):
        return f"{self.nombre_usuario}"

class Incidencias(models.Model):
    id_incidencia = models.AutoField(primary_key=True)
    id_empleado = models.ForeignKey(Empleado, on_delete=models.CASCADE)

    INCIDENCIAS = {
        "RET": "Retardo",
        "FAL": "Falta",
        "SAA": "Salida anticipada",
        "TIE": "Tiempo extra",
        "JUS": "Justificación"
    }
    tipo_incidencia = models.CharField(max_length=3, choices=INCIDENCIAS)
    fecha = models.DateField
    minutos = models.IntegerField()
    justificacion = models.TextField()

    ESTADOS = {
        "PEN": "Pendiente",
        "APR": "Aprobado",
        "REC": "Rechazado"
    }
    estado = models.CharField(max_length=3, choices=ESTADOS)
    fecha_aprobacion = models.DateTimeField()

    def __str__(self):
        return f"{self.id_empleado} - {self.tipo_incidencia}"