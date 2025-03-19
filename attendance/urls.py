from django.urls import path
from attendance import views

urlpatterns = [
    path('', views.home, name='home'),
]