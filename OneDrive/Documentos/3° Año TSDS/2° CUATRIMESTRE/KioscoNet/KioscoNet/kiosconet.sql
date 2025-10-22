-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 16-10-2025 a las 01:28:35
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.3.6
-- OPTIMIZADO: Foreign Keys e Índices agregados

-- Eliminar la base de datos si existe y crearla nuevamente
DROP DATABASE IF EXISTS `kiosconet`;
CREATE DATABASE IF NOT EXISTS `kiosconet` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `kiosconet`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- OPTIMIZACIÓN: Se agregan constraints de Foreign Keys para integridad referencial
-- Se optimizan índices para mejorar rendimiento de consultas
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

DROP TABLE IF EXISTS `caja`;
CREATE TABLE IF NOT EXISTS `caja` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint UNSIGNED DEFAULT NULL,
  `tipo` enum('ingreso','egreso') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ingreso',
  `concepto` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monto` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `caja_usuario_id_foreign` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`id`, `usuario_id`, `tipo`, `concepto`, `monto`, `created_at`, `updated_at`) VALUES
(1, 1, 'ingreso', 'Venta #20250920000001', 750.00, '2025-09-20 03:57:13', '2025-09-20 03:57:13'),
(2, 1, 'ingreso', 'Venta #20250920000002', 2200.00, '2025-09-20 04:59:57', '2025-09-20 04:59:57'),
(3, 1, 'ingreso', 'Venta #20250920000003', 300.00, '2025-09-20 05:03:55', '2025-09-20 05:03:55'),
(4, 1, 'ingreso', 'Venta #20250920000004', 900.00, '2025-09-20 05:51:36', '2025-09-20 05:51:36'),
(5, 1, 'egreso', 'Anulación venta #20250920000001', 750.00, '2025-09-20 19:12:57', '2025-09-20 19:12:57'),
(6, 1, 'egreso', 'Anulación venta #20250920000001', 750.00, '2025-09-20 19:15:17', '2025-09-20 19:15:17'),
(7, 1, 'egreso', 'Anulación venta #20250920000001', 750.00, '2025-09-20 22:03:50', '2025-09-20 22:03:50'),
(8, 1, 'egreso', 'Anulación venta #20250920000001', 750.00, '2025-09-20 22:07:50', '2025-09-20 22:07:50'),
(9, 1, 'ingreso', 'Venta #20250921000005', 450.00, '2025-09-21 19:08:04', '2025-09-21 19:08:04'),
(10, 1, 'ingreso', 'Venta #20250921000006', 750.00, '2025-09-21 20:30:43', '2025-09-21 20:30:43'),
(11, 4, 'ingreso', NULL, 4200.00, '2025-10-03 04:59:43', '2025-10-03 04:59:43'),
(12, 4, 'ingreso', NULL, 8400.00, '2025-10-03 05:51:09', '2025-10-03 05:51:09'),
(13, 4, 'ingreso', NULL, 6580.00, '2025-10-03 05:52:27', '2025-10-03 05:52:27'),
(14, 4, 'ingreso', NULL, 1820.00, '2025-10-04 01:01:25', '2025-10-04 01:01:25'),
(15, 4, 'ingreso', NULL, 4200.00, '2025-10-10 03:20:22', '2025-10-10 03:20:22'),
(16, 4, 'ingreso', NULL, 13160.00, '2025-10-10 03:21:17', '2025-10-10 03:21:17'),
(17, 4, 'ingreso', NULL, 6580.00, '2025-10-11 01:57:26', '2025-10-11 01:57:26'),
(18, 4, 'ingreso', NULL, 7280.00, '2025-10-11 02:56:50', '2025-10-11 02:56:50'),
(19, 4, 'ingreso', NULL, 7140.00, '2025-10-11 03:28:45', '2025-10-11 03:28:45'),
(20, 4, 'ingreso', NULL, 5460.00, '2025-10-11 04:08:22', '2025-10-11 04:08:22'),
(21, 4, 'ingreso', NULL, 1000.00, '2025-10-11 19:22:10', '2025-10-11 19:22:10'),
(22, 4, 'ingreso', NULL, 1380.00, '2025-10-11 19:22:10', '2025-10-11 19:22:10'),
(23, 4, 'ingreso', NULL, 6636.00, '2025-10-12 03:49:57', '2025-10-12 03:49:57'),
(24, 4, 'ingreso', NULL, 8339.52, '2025-10-13 05:03:45', '2025-10-13 05:03:45'),
(25, 4, 'ingreso', NULL, 10000.00, '2025-10-13 05:13:19', '2025-10-13 05:13:19'),
(26, 4, 'ingreso', NULL, 5428.00, '2025-10-13 05:13:19', '2025-10-13 05:13:19'),
(27, 4, 'ingreso', NULL, 1000.00, '2025-10-14 00:21:22', '2025-10-14 00:21:22'),
(28, 4, 'ingreso', NULL, 820.00, '2025-10-14 00:21:22', '2025-10-14 00:21:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `limite_credito` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tipo_cliente` enum('minorista','mayorista','especial') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'minorista',
  `saldo_cc` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellido`, `telefono`, `email`, `direccion`, `limite_credito`, `tipo_cliente`, `saldo_cc`, `created_at`, `updated_at`, `deleted_at`, `activo`) VALUES
(1, 'Juan', 'Pérez', '2645000000', 'juanperez@test.com', 'Calle Falsa 123', 100000.00, 'mayorista', 8400.00, '2025-09-18 04:33:08', '2025-10-12 21:51:27', NULL, 1),
(2, 'María Rosa', 'Videla', '264-155321654', 'maria@gmail.com', 'San Cristobal 1050 Oeste, san Juan', 5000.00, 'minorista', 0.00, '2025-10-12 20:08:17', '2025-10-12 21:50:06', NULL, 1),
(3, 'Alan', 'Rodriguez', '264-154875620', 'alan12@gmail.com', 'Urquiza 1020 Sur', 10000.00, 'minorista', 0.00, '2025-10-12 20:19:56', '2025-10-12 21:50:19', NULL, 1),
(4, 'Tobías Benjamín', 'Peralta Vera', '264-155632951', 'tobias02@gmail.com', '9 DE JULIO S/N', 3500.00, 'minorista', 0.00, '2025-10-12 21:30:42', '2025-10-13 00:11:59', NULL, 1),
(5, 'Gastón', 'Lopez', '264-154789456', 'gaston153@gmail.com', 'Hipolito Yrigoyen 1580 este', 0.00, 'minorista', 0.00, '2025-10-12 21:31:46', '2025-10-12 21:58:30', NULL, 1),
(6, 'Ramiro Gastón', 'Garibay', '264-154863852', 'gary147@xn--gmail-1n0c.com', 'B° Enoe Mendoza Mza C C2', 10000.00, 'mayorista', 0.00, '2025-10-13 00:13:18', '2025-10-13 00:13:18', NULL, 1),
(7, 'Eduardo', 'Quiroga', '264154741258', 'edua23@gmail.com', 'Ignacio de La Roza 153 O', 250000.00, 'mayorista', 0.00, '2025-10-13 00:14:46', '2025-10-13 00:14:46', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

DROP TABLE IF EXISTS `detalle_venta`;
CREATE TABLE IF NOT EXISTS `detalle_venta` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `venta_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` int NOT NULL DEFAULT '1',
  `precio_unitario` decimal(12,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_venta_venta_id_foreign` (`venta_id`),
  KEY `detalle_venta_producto_id_foreign` (`producto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio_unitario`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 150.00, 150.00, '2025-09-20 03:57:13', '2025-09-20 03:57:13'),
