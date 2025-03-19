-- phpMyAdmin SQL Dump
-- version 4.9.10
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 18-03-2025 a las 15:38:06
-- Versión del servidor: 10.6.15-MariaDB
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_asistencia2025`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Auditoria`
--

CREATE TABLE `Auditoria` (
  `id_log` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `accion` varchar(255) NOT NULL,
  `tabla_afectada` varchar(50) DEFAULT NULL,
  `id_registro_afectado` int(11) DEFAULT NULL,
  `datos_antiguos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_antiguos`)),
  `datos_nuevos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_nuevos`)),
  `fecha_hora` datetime DEFAULT current_timestamp(),
  `ip_usuario` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Cargos`
--

CREATE TABLE `Cargos` (
  `id_cargo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Configuracion`
--

CREATE TABLE `Configuracion` (
  `id_configuracion` int(11) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `valor` text NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DatosBiometricos`
--

CREATE TABLE `DatosBiometricos` (
  `id_datos_biometricos` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `imagen_rostro` longblob NOT NULL,
  `caracteristicas_faciales` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`caracteristicas_faciales`)),
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Departamentos`
--

CREATE TABLE `Departamentos` (
  `id_departamento` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Empleados`
--

CREATE TABLE `Empleados` (
  `id_empleado` int(11) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `fecha_contratacion` date NOT NULL,
  `id_departamento` int(11) DEFAULT NULL,
  `id_cargo` int(11) DEFAULT NULL,
  `estado` enum('Activo','Inactivo','Suspendido') DEFAULT 'Activo',
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EmpleadosHorarios`
--

CREATE TABLE `EmpleadosHorarios` (
  `id_empleado_horario` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL,
  `id_sede` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Horarios`
--

CREATE TABLE `Horarios` (
  `id_horario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `hora_entrada` time NOT NULL,
  `hora_salida` time NOT NULL,
  `tolerancia_entrada` int(11) DEFAULT 10,
  `tolerancia_salida` int(11) DEFAULT 10,
  `dias_laborables` set('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo') NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Incidencias`
--

CREATE TABLE `Incidencias` (
  `id_incidencia` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `tipo_incidencia` enum('Retardo','Falta','Salida Anticipada','Tiempo Extra','Justificación') NOT NULL,
  `fecha` date NOT NULL,
  `minutos` int(11) DEFAULT NULL,
  `justificacion` text DEFAULT NULL,
  `estado` enum('Pendiente','Aprobada','Rechazada') DEFAULT 'Pendiente',
  `id_aprobador` int(11) DEFAULT NULL,
  `fecha_aprobacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Justificantes`
--

CREATE TABLE `Justificantes` (
  `id_justificante` int(11) NOT NULL,
  `id_incidencia` int(11) NOT NULL,
  `documento` longblob DEFAULT NULL,
  `tipo_documento` varchar(50) DEFAULT NULL,
  `fecha_carga` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RegistrosAsistencia`
--

CREATE TABLE `RegistrosAsistencia` (
  `id_registro` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `tipo_registro` enum('Entrada','Salida') NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `imagen_verificacion` longblob DEFAULT NULL,
  `confianza_reconocimiento` decimal(5,2) DEFAULT NULL,
  `latitud` decimal(10,8) NOT NULL,
  `longitud` decimal(11,8) NOT NULL,
  `id_sede` int(11) DEFAULT NULL,
  `dentro_perimetro` tinyint(1) DEFAULT NULL,
  `ip_dispositivo` varchar(45) DEFAULT NULL,
  `dispositivo_info` varchar(255) DEFAULT NULL,
  `estatus` enum('Válido','Fuera de Perímetro','Retrasado','Manual','Sospechoso') DEFAULT 'Válido',
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Sedes`
--

CREATE TABLE `Sedes` (
  `id_sede` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `latitud` decimal(10,8) NOT NULL,
  `longitud` decimal(11,8) NOT NULL,
  `radio_geo_permitido` int(11) DEFAULT 100,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `rol` enum('Administrador','Supervisor','Recursos Humanos','Consulta') NOT NULL,
  `ultimo_acceso` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Auditoria`
--
ALTER TABLE `Auditoria`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `Cargos`
--
ALTER TABLE `Cargos`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indices de la tabla `Configuracion`
--
ALTER TABLE `Configuracion`
  ADD PRIMARY KEY (`id_configuracion`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `DatosBiometricos`
--
ALTER TABLE `DatosBiometricos`
  ADD PRIMARY KEY (`id_datos_biometricos`),
  ADD KEY `idx_empleado` (`id_empleado`);

--
-- Indices de la tabla `Departamentos`
--
ALTER TABLE `Departamentos`
  ADD PRIMARY KEY (`id_departamento`);

--
-- Indices de la tabla `Empleados`
--
ALTER TABLE `Empleados`
  ADD PRIMARY KEY (`id_empleado`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_departamento` (`id_departamento`),
  ADD KEY `id_cargo` (`id_cargo`),
  ADD KEY `cedula` (`cedula`);

--
-- Indices de la tabla `EmpleadosHorarios`
--
ALTER TABLE `EmpleadosHorarios`
  ADD PRIMARY KEY (`id_empleado_horario`),
  ADD UNIQUE KEY `unique_empleado_fecha` (`id_empleado`,`fecha_inicio`),
  ADD KEY `id_horario` (`id_horario`),
  ADD KEY `id_sede` (`id_sede`);

--
-- Indices de la tabla `Horarios`
--
ALTER TABLE `Horarios`
  ADD PRIMARY KEY (`id_horario`);

--
-- Indices de la tabla `Incidencias`
--
ALTER TABLE `Incidencias`
  ADD PRIMARY KEY (`id_incidencia`),
  ADD KEY `id_empleado` (`id_empleado`),
  ADD KEY `id_aprobador` (`id_aprobador`);

--
-- Indices de la tabla `Justificantes`
--
ALTER TABLE `Justificantes`
  ADD PRIMARY KEY (`id_justificante`),
  ADD KEY `id_incidencia` (`id_incidencia`);

--
-- Indices de la tabla `RegistrosAsistencia`
--
ALTER TABLE `RegistrosAsistencia`
  ADD PRIMARY KEY (`id_registro`),
  ADD KEY `id_sede` (`id_sede`),
  ADD KEY `idx_empleado_fecha` (`id_empleado`,`fecha_hora`);

--
-- Indices de la tabla `Sedes`
--
ALTER TABLE `Sedes`
  ADD PRIMARY KEY (`id_sede`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Auditoria`
--
ALTER TABLE `Auditoria`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Cargos`
--
ALTER TABLE `Cargos`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Configuracion`
--
ALTER TABLE `Configuracion`
  MODIFY `id_configuracion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `DatosBiometricos`
--
ALTER TABLE `DatosBiometricos`
  MODIFY `id_datos_biometricos` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Departamentos`
--
ALTER TABLE `Departamentos`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Empleados`
--
ALTER TABLE `Empleados`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `EmpleadosHorarios`
--
ALTER TABLE `EmpleadosHorarios`
  MODIFY `id_empleado_horario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Horarios`
--
ALTER TABLE `Horarios`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Incidencias`
--
ALTER TABLE `Incidencias`
  MODIFY `id_incidencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Justificantes`
--
ALTER TABLE `Justificantes`
  MODIFY `id_justificante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `RegistrosAsistencia`
--
ALTER TABLE `RegistrosAsistencia`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Sedes`
--
ALTER TABLE `Sedes`
  MODIFY `id_sede` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Auditoria`
--
ALTER TABLE `Auditoria`
  ADD CONSTRAINT `Auditoria_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `Usuarios` (`id_usuario`);

--
-- Filtros para la tabla `DatosBiometricos`
--
ALTER TABLE `DatosBiometricos`
  ADD CONSTRAINT `DatosBiometricos_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `Empleados` (`id_empleado`);

--
-- Filtros para la tabla `Empleados`
--
ALTER TABLE `Empleados`
  ADD CONSTRAINT `Empleados_ibfk_1` FOREIGN KEY (`id_departamento`) REFERENCES `Departamentos` (`id_departamento`),
  ADD CONSTRAINT `Empleados_ibfk_2` FOREIGN KEY (`id_cargo`) REFERENCES `Cargos` (`id_cargo`);

--
-- Filtros para la tabla `EmpleadosHorarios`
--
ALTER TABLE `EmpleadosHorarios`
  ADD CONSTRAINT `EmpleadosHorarios_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `Empleados` (`id_empleado`),
  ADD CONSTRAINT `EmpleadosHorarios_ibfk_2` FOREIGN KEY (`id_horario`) REFERENCES `Horarios` (`id_horario`),
  ADD CONSTRAINT `EmpleadosHorarios_ibfk_3` FOREIGN KEY (`id_sede`) REFERENCES `Sedes` (`id_sede`);

--
-- Filtros para la tabla `Incidencias`
--
ALTER TABLE `Incidencias`
  ADD CONSTRAINT `Incidencias_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `Empleados` (`id_empleado`),
  ADD CONSTRAINT `Incidencias_ibfk_2` FOREIGN KEY (`id_aprobador`) REFERENCES `Empleados` (`id_empleado`);

--
-- Filtros para la tabla `Justificantes`
--
ALTER TABLE `Justificantes`
  ADD CONSTRAINT `Justificantes_ibfk_1` FOREIGN KEY (`id_incidencia`) REFERENCES `Incidencias` (`id_incidencia`);

--
-- Filtros para la tabla `RegistrosAsistencia`
--
ALTER TABLE `RegistrosAsistencia`
  ADD CONSTRAINT `RegistrosAsistencia_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `Empleados` (`id_empleado`),
  ADD CONSTRAINT `RegistrosAsistencia_ibfk_2` FOREIGN KEY (`id_sede`) REFERENCES `Sedes` (`id_sede`);

--
-- Filtros para la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD CONSTRAINT `Usuarios_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `Empleados` (`id_empleado`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
