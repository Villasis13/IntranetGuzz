-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 27-01-2024 a las 15:55:05
-- Versión del servidor: 5.7.33
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda_vargas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `id_caja` int(11) NOT NULL,
  `fecha_caja` datetime NOT NULL,
  `monto_caja` decimal(10,2) NOT NULL,
  `estado_caja` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `categoria_nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `categoria_nombre`) VALUES
(1, 'Cervezas'),
(2, 'Gaseosas'),
(3, 'Galletas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `cliente_nombre` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cliente_apellidos` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cliente_dni` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `cliente_celular` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cliente_email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cliente_genero` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `cliente_nombre`, `cliente_apellidos`, `cliente_dni`, `cliente_celular`, `cliente_email`, `cliente_genero`) VALUES
(1, 'Jorge', 'Tenazoa Dorado', '67868973	', '980786745', 'jorge@gmail.com', 'MASCULINO'),
(2, 'Roger', 'Chavez', '67868973', '930572455', 'roger@gmail.com', 'MASCULINO'),
(3, 'Bryan', 'Diaz', '67589473', '908957842', 'bryan@gmail.com', 'MASCULINO'),
(4, 'Diana', 'Sanchez', '76890784', '970784577', 'diana@gmail.com', 'FEMENINO'),
(5, 'Wagner', 'Villasis', '45678923', '956738234', 'villasis@gmail.com', 'MASCULINO'),
(6, 'Anónimo', 'Cliente', '00000000', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_formato_ingreso`
--

CREATE TABLE `detalle_formato_ingreso` (
  `id_detalle` int(11) NOT NULL,
  `id_formato` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `id_medida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_formato_ingreso`
--

INSERT INTO `detalle_formato_ingreso` (`id_detalle`, `id_formato`, `id_producto`, `cantidad`, `id_medida`) VALUES
(1, 2, 1, 12312, 3),
(2, 2, 25, 112, 1),
(3, 3, 1, 500, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id_detalle_venta` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `detalle_venta_cantidad` decimal(10,2) NOT NULL,
  `detalle_venta_subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id_detalle_venta`, `id_venta`, `id_producto`, `detalle_venta_cantidad`, `detalle_venta_subtotal`) VALUES
