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
    imagen_rostro = models.ImageField(upload_to='biometric_pics/')
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

class Auditoria(models.Model):
    id_log = models.AutoField(primary_key=True)
    id_usuario = models.ForeignKey(Usuario, on_delete=models.CASCADE)
    accion = models.CharField(max_length=50)
    tabla_afectada = models.CharField(max_length=255)
    id_registro_afectado = models.IntegerField()
    datos_antiguos = models.TextField()
    datos_nuevos = models.TextField()
    fecha_hora = models.DateField()
    ip_usuario = models.CharField(max_length=100)

    def __str__(self):
        return f"{self.id_usuario} - {self.id_log} - {self.accion}"
    
class Justificante(models.Model):
    id_justificante = models.AutoField(primary_key=True)
    id_incidencia = models.ForeignKey(Incidencias, on_delete=models.CASCADE)
    documento = models.FileField()
    tipo_documento = models.CharField(max_length=50)
    fecha_carga = models.DateField()

    def __str__(self):
        return self.id_justificante, self.documento
    
class Horario(models.Model):
    id_horario = models.AutoField(primary_key=True)
    hora_entrada = models.TimeField()
    hora_salida = models.TimeField()
    tolerancia_entrada = models.IntegerField()
    tolerancia_salida = models.IntegerField()


    DIAS = {
        "LUN": "Lunes",
        "MAR": "Martes",
        "MIR": "Miercoles",
        "JUE": "Jueves",
        "VIE": "Viernes",
        "SAB": "Sabado",
        "DOM": "Domingo"
    }
    dias_laborables = models.CharField(max_length=3, choices=DIAS)

    activo = models.BooleanField(default=True)

    def __str__(self):
        return f'H:{self.hora_entrada}-{self.hora_salida}: {self.dias_laborables}'

class Sede(models.Model):
    id_sede = models.AutoField(primary_key=True)
    nombre = models.CharField(max_length=255)
    direccion = models.CharField(max_length=200)
    latitud = models.CharField(max_length=255)
    longitud = models.CharField(max_length=255)
    radio_geo_permitido = models.IntegerField()
    activo = models.BooleanField(default=True)

    def __str__(self):
        return f'{self.nombre}: {self.latitud} {self.longitud}'

class EmpleadoHorario(models.Model):
    id_empleado_horario = models.AutoField(primary_key=True)
    id_empleado = models.ForeignKey(Empleado, on_delete=models.CASCADE)
    id_horario = models.ManyToManyField('Horario', related_name='Empleado')
    id_sede = models.ForeignKey(Sede, on_delete=models.CASCADE)

    def __str__(self):
        return f'{self.id_sede}: {self.id_horario}'

class RegistroAsistencia(models.Model):
    id_registro = models.AutoField(primary_key=True)
    id_empleado = models.ForeignKey(Empleado, on_delete=models.CASCADE)

    TIPOS_REGISTROS = {
        "ENT": "Entrada",
        "SAL": "Salida"
    }
    tipo_registro = models.CharField(max_length=3, choices=TIPOS_REGISTROS)
    fecha_hora = models.DateField()
    imagen_verificacion = models.ImageField(upload_to='profile_pics/')
    confianza_reconocimiento = models.FloatField()
    latitud = models.CharField(max_length=100)
    longitud = models.CharField(max_length=100)

    id_sede = models.ForeignKey(Sede, on_delete=models.CASCADE)
    permitido = models.BooleanField()
    ip_dispositivo = models.CharField(max_length=20)
    dispositivo_info = models.TextField()

    ESTATUS_INFO = {
        "VAL": "Valido",
        "FUP": "Fuera de perimetro",
        "RET": "Retrasado",
        "MAN": "Manual",
        "SOS": "Sospechoso"
    }
    estatus = models.CharField(max_length=3, choices=ESTATUS_INFO)
    observaciones = models.TextField(blank=True, null=True)
