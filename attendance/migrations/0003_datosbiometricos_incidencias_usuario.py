# Generated by Django 5.1.7 on 2025-03-20 04:24

import django.db.models.deletion
from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('attendance', '0002_alter_cargo_id_cargo_and_more'),
    ]

    operations = [
        migrations.CreateModel(
            name='DatosBiometricos',
            fields=[
                ('id_datos_biometricos', models.AutoField(primary_key=True, serialize=False)),
                ('imagen_rostro', models.ImageField(upload_to='')),
                ('caracteristicas_facilales', models.TextField()),
                ('fecha_registro', models.DateField(auto_now_add=True)),
                ('fecha_actualizacion', models.DateField(auto_now=True)),
                ('id_empleado', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, to='attendance.empleado')),
            ],
        ),
        migrations.CreateModel(
            name='Incidencias',
            fields=[
                ('id_incidencia', models.AutoField(primary_key=True, serialize=False)),
                ('tipo_incidencia', models.CharField(choices=[('RET', 'Retardo'), ('FAL', 'Falta'), ('SAA', 'Salida anticipada'), ('TIE', 'Tiempo extra'), ('JUS', 'Justificación')], max_length=3)),
                ('minutos', models.IntegerField()),
                ('justificacion', models.TextField()),
                ('estado', models.CharField(choices=[('PEN', 'Pendiente'), ('APR', 'Aprobado'), ('REC', 'Rechazado')], max_length=3)),
                ('fecha_aprobacion', models.DateTimeField()),
                ('id_empleado', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, to='attendance.empleado')),
            ],
        ),
        migrations.CreateModel(
            name='Usuario',
            fields=[
                ('id_usuario', models.AutoField(primary_key=True, serialize=False)),
                ('nombre_usuario', models.CharField(max_length=50, unique=True)),
                ('password', models.CharField(max_length=255)),
                ('ultimo_acceso', models.DateField(auto_now=True)),
                ('activo', models.BooleanField(default=True)),
                ('rol', models.CharField(choices=[('ADM', 'Admin'), ('SUP', 'Supervisor'), ('RRH', 'Recursos Humanos'), ('CON', 'Consulta')], max_length=3)),
                ('id_empleado', models.ForeignKey(on_delete=django.db.models.deletion.CASCADE, to='attendance.empleado')),
            ],
        ),
    ]