(27, 22, 2, '3.00', '9.00'),
(28, 23, 24, '1.00', '0.50'),
(29, 24, 25, '8.00', '4.00'),
(30, 25, 1, '8.00', '24.00'),
(31, 25, 12, '5.00', '200.00'),
(32, 26, 1, '9.00', '27.00'),
(33, 27, 1, '3.00', '9.00'),
(34, 28, 1, '5.00', '15.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE `documento` (
  `id_documento` int(11) NOT NULL,
  `id_tipo_documento` int(11) NOT NULL,
  `documento_serie` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `documento`
--

INSERT INTO `documento` (`id_documento`, `id_tipo_documento`, `documento_serie`) VALUES
(1, 1, 'B001-000001'),
(2, 2, 'F001-000001'),
(3, 1, 'B001-000002'),
(4, 2, 'F001-000002'),
(5, 1, 'B001-000011'),
(6, 1, 'B001-000012'),
(7, 2, 'F001-000003'),
(8, 1, 'B001-000013'),
(9, 1, 'B001-000014'),
(10, 2, 'F001-000004'),
(11, 1, 'B001-000015'),
(12, 1, 'B001-000016'),
(13, 1, 'B001-000017'),
(14, 2, 'F001-000005'),
(15, 2, 'F001-000006'),
(16, 1, 'B001-000018'),
(17, 1, 'B001-000019'),
(18, 1, 'B001-000020'),
(19, 1, 'B001-000021'),
(20, 1, 'B001-000022');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formato_ingreso`
--

CREATE TABLE `formato_ingreso` (
  `id_formato` int(11) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `hora_ingreso` time NOT NULL,
  `id_proveedor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `formato_ingreso`
--

INSERT INTO `formato_ingreso` (`id_formato`, `fecha_ingreso`, `hora_ingreso`, `id_proveedor`) VALUES
(1, '2004-09-23', '01:56:50', 2),
(2, '2004-09-23', '01:58:43', 1),
(3, '2004-09-23', '02:07:03', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medida`
--

CREATE TABLE `medida` (
  `id_medida` int(11) NOT NULL,
  `nombre_medida` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `medida_codigo` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `medida`
--

INSERT INTO `medida` (`id_medida`, `nombre_medida`, `medida_codigo`) VALUES
(1, 'UNIDADES INDIVIDUALES', 'UI'),
(2, 'DOCENAS', 'DC'),
(3, 'PAQUETES', 'PQ'),
(4, 'GRAMOS', 'GR'),
(5, 'KILOGRAMOS', 'KG'),
(6, 'LITROS', 'LI'),
(7, 'BOLSAS', 'BL'),
(8, 'BOTELLAS', 'BT');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus`
--

CREATE TABLE `menus` (
  `id_menu` int(11) NOT NULL,
  `menu_nombre` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `menu_controlador` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `menu_icono` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `menu_orden` int(11) NOT NULL,
  `menu_mostrar` tinyint(1) NOT NULL,
  `menu_estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `menus`
--

INSERT INTO `menus` (`id_menu`, `menu_nombre`, `menu_controlador`, `menu_icono`, `menu_orden`, `menu_mostrar`, `menu_estado`) VALUES
(1, 'Login', 'Login', '-', 0, 0, 1),
(2, 'Panel de Inicio', 'Admin', 'fa fa-dashboard', 1, 0, 1),
(3, 'Gestión de Menu', 'Menu', 'menu-icon fa fa-desktop', 2, 1, 1),
(4, 'Roles de Usuario', 'Rol', 'fa fa-user-secret', 4, 0, 1),
(5, 'Usuarios', 'Usuario', 'fa fa-user', 5, 0, 1),
(6, 'Datos Personales', 'Datos', 'fa fa-', 0, 0, 1),
(11, ' Productos', 'Productos', 'menu-icon bi bi-box-seam', 6, 1, 1),
(12, 'Clientes', 'Clientes', 'menu-icon fa fa-users', 7, 1, 1),
(13, 'Ventas', 'Ventas', 'menu-icon bi bi-cash-coin', 8, 1, 1),
(15, 'Reportes', 'Reportes', 'menu-icon fa fa-book', 10, 1, 1),
(16, 'Caja', 'Caja', 'menu-icon bi bi-inboxes-fill', 11, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones`
--

CREATE TABLE `opciones` (
  `id_opcion` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `opcion_nombre` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `opcion_funcion` varchar(35) COLLATE utf8_unicode_ci NOT NULL,
  `opcion_icono` char(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `opcion_mostrar` tinyint(1) NOT NULL,
  `opcion_orden` int(11) NOT NULL,
  `opcion_estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `opciones`
--

INSERT INTO `opciones` (`id_opcion`, `id_menu`, `opcion_nombre`, `opcion_funcion`, `opcion_icono`, `opcion_mostrar`, `opcion_orden`, `opcion_estado`) VALUES
(1, 1, 'Inicio de Sesion', 'inicio', '-', 0, 0, 1),
(2, 2, 'Inicio', 'inicio', 'fa fa-play', 1, 1, 1),
(3, 2, 'Cerrar Sesión', 'finalizar_sesion', 'fa fa-sign-out', 0, 1, 1),
(4, 3, 'Gestionar Menús', 'inicio', NULL, 1, 1, 1),
(5, 3, 'Iconos', 'iconos', NULL, 1, 2, 1),
(6, 3, 'Accesos por Rol', 'roles', NULL, 0, 0, 1),
(7, 3, 'Opciones por Menú', 'opciones', NULL, 0, 0, 1),
(8, 3, 'Gestionar Permisos(breve)', 'gestion_permisos', '', 0, 0, 1),
(9, 4, 'Gestionar Roles', 'inicio', '', 1, 1, 1),
(10, 4, 'Accesos por Rol', 'accesos', '', 0, 0, 1),
(11, 3, 'Gestionar Restricciones(breve)', 'gestion_restricciones', '', 0, 0, 1),
(12, 5, 'Gestionar Usuarios', 'inicio', '', 1, 1, 1),
(13, 6, 'Editar Datos', 'editar_datos', '', 0, 0, 1),
(14, 6, 'Editar Usuario', 'editar_usuario', '', 0, 0, 1),
(15, 6, 'Cambiar Contraseña', 'cambiar_contrasenha', '', 0, 0, 1),
(19, 10, 'Ver Caja', 'inicio', '', 1, 1, 1),
(20, 11, 'Listar Productos', 'inicio', '', 1, 1, 1),
(21, 12, 'Ver Clientes', 'inicio', '', 1, 1, 1),
(22, 13, 'Realizar Venta', 'inicio', '', 1, 1, 1),
(23, 14, 'Gestionar Caja', 'inicio', '', 1, 1, 1),
(24, 11, 'Formato de Ingreso', 'formato', '', 1, 2, 1),
(25, 16, 'Gestionar Caja', 'inicio', '', 1, 1, 1),
(26, 15, 'Ver Reportes', 'inicio', '', 1, 1, 1),
(27, 15, 'Ver Ventas', 'historial_ventas', '', 0, 2, 1),
(28, 15, 'Ver Compras', 'historial_compras', '', 0, 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_permiso` int(11) NOT NULL,
  `id_opcion` int(11) NOT NULL,
  `permiso_accion` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `permiso_estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_permiso`, `id_opcion`, `permiso_accion`, `permiso_estado`) VALUES
(1, 1, 'validar_sesion', 1),
(2, 4, 'guardar_menu', 1),
(3, 6, 'configurar_relacion', 1),
(4, 7, 'guardar_opcion', 1),
(5, 7, 'agregar_permiso', 1),
(6, 7, 'eliminar_permiso', 1),
(7, 7, 'configurar_acceso', 1),
(8, 9, 'guardar_rol', 1),
(9, 10, 'gestionar_acceso_rol', 1),
(10, 12, 'guardar_nuevo_usuario', 1),
(11, 12, 'guardar_edicion_usuario', 1),
(12, 12, 'guardar_edicion_persona', 1),
(13, 12, 'restablecer_contrasenha', 1),
(14, 13, 'guardar_datos', 1),
(15, 14, 'guardar_usuario', 1),
(16, 15, 'guardar_contrasenha', 1),
(17, 20, 'guardar_editar_productos', 1),
(18, 20, 'edicion_productos', 1),
(19, 20, 'eliminar_producto', 1),
(20, 21, 'guardar_editar_clientes', 1),
(21, 21, 'edicion_clientes', 1),
(22, 21, 'eliminar_cliente', 1),
(23, 23, 'abrir_caja', 1),
(24, 24, 'listar_productos_input', 1),
(25, 22, 'listar_productos_comprar', 1),
(26, 22, 'guardar_realizar_venta', 1),
(29, 22, 'ultima_serie', 1),
(30, 25, 'abrir_caja', 1),
(31, 24, 'guardar_formato_ingreso', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id_persona` int(11) NOT NULL,
  `persona_nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `persona_apellido_paterno` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `persona_apellido_materno` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `persona_nacimiento` date DEFAULT NULL,
  `persona_telefono` char(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `persona_creacion` datetime NOT NULL,
  `persona_modificacion` datetime NOT NULL,
  `person_codigo` varchar(40) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id_persona`, `persona_nombre`, `persona_apellido_paterno`, `persona_apellido_materno`, `persona_nacimiento`, `persona_telefono`, `persona_creacion`, `persona_modificacion`, `person_codigo`) VALUES
(1, 'Wagner Villasis', 'Hidalgo', NULL, NULL, NULL, '2020-09-17 00:00:00', '2020-09-17 00:00:00', '010101010101'),
(2, 'Leandro Villasis', 'Hidalgo', NULL, NULL, NULL, '2023-06-06 05:44:43', '2023-06-06 05:44:43', '010101010100');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `id_medida` int(11) NOT NULL,
  `producto_nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `producto_precio` decimal(10,2) NOT NULL,
  `producto_stock` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_creacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `id_medida`, `producto_nombre`, `producto_precio`, `producto_stock`, `fecha_creacion`) VALUES
(1, 3, 'Vaso Plástico', '3.00', '20', NULL),
(2, 1, 'Papel Toalla Higienol', '3.00', '14', NULL),
(3, 1, 'Ayudin Grande', '9.00', '5', NULL),
(4, 1, 'Ayudin Pequeño', '3.00', '15', NULL),
(5, 3, 'Papel Suave', '15.00', '0', NULL),
(6, 3, 'Pamper Ninet', '50.00', '10', NULL),
(7, 1, 'Cuadernos Grande', '5.00', '49', NULL),
(8, 3, 'Jabones en Barra', '70.00', '5', NULL),
(9, 3, 'Leche Gloria', '99.99', '5', NULL),
(10, 1, 'Filete Real', '7.50', '48', NULL),
(11, 2, 'Huevos', '8.00', '24', NULL),
(12, 3, 'Pasta Dental Kolynos', '40.00', '2', NULL),
(13, 3, 'Sardina Rosaimar', '99.99', '5', NULL),
(14, 1, 'Ajinomen', '1.80', '15', NULL),
(15, 1, 'Lejía Margot', '0.50', '70', NULL),
(16, 1, 'Aceite Tondero', '10.00', '36', NULL),
(17, 1, 'Aceite Palmerola', '8.50', '48', NULL),
(18, 3, 'Gaseosa Coca Cola', '50.00', '10', NULL),
(19, 5, 'Arroz', '3.50', '10', NULL),
(20, 5, 'Azúcar', '4.00', '15', NULL),
(21, 2, 'Jabonsillo Protex', '30.00', '10', NULL),
(22, 1, 'Bolígrafo Faber Castell', '0.70', '40', NULL),
(23, 3, 'Cigarro Hamilton', '30.00', '5', NULL),
(24, 1, 'Galleta Soda', '0.50', '54', NULL),
(25, 1, 'Galleta Vainilla', '0.50', '52', NULL),
(26, 1, 'Pila Panasonic Grande', '4.50', '20', NULL),
(27, 1, 'Pila Panasonic Pequeño', '2.50', '40', NULL),
(28, 1, 'Tokai', '3.00', '17', NULL),
(29, 5, 'Maíz', '2.00', '10', NULL),
(30, 3, 'Yomost', '11.00', '5', NULL),
(31, 1, 'Mentol Vick Vaporub', '3.50', '24', NULL),
(32, 1, 'Mentol Sikura', '3.50', '36', NULL),
(33, 1, 'Sal Yodada Yamisal', '1.00', '25', NULL),
(34, 1, 'Linterna Luken', '6.00', '16', NULL),
(35, 1, 'Jamonilla Tulip', '9.00', '20', NULL),
(36, 1, 'Hot Dog Pequeño', '9.00', '8', NULL),
(37, 1, 'Hot Dog Grande', '13.00', '0', NULL),
(38, 1, 'Entero de Anchoveta Rosaimar', '5.50', '12', NULL),
(39, 1, 'Toalla Higiénica Nosotras', '5.50', '60', NULL),
(40, 3, 'Fósforo Cocinero', '3.00', '7', NULL),
(41, 1, 'Pulp Grande Durazno', '5.00', '8', NULL),
(42, 1, 'Pulp Mediano Durazno', '2.00', '15', NULL),
(43, 1, 'Pulp Pequeño Durazno', '1.00', '30', NULL),
(44, 1, 'Gaseosa Coca Cola personal', '3.00', '49', NULL),
(45, 1, 'Gaseosa Inca Kola personal', '3.00', '47', NULL),
(46, 6, 'Gaseosa Coca Cola Litro', '10.00', '20', NULL),
(47, 6, 'Gaseosa Inca Kola Litro', '10.00', '20', NULL),
(48, 1, 'Jabón Popeye', '2.00', '24', NULL),
(49, 1, 'Jabón Bolivar', '3.00', '28', NULL),
(50, 1, 'Jabón Jumbo', '2.00', '22', NULL),
(51, 3, 'Vela Rayo', '5.00', '15', NULL),
(52, 1, 'Poett Grande', '3.50', '4', NULL),
(53, 1, 'Poett Pequeño', '2.50', '5', NULL),
(54, 7, 'Bolsa Bombón Globo Pop', '7.00', '9', NULL),
(55, 7, 'Chicle Huevito', '8.00', '10', NULL),
(56, 3, 'Agua San Luis Pequeño', '20.00', '4', NULL),
(57, 3, 'Agua San Luis Grande ', '30.00', '6', NULL),
(58, 8, 'Ron Cartavio', '20.00', '6', NULL),
(59, 8, 'Sillao', '3.00', '8', NULL),
(60, 8, 'Vinagre', '3.00', '12', NULL),
(61, 1, 'Avena Grano de Oro', '1.00', '35', NULL),
(62, 1, 'Tallarín Santa Catalina', '3.00', '20', NULL),
(63, 1, 'Macarrón Espiga de Oro', '2.00', '19', NULL),
(64, 1, 'Café Kirma', '1.00', '97', NULL),
(65, 5, 'Fariña', '5.00', '10', NULL),
(66, 1, 'Cocoa D\'Gussto', '1.00', '50', NULL),
(67, 1, 'Milo', '1.20', '42', NULL),
(68, 1, 'Shampoo Head ', '1.00', '45', NULL),
(69, 1, 'Colonia Pera in Love', '22.00', '9', NULL),
(70, 1, 'Perfume Mersi', '40.00', '12', NULL),
(71, 1, 'Perfume Kalos', '60.00', '7', NULL),
(72, 1, 'Talco Imágenes ', '15.00', '24', NULL),
(73, 1, 'Betún Santiago Grande', '4.00', '15', NULL),
(74, 1, 'Mayonesa Alacena', '6.50', '25', NULL),
(75, 1, 'Sal de Andrews', '0.70', '80', NULL),
(76, 1, 'Gelatina Cifrut ', '3.50', '34', NULL),
(77, 1, 'Suavitel', '1.20', '19', NULL),
(78, 1, 'Timolina', '3.00', '12', NULL),
(79, 1, 'Agua Oxigenada', '2.00', '16', NULL),
(80, 1, 'Leche de Magnesia', '9.00', '26', NULL),
(81, 7, 'Algodón', '2.00', '18', NULL),
(82, 1, 'Paraguas', '22.00', '6', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `id_proveedor` int(11) NOT NULL,
  `nombre_proveedor` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`id_proveedor`, `nombre_proveedor`) VALUES
(1, 'Comercial Lander'),
(2, 'Tienda Doosma');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `restricciones`
--

CREATE TABLE `restricciones` (
  `id_restriccion` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_opcion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `rol_nombre` char(20) COLLATE utf8_unicode_ci NOT NULL,
  `rol_descripcion` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `rol_estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `rol_nombre`, `rol_descripcion`, `rol_estado`) VALUES
(1, 'Libre', 'Accesos sin inicio de sesión', 1),
(2, 'SuperAdmin', 'Tiene acceso a la gestión total del sistema', 1),
(3, 'Admin', 'Gestión del sistema', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_menus`
--

CREATE TABLE `roles_menus` (
  `id_rol_menu` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `roles_menus`
--

INSERT INTO `roles_menus` (`id_rol_menu`, `id_rol`, `id_menu`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 2, 3),
(4, 2, 4),
(5, 2, 5),
(6, 3, 2),
(7, 3, 5),
(8, 2, 6),
(9, 3, 6),
(10, 2, 7),
(11, 2, 8),
(12, 1, 8),
(13, 2, 9),
(14, 3, 9),
(15, 1, 9),
(16, 1, 10),
(17, 2, 10),
(18, 3, 10),
(19, 2, 11),
(20, 2, 12),
(21, 2, 13),
(22, 2, 14),
(23, 3, 14),
(24, 2, 14),
(25, 1, 14),
(26, 1, 15),
(27, 2, 15),
(28, 3, 15),
(29, 2, 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `id_tipo_documento` int(11) NOT NULL,
  `tipo_documento_nombre` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`id_tipo_documento`, `tipo_documento_nombre`) VALUES
(1, 'Boleta'),
(2, 'Factura');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_pago`
--

CREATE TABLE `tipo_pago` (
  `id_tipo_pago` int(11) NOT NULL,
  `tipo_pago_nombre` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_pago`
--

INSERT INTO `tipo_pago` (`id_tipo_pago`, `tipo_pago_nombre`) VALUES
(1, 'Efectivo'),
(2, 'Tarjeta'),
(3, 'Crédito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `usuario_nickname` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `usuario_contrasenha` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `usuario_email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `usuario_imagen` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `usuario_estado` tinyint(1) NOT NULL,
  `usuario_creacion` datetime NOT NULL,
  `usuario_ultimo_login` datetime NOT NULL,
  `usuario_ultima_modificacion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_persona`, `id_rol`, `usuario_nickname`, `usuario_contrasenha`, `usuario_email`, `usuario_imagen`, `usuario_estado`, `usuario_creacion`, `usuario_ultimo_login`, `usuario_ultima_modificacion`) VALUES
(1, 1, 2, 'superadmin', '$2y$10$oPOOOgTUr4zIh511ATm/q.vzsAmxP.e2.vzyEbRn/1pzyWz2oXj0a', 'cesarjose@bufeotec.com', 'media/usuarios/usuario.jpg', 1, '2020-09-17 00:00:00', '2023-09-12 19:39:55', '2020-09-17 00:00:00'),
(2, 2, 3, 'admin', '$2y$10$8ZxmfjUaJocc1SOYS8vDNufcPgcru5aMiEp4HP9J8KA.7mnlkFfiu', 'carlos@gmail.com', 'media/usuarios/usuario.jpg', 1, '2020-10-27 18:29:10', '2023-09-11 17:40:34', '2020-10-27 18:29:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_documento` int(11) NOT NULL,
  `id_tipo_pago` int(11) NOT NULL,
  `venta_fecha` datetime NOT NULL,
  `venta_monto` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_cliente`, `id_documento`, `id_tipo_pago`, `venta_fecha`, `venta_monto`) VALUES
(22, 3, 14, 3, '2023-09-03 16:48:28', '9.00'),
(23, 1, 15, 1, '2023-09-03 17:14:24', '0.50'),
(24, 1, 16, 1, '2023-09-04 01:18:18', '4.00'),
(25, 6, 17, 2, '2023-09-04 02:01:46', '224.00'),
(26, 6, 18, 1, '2023-09-04 02:06:25', '27.00'),
(27, 6, 19, 1, '2023-09-11 20:11:44', '9.00'),
(28, 6, 20, 1, '2023-09-11 20:12:31', '15.00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`id_caja`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `detalle_formato_ingreso`
--
ALTER TABLE `detalle_formato_ingreso`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_formato` (`id_formato`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_medida` (`id_medida`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id_detalle_venta`),
  ADD KEY `id_venta` (`id_venta`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `id_tipo_documento` (`id_tipo_documento`);

--
-- Indices de la tabla `formato_ingreso`
--
ALTER TABLE `formato_ingreso`
  ADD PRIMARY KEY (`id_formato`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `medida`
--
ALTER TABLE `medida`
  ADD PRIMARY KEY (`id_medida`);

--
-- Indices de la tabla `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indices de la tabla `opciones`
--
ALTER TABLE `opciones`
  ADD PRIMARY KEY (`id_opcion`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permiso`),
  ADD KEY `id_opcion` (`id_opcion`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id_persona`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_medida` (`id_medida`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `restricciones`
--
ALTER TABLE `restricciones`
  ADD PRIMARY KEY (`id_restriccion`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_opcion` (`id_opcion`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `roles_menus`
--
ALTER TABLE `roles_menus`
  ADD PRIMARY KEY (`id_rol_menu`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`id_tipo_documento`);

--
-- Indices de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  ADD PRIMARY KEY (`id_tipo_pago`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_persona` (`id_persona`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_cliente` (`id_cliente`,`id_documento`,`id_tipo_pago`),
  ADD KEY `id_tipo_pago` (`id_tipo_pago`),
  ADD KEY `id_documento` (`id_documento`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `detalle_formato_ingreso`
--
ALTER TABLE `detalle_formato_ingreso`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id_detalle_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `documento`
--
ALTER TABLE `documento`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `formato_ingreso`
--
ALTER TABLE `formato_ingreso`
  MODIFY `id_formato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `medida`
--
ALTER TABLE `medida`
  MODIFY `id_medida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `opciones`
--
ALTER TABLE `opciones`
  MODIFY `id_opcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `restricciones`
--
ALTER TABLE `restricciones`
  MODIFY `id_restriccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `roles_menus`
--
ALTER TABLE `roles_menus`
  MODIFY `id_rol_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `id_tipo_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  MODIFY `id_tipo_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_formato_ingreso`
--
ALTER TABLE `detalle_formato_ingreso`
  ADD CONSTRAINT `detalle_formato_ingreso_ibfk_1` FOREIGN KEY (`id_formato`) REFERENCES `formato_ingreso` (`id_formato`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_formato_ingreso_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_formato_ingreso_ibfk_3` FOREIGN KEY (`id_medida`) REFERENCES `medida` (`id_medida`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `documento`
--
ALTER TABLE `documento`
  ADD CONSTRAINT `documento_ibfk_1` FOREIGN KEY (`id_tipo_documento`) REFERENCES `tipo_documento` (`id_tipo_documento`);

--
-- Filtros para la tabla `formato_ingreso`
--
ALTER TABLE `formato_ingreso`
  ADD CONSTRAINT `formato_ingreso_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedor` (`id_proveedor`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD CONSTRAINT `permisos_ibfk_1` FOREIGN KEY (`id_opcion`) REFERENCES `opciones` (`id_opcion`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_medida`) REFERENCES `medida` (`id_medida`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_tipo_pago`) REFERENCES `tipo_pago` (`id_tipo_pago`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`id_documento`) REFERENCES `documento` (`id_documento`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