(2, 1, 3, 2, 300.00, 600.00, '2025-09-20 03:57:13', '2025-09-20 03:57:13'),
(3, 2, 2, 2, 1100.00, 2200.00, '2025-09-20 04:59:57', '2025-09-20 04:59:57'),
(4, 3, 3, 1, 300.00, 300.00, '2025-09-20 05:03:55', '2025-09-20 05:03:55'),
(5, 4, 3, 3, 300.00, 900.00, '2025-09-20 05:51:36', '2025-09-20 05:51:36'),
(6, 5, 1, 3, 150.00, 450.00, '2025-09-21 19:08:04', '2025-09-21 19:08:04'),
(7, 6, 3, 2, 300.00, 600.00, '2025-09-21 20:30:43', '2025-09-21 20:30:43'),
(8, 6, 1, 1, 150.00, 150.00, '2025-09-21 20:30:43', '2025-09-21 20:30:43'),
(9, 7, 1, 1, 1820.00, 1820.00, '2025-10-03 04:59:43', '2025-10-03 04:59:43'),
(10, 7, 3, 1, 2380.00, 2380.00, '2025-10-03 04:59:43', '2025-10-03 04:59:43'),
(11, 8, 1, 1, 1820.00, 1820.00, '2025-10-03 05:51:09', '2025-10-03 05:51:09'),
(12, 8, 2, 1, 6580.00, 6580.00, '2025-10-03 05:51:09', '2025-10-03 05:51:09'),
(13, 9, 2, 1, 6580.00, 6580.00, '2025-10-03 05:52:27', '2025-10-03 05:52:27'),
(14, 10, 1, 1, 1820.00, 1820.00, '2025-10-04 01:01:25', '2025-10-04 01:01:25'),
(15, 11, 1, 1, 1820.00, 1820.00, '2025-10-10 03:20:22', '2025-10-10 03:20:22'),
(16, 11, 3, 1, 2380.00, 2380.00, '2025-10-10 03:20:22', '2025-10-10 03:20:22'),
(17, 12, 2, 2, 6580.00, 13160.00, '2025-10-10 03:21:17', '2025-10-10 03:21:17'),
(18, 13, 1, 1, 1820.00, 1820.00, '2025-10-11 01:57:26', '2025-10-11 01:57:26'),
(19, 13, 3, 2, 2380.00, 4760.00, '2025-10-11 01:57:26', '2025-10-11 01:57:26'),
(20, 14, 1, 4, 1820.00, 7280.00, '2025-10-11 02:56:50', '2025-10-11 02:56:50'),
(21, 15, 3, 3, 2380.00, 7140.00, '2025-10-11 03:28:45', '2025-10-11 03:28:45'),
(22, 16, 1, 3, 1820.00, 5460.00, '2025-10-11 04:08:22', '2025-10-11 04:08:22'),
(23, 17, 2, 2, 6580.00, 13160.00, '2025-10-11 05:06:45', '2025-10-11 05:06:45'),
(24, 17, 3, 2, 2380.00, 4760.00, '2025-10-11 05:06:45', '2025-10-11 05:06:45'),
(25, 18, 3, 2, 2380.00, 4760.00, '2025-10-11 05:14:04', '2025-10-11 05:14:04'),
(26, 18, 1, 2, 1820.00, 3640.00, '2025-10-11 05:14:04', '2025-10-11 05:14:04'),
(27, 19, 3, 1, 2380.00, 2380.00, '2025-10-11 05:19:23', '2025-10-11 05:19:23'),
(28, 20, 1, 1, 1820.00, 1820.00, '2025-10-11 05:49:31', '2025-10-11 05:49:31'),
(29, 21, 3, 2, 2380.00, 4760.00, '2025-10-11 06:15:28', '2025-10-11 06:15:28'),
(30, 22, 3, 1, 2380.00, 2380.00, '2025-10-11 19:22:10', '2025-10-11 19:22:10'),
(31, 23, 4, 2, 2268.00, 4536.00, '2025-10-12 03:49:57', '2025-10-12 03:49:57'),
(32, 23, 5, 1, 2100.00, 2100.00, '2025-10-12 03:49:57', '2025-10-12 03:49:57'),
(33, 24, 4, 2, 2268.00, 4536.00, '2025-10-13 05:03:45', '2025-10-13 05:03:45'),
(34, 24, 1, 2, 1820.00, 3640.00, '2025-10-13 05:03:45', '2025-10-13 05:03:45'),
(35, 25, 2, 2, 6580.00, 13160.00, '2025-10-13 05:13:19', '2025-10-13 05:13:19'),
(36, 25, 4, 1, 2268.00, 2268.00, '2025-10-13 05:13:19', '2025-10-13 05:13:19'),
(37, 26, 1, 1, 1820.00, 1820.00, '2025-10-14 00:21:22', '2025-10-14 00:21:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listas_precios`
--

DROP TABLE IF EXISTS `listas_precios`;
CREATE TABLE IF NOT EXISTS `listas_precios` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `listas_precios`
--

INSERT INTO `listas_precios` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Minorista', 'Precios de venta al público', '2025-09-18 04:33:08', '2025-09-18 04:33:08'),
(2, 'Mayorista', 'Precios para compras por volumen', '2025-09-18 04:33:08', '2025-09-18 04:33:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_01_01_000001_create_usuarios_table', 1),
(4, '2025_01_01_000002_create_proveedores_table', 1),
(5, '2025_01_01_000003_create_productos_table', 1),
(6, '2025_01_01_000004_create_listas_precios_table', 1),
(7, '2025_01_01_000005_create_clientes_table', 1),
(8, '2025_01_01_000006_create_ventas_table', 1),
(9, '2025_01_01_000007_create_detalle_venta_table', 1),
(10, '2025_01_01_000008_create_caja_table', 1),
(11, '2025_09_18_020649_add_remember_token_to_usuarios_table', 2),
(12, '2025_09_18_021646_add_remember_token_to_usuarios_table', 2),
(13, '2025_09_18_022306_add_remember_token_to_usuarios_table', 3),
(14, '2025_09_20_134613_add_anulacion_fields_to_ventas_table', 4),
(15, '2025_09_20_160633_add_anulacion_fields_to_ventas_table', 5),
(16, '2025_09_20_161336_add_anulacion_fields_to_ventas_table', 6),
(17, '2025_09_20_190505_add_anulacion_fields_to_ventas_table', 7),
(18, '2025_09_23_013133_add_limite_credito_to_clientes_table', 8),
(20, '2025_09_23_013148_update_ventas_table', 9),
(22, '2025_10_10_224234_add_new_fields_to_ventas_table_v2', 10),
(23, '2025_10_11_001440_add_codigo_autorizacion_to_ventas_table', 10),
(24, '2025_10_11_003904_add_transferencia_fields_to_ventas_table', 11),
(25, '2025_10_11_013331_add_credit_fields_to_clientes_table', 12),
(26, '2025_10_11_020526_update_metodo_pago_enum_in_ventas_table', 13),
(27, '2025_10_11_023304_create_pagos_mixtos_table', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_mixtos`
--

DROP TABLE IF EXISTS `pagos_mixtos`;
CREATE TABLE IF NOT EXISTS `pagos_mixtos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `venta_id` bigint UNSIGNED NOT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia') COLLATE utf8mb4_unicode_ci NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `monto_recibido` decimal(12,2) DEFAULT NULL,
  `vuelto` decimal(12,2) DEFAULT NULL,
  `tipo_tarjeta` enum('debito','credito') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ultimos_digitos` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_autorizacion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_transferencia` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banco` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_transferencia` date DEFAULT NULL,
  `hora_transferencia` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pagos_mixtos_venta_id_foreign` (`venta_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pagos_mixtos`
--

INSERT INTO `pagos_mixtos` (`id`, `venta_id`, `metodo_pago`, `monto`, `monto_recibido`, `vuelto`, `tipo_tarjeta`, `ultimos_digitos`, `codigo_autorizacion`, `numero_transferencia`, `banco`, `fecha_transferencia`, `hora_transferencia`, `created_at`, `updated_at`) VALUES
(1, 22, 'efectivo', 1000.00, 1000.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-11 19:22:10', '2025-10-11 19:22:10'),
(2, 22, 'tarjeta', 1380.00, NULL, NULL, 'debito', '1234', 'abc123', NULL, NULL, NULL, NULL, '2025-10-11 19:22:10', '2025-10-11 19:22:10'),
(3, 25, 'efectivo', 10000.00, 10000.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 05:13:19', '2025-10-13 05:13:19'),
(4, 25, 'transferencia', 5428.00, NULL, NULL, NULL, NULL, NULL, 'trf123456', 'Banco de la Nación Argentina', '2025-10-12', '02:12:00', '2025-10-13 05:13:19', '2025-10-13 05:13:19'),
(5, 26, 'efectivo', 1000.00, 1000.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 00:21:22', '2025-10-14 00:21:22'),
(6, 26, 'transferencia', 820.00, NULL, NULL, NULL, NULL, NULL, 'trf123456', 'Banco Galicia', '2025-10-13', '21:21:00', '2025-10-14 00:21:22', '2025-10-14 00:21:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categoria` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proveedor_id` bigint UNSIGNED DEFAULT NULL,
  `precio_compra` decimal(12,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL DEFAULT '0',
  `stock_minimo` int NOT NULL DEFAULT '0',
  `fecha_vencimiento` date DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `productos_codigo_unique` (`codigo`),
  KEY `productos_proveedor_id_foreign` (`proveedor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `codigo`, `imagen`, `categoria`, `proveedor_id`, `precio_compra`, `stock`, `stock_minimo`, `fecha_vencimiento`, `activo`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Coca Cola 500ml', 'BEB001', NULL, 'Bebidas', 1, 1300.00, 32, 10, NULL, 1, '2025-09-18 04:33:08', '2025-10-14 00:21:22', NULL),
(2, 'Marlboro Box', 'CIG001', NULL, 'Cigarrillos', 2, 4700.00, 10, 5, NULL, 1, '2025-09-18 04:33:08', '2025-10-13 05:13:19', NULL),
(3, 'Chocolate Milka', 'GOL001', NULL, 'Golosinas', 3, 1700.00, 16, 5, NULL, 1, '2025-09-18 04:33:08', '2025-10-11 19:22:10', NULL),
(4, 'Galletitas Chocolinas Paq. 250gr', NULL, NULL, 'Golosinas', 1, 1620.00, 7, 5, '2026-05-20', 1, '2025-10-12 02:35:49', '2025-10-13 05:13:19', NULL),
(5, 'Galletitas Oreo paq x 118gr', NULL, NULL, 'Snacks', 3, 1500.00, 12, 5, '2026-05-25', 1, '2025-10-12 03:48:21', '2025-10-12 03:49:57', NULL),
(6, 'Cerveza Schneider Lata x 710 cc.', NULL, 'productos/1760325012_schneider -710.jpg', 'Bebidas', 6, 2200.00, 30, 5, '2026-07-25', 1, '2025-10-13 06:10:12', '2025-10-13 06:10:12', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_precio`
--

DROP TABLE IF EXISTS `producto_precio`;
CREATE TABLE IF NOT EXISTS `producto_precio` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `producto_id` bigint UNSIGNED NOT NULL,
  `lista_precio_id` bigint UNSIGNED NOT NULL,
  `precio` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `producto_precio_producto_id_lista_precio_id_unique` (`producto_id`,`lista_precio_id`),
  KEY `producto_precio_lista_precio_id_foreign` (`lista_precio_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `producto_precio`
--

INSERT INTO `producto_precio` (`id`, `producto_id`, `lista_precio_id`, `precio`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 150.00, '2025-09-18 04:33:08', '2025-09-18 04:33:08'),
(2, 1, 2, 130.00, '2025-09-18 04:33:08', '2025-09-18 04:33:08'),
(3, 2, 1, 1100.00, '2025-09-18 04:33:08', '2025-09-18 04:33:08'),
(4, 2, 2, 1000.00, '2025-09-18 04:33:08', '2025-09-18 04:33:08'),
(5, 3, 1, 300.00, '2025-09-18 04:33:08', '2025-09-18 04:33:08'),
(6, 3, 2, 250.00, '2025-09-18 04:33:08', '2025-09-18 04:33:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
CREATE TABLE IF NOT EXISTS `proveedores` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `telefono`, `email`, `direccion`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Distribuidora San Juan', '2644000001', 'ventas@dsj.com', NULL, '2025-09-18 04:33:08', '2025-09-18 04:33:08', NULL),
(2, 'Cigarrillos SA', '2644000002', 'contacto@cigsa.com', NULL, '2025-09-18 04:33:08', '2025-09-18 04:33:08', NULL),
(3, 'Golosinas SRL', '2644000003', 'info@golosinas.com', NULL, '2025-09-18 04:33:08', '2025-09-18 04:33:08', NULL),
(4, 'Distribuidora AMAF', '2644267895', 'amaf@gmail.com', 'Av Ignacio de La Roza 102 Oeste San Juan', '2025-10-13 05:43:53', '2025-10-13 05:50:53', NULL),
(5, 'Distribuidora Joel', '264156123456', 'distribuidorajoel@gmail.com', 'Mendoza 255 Norte', '2025-10-13 05:51:55', '2025-10-13 05:52:04', '2025-10-13 05:52:04'),
(6, 'Distribuidora Joel', '264-15945257', 'distribuidorajoel@gmail.com', 'Mendoza 666 sur 2 Piso D', '2025-10-13 05:52:49', '2025-10-13 05:52:49', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('administrador','vendedor') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'vendedor',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_email_unique` (`email`),
  UNIQUE KEY `usuarios_usuario_unique` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `usuario`, `password`, `rol`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 'Administrador', 'admin@kiosconet.com', 'admin', '$2y$12$MXHWuskz4o2jUXcZXG5//uEcsaTTN.C16XGRbCuR0pM9c63/yeNEa', 'administrador', NULL, '2025-09-25 02:40:30', '2025-09-30 05:44:59', NULL),
(5, 'Carlos García', 'carlos123@gmail.com', 'Carlos123', '$2y$12$ivIovnNHwN.YVIWj92SiY.I1plZVHNgKj2W1hiNqpcb3VFoWI9DRW', 'vendedor', NULL, '2025-10-13 07:00:10', '2025-10-13 07:00:10', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

DROP TABLE IF EXISTS `ventas`;
CREATE TABLE IF NOT EXISTS `ventas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `numero` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario_id` bigint UNSIGNED NOT NULL,
  `cliente_id` bigint UNSIGNED DEFAULT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'completada',
  `metodo_pago` enum('efectivo','tarjeta','transferencia','billetera','cc','cuenta_corriente','mixto') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'efectivo',
  `tipo_tarjeta` enum('debito','credito') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monto_recibido` decimal(12,2) NOT NULL DEFAULT '0.00',
  `vuelto` decimal(12,2) NOT NULL DEFAULT '0.00',
  `anulada` tinyint(1) NOT NULL DEFAULT '0',
  `usuario_anulacion_id` bigint UNSIGNED DEFAULT NULL,
  `fecha_anulacion` timestamp NULL DEFAULT NULL,
  `motivo_anulacion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lista_precios` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'minorista',
  `fecha_venta` timestamp NULL DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `descuento_porcentaje` decimal(5,2) NOT NULL DEFAULT '0.00',
  `descuento_monto` decimal(10,2) NOT NULL DEFAULT '0.00',
  `comision` decimal(10,2) NOT NULL DEFAULT '0.00',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `observaciones_cc` text COLLATE utf8mb4_unicode_ci,
  `saldo_anterior` decimal(10,2) DEFAULT NULL,
  `nuevo_saldo` decimal(10,2) DEFAULT NULL,
  `impreso` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_impresion` timestamp NULL DEFAULT NULL,
  `cantidad_reimpresiones` int NOT NULL DEFAULT '0',
  `token_comprobante` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ultimos_digitos` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_autorizacion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_transferencia` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_transferencia` date DEFAULT NULL,
  `hora_transferencia` time DEFAULT NULL,
  `banco` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ventas_numero_unique` (`numero`),
  UNIQUE KEY `ventas_token_comprobante_unique` (`token_comprobante`),
  KEY `ventas_usuario_id_foreign` (`usuario_id`),
  KEY `ventas_cliente_id_foreign` (`cliente_id`),
  KEY `ventas_usuario_anulacion_id_foreign` (`usuario_anulacion_id`),
  KEY `ventas_anulada_index` (`anulada`),
  KEY `ventas_anulada_created_at_index` (`anulada`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `numero`, `usuario_id`, `cliente_id`, `total`, `estado`, `metodo_pago`, `tipo_tarjeta`, `monto_recibido`, `vuelto`, `anulada`, `usuario_anulacion_id`, `fecha_anulacion`, `motivo_anulacion`, `created_at`, `updated_at`, `lista_precios`, `fecha_venta`, `subtotal`, `descuento_porcentaje`, `descuento_monto`, `comision`, `observaciones`, `observaciones_cc`, `saldo_anterior`, `nuevo_saldo`, `impreso`, `fecha_impresion`, `cantidad_reimpresiones`, `token_comprobante`, `ultimos_digitos`, `codigo_autorizacion`, `numero_transferencia`, `fecha_transferencia`, `hora_transferencia`, `banco`) VALUES
(1, '20250920000001', 1, NULL, 750.00, 'completada', 'efectivo', NULL, 1000.00, 250.00, 1, 1, '2025-09-20 22:07:50', 'Devolución', '2025-09-20 03:57:13', '2025-09-20 22:07:50', 'minorista', NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '20250920000002', 1, NULL, 2200.00, 'completada', 'efectivo', NULL, 5000.00, 2800.00, 0, NULL, NULL, NULL, '2025-09-20 04:59:57', '2025-09-20 04:59:57', 'minorista', NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, '20250920000003', 1, NULL, 300.00, 'completada', 'efectivo', NULL, 500.00, 200.00, 0, NULL, NULL, NULL, '2025-09-20 05:03:55', '2025-09-20 05:03:55', 'minorista', NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, '20250920000004', 1, NULL, 900.00, 'completada', 'efectivo', NULL, 1500.00, 600.00, 0, NULL, NULL, NULL, '2025-09-20 05:51:36', '2025-09-20 05:51:36', 'minorista', NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, '20250921000005', 1, NULL, 450.00, 'completada', 'efectivo', NULL, 1000.00, 550.00, 0, NULL, NULL, NULL, '2025-09-21 19:08:04', '2025-09-21 19:08:04', 'minorista', NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, '20250921000006', 1, NULL, 750.00, 'completada', 'efectivo', NULL, 1000.00, 250.00, 0, NULL, NULL, NULL, '2025-09-21 20:30:43', '2025-09-21 20:30:43', 'minorista', NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'V250921000007', 4, 1, 4200.00, 'completada', 'efectivo', NULL, 5000.00, 800.00, 1, 4, '2025-10-03 05:48:30', 'Venta eliminada desde el listado', '2025-10-03 04:59:43', '2025-10-03 05:48:30', 'minorista', NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 'V250921000008', 4, 1, 8400.00, 'completada', 'efectivo', NULL, 10000.00, 1600.00, 0, NULL, NULL, NULL, '2025-10-03 05:51:09', '2025-10-03 05:51:09', 'minorista', NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'V250921000009', 4, 1, 6580.00, 'completada', 'efectivo', NULL, 8000.00, 1420.00, 0, NULL, NULL, NULL, '2025-10-03 05:52:27', '2025-10-03 05:52:27', 'minorista', NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 'V250921000010', 4, 1, 1820.00, 'completada', 'efectivo', NULL, 2000.00, 180.00, 0, NULL, NULL, NULL, '2025-10-04 01:01:25', '2025-10-04 01:01:25', 'minorista', NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 'V250921000011', 4, 1, 4200.00, 'completada', 'efectivo', NULL, 5000.00, 800.00, 0, NULL, NULL, NULL, '2025-10-10 03:20:22', '2025-10-10 03:20:22', 'minorista', NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 'V250921000012', 4, 1, 13160.00, 'completada', 'efectivo', NULL, 15000.00, 1840.00, 0, NULL, NULL, NULL, '2025-10-10 03:21:17', '2025-10-10 03:21:17', 'minorista', NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 'V250921000013', 4, 1, 6580.00, 'completada', 'efectivo', NULL, 7000.00, 420.00, 0, NULL, NULL, NULL, '2025-10-11 01:57:26', '2025-10-11 01:57:56', 'minorista', NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 'V250921000014', 4, 1, 7280.00, 'completada', 'efectivo', NULL, 10000.00, 2720.00, 0, NULL, NULL, NULL, '2025-10-11 02:56:50', '2025-10-11 02:57:48', 'minorista', NULL, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 'V250921000015', 4, 1, 7140.00, 'completada', 'tarjeta', 'debito', 0.00, 0.00, 0, NULL, NULL, NULL, '2025-10-11 03:28:45', '2025-10-11 03:29:01', 'minorista', '2025-10-11 03:00:00', 7140.00, 0.00, 0.00, 142.80, NULL, NULL, NULL, NULL, 1, '2025-10-11 03:29:01', 0, 'v0EtuqNXcGwYfPzMIVccDOU99606gnmK2xDNKsW5', '1234', 'abc123', NULL, NULL, NULL, NULL),
(16, 'V250921000016', 4, 1, 5460.00, 'completada', 'transferencia', NULL, 0.00, 0.00, 0, NULL, NULL, NULL, '2025-10-11 04:08:22', '2025-10-11 04:08:27', 'minorista', '2025-10-11 03:00:00', 5460.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, '2025-10-11 04:08:27', 0, 'FCVxCqJgdluReeX88XxKqyqVMtXWvHfb4J7L6pRo', NULL, NULL, 'TRF123456789', '2025-10-11', NULL, 'Banco Supervielle'),
(17, 'V250921000017', 4, 1, 17920.00, 'completada', 'cuenta_corriente', NULL, 0.00, 0.00, 0, NULL, NULL, NULL, '2025-10-11 05:06:45', '2025-10-11 05:06:45', 'mayorista', '2025-10-11 03:00:00', 17920.00, 0.00, 0.00, 0.00, NULL, NULL, 0.00, 17920.00, 0, NULL, 0, 'fe82HHgnw7eFai7ohjxBvBLtdlrLxBLRlCWwGqvy', NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'V250921000018', 4, 1, 8400.00, 'completada', 'cuenta_corriente', NULL, 0.00, 0.00, 0, NULL, NULL, NULL, '2025-10-11 05:14:04', '2025-10-11 05:15:39', 'mayorista', '2025-10-11 03:00:00', 8400.00, 0.00, 0.00, 0.00, NULL, NULL, 0.00, 8400.00, 1, '2025-10-11 05:15:39', 0, 'Hmgp4GvVfB2ZKA9eUaYmcUyJnqEocHsg6THYSD2Z', NULL, NULL, NULL, NULL, NULL, NULL),
(19, 'V250921000019', 4, 1, 2380.00, 'completada', 'mixto', NULL, 0.00, 0.00, 0, NULL, NULL, NULL, '2025-10-11 05:19:23', '2025-10-11 05:19:23', 'mayorista', '2025-10-11 03:00:00', 2380.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 0, NULL, 0, '6w3aGQxox4plObn4yBkO2cH7bVmyEe6wOA6uk6E2', NULL, NULL, NULL, NULL, NULL, NULL),
(20, 'V250921000020', 4, 1, 1820.00, 'completada', 'mixto', NULL, 0.00, 0.00, 0, NULL, NULL, NULL, '2025-10-11 05:49:31', '2025-10-11 05:49:31', 'mayorista', '2025-10-11 03:00:00', 1820.00, 0.00, 0.00, 0.00, ' [PAGO MIXTO]', NULL, NULL, NULL, 0, NULL, 0, 'f3Frtze7ySsmTL3NJi9yFp47sYSgIxTEJCMXrx6U', NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'V250921000021', 4, 1, 4760.00, 'completada', 'mixto', NULL, 0.00, 0.00, 0, NULL, NULL, NULL, '2025-10-11 06:15:28', '2025-10-11 06:15:28', 'mayorista', '2025-10-11 03:00:00', 4760.00, 0.00, 0.00, 0.00, ' [PAGO MIXTO]', NULL, NULL, NULL, 0, NULL, 0, 'sHbkUdsbQCv8WPTUnDoTXGwcATK04nJ1mOsOhj46', NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'V250921000022', 4, 1, 2380.00, 'completada', 'mixto', NULL, 0.00, 0.00, 0, NULL, NULL, NULL, '2025-10-11 19:22:10', '2025-10-11 19:22:15', 'mayorista', '2025-10-11 03:00:00', 2380.00, 0.00, 0.00, 0.00, ' [PAGO MIXTO]', NULL, NULL, NULL, 1, '2025-10-11 19:22:15', 0, 'frs6qg8tIqfqWhXvOqqbzqEoJempo2B6Rv2OArLL', NULL, NULL, NULL, NULL, NULL, NULL),
(23, 'V250921000023', 4, 1, 6636.00, 'completada', 'efectivo', NULL, 7000.00, 364.00, 0, NULL, NULL, NULL, '2025-10-12 03:49:57', '2025-10-12 03:49:59', 'mayorista', '2025-10-12 03:00:00', 6636.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, 1, '2025-10-12 03:49:59', 0, '9ZhekFmdOe8VreveqSCg2SbtpsbXnO6ocWMWRgQK', NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'V250921000024', 4, 3, 8339.52, 'completada', 'tarjeta', 'debito', 0.00, 0.00, 0, NULL, NULL, NULL, '2025-10-13 05:03:45', '2025-10-13 05:03:51', 'minorista', '2025-10-13 03:00:00', 8176.00, 0.00, 0.00, 163.52, NULL, NULL, NULL, NULL, 1, '2025-10-13 05:03:51', 0, '93AapMKru20lNWZe9ZKEITE8LoMfJyFEGaUBbS0Y', '1234', 'abc123', NULL, NULL, NULL, NULL),
(25, 'V250921000025', 4, 7, 15428.00, 'completada', 'mixto', NULL, 0.00, 0.00, 0, NULL, NULL, NULL, '2025-10-13 05:13:19', '2025-10-13 05:13:19', 'mayorista', '2025-10-12 03:00:00', 15428.00, 0.00, 0.00, 0.00, ' [PAGO MIXTO]', NULL, NULL, NULL, 0, NULL, 0, 'g4hn3EfM8PB1SsOO8zLaA29tamQaGHx7Uh3SLGln', NULL, NULL, NULL, NULL, NULL, NULL),
(26, 'V250921000026', 4, 3, 1820.00, 'completada', 'mixto', NULL, 0.00, 0.00, 0, NULL, NULL, NULL, '2025-10-14 00:21:22', '2025-10-14 00:21:30', 'minorista', '2025-10-13 03:00:00', 1820.00, 0.00, 0.00, 0.00, ' [PAGO MIXTO]', NULL, NULL, NULL, 1, '2025-10-14 00:21:30', 0, 'vZ2LOHKaL6lrMdxEq2pneiDnzcuYKfhCD7PEA1H4', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------
--
-- CONSTRAINTS - FOREIGN KEYS (RELACIONES ENTRE TABLAS)
-- Se agregan al final para asegurar integridad referencial
--
-- --------------------------------------------------------

--
-- Filtros para la tabla `caja`
--
ALTER TABLE `caja`
  ADD CONSTRAINT `caja_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_venta_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos_mixtos`
--
ALTER TABLE `pagos_mixtos`
  ADD CONSTRAINT `pagos_mixtos_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_precio`
--
ALTER TABLE `producto_precio`
  ADD CONSTRAINT `producto_precio_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_precio_lista_precio_id_foreign` FOREIGN KEY (`lista_precio_id`) REFERENCES `listas_precios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_usuario_anulacion_id_foreign` FOREIGN KEY (`usuario_anulacion_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- --------------------------------------------------------
--
-- ÍNDICES ADICIONALES PARA OPTIMIZACIÓN
-- Mejoran el rendimiento de consultas frecuentes
--
-- --------------------------------------------------------

--
-- Índices para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD INDEX `idx_clientes_tipo` (`tipo_cliente`),
  ADD INDEX `idx_clientes_activo` (`activo`),
  ADD INDEX `idx_clientes_email` (`email`),
  ADD INDEX `idx_clientes_nombre_apellido` (`nombre`, `apellido`);

--
-- Índices para la tabla `productos`
--
ALTER TABLE `productos`
  ADD INDEX `idx_productos_categoria` (`categoria`),
  ADD INDEX `idx_productos_activo` (`activo`),
  ADD INDEX `idx_productos_stock` (`stock`),
  ADD INDEX `idx_productos_vencimiento` (`fecha_vencimiento`);

--
-- Índices para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD INDEX `idx_ventas_fecha_venta` (`fecha_venta`),
  ADD INDEX `idx_ventas_estado` (`estado`),
  ADD INDEX `idx_ventas_metodo_pago` (`metodo_pago`),
  ADD INDEX `idx_ventas_lista_precios` (`lista_precios`),
  ADD INDEX `idx_ventas_created_at` (`created_at`);

--
-- Índices para la tabla `caja`
--
ALTER TABLE `caja`
  ADD INDEX `idx_caja_tipo` (`tipo`),
  ADD INDEX `idx_caja_created_at` (`created_at`);

--
-- Índices para la tabla `pagos_mixtos`
--
ALTER TABLE `pagos_mixtos`
  ADD INDEX `idx_pagos_metodo` (`metodo_pago`);

--
-- Índices para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD INDEX `idx_usuarios_rol` (`rol`);

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
