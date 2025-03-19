from django.contrib import admin

from attendance import models

# Register your models here.
admin.site.register(models.Cargo)
admin.site.register(models.Empleado)
admin.site.register(models.Departamento)