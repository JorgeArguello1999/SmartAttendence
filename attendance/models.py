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