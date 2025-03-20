from django.contrib import admin

from attendance import models

# Register your models here.
admin.site.register(models.Cargo)
admin.site.register(models.Empleado)
admin.site.register(models.Departamento)

admin.site.register(models.Usuario)
admin.site.register(models.Incidencias)
admin.site.register(models.DatosBiometricos)