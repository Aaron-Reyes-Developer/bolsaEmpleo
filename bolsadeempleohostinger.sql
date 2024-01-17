-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-01-2024 a las 22:06:25
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bolsadeempleohostinger`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `adminBuscarDatosEmpresa` (`estado_cuenta` INT, `dato` VARCHAR(100))   BEGIN
	SELECT usuEm.id_usuario_empresa, usuEm.correo, usuEm.fecha_creacion, datosEm.nombreUsuario, datosEm.id_datos_empresa FROM usuario_empresa usuEm
	LEFT JOIN datos_empresa datosEm
	ON usuEm.id_usuario_empresa = datosEm.fk_id_usuario_empresa
	WHERE usuEm.estado_cuenta = estado_cuenta
	AND (datosEm.nombreUsuario LIKE concat(dato,'%') OR usuEm.correo = dato);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `adminConsultaAspirante` (`cedulaORnombre` VARCHAR(100))   BEGIN
	SELECT usuEs.id_usuEstudiantes , datos.cedula, datos.nombre, datos.apellido FROM usuario_estudiantes as usuEs 
	LEFT JOIN datos_estudiantes as datos
	ON usuEs.id_usuEstudiantes = datos.fk_id_usuEstudiantes
	WHERE datos.cedula = cedulaORnombre OR datos.apellido LIKE CONCAT(cedulaORnombre,'%')
    AND usuEs.estado_cuenta = 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `adminDatosEmpresa` (`estado_cuenta` INT, `desde` INT, `limite` INT)   BEGIN
	SELECT usuEm.id_usuario_empresa, usuEm.correo, usuEm.fecha_creacion, datosEm.nombreUsuario, datosEm.id_datos_empresa FROM usuario_empresa usuEm
	LEFT JOIN datos_empresa datosEm
	ON usuEm.id_usuario_empresa = datosEm.fk_id_usuario_empresa
	WHERE usuEm.estado_cuenta = estado_cuenta
	LIMIT desde,limite;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `aspiranteAprobado` (`aprobado` INT, `id_aspirante` INT, `id_oferta_trabajo` INT, `estado_cuenta` INT)   BEGIN
SELECT  post.id_postula FROM postula post
LEFT JOIN usuario_estudiantes usuEs
ON usuEs.id_usuEstudiantes = post.fk_id_usuEstudiantes
WHERE post.aprobado = aprobado 
AND usuEs.id_usuEstudiantes = id_aspirante
AND post.fk_id_oferta_trabajo = id_oferta_trabajo
AND usuEs.estado_cuenta = estado_cuenta;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `BuscarInicioAspiranteConLimite` (IN `carrera` VARCHAR(100) CHARSET utf8mb4, IN `buscar` VARCHAR(100) CHARSET utf8mb4, IN `filtrarEstado` VARCHAR(100) CHARSET utf8mb4, IN `filtrarEmpresa` VARCHAR(100) CHARSET utf8mb4, IN `estado` INT, IN `desde` INT, IN `limite` INT)   BEGIN
	SELECT * FROM oferta_trabajo  as oft 
	LEFT JOIN usuario_empresa as usuEm 
	ON usuEm.id_usuario_empresa = oft.fk_id_usuario_empresa 
	LEFT JOIN datos_empresa as dt 
	ON usuEm.id_usuario_empresa = dt.fk_id_usuario_empresa 
	WHERE	oft.categoria_carrera = carrera
	AND 
    #cuando solo se busca por oferta
    if( buscar != '' &&  filtrarEstado = '' && filtrarEmpresa = '', oft.puesto LIKE concat('%', buscar,'%'), 
		
        #cuando solo se busca por estado de empleo
        if(filtrarEstado != '' &&  buscar = '' && filtrarEmpresa = '', oft.tipo_empleo LIKE concat('%', filtrarEstado, '%' ), 
			
            #cuando solo se busca por empresa
            if(filtrarEmpresa != '' &&  buscar = '' && filtrarEstado = '', dt.nombre LIKE concat('%', filtrarEmpresa, '%' ),
            
				#cuando se busca por oferta y se filtra por estado
                if(buscar != '' &&  filtrarEstado != '' && filtrarEmpresa = '', oft.puesto LIKE concat('%', buscar, '%' ) AND oft.tipo_empleo LIKE concat('%', filtrarEstado, '%' ) , 
					
                    #cuando se busca por oferta y se filtra por empresa
                    if(buscar != '' && filtrarEmpresa != '' && filtrarEstado = '', oft.puesto LIKE concat('%', buscar, '%' ) AND dt.nombre LIKE concat('%', filtrarEmpresa, '%' ) ,
                    
						#cuando se busca por filtrado por estado y filtrado de empres
                        if( filtrarEstado != '' && filtrarEmpresa != '' && buscar = '', dt.nombre LIKE concat('%', filtrarEmpresa, '%' ) AND oft.tipo_empleo LIKE concat('%', filtrarEstado, '%' ) ,
                        
							#cuando se busca por oferta y se filtra por estado y se filtra por empresa
                            if(filtrarEstado != '' && filtrarEmpresa != '' && buscar != '', oft.puesto LIKE concat('%', buscar, '%' ) AND dt.nombre LIKE concat('%', filtrarEmpresa, '%' ) AND oft.tipo_empleo LIKE concat('%', filtrarEstado, '%' ),'')
                        )
                    )
                    
                )
            )
        ) 
	)
    AND estado_oferta = estado
	ORDER BY id_oferta_trabajo DESC 
	LIMIT desde, limite;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `BuscarInicioAspiranteSinLimite` (`carrera` VARCHAR(100), `buscar` VARCHAR(100), `filtrarEstado` VARCHAR(100), `filtrarEmpresa` VARCHAR(100), `estado` INT)   BEGIN
SELECT COUNT(*) totalOferta FROM oferta_trabajo  as oft 
	LEFT JOIN usuario_empresa as usuEm 
	ON usuEm.id_usuario_empresa = oft.fk_id_usuario_empresa 
	LEFT JOIN datos_empresa as dt 
	ON usuEm.id_usuario_empresa = dt.fk_id_usuario_empresa 
	WHERE	oft.categoria_carrera = carrera
	AND 
    #cuando solo se busca por oferta
    if( buscar != '' &&  filtrarEstado = '' && filtrarEmpresa = '', oft.puesto LIKE concat('%', buscar,'%'), 
		
        #cuando solo se busca por estado de empleo
        if(filtrarEstado != '' &&  buscar = '' && filtrarEmpresa = '', oft.tipo_empleo LIKE concat('%', filtrarEstado, '%' ), 
			
            #cuando solo se busca por empresa
            if(filtrarEmpresa != '' &&  buscar = '' && filtrarEstado = '', dt.nombre LIKE concat('%', filtrarEmpresa, '%' ),
            
				#cuando se busca por oferta y se filtra por estado
                if(buscar != '' &&  filtrarEstado != '' && filtrarEmpresa = '', oft.puesto LIKE concat('%', buscar, '%' ) AND oft.tipo_empleo LIKE concat('%', filtrarEstado, '%' ) , 
					
                    #cuando se busca por oferta y se filtra por empresa
                    if(buscar != '' && filtrarEmpresa != '' && filtrarEstado = '', oft.puesto LIKE concat('%', buscar, '%' ) AND dt.nombre LIKE concat('%', filtrarEmpresa, '%' ) ,
                    
						#cuando se busca por filtrado por estado y filtrado de empres
                        if( filtrarEstado != '' && filtrarEmpresa != '' && buscar = '', dt.nombre LIKE concat('%', filtrarEmpresa, '%' ) AND oft.tipo_empleo LIKE concat('%', filtrarEstado, '%' ) ,
                        
							#cuando se busca por oferta y se filtra por estado y se filtra por empresa
                            if(filtrarEstado != '' && filtrarEmpresa != '' && buscar != '', oft.puesto LIKE concat('%', buscar, '%' ) AND dt.nombre LIKE concat('%', filtrarEmpresa, '%' ) AND oft.tipo_empleo LIKE concat('%', filtrarEstado, '%' ),'')
                        )
                    )
                    
                )
            )
        ) 
	)
    AND estado_oferta = estado
	ORDER BY id_oferta_trabajo DESC;
	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `BuscarInicioEmpresaConLimite` (`apellido` VARCHAR(50), `carrera` VARCHAR(100), `estado_trabajo` VARCHAR(50), `especialidad` VARCHAR(50), `estado_cuenta` INT, `desde` INT, `limite` INT)   BEGIN
	SELECT 	usu.id_usuEstudiantes,
			concat(dat.nombre , ' ', dat.apellido) nombres, 
			cv.detalle_curriculum,
			dat.carrera_graduada,
            dat.imagen_perfil,
			cv.especializacion_curriculum
	FROM usuario_estudiantes as usu 
	LEFT JOIN datos_estudiantes as dat 
    ON usu.id_usuEstudiantes = dat.fk_id_usuEstudiantes 
    LEFT JOIN curriculum as cv 
    ON usu.id_usuEstudiantes = cv.fk_id_usuEstudiantes 
    LEFT JOIN conocimientos as cono 
    ON cv.id_curriculum = cono.fk_id_curriculum 
    LEFT JOIN experiencia as xp 
    ON cv.id_curriculum = xp.fk_id_curriculum 
    LEFT JOIN educacion as edu 
    ON cv.id_curriculum = edu.fk_id_curriculum 
    WHERE 
    
    CASE
		#buscar solo apellido
		WHEN apellido != '' && carrera = '' && estado_trabajo = '' && especialidad = '' THEN dat.apellido LIKE concat('%',apellido ,'%')
		
        #buscar solo por carrera
		WHEN carrera != '' && apellido = '' && estado_trabajo = '' && especialidad = '' THEN dat.carrera_graduada LIKE concat('%',carrera ,'%')
		
		#buscar solo por estado de trabajo (pasante, etc...)
		WHEN estado_trabajo != '' && apellido = '' &&  carrera = '' && especialidad = '' THEN cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%')
    
		#buscar solo por especialidad
		WHEN especialidad != '' && apellido = '' &&  carrera = '' && estado_trabajo = '' THEN cv.especializacion_curriculum LIKE concat('%',especialidad ,'%')
    
		#buscar solo por apellido y carrera
		WHEN apellido != '' &&  carrera != '' && especialidad = '' &&  estado_trabajo = '' THEN dat.apellido LIKE concat('%',apellido ,'%') AND dat.carrera_graduada LIKE concat('%',carrera ,'%')
    
		#buscar solo por apellido y estado de trabajo
        WHEN apellido != '' &&  estado_trabajo != '' && especialidad = '' &&  carrera = '' THEN dat.apellido LIKE concat('%',apellido ,'%') AND cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%')
    
		#buscar solo por apellido y especialidad
        WHEN apellido != '' &&  especialidad != '' && estado_trabajo = '' &&  carrera = '' THEN dat.apellido LIKE concat('%',apellido ,'%') AND cv.especializacion_curriculum LIKE concat('%',especialidad ,'%')
    
		#buscar carrera y estado trabajo
        WHEN carrera  != '' &&  estado_trabajo != '' && especialidad = '' &&  apellido = '' THEN dat.carrera_graduada LIKE concat('%',carrera ,'%') AND cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%')
    
		#buscar carrera y esprecialidad
        WHEN carrera  != '' &&  especialidad != '' && estado_trabajo = '' &&  apellido = '' THEN dat.carrera_graduada LIKE concat('%',carrera ,'%') AND cv.especializacion_curriculum LIKE concat('%',especialidad ,'%')
    
		#buscar estado y esprecialidad
        WHEN estado_trabajo  != '' &&  especialidad != '' && carrera = '' &&  apellido = '' THEN cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%') AND cv.especializacion_curriculum LIKE concat('%',especialidad ,'%')
    
		#buscar (tres campos) apelldio y carrera y estado
        WHEN apellido != '' &&  carrera != '' &&  estado_trabajo != '' &&  especialidad = '' THEN dat.apellido LIKE concat('%',apellido ,'%') AND dat.carrera_graduada LIKE concat('%',carrera ,'%') AND cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%')
    
		#buscar (tres campos) apelldio y estado y especialidad
        WHEN apellido != '' &&  estado_trabajo != '' &&  especialidad != '' &&  carrera = '' THEN dat.apellido LIKE concat('%',apellido ,'%') AND cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%') AND cv.especializacion_curriculum LIKE concat('%',especialidad ,'%')
		
        #buscar (tres campos) carrera, estado y especialidad
        WHEN carrera  != '' &&  estado_trabajo != '' &&  especialidad != '' &&  apellido = '' THEN dat.carrera_graduada LIKE concat('%',carrera ,'%') AND cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%') AND cv.especializacion_curriculum LIKE concat('%',especialidad ,'%')
    
		#buscar (tres campos) apellido, carrera y especialidad
        WHEN apellido   != '' &&  carrera != '' &&  especialidad != '' && estado_trabajo = '' THEN dat.apellido LIKE concat('%',apellido ,'%') AND dat.carrera_graduada LIKE concat('%',carrera ,'%') AND cv.especializacion_curriculum LIKE concat('%',especialidad ,'%')
    
		#buscar todo
        WHEN apellido   != '' &&  carrera != '' &&  especialidad != '' && estado_trabajo != '' THEN dat.apellido LIKE concat('%',apellido ,'%') AND dat.carrera_graduada LIKE concat('%',carrera ,'%') AND cv.especializacion_curriculum LIKE concat('%',especialidad ,'%') AND cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%')
    
    END
    
    AND usu.estado_cuenta = estado_cuenta
    group by usu.id_usuEstudiantes 
    ORDER BY usu.fecha_creacion DESC
    LIMIT desde, limite;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `BuscarInicioEmpresaConLimiteDOS` (IN `apellido` VARCHAR(50), IN `carrera` VARCHAR(100), IN `estado_trabajo` VARCHAR(50), IN `especialidad` VARCHAR(50), IN `estado_cuenta` INT, IN `desde` INT, IN `limite` INT)   BEGIN
    SELECT 
        usu.id_usuEstudiantes,
        CONCAT(dat.nombre, ' ', dat.apellido) AS nombres, 
        cv.detalle_curriculum,
        dat.carrera_graduada,
        dat.imagen_perfil,
        cv.especializacion_curriculum
    FROM usuario_estudiantes AS usu 
    LEFT JOIN datos_estudiantes AS dat ON usu.id_usuEstudiantes = dat.fk_id_usuEstudiantes 
    LEFT JOIN curriculum AS cv ON usu.id_usuEstudiantes = cv.fk_id_usuEstudiantes 
    LEFT JOIN conocimientos AS cono ON cv.id_curriculum = cono.fk_id_curriculum 
    LEFT JOIN experiencia AS xp ON cv.id_curriculum = xp.fk_id_curriculum 
    LEFT JOIN educacion AS edu ON cv.id_curriculum = edu.fk_id_curriculum 
    WHERE usu.estado_cuenta = estado_cuenta
    AND (
        (apellido != '' AND dat.apellido LIKE CONCAT('%', apellido, '%')) OR
        (carrera != '' AND dat.carrera_graduada LIKE CONCAT('%', carrera, '%')) OR
        (estado_trabajo != '' AND cv.estado_trabajo LIKE CONCAT('%', estado_trabajo, '%')) OR
        (especialidad != '' AND cv.especializacion_curriculum LIKE CONCAT('%', especialidad, '%'))
    )
    GROUP BY usu.id_usuEstudiantes 
    ORDER BY usu.fecha_creacion DESC
    LIMIT desde, limite;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `BuscarInicioEmpresaSinLimite` (`apellido` VARCHAR(50), `carrera` VARCHAR(100), `estado_trabajo` VARCHAR(50), `especialidad` VARCHAR(50), `estado_cuenta` INT)   BEGIN
	SELECT 	
		#usa el id para determina la cantidad de filas que devuelve
		usu.id_usuEstudiantes
			
	FROM usuario_estudiantes as usu 
	LEFT JOIN datos_estudiantes as dat 
    ON usu.id_usuEstudiantes = dat.fk_id_usuEstudiantes 
    LEFT JOIN curriculum as cv 
    ON usu.id_usuEstudiantes = cv.fk_id_usuEstudiantes 
    LEFT JOIN conocimientos as cono 
    ON cv.id_curriculum = cono.fk_id_curriculum 
    LEFT JOIN experiencia as xp 
    ON cv.id_curriculum = xp.fk_id_curriculum 
    LEFT JOIN educacion as edu 
    ON cv.id_curriculum = edu.fk_id_curriculum 
    WHERE 
    
    CASE
		#buscar solo apellido
		WHEN apellido != '' && carrera = '' && estado_trabajo = '' && especialidad = '' THEN dat.apellido LIKE concat('%',apellido ,'%')
		
        #buscar solo por carrera
		WHEN carrera != '' && apellido = '' && estado_trabajo = '' && especialidad = '' THEN dat.carrera_graduada LIKE concat('%',carrera ,'%')
		
		#buscar solo por estado de trabajo (pasante, etc...)
		WHEN estado_trabajo != '' && apellido = '' &&  carrera = '' && especialidad = '' THEN cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%')
    
		#buscar solo por especialidad
		WHEN especialidad != '' && apellido = '' &&  carrera = '' && estado_trabajo = '' THEN cv.especializacion_curriculum LIKE concat('%',especialidad ,'%')
    
		#buscar solo por apellido y carrera
		WHEN apellido != '' &&  carrera != '' && especialidad = '' &&  estado_trabajo = '' THEN dat.apellido LIKE concat('%',apellido ,'%') AND dat.carrera_graduada LIKE concat('%',carrera ,'%')
    
		#buscar solo por apellido y estado de trabajo
        WHEN apellido != '' &&  estado_trabajo != '' && especialidad = '' &&  carrera = '' THEN dat.apellido LIKE concat('%',apellido ,'%') AND cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%')
    
		#buscar solo por apellido y especialidad
        WHEN apellido != '' &&  especialidad != '' && estado_trabajo = '' &&  carrera = '' THEN dat.apellido LIKE concat('%',apellido ,'%') AND cv.especializacion_curriculum LIKE concat('%',especialidad ,'%')
    
		#buscar carrera y estado trabajo
        WHEN carrera  != '' &&  estado_trabajo != '' && especialidad = '' &&  apellido = '' THEN dat.carrera_graduada LIKE concat('%',carrera ,'%') AND cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%')
    
		#buscar carrera y esprecialidad
        WHEN carrera  != '' &&  especialidad != '' && estado_trabajo = '' &&  apellido = '' THEN dat.carrera_graduada LIKE concat('%',carrera ,'%') AND cv.especializacion_curriculum LIKE concat('%',especialidad ,'%')
    
		#buscar estado y esprecialidad
        WHEN estado_trabajo  != '' &&  especialidad != '' && carrera = '' &&  apellido = '' THEN cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%') AND cv.especializacion_curriculum LIKE concat('%',especialidad ,'%')
    
		#buscar (tres campos) apelldio y carrera y estado
        WHEN apellido != '' &&  carrera != '' &&  estado_trabajo != '' &&  especialidad = '' THEN dat.apellido LIKE concat('%',apellido ,'%') AND dat.carrera_graduada LIKE concat('%',carrera ,'%') AND cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%')
    
		#buscar (tres campos) apelldio y estado y especialidad
        WHEN apellido != '' &&  estado_trabajo != '' &&  especialidad != '' &&  carrera = '' THEN dat.apellido LIKE concat('%',apellido ,'%') AND cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%') AND cv.especializacion_curriculum LIKE concat('%',especialidad ,'%')
		
        #buscar (tres campos) carrera, estado y especialidad
        WHEN carrera  != '' &&  estado_trabajo != '' &&  especialidad != '' &&  apellido = '' THEN dat.carrera_graduada LIKE concat('%',carrera ,'%') AND cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%') AND cv.especializacion_curriculum LIKE concat('%',especialidad ,'%')
    
		#buscar (tres campos) apellido, carrera y especialidad
        WHEN apellido   != '' &&  carrera != '' &&  especialidad != '' && estado_trabajo = '' THEN dat.apellido LIKE concat('%',apellido ,'%') AND dat.carrera_graduada LIKE concat('%',carrera ,'%') AND cv.especializacion_curriculum LIKE concat('%',especialidad ,'%')
    
		#buscar todo
        WHEN apellido   != '' &&  carrera != '' &&  especialidad != '' && estado_trabajo != '' THEN dat.apellido LIKE concat('%',apellido ,'%') AND dat.carrera_graduada LIKE concat('%',carrera ,'%') AND cv.especializacion_curriculum LIKE concat('%',especialidad ,'%') AND cv.estado_trabajo LIKE concat('%',estado_trabajo ,'%')
    
    END
    
    AND usu.estado_cuenta = estado_cuenta
    group by usu.id_usuEstudiantes 
    ORDER BY usu.fecha_creacion DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaCarreras` (`categiria` VARCHAR(100))   BEGIN
	SELECT * FROM carreras 	WHERE categoria = categiria;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaCuantosAspirantesEstanEnUnaOferta` (IN `id_usuario_empresa` INT(1), IN `desde` INT(0), IN `limite` INT(10))   BEGIN
	SELECT * FROM oferta_trabajo as ofert
	INNER JOIN postula as post 
	ON ofert.id_oferta_trabajo = post.fk_id_oferta_trabajo
	WHERE ofert.fk_id_usuario_empresa = id_usuario_empresa
    AND ofert.estado_oferta = 1
	GROUP BY ofert.id_oferta_trabajo
    ORDER BY ofert.fecha_oferta DESC
    LIMIT desde, limite;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `consultaOfertaEmpleoEstudiante` (`id` INT)   BEGIN
	SELECT 
	usuEm.id_usuario_empresa,
    datosEm.nombre as nombre_empresa,
	ofert.id_oferta_trabajo,
	ofert.puesto,
	ofert.ubicacion_empleo,
	tip_hor.nombre as horario,
	tip_ofer.nombre as tipo_oferta,
	tip_lug.nombre as tipo_lugar,
	ofert.precio
	FROM usuario_empresa as usuEm

	INNER JOIN datos_empresa as datosEm
	ON usuEm.id_usuario_empresa = datosEm.fk_id_usuario_empresa

	INNER JOIN oferta_trabajo as ofert
	ON usuEm.id_usuario_empresa = ofert.fk_id_usuario_empresa

	INNER JOIN tipo_horario_oferta tip_hor
	ON tip_hor.id_tipo_horario_oferta = ofert.fk_id_horario

	INNER JOIN tipos_oferta tip_ofer
	ON tip_ofer.id_tipo_oferta = ofert.fk_id_tipo_oferta

	INNER JOIN tipo_lugar_oferta tip_lug
	ON tip_lug.id_tipo_lugar_oferta = ofert.fk_id_tipo_lugar_oferta

	INNER JOIN postula as post
	ON ofert.id_oferta_trabajo = post.fk_id_oferta_trabajo

	WHERE post.fk_id_usuEstudiantes = id
    AND post.aprobado = 0
    ORDER BY post.fecha_aprobado DESC LIMIT 5;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `datosMainEstudiante` (`id_usuario` INT)   BEGIN
	SELECT * FROM usuario_estudiantes as usuEs
	LEFT JOIN datos_estudiantes as dat
	ON usuEs.id_usuEstudiantes = dat.fk_id_usuEstudiantes 
    INNER JOIN carreras car
	ON car.id_carrera = dat.fk_id_carrera
	LEFT JOIN curriculum as cv
	ON usuEs.id_usuEstudiantes = cv.fk_id_usuEstudiantes
	LEFT JOIN experiencia as xp
	ON cv.id_curriculum = xp.fk_id_curriculum
	LEFT JOIN educacion as edu
	ON cv.id_curriculum = edu.fk_id_curriculum
	LEFT JOIN conocimientos as cono
	ON cv.id_curriculum = cono.fk_id_curriculum
    LEFT JOIN idioma as idi
    ON cv.id_curriculum = idi.fk_id_curriculum
	WHERE usuEs.id_usuEstudiantes = id_usuario
	group by cono.fk_id_curriculum;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `detalleOferta` (`id_empresa` INT, `id_oferta` INT)   BEGIN
	SELECT 
	oft.puesto,
	oft.precio,
	oft.ubicacion_empleo,
	oft.tareas_realizar,
	oft.detalle,
	oft.fecha_oferta,
	oft.estado_oferta,
    hor.id_tipo_horario_oferta,
	hor.nombre as hora,
    tip_ofert.id_tipo_oferta,
	tip_ofert.nombre as tipo_oferta,
    tip_lu_oft.id_tipo_lugar_oferta,
	tip_lu_oft.nombre as tipo_lugar,
	car.id_carrera,
	car.nombre_carrera as nombre_carrera
	FROM oferta_trabajo  oft
	INNER JOIN tipo_horario_oferta hor
	ON hor.id_tipo_horario_oferta = oft.fk_id_horario
	INNER JOIN tipos_oferta tip_ofert
	ON tip_ofert.id_tipo_oferta = oft.fk_id_tipo_oferta
	INNER JOIN tipo_lugar_oferta tip_lu_oft
	ON tip_lu_oft.id_tipo_lugar_oferta = oft.fk_id_tipo_lugar_oferta
	INNER JOIN carreras car
	ON car.id_carrera = oft.fk_id_carrera 
	WHERE oft.id_oferta_trabajo = id_oferta
	AND oft.fk_id_usuario_empresa = id_empresa;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `mostrarTodosLosAspirantesDentroOferta` (`id_oferta` INT, `desde` INT, `limite` INT)   BEGIN
	#mostrar cuantos aspirantes estan en una postulacion (cuando se entra a ver el detalle de la postulacion en el incio de la empresa)
	SELECT 
	usuEstu.id_usuEstudiantes,
	concat(datEs.nombre, ' ' ,datEs.apellido) as nombre,
	datEs.imagen_perfil,
	car.nombre_carrera,
	curr.especializacion_curriculum

	FROM usuario_estudiantes as usuEstu 

	INNER JOIN datos_estudiantes datEs
	ON usuEstu.id_usuEstudiantes = datEs.fk_id_usuEstudiantes

	INNER JOIN carreras car
	ON car.id_carrera = datEs.fk_id_carrera

	INNER JOIN curriculum as curr
	ON usuEstu.id_usuEstudiantes = curr.fk_id_usuEstudiantes

	LEFT JOIN postula as post 
	ON usuEstu.id_usuEstudiantes = post.fk_id_usuEstudiantes

	LEFT JOIN oferta_trabajo as ofert
	ON ofert.id_oferta_trabajo = post.fk_id_oferta_trabajo
	WHERE post.fk_id_oferta_trabajo = id_oferta
    AND usuEstu.estado_cuenta = 1
	GROUP BY usuEstu.id_usuEstudiantes
	ORDER BY post.fecha_postulacion DESC
    LIMIT desde, limite;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `notificacion` (`id_aspirante` INT)   BEGIN
	SELECT 
	usuEm.id_usuario_empresa,
	oft.id_oferta_trabajo,
	postu.id_postula, 
	postu.estado_noti,  
	oft.puesto, 
	postu.fk_id_oferta_trabajo 
	FROM postula as postu

	#oferta
	LEFT JOIN oferta_trabajo as oft
	ON oft.id_oferta_trabajo = postu.fk_id_oferta_trabajo

	#usuario empresa
	INNER JOIN usuario_empresa usuEm
	ON usuEm.id_usuario_empresa = oft.fk_id_usuario_empresa
	WHERE estado_noti = 1 
	AND fk_id_usuEstudiantes = id_aspirante;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `notificacionEmpresa` (`id_empresa` INT)   BEGIN
	SELECT  ofet.id_oferta_trabajo, ofet.puesto , post.fk_id_oferta_trabajo FROM usuario_empresa as usuEm
	LEFT JOIN oferta_trabajo ofet
	ON usuEm.id_usuario_empresa = ofet.fk_id_usuario_empresa
	LEFT JOIN postula post
	ON ofet.id_oferta_trabajo = post.fk_id_oferta_trabajo
    WHERE post.estado_noti_empresa = 1 AND usuEm.id_usuario_empresa = id_empresa
    GROUP BY ofet.puesto;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `notificacionEmpresaPaginacion` (`id_empresa` INT, `desde` INT, `limite` INT)   BEGIN
	SELECT  ofet.id_oferta_trabajo, ofet.puesto ,ofet.fecha_oferta ,post.fk_id_oferta_trabajo FROM usuario_empresa as usuEm
	LEFT JOIN oferta_trabajo ofet
	ON usuEm.id_usuario_empresa = ofet.fk_id_usuario_empresa
	LEFT JOIN postula post
	ON ofet.id_oferta_trabajo = post.fk_id_oferta_trabajo
	WHERE usuEm.id_usuario_empresa = id_empresa
    GROUP BY ofet.puesto
    ORDER BY ofet.fecha_oferta DESC
    LIMIT desde, limite;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ofertasAprobadasDeEmpresas` (`id_empresa` INT, `desde` INT, `limite` INT)   BEGIN
	SELECT post.id_postula, oft.id_oferta_trabajo, oft.puesto, oft.detalle FROM oferta_trabajo oft
	LEFT JOIN usuario_empresa as usuEm 
	ON usuEm.id_usuario_empresa = oft.fk_id_usuario_empresa
	LEFT JOIN postula post
	ON oft.id_oferta_trabajo = post.fk_id_oferta_trabajo
	WHERE usuEm.id_usuario_empresa =  id_empresa
	AND post.aprobado = 1
    ORDER BY post.fecha_aprobado DESC
    
    LIMIT desde, limite;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `perfilesAspirantesPorCarrera` (`carrera` VARCHAR(50))   BEGIN
	SELECT * FROM usuario_estudiantes as usu 
    LEFT JOIN datos_estudiantes as dat 
    ON usu.id_usuEstudiantes = dat.fk_id_usuEstudiantes 
    LEFT JOIN curriculum as cv 
    ON usu.id_usuEstudiantes = cv.fk_id_usuEstudiantes 
    LEFT JOIN conocimientos as cono 
    ON cv.id_curriculum = cono.fk_id_curriculum 
    LEFT JOIN experiencia as xp 
    ON cv.id_curriculum = xp.fk_id_curriculum 
    LEFT JOIN educacion as edu 
    ON cv.id_curriculum = edu.fk_id_curriculum 
    WHERE dat.carrera_graduada = carrera
    group by usu.id_usuEstudiantes ;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `postulacionAprobada` (`id_estudiante` INT)   BEGIN
	SELECT 
    ofert.id_oferta_trabajo,
	usuEm.id_usuario_empresa,
	datEm.nombreUsuario,
	ofert.puesto,
	ofert.ubicacion_empleo,
	tip_hora.nombre as horario,
	tip_oft.nombre as tipo_oferta,
	tip_lug.nombre as tipo_lugar
	FROM usuario_empresa as usuEm 

	#oferta de trabajo
	LEFT JOIN oferta_trabajo as ofert 
	ON usuEm.id_usuario_empresa = ofert.fk_id_usuario_empresa

	#horario
	INNER JOIN tipo_horario_oferta tip_hora
	ON tip_hora.id_tipo_horario_oferta = ofert.fk_id_horario

	#tipo empleo
	INNER JOIN tipos_oferta tip_oft
	ON tip_oft.id_tipo_oferta = ofert.fk_id_tipo_oferta

	#tipo lugar empleo
	INNER JOIN tipo_lugar_oferta tip_lug
	ON tip_lug.id_tipo_lugar_oferta = ofert.fk_id_tipo_lugar_oferta

	#datos empresa
	LEFT JOIN datos_empresa as datEm
	ON usuEm.id_usuario_empresa = datEm.fk_id_usuario_empresa

	LEFT JOIN postula as postu
	ON ofert.id_oferta_trabajo = postu.fk_id_oferta_trabajo
	WHERE postu.aprobado = 1 
    AND postu.fk_id_usuEstudiantes = id_estudiante
    ORDER BY postu.fecha_aprobado DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `todasLasNotificaciones` (`id_aspirante` INT, `desde` INT, `limite` INT)   BEGIN
	SELECT 
	usuEm.id_usuario_empresa, 
	oft.id_oferta_trabajo, 
	dtEm.imagen_perfil, 
	dtEm.nombre as nombre_empresa, 
	oft.puesto, 
	tip_oft.nombre as tipo_oferta, 
	oft.precio, 
	post.fecha_aprobado  

	FROM usuario_estudiantes usuEs

	#postula
	INNER JOIN postula post
	ON usuEs.id_usuEstudiantes = post.fk_id_usuEstudiantes

	#oferta
	INNER JOIN oferta_trabajo oft
	ON oft.id_oferta_trabajo = post.fk_id_oferta_trabajo

	#tipo empleo
	INNER JOIN tipos_oferta tip_oft
	ON tip_oft.id_tipo_oferta = oft.fk_id_tipo_oferta

	#usuario empresa
	INNER JOIN 	usuario_empresa usuEm
	ON usuEm.id_usuario_empresa = oft.fk_id_usuario_empresa

	#datos empresa
	INNER JOIN datos_empresa dtEm
	ON usuEm.id_usuario_empresa = dtEm.fk_id_usuario_empresa
    
	WHERE usuEs.id_usuEstudiantes = id_aspirante
	AND post.aprobado = 1
    LIMIT desde, limite;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `todasLasNotificacionesSinLimite` (`id_aspirante` INT)   BEGIN
SELECT usuEs.id_usuEstudiantes FROM usuario_estudiantes usuEs
	INNER JOIN postula post
	ON usuEs.id_usuEstudiantes = post.fk_id_usuEstudiantes
	INNER JOIN oferta_trabajo oft
	ON oft.id_oferta_trabajo = post.fk_id_oferta_trabajo
	INNER JOIN 	usuario_empresa usuEm
	ON usuEm.id_usuario_empresa = oft.fk_id_usuario_empresa
	INNER JOIN datos_empresa dtEm
	ON usuEm.id_usuario_empresa = dtEm.fk_id_usuario_empresa
	WHERE usuEs.id_usuEstudiantes = id_aspirante
	AND post.aprobado = 1;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adminunesum`
--

CREATE TABLE `adminunesum` (
  `id_adminUnesum` int(11) NOT NULL,
  `contra` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `adminunesum`
--

INSERT INTO `adminunesum` (`id_adminUnesum`, `contra`) VALUES
(1, '111');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `id_carrera` int(11) NOT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `nombre_carrera` varchar(100) DEFAULT NULL,
  `tituloGraduado` varchar(100) DEFAULT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `carreras`
--

INSERT INTO `carreras` (`id_carrera`, `categoria`, `nombre_carrera`, `tituloGraduado`, `estado`) VALUES
(1, 'Ciencias Técnicas', 'Tecnología de la información y comunicación', 'Ing. Tecnología de la información y comunicación', 1),
(2, 'Ciencias en la Salud', 'Medicina', 'Lic. medico', 1),
(3, 'Ciencias Economicas', 'Contavilidad', 'Lic. Contavilidad', 1),
(4, 'Ciencias Naturales y de la Agricultura', 'Agropecuaria', 'Ing. Agropecuario', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cedula`
--

CREATE TABLE `cedula` (
  `id_cedula` int(11) NOT NULL,
  `cedula` varchar(50) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `fk_id_usuEstudiantes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cedula`
--

INSERT INTO `cedula` (`id_cedula`, `cedula`, `nombre`, `apellido`, `fk_id_usuEstudiantes`) VALUES
(13, '1314025733', 'Aaron Josue', 'Reyes Carvajal ', 36),
(14, '1307509040', 'Lucila Esperanza ', 'Carvajal Ponce', NULL),
(15, '1314025741', 'Carlos Antonio ', 'Reyes Carvajal ', 38),
(16, '1308120508', 'José Enrique ', 'Reyes Lopez', NULL),
(17, '1308202496', 'Gloria Maricela', 'Carvajal Ponce', NULL),
(18, '1317889341', 'Amy Geraldine', 'Reyes Carvajal ', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigo_empresa`
--

CREATE TABLE `codigo_empresa` (
  `id_codigo_empresa` int(11) NOT NULL,
  `codigo_empresa` varchar(50) NOT NULL,
  `nombre_empresa` varchar(100) NOT NULL,
  `ruc` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `codigo_empresa`
--

INSERT INTO `codigo_empresa` (`id_codigo_empresa`, `codigo_empresa`, `nombre_empresa`, `ruc`) VALUES
(1, 'InnoVistaTech_Solutions_134FB', 'InnoVistaTech Solutions', '1234567890123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id_comentario` int(11) NOT NULL,
  `comentario` varchar(600) NOT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `fk_id_usuEstudiantes` int(11) DEFAULT NULL,
  `fk_id_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conocimientos`
--

CREATE TABLE `conocimientos` (
  `id_conocimientos` int(11) NOT NULL,
  `nombre_conocimiento` varchar(50) DEFAULT NULL,
  `fk_id_curriculum` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `conocimientos`
--

INSERT INTO `conocimientos` (`id_conocimientos`, `nombre_conocimiento`, `fk_id_curriculum`) VALUES
(7, 'PHP', 54),
(8, 'Mysql', 54),
(9, 'JavaScript', 54),
(10, 'Html', 54),
(12, 'Css', 54),
(13, 'Hadware pc', 55),
(14, 'Restauración de equipos de computo', 54);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curriculum`
--

CREATE TABLE `curriculum` (
  `id_curriculum` int(11) NOT NULL,
  `estado_trabajo` varchar(50) DEFAULT NULL,
  `detalle_curriculum` varchar(1000) DEFAULT NULL,
  `habilidades` varchar(1000) DEFAULT NULL,
  `especializacion_curriculum` varchar(100) DEFAULT NULL,
  `portafolio` varchar(500) DEFAULT NULL,
  `otrosLinks` varchar(500) DEFAULT NULL,
  `fk_id_usuEstudiantes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `curriculum`
--

INSERT INTO `curriculum` (`id_curriculum`, `estado_trabajo`, `detalle_curriculum`, `habilidades`, `especializacion_curriculum`, `portafolio`, `otrosLinks`, `fk_id_usuEstudiantes`) VALUES
(54, 'En busca de empleo', 'Soy una persona apasionada a la programación, con capacidad de resolver nuevos problemas, inteligente, amigable y con un alto grado de competencia, mi especialidad es la tecnología', 'Tengo un solido conocimiento en diseño web', 'Programador Web', 'https://tattoosmoking.000webhostapp.com', 'https://drive.google.com/file/d/1-w-ZkBYYK7DhQEea-xwO30aL7ZFdtVLM/view?usp=drivesdk', 36),
(55, 'En busca de empleo', 'Soy una persona con la capacidad de aprender nuevas tecnologías y poder resolver problemas nuevos, soy autodidacta, apasionado por la programación', 'Puedo resolver problemas nuevos, me gusta ser autodidacta.', 'Técnico en computadoras', 'https://www.youtube.com/watch?v=19q5HkCpBzA&list=RDUrRxJEU1Dt0&index=2', 'https://drive.google.com/file/d/1-w-ZkBYYK7DhQEea-xwO30aL7ZFdtVLM/view', 38);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_empresa`
--

CREATE TABLE `datos_empresa` (
  `id_datos_empresa` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `lugar` varchar(50) DEFAULT NULL,
  `imagen_perfil` longblob DEFAULT NULL,
  `nombreUsuario` varchar(50) DEFAULT NULL,
  `lugarMaps` varchar(500) DEFAULT NULL,
  `servicios_ofrecer` varchar(1000) DEFAULT NULL,
  `detalle_empresa` varchar(1000) DEFAULT NULL,
  `gerente_general` varchar(100) DEFAULT NULL,
  `recursos_humanos` varchar(100) DEFAULT NULL,
  `antiguedad_empresa` int(11) DEFAULT NULL,
  `pagina_web` varchar(300) DEFAULT NULL,
  `ofertas_aprobadas` int(11) DEFAULT NULL,
  `fk_id_usuario_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `datos_empresa`
--

INSERT INTO `datos_empresa` (`id_datos_empresa`, `nombre`, `correo`, `lugar`, `imagen_perfil`, `nombreUsuario`, `lugarMaps`, `servicios_ofrecer`, `detalle_empresa`, `gerente_general`, `recursos_humanos`, `antiguedad_empresa`, `pagina_web`, `ofertas_aprobadas`, `fk_id_usuario_empresa`) VALUES
(14, 'InnoVistaTech', 'InnoVistaTechContacto@gmail.com', 'Jipijapa Manabí Ecuador', 0x89504e470d0a1a0a0000000d4948445200000102000000c30802000000e6ee88a20000200049444154789ced9d7b781355fac7dfc94c2e9d366942635b68a146a040cbb5a8945d01a908aeb08af745f7e25a450145d70bb0fc58506461bd2cac08ab2208de16af086b11052c02ae2d5e5a28b450b04d5b52683069d34c3a49a699ccef8fb70c43d296b6b494c0f93c3c3ce9c999999399f39df39e73def31e2a180c02817079a3eaee021008dd0f9101814064402010191008406440200091018100440604021019100840644020009101810044060402101910084064402000910181004406040210191008406440200091018100440604021019100840644020009101810044060402101910084064402000910181004406040210191008406440200091018100440604021019100800c07477012e17288aeac0519224757a4908e11019742d72ed9724a9b6b6d6ed76f3bcd7eff7bbdc1c000402013927c330006034e8b55a2dcb46190c86e8e8689d4e271f7ec1cb7e1941914d9fba02b9f63b9dce9a1abbfd1787b2c6b71d866112ae30272626c4c5c5610ad143574064d0c9a000388eabaa3a5e7db226e45b7d346b301858364aadd64445e90040a3d10080200800e0f5fa1a1b059ef7badd6eae810f3936a967a2ac072286ce85c8a0d340017c69cd0f1e6f6084333d01730f93c964349bcd7abdbe5d27e438cee170d4d5b91cb5757222c330a9fdfa262727011143e74164d009c802f8cdf72b417025b3bdd6c5deab8f667bf7ee9d94d44bd93de8c069f1c0eaea13c78f1f979b082286ce85c8e0bcc09a7ad85931f19b2536fe04a8b4d0e85e356256f6809bb177db5975142fe4f3f9cacacaab4fd6300c13080418861936243d2e2e8e28e13c2132e83814454992f4f0372fbf69db85029837e09e3923a699747ae89a9774b36248ea99989636080bd3e957bc4c2032e82014451d7656a4ed9c0f012f04fd13e2866c18fb4c528c19da2300e564427b8ff2f97c8585fbd14c621826ebfab144061d86c8a0235014b5fac0a6470b5783da0041ffb6eb16de64c98476b60028a49ccaef00604acaaf06c55dd9dec301c066ab2e39520a0037de30be7dbf81a080c8a07d60e59bbeeb2534842698523fca5a60d2e93bd0fd3d232400ec51cc1a767b07ce838710a3e87c20326807a8811bbf98bbb3ee2804fdf3fa4d5d367a3a746808a8dae348fee41e884a3893eab5dbeefc3029c64c6af38587b8d6b5157cddf6fef4c19d7547a1d1bd3e63d6b2d1d32549ea58addd51b9afa91d90511b7654eeeb9cb212da09f1296a13d80e4cdc36cfe67342a37bdbb8bfdf64c924afed4b062283b672fb578bb01dc8cd7a717c9f9144039712c4283a371445fd356fcd678e2208fab78dfb3bd1c0a507690dce0145515f5af3fff1f366007831fdf7c416ba242132680d8aa2ea7cdc6fbe5d0c2aed6de6a1cf64dc2b6ba0c39e42848b102283737077ee1250698189fa74e2b39882434656ab95e7bdb1b1b1c9c9494409910e91418b5014b5a178eb4ee74110fd2537adc0da8fffefdabd173ddbaa4fd6d4d7d7a7a7a70169162219d2456e1eacee7f2e580d2aedbc01f7283d1df2f3f7c1e93593a0d3d71f2f0d94ee418574638109e703690d5a647efe9ba0d202c0d2cc873085a228abd5ca35f0a881a08ac9c87b2aaa66af1f0000747f3948270fbe68db04e26dd10aa4356806ec19e3e8d0fae10fcae690cfe73b5666450d0498a8f4fdcb635c7ba968c07f8daf0de9ee82b786cd56dddd45b8782132689e170b3762cff8fef4c9f24bb4b070bfdc0e24d9769bec9f06e93387883e106d872e42d388a2a89292c325474a4b4a0e5f84c5bb1820320845d9146cbb76b69ce8743acfac810cf07d0f3ea3d400a28aeb7d014bda0e62636301a0fa640de9c3340b914133fca77407f60a949365478f1e93cda101875e0f39442502337e0915157b11dadf922425272761e12b2a2ababb3817234406cdf0e8914f0060d5e03fe09fcaa620a862623c3643e5a7214d81143b543be5ff2e420dc858527a0380b5f2787717e46284c8e02c704518082e08faa7f61d2ba7cb4d4150a54e3db8828a3eeb28a901a21ed976818bda5e7af6ec0900814080e3386217854064104a4ee577a0d24e881b822b60708048d91444d5ec55e64773080cbd2ee6a64092249d4ea78f6601a0aa8a3408a110198432e7580e00dc9772a6293879f264d364194072c517b42ef410cde4f917aa74e74562620200d87f717477412e3a880cce806b23d122ba3165949caeb4a7e38faf55f60a5422a86e581d293353b25de4f3f9885da484c8e02c0efef233a8b4a0312a2d228cc21b5431718e4321f9451fa847dfd71d256d37681761b3e67038bbbb381717440667b1bba608001e8a1f21a7286b4cdca91f94995522d023a65f9c83a42d6134e801a0bebebebb0b7271416470163fd69501c0afe2d3e494fafa7a798cc8ecfc3264da981976c7052fe37961321901c0ed767777412e2e880cce0223afa4e813e514b9c63001af546b0bc94ff71b051185c1600080f0a8f1973944064da00f0504fd0090da23454e97874a4db54742c688a81ec991651101407474d39407e9252b213238434d435337a057741c0060ff58fe56239c6548a84450a5dc7c218b77fe4892a4d56af173636363f716e6a282c8e00c4d325069e50150655d510bae90fcaa5ec32e60e93a078aa2b0ab83fbeb1010228333f8c54674ae9653947545c7db43f253da980b54b22ec0ebf59d3bd3650391c1d904fdc90cdbec376aa12e24853224369b9310711019741031625fa6515a4d7717e1a283c8a0ad346a4cca3fc33d8b2205af9ff40a42213208c516687e4c3d486b43522477e87eaf11046e474b40880cce9082b6be624408372d4604ad31247fb02e223d963bb651f9a50d914133d4f9381c338d89393316e4536ec9010000d2c9820b5baef345e909ab543881c8e00c89d171f8419e479347d901c0a34f56660ed200159b2e64f13a058fc7831f626262226bfebb4b213268429224934e0f2a2da8b4950aa31f5d3255c100cf86b606a20fc07d22b25c12eaebdd00c0304c6415bbab2132388b09a654003858572ea7a04b260004556a8f718c3233ad83c6833b2f64f1ce9fc646014e6b9b20436470161313870140de2fa5728ac160c03ea52ad8589b3056259ec91ca421f0bf7f76ec4237a68c82c6b3bd9d1bddca256f5d445d9d0b14da262044066771b57900007c767a798d24493d7af490bf75c48f0cc91fb4177520529d24494931e655236681d70e012f04bce0b5af1a31eb026c83e9a8ad83d3eed6041912caf72c86c7a7a2aff561670546b1a628cadcc3e47273aa60c013931c6493c17f66d501ad83c66f5ea57fff467b2f2449d2ac61b767256774787bf0f682d196f0738f1e3d48ff5809690dce80bde409714340a5c5da89c4c7c7cb76d1f13e0f85d84562e19a8e759425491a1477e53319f73e93716f576b00a9a9b103803e9a25fde310880c42c1d02c18a605d08049ea859f55c14075efeb43f2d33af07df858c7ae2529e86879db018666c1302d04254406a1dc98320a827e105c879d15f8d6a4282aa96793336950c5d45c392bb44138b2297070fb057ec5526773cecc4ea713db340cd3425042647016d87945bb6845d1c7727adfbe579db68b02e5fdef0e398a8a06ff864992b7fe822901e7830b0a0a73bfd9939797ef743acf79e9caca2a00d047b33a9d8e740c4220326886a706dd0641ff9bb65db257854ea733f768f2300daa98ca81cf291b0400a0a2c1bb7c302876c8ec3af012f9f9fb5c6e0e00bc7ee1a7c2031899b4d9abe36a521c234a4deddfd5c58b44880c429124e9264b26688c8011de4f939636486e102a2dbf916287861ca8e26d8e97c642172b014f9e97972ffb4b07554ccac96febde7fb2f17fefb4b47d4159593900300c131717479a8270880c9a2777f45337e8065aea63e5dd2f753a5d52cf4479c8a870e8b3210d42908618d75edf9b77409729014f5b5050286b20c0440ddf372fe5c8a2f8e36b854d7ff22eed13a2042c7cf5c91a0048edd7b72b4a75094064d00c92248def3372fe155375417549c96139313d3d0d3dedd0c5a86cc84be14a808a4dde178775453f012b745e5e3eda42001060a2061e5a13e3da1ba4214803150d52ad2df0ddbb210796941c6618866118b285734b1019b4486abfbe8140a0fa648d7243806b468e904da3eae471a77a3f18ae04aabec8bbcc88ddd64e11039ec7e974eedabd57d90ea41e7e3b24b430ad3b6b150445511cc7559fac090402a42968052283e6c18d92f4d12cc330870e15cb897abdbe7f5f0b2a8109788f0c9e5e977047b812542254edfeb4b8b8e4fcf71ac346a0b8b8e4a7c2037222b6038915ab4336dd117d40a75ca34cd95bf83dc330fa68963405ad4064d01a8307a7070201ae81b75aad589b2549b2582cf2a8912ad87878e8ec668fa545ff49a76bc7d7bbac562b9c7ea3b7fdd2727eabd5bae3eb5df65f1cf2269c411533a4e08590760030b4f0c0db992113b1ba5314b5fac0a6c9e54bd739be1e3c38bd033ffff281f814b508befb937a26569fac395666359bcd7abd1e677c333246e0584d50a51ebe6f5ef8b1411a1c570c5305032a863956663d56664dea99989898101717177e1568ae4bed743a6b6aecd8b59597fe0498a8188f6df84fb355bc2d5c0352ecd0a8873ec53f71f7aa470b5783da30fcaa0158f24eb8299728543018ecee325cd45014258f4e8e1f3746de2a5c92a45dbbf702c0a8cfc7856c85a612a12ee18e83197355c14655f0ccc25f34a5cc3d4c2693d1603068341ab55a8dd114fd7e7f6363a320086eb7bbaece8563fc72ed0780a08a01807ea5ffb9e2c8ea90cbe115836c72d4fc2ae54ee6519f4e0380645ddcf13bd6120db40e91c139c02ab5e7dbef188689d26a468fce94dfdf92247dbd376ff4965f375f2f69b0a6bd549d3c2e440cd0eaa27865d547822a26a85227d9765b4a9ec1d33673adb3350000377e3117a373d7def5a149479a82734064706e7094e6a7c2030cc3180dfa8c8c114a251c7f637adcb1b5e14a80d362b0f5fbbf13c9d70b9a5855b011004224d12cf8ee0faad41aa1be97ed9be49fffdeac00f0121ee318f3337b40615fddfed5a2cf1c45d0e8cecd7a717c9f914403e784c8a04d501465b355971c290500730f93520900e0cff97b60d782669500002a11441f7813c7d87bdf526feceb8949060094443841951a00623cb6b85f0ec6d5ec8caad94beb9a170000480dc08c5fa29df27fa0d0c0f45d2fbd79e23b08fad767ccba3f7d32d1405b2032682b144559add663655638ad045054be40e99ec03be3005aacb20080e3aa411a78fd185e3fc0c726489426a066018069e42949d0f176962b65b9bd72ced64fc5fc71373360ac5290b206560d7d70d6b0db8906da0891413b502a411fcd66668e9223ffe007fffb8f88856b5a6a1694844c35c8b452f565a406a0474cd7def77ac8d5276e9b87fd01a281f64264d03e64eb08fbb2bfcabc56f65bc6f7b1683b247cf860f0c4beb688a1bd480da0ea354a73cf5a3a793028daa26a8f2379eb6c0878a1d1bdfe9a27892dd45e880cda8db2c71c0804d4a9b1e3fb8c84b3670002a57b5cff5dd0ba65df76e4de85f19625cc80b121d7da50bcf5cf05ab41a585a03f77dcf3a44fdc01880c3a028ea21616eeff9beda3af1b0e4c88bffaa3ac05ca71c9a6e930f709ff9ef5e2f70ba486a608d86d97045a4da20fa868a0af5da21dfb6730f482d30280d39bb565ef7ef9b3533fe0ee24b6c92b2f406c8b4b1222830e8215fda582ffcc295a076a836c91c3d935153f88b643e2d13dc1b2afc5234df11e5b8a0b2f6f9b400fbc5dd5f7063a752cda3fe1a75d7d60d3a3456bb11198d76feab2d1d3957908ed82c8e0bca0286a57d54f5979ff84801753d60f7ff0fef4c9f8395c0f0000ee1381933f4b8e0ac9ef91f8333be850ac89d2c650e62b999efdf0c5dfd24936146ffdf3feb54d5f3351b9a39f525a65840e406470be84be9b010060d5e03fdc3be04693ae2946a2b282b6c5c1aed9fc753eee3fa53b1e3d747a394173ed0fa1631019740e68a9bf58b8f11f3f6f6e1243d07f5bfc35d3537f33aa67baac07685b95554aa5cec7ed3b59bce6e8b6a63e00005a4173464c235e129d059141a78175d7e7f3ad2bfde2d1239f80e0926b6d32db6bb625eb6af380d41e294931e6739eaadae3385a5bf9a3a374a535d7c69f90cf031ae3aa8177660fb859a7d30169043a0f22834e467e91efaafa697bf54fff28df0641bf6c2c6164c864b6d7353149664d4c9c2eb6873606006afd1ea7afde21787ef054dbf8130070d6212aedbcab7e33316924f6018008a0b32132e82a643d1c7656e4da0a3657efc3295e0038f3766f0595f636f3d01b12876525670c8abb12d348edef22880cba1ca5a18fd68e5f6c2c739fe0451f36020080cd024bebfa1a7aa518120dda18a5ed446a7f574364704169fb3a4c52f52f246411e6058554ee8b13b2249f4020ad41e4a034a8baab55b918cad015101944061445e5e46c2d2e2e06806b4665665d3ff6c2d7428aa28a8b4b72727200202121e1fefbff74c92881c82062b0dbed050585c16070c08001dd5506b7dbfdfdf73fea745a8bc5d35d65e80a224f0672d8accbad00344d87c7adb8f0300c4dd3b45addfd25e94422ac8b8c8bbf0a0a0abb6bf72e8aa244dba140e91eb27dd8a54424691a353063c64c00b8f7de69d3a6fd2ee495dc6cd56ce5b5dd81fc92b7deb7620800c0f4ddca8560cd9e30fc54e15dcc56e4749e0d4e4b676eef0d69e590f0fc11da5b88241900c0a953a71886d16834555555ca7439dc676eeeaee2e212bfdf6f3299d2d3d3264ebcb1d98d2de48d02f6eedd5b56562e8aa2c964bafaea9137dd34a9d93d91307f7171497a6f23e0a219c167b3559bcd71cab5c8050585efbdf73ecbb23ccf3ff7dca290908914452d58b01000789ebfebae3b478fce04009fcff75dfef73feccb3f72a4d4ede6542a2a3e3e3e23634456d6788bc5d2f62a1bfe46703a9d9f7f9ef3c30f3f9e3a752a18940c06fdf0e1c36eb9e5b7168ba5d9fcf80377eedcb97fff012c49cf9e3d478fcebcedb6a9e1f744a7d3f97cbef7dfffcfeedd7b388ed3ebf5234766e0c9235109f4a2458bbabb0ceda057af9e9224b12c3b73e60c0c7b08a71fe10b2fbcb86edd7a9baddae7f30982505f5f5f5a5afae1871f51143564c810e5497009e59c39f33eff7cabdd6e170401f31f3a54fcc1071fa6a4a4f4eedd3b24bfd3e99c35ebb1dadadaebae19dab87bb98a01d5e03f7c5f59f7f4534f0f1b362c3e3e1eb3f5ecd9f3adb7d6373434d4d5d54547470f1c385079928282c24d9b367bbdbcdd6e7feaa9273165e6cc478b0e1ca8adad932449a351abd56a4110cacbcb376fdee2f57a333232e4c3f7ef3f505e5e4ed3b44aa51202e2b19fcbcacaad65e5d6c325256ab5da643229afb571e307cf3ffff7eaeaeac6c646b55aadd1a82549b2d96c39395bed763bc6d408b9218b173fffc1071f9e3af50b0060497c3e5f6969a9f29ed86cb6fcfc7d5aadd6ef173ef8e043abb542a55269b55a9aa6abaa8e6fdebc25fc6e4704912403ac37fdfbf71b376edcd75fe76225c3c7f9e4934f9797974747470380288a8d8d8d144569341a96650f1c28e2384e599f7c3edff4e98ff03c1f15a50bc9afd3e9befe3ab787f98a7e7daf92f33b9dce471e9909002929299923d26419581b54870e1edcb6edcb810307a23e298a52abd5870e15eb74ba929292db6e9baa2cfc9a356f7a3c1e51146fbef93723460c0780c4c4c4ad5bbf50a9540d0d0d66b3b97fff7ef1f157d4d4d400805eaf3f74a844abd50c1ad4f43351066ab5da6e3fb52f3fbfb0a000fffdf8e38fc9c9c9a9a9a9f285d6ad7b6bcb96cf4d2613feb461c386c6c75f515b5bebf7fbf57a7d79b9b5bcbcfcbaeb7e2de7972469faf4471c0e477474b4dfef1745b15fbfbe0ca3b6dbed3a9d4eabd5eedab56bf2e49bb55a2dca00b50a000d0d0d49494966b3f9e4c9931a8d262626e6a79f0ae2137b5e65b9b2ebab436712614691dfef9f3dfb098661860f1f3675eaad98b86edd5bd5d5d52ccb0a82a0d168eebefbaeb8b8b88a8a8acd9bb768b5da9898989c9cad69696972f8d1254b968aa2a8d168789e4f4a4acaca1a1f1717575252f2c5175f1a0c7aa3d1f8ea2bafa40d1c206f08f0f4d373a2a2a200c0e7f38517c96c363ffffc9277ded9a0d7eb01e0a69b26bdfdf63b1a8dc6ebf5161414627c3bd4d2fefd078c4623c77177dcd1b4648ca2a8ec871eb29f3c3165ca643c5cfe45b9b9bb0c06fdbbefbe3f75eaad21f68f288a4a4f306544d4d343fb5b8d46a3c7e3b9efbe7be5bb0400b9dfec59fdea2a83419f9fbf4f2e1b00ac5cf92ade3a97cb959dfdc094294d8b48398e7be9a5979dceda356b5ec7150eca32e8f5fa152bfe8931ba25495abcf8f9b2b272a3d1b8eecd37b3ae1fdb8187db8d44980c00806559a539c471dce6cd5bcc66b3288aa9a9fd172d5a885f8d1e9d3965cae4d9b39fc007b666cd9ba3476762bcade2e262a3d12808c2c4893766673f20e79f3469d2534f3dcdb2acc9647afffdf7e7ce9d435154ee377b3c1e4f4c4c8cc7e31931f26a6549faf7b50882c0b22ccbb21f7df47176f603b845da9429937373774545457dfef9e718dc0e00b66fdf8142bdfefa71729f4192a4666b4c76f603b9b9bbf0b3d56a456b1e1104212b6bfca449931a1b0539d16030c89f3ff9e413bd5e2f08c24d374d526a0000b2ae1fcb7bb88d1b3fd0ebf5efbdf77e46c608d4e7ce9d5f9bcd669ee767cc9a357142169cee39e8f5fac58b9f9323782bd5d8d0c0a306e47ed1dcb973eebbef8f0683dee7f3639923a893106103a6e1fcf05321cbb200e0f57ae7ce9d038aade7f57afdc2850b1a1a789aa65d2e97cd560d00bb77ef6159561445a3d1881a90f32727273d3c6306cff3344de7e7efc3a7b877f737515151a2280e183060e2842cd15d8bd795dc35168be5eebbefc2f7e8f6ed67f6cc9c3af5563cc9fefd07e41d8b376fde82edcf2db7fc36fc57f87c3eabd55a5c5c82ffac566b5c5c9c288a0c43f3bc3724735c5c5c72729245015647acacfbf71fa0693a1008a4a5a5c92794ff592c96402040d3b4d56ac5c6edc0c162bc815aad76e2842cbc154dbf5192e4d32a0b208a624a4a1fe5d803ea3f3d7d1096b9a6c67ebecff5c21279ad4108f6932718861145f1eaab47860c68489264b158743a2d0068349a53a74e252727d9ed769aa6455144e338e4015f3372c4ab82c0b2ac4a4557579f484e4e2a2b2ba7699ae7f9ac0913004095944eeb40f4813a250300c68c19b369d3671a8d4610041c309124292e2e2e337354717109cbb2dbb7ef9836ed777979f900208a22d65a65b0099bad7acd9a35070f1e0a991a8b8a8aa2e9d6a21a35fbaeadadad55a968006059f65fff7a25dc8b5ea55261a557a96887c3999c9c24dfc0912333da7ea1d858437822f6cd2291886f0dfc7e3f0088a21862bcca68341a65ce868606fc13cdfd10d46a75488a2836451b8dd26a0080a228ed9220bbc485e183a2a274c160532dc15e233265ca9486065ea3d16cdebc0500b66efd42abd57abddedffffe3e390f5a683366ccaca8a8d4ebf52a954aa56af7e3a0140080d77ba6f7d2ec94733018f4783c1e8f47109ad6bec937b0bd97be9488f8d6a0bdb4fe8a0d272121c1e170300c53525272668f8fa8a6fd928f1e3dc6304d27ecd1a3077e9024293d3d2d29a997cbe5621866e3c60f8e1d3b869d75b9578aac58f10a0e749acde6bbeeba5336f159366ac58a575c2e57eb65c3ae8bb5ec6700c03100b3392e18140180e7f98767cc48b8a2b5e5ff66731c5e3a100868b5daa3478fb5ebce5c4a5c7632682f63c78ef9f8e34f341a4d4ece569cd2c2741c787dedb5d7b1a73164c8e0101b7adab4df2d5fbe02c7a970422dfba187e46f31b3d56a359bcd1e8fe7c927ff929c9ca4bc6e1bdd87aa2aac5f7ef91500b02c3b7a74a64ea7b3582ca85bfbc9136d19b1193e7cf8ba756fb12c5b53632f2e2e494f4f0b9f178fa0ce6ec78878a3a8abb9e9a6493ccf0380d1689c33675e4ece56a7d3e9743af3f2f267cc988579388ebbe79e7b94474992347a7426da5db25576e30de39579e48117954a555f5faffc8ae3b8caca2a9aa6038133b68ad16804009aa62b2b2be5c32b2b2ba3a2a2542a55dc15f198edaebbee447beca38f3ec63e8912dca356497272527a7aba288a06837ee9d265365bb5d2d0cafd66cfba756fc946d7a50a690dce814ea79b33e799a54b9799cde69898988d1b3f58bffe6d0060189a65599aa63d1ecf942993435ea2c8dd77dff5eebbefe138290eff2b8756288abaeeba5f17151d645976f1e2e7a74ebd153da84f9c38f1d1471f1b0c7aac9ae9e96978c8b06143df7efb1da3d1f8dd7779f1f1f103060c282a2a2a29398c4d4ddac001705a7ee9e9837008ffe597975f7bedd563c78e35180cf65f1c7b777ff3e38f3f85bb63fde52f8f3ff8e074bd5eafd1681e7b6cf6942993d3d2d2bc7e61efee6f8a8a0e310c6db3d9e6ce9dd352efeb1280c8e01c60c5facb534fadf8e73f71fb4af9ed2e8a22c77153a64cc61983f063274fbe1935c3f3bc3c27a564e6cc198f3c3213271f7272b6627f1a9da6d0c5e3f5d7ff0da76d128bc5327cf830acf798996118d4c08409372877ff5ebcf8b9050b169696964647b3c5c52505058598aed56a4d26d31b6fbc9999394a1eb0c2a1ade5cb5f9e376f3ec33046a3313777178eff6ab55a8341cff37c7dbdbbb3efebc545e419453ccf731cd7d8d834752a8aa2dbed76bbddcd4ef10280c7e371b95c68d800407dbd9be338b7dbedf5868ec7b7044e726dd8f0d6b5d75ee372b95c2e97dbcdb95cae3e7dfa2c59f27c4b1ac0f7fd6f7f3bd96aad504e992933e8f5fa75ebdec4d3fa7c4d43378220381c8ea14387bcf5d6da10bfc0458b16feea57a31d0e07ce1c63ce89136f7cfcf1d9cad302c092258b67cc9a45d334c771f25738a4bb6cd9df43e6b67064f9edb7d767658d5796a4a181e7797edab4df2d5ffeb2dc1484dc7f251ce7e1384ebed51144240568c15ea9c3e1445f209cc6e738aebede2da734eb6809005eaf0fbd41e53f63630de11ea03e9f6fdab4fb8c46a3dbcdbdf2ca0ae52b56368ef10c3d7af43867f7512eb0d211353c0f9ea4a2a202679d0c0643dfbe57351b9e1133731c77ecd8cf7ebf5fced9ca996db6ea53a74ef9fd7ead568b735e2d95592e4949c961b7db0d008989094a77d4f0fb1f72f7426e750475ac234906702e7ffd569eae9ca195fcadcba0a502b4b1c0ad676e97e3fe3957357438f339f3b7fef32377c17e84f50dc26fee396f77b829d2b905389ffc1dab37ed2a43e716f87cbebd988930195c4aa015919797cfc6e8c78f1b13eeba43b8601019740f6880dd7fff032ccb0602e20ffbf2d12f90d02d10199c05764c054110047fb3de636da42d5d82b2b2729d4e871e7b3b9d00000a0f494441548e6ef29826a15b2032380376a05f7d75e5575f7d959999193ec4d946da68def4e9d3dbe7f369349a4020d0bf7fff0e5c88d05944d8485197828b783efd74d36f7f3b252f2f3f3d3dad036b475003e3c74f888b8b733a9defbdf74ef870939c1397c0c7c6c6de71c7ed1d561de1fc21adc159a8d5ea4f3fddb475eb173e9fefb5d7fedde1f3984c4683410f0038c4de2ce8882afb4a74ae06da38504b40225206adbb79b5f4e0db3882ce304c4c4c8cbc8a00ba75b0bcbd818094b5dfe3f1a8d56a79f6b7f592877359e927f264802bb676eedc595474b0aeae4ef9952008f3e7ff35dccb4d8e62545c5c820e05168b055fc36d994ecacbcb5fb56ab546a3494a4a5ab26471c8d4e9c68d1f6cdbf6250064658dbffffe3fb57412e59ff2c4dfc2858b8e1fb70982f0e8a3b3e4c50ccaa324492a2cdc5f5555e5f57af57a3dda692d151baf9293b375fbf61dc78f1fc744bd5e3f6edcd8bbefbeabd929f32fbffcaaa0a0c0e9ac557a4088a288bfe5f2514284c9002be5d2a5cb0c0643f8021a9ee7712d55c82156abf5e59797d7d4d819a6290ca820085151514f3cf178c83a9896c0f5bb2d79cbe0d2adf0055c3a9df6f3cf738c46a3d27f4919412c189444510c04025ebf10722c4551db77e6bef1daeb2a1585ce7c81404010048bc5828b139a751b79fae9393ccf6bb55a39ce054dd3b9b9bb7272b62e59f2bcfc82c0cc33673e0a00727c03198cd172ce7b7229114932c017d88b2fbe64369b01c0ede65252fae08023823520e410948dc96442635d14459aa6b1622d5af46c76f60353a7deda45af3d8d46b363c74e658adbed1e3366cc390fa428ea955756eed9f3ad5c663c1bcbb20e8763c68c99fff8c73265a3877766f6ec27689a6659d6e3f1c4c7c7a7a6f6afacacaaacac3218f41a8d66c182bf6114193c6ac58a5730cc16cff366b35919ed8be779743dba7c882419004049c961acc13ccf6fd8b04e19db47465939ac562b2e15c0435896eddbf7aa53a77e3975ea544c4c8cd96c5eb7eead8484847083a4b3080402caa5331ce751065669168aa2366fde226b00d59e90108f41295996359bcd0b16fc6dedda354ae7b637de580300344dbbdddcd34f3f89912101c06ab5ce9b375f1945063583516a789e7fe289c7e5cc4a2e1f8b08224e06b2cdc3b26cb806c29f9cbcd8d7ede6663df6a8bc28b1a0a0f085175ec42ab56ad56a0c61d4e9a5e5797ee1c2bfc5c6c62a137105704be0a02d2eaf010051145f7ef90579e5e7e6cd5bde7df77d8341cfb2ecdab5eb9413cf281b4110eebcf376a5aa2d164bf6430fbdbd7ebd46a3f9f6dbff614c9ac6c646fc361010535343a72c2e2b0120112683c4c4045ca42208c2860d6f0f1d3a14d3b55a6d5ada20e5bc15f6a48f1f3f8eefbc87673ca2dc21262363c4c2857f5bb8f059ac3a18c2add34b1b0888898909e10646ebf56cf7ee3dd8e2b95cae575f5da9ec064c9d7aabcbe5dab16327d6e9c71ff7c9fecc1819401004f99ec8a40d1ce0f3f9351a8dc3e1c0145c680600d1d1ecaa55ab274e9c88c6a456abedd3a737be5f2e2b3144920c707588c562b1dbed6876e3100d00040262302862e041f9f91dfdb90c1f763018c4606cca53a5a7a7252626701ca7d1684a4b4bbb4206cacbb53d73595919060ecacc1c15de15bee38edbb76cf92f865b2d2b2b97a71d64583634f08c3c77c1308cec6a3e6ddaefb0cd292b2bffd7bf5e9133f33c7fdd75bf9e33e799cbcad52ff2569f2d5dba64e8d021f20a2c243a9a351a8d6fbcb1262f2f5f366f780f87f509234e873fd43e7d9a22575f9805532131855ac2e5726140b184848490af70c11a7e56a954e1c3626d4492a4a9536ffdd39ffee872b994a3585aadd66c36171414ae5cf96ac7ce1ca144526b00a78304ce9d3b67e6cca6155800e0f50b9f7efc31c771269369ebd62fe40e1f1ba30f04021a8d06577585bfdeaaaa9a06d795c34d5d0445511b36bc0d00a2284e9a3429241c8b12a3d188c359188122e424f2a2ca6030183ed6d9765009b7de7a8bbcd60c00f6ecd983c1f676eefcfae187a747d60ab2f321c264008a28b34a33c668d06397d76ab5ca89a9fdfa62478261e8ed3b7395761145510505853535768341eff57a5bdf540f6b1b4dd3767b33723a7af428bebccfa9a5dcdc5da228f23c9f9999d98a0c06a50ffeeebb3c8d46b37fff019bad5ab68bb00df9e8a38ff1423e9fafefe9e8f31d030d24a559959939eaf6dbef341a8d3a9deee4c993ca10c29736116614b56454c8e6813ca78611782c160b56d0d756afdebe33573ebca0a070d9b27fe088a446a369bd6390963648b69a3078847c1eabd55a547488a669b96f8a750bfb2472505b2c30860f52a9e870f35dc9d8eb7e8571208d46e3dcb9f3ac56abfc93376efce0cb2fbfc2b815d75df7ebf3795bb77e1b834149add674eccc914824b50668121c3a541c92ee743af11d2908c2d0a1676db5f2e4937f993163a6d96cc6b8fbefbdf3ce9557a61c3f6ec358ed00e070389e7bee59085ba62c8366d88409377cfffd0f1a8de6dd77dfafacac1c33668c56ab2d2a2afae4934d185028262646399f959131a2a8e8a056abfdf7bf5fcbce7e40abd5e6e4e44447630c5d0a1d575bb95c76f603ebd7bf8dd35e73e6cc8b8f8f37994cc78e1d437f2700e0797ee6cc19e77327c3237979fd42ce7fff2bf73d5a69af2e3d224906005055757ce9d26521e60706f6c1482d0f3e982da76383307ffe5f7116198faaa8a8a4695ad64076f6036df1a778f8e1e9fbf71f1004c160d07ffffd0fdf7d9787d7450d701cf7eaab2b95f9efbbefbe6fbf9d69369b398e7be185170100a76c711f8dd6af2549d2942993ed763beed6111313c3711cc771587ebcdcf2e52f9fa76376f86d84d3e69fc3e178ecf1c7e1721a338d30a3a8590441c0a841afbdf6ef90a82192248d1e9db972e5bff47a3d462b42ebdcede600e0b9e79e0df7a440d71d39e0339c7e43bff5d6da6bafbd0647a818866118261008b85c2eb3d9bc76ed1ae5c826caefb9e79ef5783c5eaf1733fbfd7e87c371f7dd77298774799ec7584021bf4892a4ecec071e7bfc714110dc6e4e14452c36feccb56bd7842f84e079dee3f1b414acc9e974f23c5f5777566ce090a526c16090e3389aa6e7cfff2b6e74d0867b7f891049cb6ed02892877794b43ee9237b981e2bb3f21e8e8dd127f54c0cf730452f83cf3edb1c1515e5f57a7123a69038456895399dce402060309a52fbf545e3a1593766f40f3d71e2442010484848183c383de484c5c525f81967d99a758cc55d3f5abf9cf254782b42dc8dcacaca01c0eff7e39e6bcafc4ae4c9becb4a0310593280565de4cf33024f1bf39c4f40a1f0cced0ab2d4fae55a8fbf14fe554b77f27213001261322010ba824ba16f40209c274406040291018140644020009101810044060402101910084064402000910181004406040210191008406440200091018100440604021019100840644020009101810044060402101910084064402000910181004406040210191008406440200091018100440604021019100840644020009101810044060402101910084064402000c0ff03ecb125572718a2080000000049454e44ae426082, 'InnoVistaTech', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d43680.41640797227!2d120.99890699832049!3d14.608198427873463!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b72096aec881%3A0xde2368e6f9fcc7a!2sInnovista%20Technologies%20Inc%20Whse!5e0!3m2!1ses!2sec!4v1702328963286!5m2!1ses!2sec\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 'La empresa se centra en proporcionar soluciones personalizadas y eficientes para ayudar a sus clientes a optimizar sus operaciones y alcanzar sus objetivos comerciales utilizando la última tecnología disponible.', 'nnoVistaTech Solutions es una empresa dedicada a ofrecer soluciones tecnológicas innovadoras para diversas industrias. Sus áreas de especialización incluyen el desarrollo de software a medida, consultoría en tecnologías de la información, implementación de sistemas, diseño de aplicaciones móviles y servicios de análisis de datos. ', 'Lic. Mark Zuckerberg', 'Lic. Elon Musk', 7, 'https://innovista.tech', 1, 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_estudiantes`
--

CREATE TABLE `datos_estudiantes` (
  `id_datos_estudiantes` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `imagen_perfil` longblob DEFAULT NULL,
  `nombreUsuario` varchar(50) DEFAULT NULL,
  `cedula` varchar(50) DEFAULT NULL,
  `numero_celular` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `lugar_donde_vive` varchar(50) DEFAULT NULL,
  `fk_id_carrera` int(11) DEFAULT NULL,
  `fk_id_usuEstudiantes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `datos_estudiantes`
--

INSERT INTO `datos_estudiantes` (`id_datos_estudiantes`, `nombre`, `apellido`, `imagen_perfil`, `nombreUsuario`, `cedula`, `numero_celular`, `fecha_nacimiento`, `lugar_donde_vive`, `fk_id_carrera`, `fk_id_usuEstudiantes`) VALUES
(32, 'Aaron Josue', 'Reyes Carvajal', 0x524946468015000057454250565038207415000090cd009d012a6501c3013e91449b49a5a42f26a9157a61e01209676e7ec7f037787ad697759d7ecd5f857fe4a0535aa380482595ee9a7148061567b039715c199edd32830752e5c46c817d34593751bd1be16ebced2dfee208ce6745e40ca63556b3e6fed49a7af1d94f46f0aafd706906f6ed9b71b723720aac4e6c48f3a540baa60b7bd1605b50b817359faebe28b78ccc4e01839c11cfb6ca0450a32717d0681b09c7143a34416a999a127f663580244d506828eb7fe2ba7c780a002f71ada24c0ac9961844030686ecd405f5f1ef168c29bca45f1eacacfd2950cf204ef215e97713e03e5e9ec7263ce6b18f3d41fadfaf33d84724c15abbc0c371e38909be60e71b41a3bfaf3eb42c185dd840c3e720cbd5f495f0ba2af32e2bd4e298c721f6da0775efb74a969cba601e8ec975a7ffff216a5df488a2a9bd5229d2253d25be03eadeb8b039d1afe3fc8fee56747eaa44c6796bae21dd3722eb5ea86d32277e314c6aaebfab8988c15e36600c753ef8399c7efaf3b081b83398871cc012e952643e275810be292f8efeafa1ffb28bd85e1a1631191fc9eec4d3014722b1e022cef04c6e5c73a56ced1cf2640f6adc8f8411c3107421649404f16ba2dfbb3d36a272246005054412f7fcbf12022d79e4fe1ea27207d74ce1b2f25f6a769821e4fb8506ad0c2ce8434342dc576c027e248afd438ed1c1f7b863dc5c3366115a244270b6271ec7e15fbc4dc0c34dfedd0ecc77106688934ceb7ef5a51d57760985531bfe9a1405e49be0e9aeca0db215ce96876f04bb621bf1e26ed6a2413a3fa30d8417db83b5f980b5045f89dcd3c206378017ddc7cdd0b854f0664c0879e63829e5104dd156cae3c4e552c768d17fceac1aeddf99b6f04d00b37277ae30e4dbb7a3939d9e45bf4aa4f78748ef7dc98c5c5f9f5aab27140519c52752a13935e5f9b9356dbfc163d35d95e7e4f0f037443ceef16a364319757343c20f2c3bcddfe819b80cdf93fd0c55b12f7e55779794cc8aa616b4c5d38eaf1e65b36ffd68729ec8e13af7fe74042fe2bb991f9b711de155faed0952d9187431fcae697739e9bd052cb3e6fbe3dd072e73c527a6a321433e693bb8d9555790f1524bd6d2d8012cb2a31e87ad14e9a8958f6d20ec67763edabe582e6dce862dad5fa7e0452e219d361bef0f19ca3f89380bce4d4ffb5637877daebbf86ae8c34fe954ac577f59a36bbf527dfac76a3176e32083e38475387aac56e387ee54596eb40e07312024b165880b319d48f63fc94787e65a568347c3dda0225be2a9dfe4ebd99d58bc717de0337198349727fd9d4cae032bfbe17f24063b458acdc1604567b676da8454094e282b1cc39bc7c1a11c0525b142ef346014eedd392dea9a0b76b2cc79fba3e8e8a6b826b1a71be2196974562962e2fe2a17c2763951280bd21dc8c94f900a72eb630983b43bda72cb55f8abfea6c658631de61418e8d3f055d2252edcdb958df47e08e9652a46d13b0460ed4c0fce0b4bca91ff8694637c95fbfdb35a355b869c930e49d79abe272bb4fc2bf57c23992ca11d19909501b9d9a9e1e5301efddc20b67d2859592ae709134f9d1c0f64cf423c4099f8d041b8a95676818e402ccb065b20d4444ce2bd4d6432c2df8f8da9a2e9b3bebed8b17109043fccf35d0d9a8728be3b6b89aa942e6df9df5bcd8d192d2a80c0275802df1b8a1f4abcad209af5fcc970d55a1c049fb2ac1d8620700fc34797befd6aae90674728b310db6448b4aa717c7be0811d2a768a6db71b4d4c1cfd7713d504f9a1ad83a5dbf18cef364f7c25677669da4603e6d107634115710ca5e3e7eaf1578ef7d2b60f53abbc92c1e23d3c7c69c8bcb943024c94a76fbf0c74f3ee405c73b306864e52139119ce88549be047b8ead09828a929e109b35f72647fcf4ed0adf0d31d98fc4d31032b225132dbcd1d3514c8fa645a011c70e6a1fd223253cb5ba248ad659dcbbe3b56817f3acd348536d039c7da6c9bf7933e699639f71889dcbb87f8f7466c25bfbe32bba4ce110585338a68e87c762d54f158a9db06e455a6f8706dfdffd57456ce446d9eae9ba3c964a45a8b6d589a2fb6a49fd2fafe040d828f755fe7d0788bc6599fc4dea71bffede5d5a52ce884424a758b03aae432bf0098069a5f4653f34b9e48620df8a11666971dfa8f8295cb0ea603236775d46af44e4f06327c40e4cf391b41a100d9894bea019dc988e82ad94b06095051f540fbbd7d8b678903f0526c81d29b55d0540f4494390b10fcaaafcc1a0cb7e50d9cc407bc71c30687540a46cfa719bd70b94b26ba3cd88b480ad688a000e20b6c9e017fc62c375b57d85cfd38a766552bbd104e475cb97a80e3d8be2098ced28fd7d1218a059303c58024f924adb80edbb1406c4b69d81414d50a80eec69e8eeb121ce544676a846279a5c9b0eb75584c29b504651bc241b0114af2303a5d909c4ab4413c26d0c083ea177a61ab15ef45c4e864e065e32332c22d6bb1d58a380000018f41773f2c7df840e1cf70020b527c582527af7850cff879c66ca837b4bae3fac873b518898cfb1e04730c6434f20033d490010467c4e10153c4228988d4146a9d0bcbb935954e86c075abb1aea409eb20c6a371056f410980217b18192781a43800000395a0fb95b6c1f622c9b7c6a99245ffb839787b1e9c43bc005a9758e6fb012424d6d0da4f0a12970759e0f0637b45e65db4608506fdcda3c77111e0abd7249311519254ca9f4eb98c585155095d5e87dd7ce5ec1546daca1641f22c8b68a0511809c44983ab0efc86ed2bac15bb5a88faeeb784b395025d29a751638d0323394e07f5eab80654c020745eb4a0d8fd8b3c5e18f80e7fbe9e4d6b855460b052c6c0ab998fe732aede4a34902ec1537afcc275614b96591515e6726388a16ae691ac354a307feb34f9f74405259fbfb9b0c6f26705aef21025f5b232e39901ed5cd7c65ffbe6b954ce1b4b98c76a183b023036b526bf7a3d46243b2e768d3b8518ba2d99d906d93ae2adcb355d22a311df670dccb1fe9abf37ca39af66f2f6971a48f93cbe0a19a82f85485be0b5764baac66d9862502bf0a8df81b9084878b95a742a27e71ef2b84414fba4a8c0064ca0e7f6a7728f48afa825a56b14a103e1e9ca76c2e28821046270d6cef94e3752e20c88fc92b9c4799d0802105c3e8a2fc5c4d6fd60821a882db7043671bf8e0883bc7af79808eda0c08427c738dbe59b7d61f49a27b0542ddca4f7f932902f67ce1c3f49911df62b291c81c4200a4fa26a65043dfb2eb71e30901434f6fdedd76815d35f9a47e1d2507c37f7e0469d01fef65f9a722d95b8b08c5eeed387b6ba4be45466aa42d9bc7ae95d4e1f90262fbb41f612ca1c26ef077bd892681c11383f924ec8a0c2a3d8647e647e7d83f7a36df21c77a26547f130ee7d451d4d71482090cfb0ff3d3f5590b1a8277d6f6689537f6b85977676a887fb5a5ff8e50ebc93e11fe4c0f8064dffa4b44724ee61db3c6c1063075a2b6cb6c8604185e3c25d93f63e222d2999c2ecb19af538e0307dcc2a92bf999dfc2fbfbb30da415a6a10ab9b5d02451e0f289977abdf2c7745041e240a7353db9747d9b634b219457e7ce7f4963ec70e777969eb921641becc88de3a4293ce27a112e3f582bd2dc6425d0dda382b7831eed0191f6cfe12e0eebcf28bcb80336500ce1a7db4e838831cfbeb308fb600595d624d06d1ced3b1290f690835e8a7d497475bb29cfefc31229d84b41549c5f784270e175b5d888c90ce6138f3c89a45c1a717db007f1c463c992560f4a06e6216ef9812370b3cd3c8e3c307e35e1163bb22aa7a62187a6f57a5d668c4ff9cf3abbfa49a8337fee8122f42017e202d0a6c55124970cc0534524afd485a466c91e0af2aad6f289f6c93ee4617bc2570f3256b615cb94ae664fd2356f7310ee7646fd6c5db43c46e1ac37ed3150037d41eba4d3ebb8326806fca9fb9715e1bae3f3478b22da65514fd30328afcdf78e33a683610091e4a2eb4c4f3d4e50ca1faf502b9323926029795f892ad6d5f467d0867b5bfeb0760b6d09ddc15cc48f4fe231041e66ac2e035fde68bcf2728d5802262c8b6c3f36b21499cec3031015531edf0a1e5cf94fd9ecfb5de657494ffcb42a3a07a585e2f5dd3a6ed9396a8e55c228f55aeb55910403a18844cbdfa5ea27e585a2f6e83f20ba2a9b457d54045f6d5ae806db5de681d39c9b535a6430cb64d54153f393bc90069bdaccfa4b34e7bc9a0d07a5b2a4447fa35b1b0ee2c9e6884a582c4d099ee0d142b8b5c5414c9e730481942f13aa8fd3d7167c86d13a84f801909c8a729c2a9b793a0d6695b6eecdf83efe4fe09b488cde522f759f2d56c9b2398db931e173a75ca6edfb918ac45ecee0f5a046ed86d49e64989ee164a67355a71ffe7b953b693909e07cbf9029e4935a154c2764230f265da45986dad580cf14fe4aa260c1bb121abce1031e742ffa28fe2abbd188b8d34488156f79be7f48dfc01f2bcd09a8af7e4a86722cb9f32955477e529b813eaf28dd4489dc729b2fc6cb59e376d847935bdfe5b8bbc368a068acb87ea40664e4521e22b2fcc94aa1c6cbf519ae9cd1b6c17990af838a3228d10758cc4f904f71352dd4aee8a6021e0d6f0b25af8d104c392e1be635ce3ac3aa11f1891b3091ec2147e04395dd7b72dc6490f6c3548e94a9aabca656e8f3f6c5be1ced46e6ba9cad85afc82c18a53bfd6ee35957d295f5cf65a1d79915678ab52ac2e531d8631b319b9f05a58b5fe26b660084e2030a032592f7fc2182255d46bde417b5a9e5d10ef0dcbc7f482ff263a0c932540d8cb1f2531e57a021dbc28c8429011d8369973dec100ea38211b9ccee9b9abd7e327623f778acb6cd0a384a997d945337717750b1d86b3d6a58822aa34157c0cb1e9a16840cb8a008944d9ce12e893dd0339dd0b9088d929b5b654d2b0e2ee4bff623ef601d99837b1bf295648674fca44bc00c9b50db70cfc4203861f95f9224f0689a9bf50c8243ab222f56907420b7128a5beaeb30c4e5486affe97fb661e071178337df6992c035febac609cd60638887a4c99a2addf555b901373d82c3df0ba527df890ca653d0cf9f9d21c2576b613594ba2195b040ca090bf7dfae08abe2eacf601fdd1f4e72ffc134fe2fe3dd7a77d341c857deeeaf4fbad0242cec7ed38e6b3aa848fc62cdb9f3bb67daa3fdf3e39532a414a454302c96116fcb6ca49163a4330553d9bc7b7365cdd9b4fbb5e5b13b7a431ea04b006f0ce174b4aff56ea3b533da0b59f4ec39dcdb4fc707cd450a47c9ef5aba1795cbc929e3cbe0fb3cb9a1b1473400eba130255cc1737ed25f93964261cb6411417990f24b396f7fb9f39229be02531f9d5150227cb2b9a1570cf035f2d056a2299efd39e3dfe324a2285a0f9e84f7d1ea7561fcf37a981d13c24df41d7d2c1f955714ea4b131cb5064f8740eebd18c495f7fa3aa553c32bd24e14ceb997a4300246ce9fc745110488c7886656cca0980a6ad68f1b976d5670ef332e5c5fe76e564a2d9289ecd19240494e970c0558eece0240fe36843765418072be18a20043d4a38f2457cf6fc47f6b52e6cc689f5cf88aaf4f78c9d41c3db5ebe71dab9b049c3bd5d00d00dfbc315297088fba54c00abeec37e842351ab77166ed14b6b5d2ce9882f5411ea3abd929e6442fc35bdb50974a7c7b8be36fee55dbadb7adb2aac9068fe1db8e8481a15b4884363d525b24149518b337d0a6088faa851b702ad62d2e9fa40a8c0938d845cf894f57b77e6e39f498b5eedbcd690ddc392ba3e1c14c7dad4991502557bc943c804aba72a17daa5d25afd8bb45c813f3cfe79b25617428e325f5af1b270134dc8031a9eda63eddf290cf71f8deac458b099996bd1ef338ab7482ddadc84ff908dd69830cf68e3c56d4c9eef3661316df07c6adf3640707ece9ab198569e0acc720df7e54b8da9527647bd6652468aabbeed8ddf92d67785868afc47c6f81350b66dfe81a06ca758471b6bb2b8fb24065bf704210fec5ff4d856b5d9f8ed42ac51ea1cd391ece812a08a301ca7fec90e1e613c1359d8f307c7ee53297b99b314084e76756cbeba5e804618680d95115af365ba90f2dac567bd0e7037baf28d3681d8ac07295aa27a09d213e01fdf2b39a18219102706dc0119119b1599f3345ec85fac5c704c99ec8cedf152c9d32da7285332d6d6f54b6fb67a648586152da685f5cb4dfb9a8c5d09d15903bc8e5536aa0f9447e65d178e8a845cd7f9021ac4413019f8a783a6874ef2a2d2389fcd276ae2b9e6680232132f5f4a213ab16e1ced955e9260f7fc97e0740de303e87faf0eb0d6749b9633c295ae169d9efb1b416c7c9c72dcb65ebe26e517a3544861bdb917835f923622a4c1c41a80400607796a017d65ad852b03853c26192109143b92b8328fcf78e9b3193a71bb7d83f2fc970292b93420722beeca503cf3603627c49df3648af9a2a761abdbdeab7f51f1c866f11c8ff6968a6f790f4986e96fbd9ee13797e25f164e6c1632a4fdd3b62dad4ec9e4d8e2e3c6ef703f737f955298d89bcde3c7ddae4d8eb9625925d23203f30d339430daf81ec6cd58a5d443a64efc74f271d31d1f65728006aa28014dbcfb9bbb222e8e53f67410ac55c31ebcc63db92e6954cd90e1babc443a483f5b68132062c2eef050d5c0fb767b9f91d6a2f91a12cead539cd81dd8bdbb1ac0bace1d50477a007bea7a73aa8c6e5e4b8d01a302537bb0b696288779e4ae5d0519d687e1917a6316be909a374795fa03dc8d97d7bbe1ebd3761a9abd49cd516b8a5fa9f2c851446df4c8e2d37fa04bab6d01fd6e7bb4430793ca1e48a276817f5f2ab68b179412ecd54e8d29bf87926f2054378e780e61f33b3adca8f44f97e9a6ff722efd679c24ffb42d49807147879109c02730bfedf62ff9a321f28ef79e98a453abb478121ba556840557ae457cb2e62e2d573d3668cd778e648ee3aacd031c83f786c14e09776fc9225984296d2837b910baae3c4037c9ee265392eb432fbfe3c466c9859379010c11288cacf6252b6bd3ec3accb64b8ad3e02d1163319505a3163d623c356d625efe53afba4d27bc7b4d7af1346a494479b847639cd2218c4a9fbd2e83c27673a87cc925dac49d48e2d3ee939112917b1e2aba0dcd468b81a6d8dc12263c8f727511a5071f95f903edde1c8a6c34d49770fed498b02adeb65f22c85dfedbb8fee207d01e5120d7877eca180a27c3eaf4def9a29284b01e994a9ce44883ca24ca1635f64b580a2c0ec851bbd8473ea0ab21acb2a51815d5cf20bdb4dda9ee38f874afecb07e0e48b80e9986c961b8151874b88d7989714c39533f871d09499b7632e44d66f5266032231519535d350e54f09d0b91695eaf57008c77965fe4517cc49bec8010ba42186a168ee7892bf322517a15f87a8d23b91f963a09e0ed85182a9a705e048bd15c46f8d0dc978c4d8cb694b0406458d1a6ea617824384d3167244229459a6b7c9d142b731bb7301dfbbbd22f85a03f33239f27f695cb8221aa792453e8b91b61547bcc017a99067374b51a46c00b83687c02d6720ae90c84ed09d8006f326a07a0d49779dda8e6467ff0d06defd15cd99fd79415918571ada50aae88142037ca9f2095fc6b214dc474ce2edbb9939d3d2fb4f98c06a18371bf9423d8c11902348e3102d1875aeacf1104612444e364a90b21402ba96c000000, 'AaronReyes', '1314025733', '0990441632', '2001-09-08', 'Manta-Manabi-Ecuador', 1, 36);
INSERT INTO `datos_estudiantes` (`id_datos_estudiantes`, `nombre`, `apellido`, `imagen_perfil`, `nombreUsuario`, `cedula`, `numero_celular`, `fecha_nacimiento`, `lugar_donde_vive`, `fk_id_carrera`, `fk_id_usuEstudiantes`) VALUES
(34, 'Carlos Antonio ', 'Reyes Carvajal', 0x52494646ba5100005745425056503820ae5100007086019d012a9701f4013e91429b4b25a3a22f2577daa1e01209676ca7fc32bba73e9184f75486d27dd0ea8cbe62255ddfa72118d5e924bf9e73dff4bcb852a56830f8f38f6d2c0a2be3348e6afe61f73bc4bfcffaf73107f19e0dfddb4f9f6bbfaeff55e811edcf4388acf6b2817f50ff1fe88537dfa23505f353bed7d8bd813ca23bdb7ee1ea15d323f7bbda6c103b5f248a7bf2f34bd731f81317902e9c5cac52cf4707941de1e6d93a09c6af5c875959d4ab6b605f1c2c80c377948f2272fcc21b9a4d94f651434d1c2683082aa7b82fb7f3112121d02627032084b3aa1bfbbb376a7348a393bad9885eef4b59b311cd71533990b9190cab7a2c4af4ccff49ce2ffe9be3661c1268fd1d449994f1b7fd19e95f9776f92a2dd2eca0b7af745f50aed3876c7142e748fecb449ab9d23fb5846746e57af3ba6fd119b1e391b603e11c6fafe34ecc936b7550db174756eb01975be6e81ce329569c4c38f3dff08b17ca3645b481bb7c63155ceb0174acbefdf08cbf1f60c4663ef10b53738d94c8dd40e33ce8b0f12e3557a7ea93dbd6b9a959700b0060b4d4b20a0d05378bacd2155bdee79ab732561a9eca53f0de02ae7d80f05db73edbdef47928d2e11e164d09524b09924371bb2e7597e571a6202d0f767e800be94e1181cbd2e438b812efefa0ff8192ffd303ce060b0191ed503b37e9d5f68ae9dc64c420eb7889ced575ad2cd9a200f35a3bac22537263eff8cb2bbc3ef1572f3afac4d6143204de57defcaff31268932ef6d06b2c968f784f8804be31df2cd0a0513d37d1e41e4408484556e574941785d12dbc44cc1243415caa2feee0209bc88ec9bb8f00320dd22fe2584ef7641f7bdc3caca872524407508af50d98172147fa9f336f4a6ede53aefe1210b7cdd76bcbbd3e52266b40680f1b2557ff922942c13b24af7c018df2df17468889530670b8ad4589569d909858bb3fc8907a11a38f1719b31e02e22d304beddad9e1ceaf74d5de1ffbc28d9b42675e035470928918393ad7fabf560336b1f5941ad6e3cc73c8de5434873e4c269c08ed4a92faaac0c211a8f718807d7a103485c02f8df044afd02543f5b7f2e3d10ed0076246731f133d8fc56a2aace1f7066e0dc55dd932f7da74917eba7ffe6608ce34bc0b831f3318ef9af0f0256af9c2926e4bf7371fa0e9cc888546f805ecbde07c72a81875175c8a18868fd2d70209da0227f00dcdbcaf382042c055cfef1a150730989ca4e2484648baff90154a7fd9efb7a6c1dc134aa4c1aae0a0c727578a6608e98e4c01c2657ccd7ba69542f73a7af2995fa00c0e7403e284a673827c39c79bdd5ffc5b5aed8498991601246ddfeb7d5b71da7287363450e863a86e0d85420f24ffc6d2a5f013534b5313caf17163a8161eedec4db557501c06d032977578fa51ad33fea36cfe4d117fe90a46fa43bbaa23c3ccbd860c7fce68109111ba16795bfebbef92392f68ff9d54ef35da436adeb4ac51991c0934c911782e284dc9bca8dffd932767996f47d201275967850f3c5e40c93a2677f8a26f55b7508a635b6c01ab9549996797bb8050a5ed43da8de7c5f13717994bccc3b60e93d3348e2b73581f65614d56fa387767246be059e8163d6dbe5a7fdfb39149cfece25f8dc1ca10430e61cec3f04eedb3edc9d43839c88e5993973517ac0c3b93cdd6e02f4bedd1492543776ff23cb3ddde5e927ff60099181a07f8b7db5ddcfc868bca748072565f467c1b8a531e41d38de9098c1a5bb2ae78819d75b8e83ecf7de570ea6318f274c8ed40a3ab0855cef2ab96dea14a67f364b0072c1145adf0be1e646d56319f82406655ebbc2ca9aaead1935321fb342ecfcb8fc9b456de22c4999e0a9c70210e71484ab132c444f0592a55e065facc1e2108f8a0487358582ed9bb7f866d2b9f8511138c82a7f9c88df731419a2793b27e7eacf9a4c826abd0539a2442012c765b534a9a32b7f5e0ae16f210e16f554c68949bd06da4f33cebb7c8b3f96b243968374915091caf014587de6e9a7db025f479fa432d2d10237f5b52a7054dff325a3475251f21a699bb36983bfd4a4c004816acbf36eb7fad9615d802bcb8e06a5f8e3e533da5487615a5a425ec96e197eeeb63bea74075a376186bda7805e79cf2afd4e43b819ccf2b8209b1639f4aac1bad944a4b18c2dfceb3eb160bb3c3d22d1e592452558ccca283aa000802d2877ace2f125c617514fa28a7a666c30148f1e27ff50cb361fc6fb505ba7103a709cafba49e060eaa4fd7a085579b0dd52166b6de20a534b46758037c6ce90372976157ed749370c6ff077d6e2eeada27ebd80b15017654a25d08e7c1e8a79cc5858e00b10545a4a221f76e6d8e633bc0be0c430b3db8c48ea6e2b8fd62c6fd1d23cd0260519730fd4fd4f6d7444dba1d0d90f5df91a9d5a34af90e55acfb9cc332731a93f9d197594dc398dc383ee26a3d4625991ed6fa7d94356323f1bfd6ff2b6c3b1fb0f25beb644823e2711c3356cdd15f116f57a827a9612fc8f049574f82045a7e6cff514fb6a9d21729d45bdb085f8304ed51316490ba8d56eb3e27baba7025c9666cf739f53f53ac8d65565d07bc13de23b059e9e23732623df51c15b348300372bd71659979d77fc3190725bf694d09cd3d4298db237f1f46a536f590e8be8a8234f1c3666ba9093b3e90c64bce21d1a5fd3dd98743f29c91ff15658510d1d60af06c63e7a257275310ae59a74d7549c6a37d27374728bd044c20c71fbbb66f26d5399c6fab3d1a6dcc95f2ae3d0ec9288a63fab219b704a5b61d7b11b8691bb9618d2c551054fe2d981509f5d65365e815c494db91406a9e09e8d35cef81ad609f86ca67f0005dc317118ae6d554b75b93e7ffad2c6b039b05c18999640d5f241d8b208f64af5807ff64fa21c67ed1bce720e63faf86cdc7d5f8f032aa80799a70321b0b34e00f04cbc42cdfe4ac294cdd56d183da055e33f89f10d83c197357b7ea09ef67fcdf86a8dcf2aae65f1d8d00c1710b21bfa963c207f04cad9cee16f9b181e827ec61b8dbbfcd724eadec53cd6b1fecfc393ee2eb43059a794f9590a4e6125f5901011fa5952e0b85ff601057cfd489dbe4876d2dcbe399921db35ef4b54b93b30b272d4731ab58e3ece0494efbeb78712afe1e9af3eed8d1f296af6db25a5d1d88647b3905d715a448886b9210fcfd54fd7f43d1072b38030b5f5835f8d1d68d18616b097879afeb69d26d098513de2711dc36d94f6f18630693119baaa62c58b47f885c80252aa9495eec17d92324d4330a5910cb1fbd92468c12d9cc7950044a17708be2c455a4d768144696d73be80705855ace5fe0b7743364b3c77d0c89e5a47107f34b7a723a721f6b7b008281b1fac2c3215866c5e6a828e1b82fdfe4d36121a930b299e30109c81b656c9b64b38ca528a20cdc4c0414056f8b2ba0e01adc1b4e3a6ea53fa0b3825f65ea424959247225fe84ba31b63f2577e82a31dbab4e5745db2fb6f54289ae6456a37fbf5cbb17980ce6673ccc3bca59a186ea433b740db1f2f1c282d1761ec86e891248cc3136399e1deff4e9cb4097662d285a33e424b9e046809c03c6ed74449a58250b4180e97795febafb632cbb4e6bc1e99493b741712e4b8aa7334a7420e64816ca1fc65c580c479d8ee4c1a25e4c91306b413c96c524d02724ece7c2c8a034b9cb0de51809cf62f497a2cda65ab2af5101fe6177b3748c8b9689b34d14ce533caf27bd46bed2ed5a6e159036f7af563325a58550c9e2ccd128ed367d7722e29140e6c58570f757e329e91ee273f164293c9a753bf21c95fa76e398edb03eba91bac136823ab4db038f6fe79d7833cea14e4fd1c50aa0b81e00b127d0a70b4ba2ea53c9d87500bfd0e2babc5ce6ec33129964a961abcbf1307e88a859a762ce9c5d5f491c31efc86e78decbbdffe48f577d674ec191ad356f08a0904cdc967c150dc735bb1a69623183abd689a267310897f63b79aacd38e657aa980f2041411573652b80a9f2509eb1d2327739ad7f6faaa4d038f2ec90b2533f3a0e3d815b7617af7ac1e01ea61365fb2ff3af47c81d8ce3a2688b463893c6fc853d2df52070a37998dd721b030c702160dd44c5b0164b89028e6526ebab24babef71cc5eb2a1db60791a99e5bde3f7823648fb9a0441e554d2a8fb09baf97107d97c14e6de22c1c94d0a9050f921f910e6b551a47ef0efe4c1d0e08e9b14192db587173e7dc271c09f57538da2e0e1e6de34f663c84b9857652b46f9a84305ab4a698d89979b5a69c9cdb17e1a2308e12468e6ec9fc3e25cf8058cf12497dd590bfc9cd032173ad76ae9fec0e253b4d14196c7561a33075a9627e758fbed304ab4f9664f0f0e54da98ac24415fae1fb915dfc5c4f36a2cf1a26a7a18cc06e0000feef5a4a04657471a6eeb3532c8366976289de294c257a79733a4379545ca2c93b306adb4cece2aab721779861a213922dcfb2ba4b783f697d869a231f1d5f99c5594be7889e0d4b30984b33881301e5efb2c4dbbbf0b9724d1d4960820b97b57efe88a2596b53d4967ad0944cebd720b112493b9bc1dcaa1b2760a0f601481b222f4b3481349f097aebac69ad7597eb55107959ec6e968b7705336886e5e059af78b96770ebe839fff6bf5c40c6cd15db3877691b517a4f116897236715eebb513a4e5f80d12974140654127dd0bc26b5141d1e9626f9b40a0a921a19c252519ee23721ca7eb8c3c638c779beeaba3503cedbbbbb11591df89f440b131c6b24657ebedc237518d971332c104d9a8f7014db58e34199b29783663e15c0cb9ef8055b27d56fe9d9acd2ece04870b0dbd47c5431b6728e2d3d4d8d22e148ced9c61e2e1b0d4994acb0b6d32b7ac29865ba62abab620df996fbfbe90b83d1e4b4797c7eebcd665b26ed944fedba1d08361032e35dbc9a9438e3193d3db92fb66da67dcbefb7de8f63476b746966e73b4de4068b1bbaff2faf37a064deac96849eb8c99e5b8315586c82833fa3f29bda388a6ea460dbfbd67995d30a6d652bc6e072e9cac0ef5e8108decc47114de0c26a667d0937d6d0b4ac73e94f6ffcf7db4da337b772577732550fe8e2d90cf63fb909b328576eb488b37f03e4f55eaf29e1ef386d56d2355ca9597735282ce9ca7d45c6633100e5274494291a067c68a76d1e090eb7bede3053f4fa3586cd8d53e10014fc8523a8cdc7386282d8953fed1d9a2a811c3598323a0452b21e56f4b09a1be0b0c09e0a8aaec5b945e38753a828f3ae56dc495ea2a2dbe7c050dadae26c2617ef088a533a0a3e983a9c8b6546926b8ca653ccecce52d3420eaffab6c0dddbd053687385c50901019741dcd6bad7cbbf10b097485fc85db7768a4642ca0f5570aaafb822f11ae571e4bffd78d78dda5354465d2c7443fe39f889a4bb36ebede63d72f53c34bd11930988b19c8a71ea3f470123619b2aad2c34f5581ea2ba6b1e696b1ae0d85831594bd86db464417dfd033981079edcfe48db4bd10ffc2a66563baf71657ece1e38c245edea438b41d45672d65e52ef13365d101876b5abc8b378e5bb2adea6c145e642d3088bf1d99676348b0df345140c87e6818497cf39c1a9019b3b1a87b1c19ac001c54955ff34affdae7c9ae09d6f5c5a85f3d0676a0f684786cfb746e33f98dcc5610509511c0c87f346c459cb9723eeaa1ac847b7c434a5ac73c2ed4ccd645fccffa06a62fd9b2f201b2d54ef24188d771148b1262dc3fecd858dec6d3986560a405f4bb8f60c1d71cfd6595835892d30ddbda5553b26097e18d1e36fd36d4190f66e95fc1b3b359e068c04d632e8c1d7908d77e73f8d242a38a3a9a2769b7b2cf308302b8c6f99750c19ff3591a42acbe4592ebe1836eba85c32c92947bbf053549c1574d2b9fdb296a8e4fc9cfc3fcf2016c7474db11fc9c7cfa7c6fb41377049a293f6611ff504d749ecd9f204e8d3e708fcc994a5b68ba03f7b91513eed173e9245d4111fd49b99fe81fa13d359dc675ffa9e55f503174f25380ef7e7da9930d8e67fe453515762686703df03f2d720198ba54d0a31e1199c240a9ff90842ed046471e0d6cd5b78fc4d7fc7443607f68069bf2be0af81d73cd9c519682d97dc35eea4152f39d59e4d4aec0994a106ed521beeafc1aeb5093b2af6ba39bd5ca32c104a9a27d30fef654c3c0051c6c2e163a9f4da21ca10405bda7a8934ca03ab998068899ededbdf070657465528f0b860d28e806948b138ee45471207a162e363daf5a66becb282eebd0428a7e720f09aebd6d9f986b106cf45a04d81423f59f65d5ebcb640ae34dc8b14d6007f5d3e559d592b3417d40825062ce83652a76317ae32ceebe6e50c783bdce4dd8e7e5c5996b85a2b38c59bd804655741aefeab5d36c13413260206a6e70a8d773ec09e87f22fd82cdb11996fc9b1459a80d96d30367b9be12c440ea1a41740313801793b0598d0a3efe17d3395e4bb6d8edbb3f06c4f923f1d90b394787314605771336b705e97898c325408e6b8b74b9fd4fd615ebf468b0f2e5780cc1265232dd5f3e938b49268fd1316a86932f1082ef29d8880a09547a74f12f4e2477f146910454be087b5699863c703f767318e5a5296d3f2c78abfab3f7dfae56c21cdff7d0009948bdd1851915ab2c8d901c2fb77a03e118a4d8c013be822d1b4e6b1bb6522d5f4e0f55744f7855394cb0695284882e0bd59e1e4a4e86bd181b19a1fa348e754a827670418835a4fe5d1fd781d7698fcfc70daa210f8e05293d65f48157f7bcdc6a2289f3aeaf08574b2786acb1731726af1f306f450636a57ff863a59a07fa52ef685d510de506026752fb97d172608dc67d93a7be7810711266e2ca422b5d5080105eb683cef9cd5fcb0eb49a8d3c598c7790c108df6da8b5b1182e0f38d6541836375d26ca7a2b73235b94c8ad0db56767336860dd75fc39a827c8c9bc5f040e25fa5bfe10cc88473eea965407d4118ba81878d99102e4904916f2c28d10397f829cbe255743b8f2a7cd9e6323d295bd82aeb4d03cd911ff1e938f73c5aa7cbd2bbdb06f35696008fe56172d0ba266ed355c698d50ffad89f393589f2659d531e16ba815522c6fcaa8b20f5ab503dd9e5d437ce711203980f1efa0b5b12bccd3987e81cff9b161ed1d266cb43b3505159cc5b6b6dd904df4a0f1b318e25bb63afdd744bfd569e3aeb734d7bd4265bd3f250e644184312adb498fae0b5169909c56ec89b4dedd715981b2156091bac23f2f644dabff949966438010a0aa58a4956b16c31a4da8ffea72608148c7ffdd8ae8fcfc11fd2916fdb58620ff244a4913c0456fc89026a50813dd0b88840dd05cf813fb99de0f9935c4b83a55c18ef063eeeaf6c41c6d502f1a9f9bf28955d0dea7beb8d705f3d5fdff23a0c9e3db98870039e672a1a8ee804d368908e0c8008cbeaf45a1fc2391b81e29247bd5a1869e8c82b1a39124d04d9766242980720c6f42a16b5484452934dd906ae19d9b2a408a5b5b107323c3b04cef26169f6cf18576eb58f9c821463d61c846972863afbdb700fb648c1fa8852de925fd07dc2c8f3294ed1249ac44af046d4a19aae3a68b03197a4a841ef8166c3d7cdfc56ebb517e9303a1670e55915bee3c7a7d3542f64fa9b55cc909f7b04c04bef622acb32d9d147027d23f7e14b56f4b719d45e6c958f44f1ee9c402278409420c9fa6d22d9847453c549aee40d544127e30009b12d749af02a9e5a3d82994ecc2cb178b0a9d136d1df22a6708ccc74b645d5de2df52c283115e2212b8b3b288853c67b456a0b29dfaaf3cc2ce409ee3292d455bb5d082b471ecc458181e3c6e44626cc7a9c3bce38bfd2004c4f88c77c8914c7afb4a646ab5188552d49dd71670f4b4ffb1112e289b80f4ee64236036bcacfe56dc66d48156ce1b5d07827f2e03fd1fa5c991e55be3bd4a0eb350799b6be3221906a57fc8026fb8d92356090c4175a5bf2c7516746fd6383e65b36ea34b5eefe9dd1fb3e91160c1fb6aebd61ab839da8d1b64fbf51687a99d393e4d03c5f763fec51d958a8e1d6b8395b64ff4dad526bfd69111e2bcf262a3ebee769bc113d6c912cee1cf498cc766ffedeed695c714f82716b64036b2666df6eff428335b24f7a624d55d7e5a144df2181fc03c848b0187dd71b0ee1faf44873d88ddd85b01e3e2112863e16872f6861ce2b9adff814bd2e24a13514243adb25e16cc494a8c911733fc26d4a737eb08891a7b98fba91876d2e2f011e162cac1602699aa6aa8620534871c94228f07a30f4f54aecaffa2ebc53e46031b776662432ec8d18b7771140297713bdb2fc4cdd90128b4284774c667e7084043a233412f346da8fabd06fd8657c436287fa6757b397590edbac597a6b7f87a0c4b39ca737ae69b75db8fcca8adaee06b1faf52c0416b353bf4d180be9edc3a1baef36d31914d8dfd1e656c074702879f0aa8b7c944d7bf93ce0f4694984e7416c60a84c2f812a260097e8f4ce060cff946b47eb1841a1ae2fb17288d3eea377c924495c8dc00a78c3b931e2da37a0f21382dad13539be24957d41dfd26575a2ca01420c640e39be8f410ef157a23dbf05188cbb14a982298afe706808f90f2a37b11924497993b77147e365c709125b8072c5558ef974918bf0a1a4df3ca9350a9083f424b8aa5467e6c32f44c76fadec44588a72f310a3891d2d6cf7b378dae00af82decd58e48f5865ddbc4651d571c3e3733c2cb949d73ea13b0199702343a47de88405b22b6a8404280cb3834bd09659324ef837d43829a4822da055eb271a8fc3d012ca4a9c3820c88dfca9d4705056b5c62d84970b822abeadcc0b88a2e575c5b07c478b955918795ef1e8cd8086166701f9cd4e2ab6bc9b236a59f4bf36cf289489ff0d0fbcc7623db839895f0337a2640e1607cbcebae8f7faf22a9740d2429b157f9ab003701dd6ad8eee2cbeeab86d4d49ac724836255c0002a57c2b8545bc300355039b4b4846bd2d1f80e11ba5ba97211c78ba8a76a5422d4ecf9125db50dc76a5a56c174eb0559626b810d15e0539c0b4245b70dbeb0e02134cb84ca889ac0c3fe9529503686175b157981d2bf76ac2bceeb8c0042d9148aeb763d174579c6a8b3219c6dde7cb2aff698f3a5f53a3ba528a0a3311375f4fd73a13e08301fb87074e6b459cd398139a8c1c2a012730ff82f5d442e55a128caf4581a16c8d180c848e13f88b715048b457b70ca026fd1b9ac7847cd967176d5c1b7cefb8cd136e170fe1239a2ac5507c7b3ed8ed7852595d3a33b6f536d561123740ac01dc1b61274472dd2aff8879ae8832c01013071401b737797beb3cf6117c09491b0e6626349c8879a9b22bc3643ebc2b8023b631e5b3a9544c8955cd530c9fb4f8d2f784d81a3554aaf9e4961c1541c744a3db224e2be01e4dec0fc6908fba4ed72e7d9da1c9f9ca558cc1f78e60fcfb97e4ca48ed5b92686797e31a4f2a22ad1c23f66ba9b8f1397bbaea572b877d4954d07d32f324eb615153a8d9a562953a20d27518446c84b4890de5782802a677befa730fdadf48b177fe7edc727914619b3d6aac593a86bb543e17b7d056cbfef5398874bbdb7c1234f9784bbc6b4beb1b72dcf451081e75f3c17c096c5ace93a3af4f898fd3fa1d555f3be260f90a4347ecdbbeb77004d187963ac6bd0889b0db1746a48290929892e5e5704db53dfdda5529b0fd2dfd9e9eb55df80c5bc389d67cbd2d03881fa322ea844ddafba39c9af5846e9d2042f6dd45c37f463e5c95eae501bd5f3fd0f4f1ac76865ae056647b07a5d0c8fe54bb98ea9e03d9588e1b80cb210566aab78f47e95052a1fe2736e1206d130e9d53861680a08d449afa8ad08656f193e74bd8a4d98adbb28e9d05f8a21ed0c0c67042d8078ec17f089feb7d5ae4f2fba406d692ab1e0223bbe05bbfc4610bcb6eaf3d8fd04118a2a6023a232e937beb1301eb6aa9ad60a5c4596eb0a7f240240d862d9886fca145d5aab39c1c786766be6e6639132dfdb381a4f20412c2e3f8f6c02a7f3fc2ea89ccaec23c6da518eafcc02fd63a531f0d5db109faa4625b5516b62d4129fd27b9f78b4cebcf3b80c8b083041c879ae6cd25c2ce169e8971d4644de5aa3009a1b6eaf442b0be8eff11a6617dcb7689147cf342508a4a50d3c0e614e133edecef3f0e610d5096b797430aae1a612d89ee809a3def6808e011aa190be9e30b22cc8ae1f8f4cdd1bd779cdd36ed7224120d05e0f4721b2b885f021ed55e717ad12f3e99ee4aa3e7080c358b25e35c4097638ef3f79968d8b322bf1c26ffa383b58f55ac132e50e9a7def3016bee2cb3a90fa5320d5930aadca528a60dc06caa17ba6ff36b3221f8b39603ef28b0f2fa271bed39a226eaaf4a558996ce87c42edf39a7112a2d184609e8c5e91b3276c738dbd4db86e878f7e54fa982edac973a1def1617084a38ef77992338c4bc80751c130a30987427c66cd66ef3782fd8d82c5f0fdd080fcaffdbe24767b6ef2b9ee7f853479aae62f767ef38750175928c831a1c0277df78bd0d6e952e45e2696e409467ad2c9622fc8274c2ce81e8e2ba976a54e868d35461bc65d36c043c3845c6071a9e729015473e2dbc210632a19b1067c7988650fbaddc6377ff2705d2c3e0013feedb13e2d00877c4d82d22532dd5873503564dc38d798d9f8ed3c1f1548539db6650c0835cea529ec8d522a025a55ff752bcf4223d4d96b62c9e9bc5359d5e6903984c1a082863338dfd3840129079777b4041a9e2c14f383bbbc5535f410057f4769b0ee5a125586c617980e9383ed4c860ac994ea7e814fb917dd85a74ee8041cd03369384f4c2ab944cbf7f5d39751cbde6fb40fa529a07c582f24465e4c97d43dbb299625b438f788da78718664fd0269e930e49f34ad2449db6a9fcce42e226d56143046e78c30f243af184606a2be4432fac4170560d618259677f14dcd0ac85e08e81c095e7977aac6b4d05ca93201d82dc42c55c55578166f22b190b0efe3f5c18daad7bccb8b5da285089c9a22ee94f98bc210852490ad464d63bdc2537dca3737e155bab12e73d2b3a053aa9fc9d205becedd85111f98299d3e55dd22310c83cb7ce7056bac69a9c4d6ee11fe567581c3fcab36486b5e3dbe5a2bffcc7bc70fc880fd311dc8fd59305fbca1894726aa8271e6853c33557be65d2fa1dd29c3a22ad658356363beb786e1d10ea62ce2a48962559953c070f5fdd093d5a0d6e25e2b21b1f9a84a593e8c33d51e91f2e30fe6420104f78e128baf26474f0a3659da600595c017289324f9eac24d60e8b18f2916488a189306a0c3b24500e0e16e8de055f79087abe959e136882bf9d0812321a8a38ae64700c5129001acb73737bf0485d7e8d1d9dd5304c663ab658d7de4b4b11da1b858884866de30fd0fc867a62f8082367aa541cf19bc1378a11f6a0381aee01bd501973bd96a4f34ba3ad43339daf39915085a200b47115f25e5fcaa782a3e9f95531cadd973feec4ddcf080e6130b511175e3206d331f742e099c4d4ddf4f1fa9267e605133c66d30926fd8f65a62b5eea29c70026c941171c0706a6d05c6bd288f4abc0e8295d9004fd3f951a6e05ff58031166478c59d4945b25eff5d70a681f405215921858dbfccde24289bc3e6c397662337b351c30a403af3bdd4ef541735fef5c8d30f48d51ceeb4adade35465db2094df493ab773f4ca6832a0725409de911dafa44c01a03ead4d51544e9ec4f1f1ededb575ff6c4e695e808444d36556959970434894c9daf6b68cc1d89b5e0fc239953fb53b6cf7bc5a6d57a7fe08d228b5a39bd03e0f7564d9a6937da5c922adfa6e244dfe5b0d5c7b4fc74564c9a6fa736d350771ae371e83e4e3b07634b43040f550964e4ddb6d9a6e7e6272ca6d83d905ca96ba529ba45591941501bd9ed76ce39258539d048791ace8893470c021dfc54cbdeca8e948cdccdf9c46628311fb5444f9557bf51c35bf2be1cbee29b6e5ccc30374bbc6939939203a4d3297b24cc7918254d32c060fbe376c9afbd5dc5ab7bcfebf5f2c320a223411e3ce2ea6d0ea9199419439ada41c3d07d3f8fe9ca33911bfcb7a947602efaa7eb570763bd0c76bfd052c56bfcff11728415f4a9466291fbd9468a13c1679029924beec8fc61f566692829ef6de8ed9bd0d5d12214b8014625491724acef03246385986373f25947c9dd83fbd4f984b9e7530c7a0a7c352f5fda42fe892519f489ba187c73acf64b6d26a25bf270eed6f155af98acbf755e4ca7a236fb8df205ea8629887067d3d4a2115e61b48efbcb1e94f589213767bffd3523e347722db446ff90316a5f36d68eabf017c2abec584d52d796a311d5d70a3e57408cae910fa59ae59a75686d700c8929c4a7220ef430ae07a3fed241072881f78e642033c7bb15c05691459e0b68468a7476c165a08d26201d5e55935737924f41ad99d710d8b884f78cc7c86fe96ff4fb74e492bc9b1675c11eae7f4e19f2f41c202af11ef0167da50a2ddf69c0b5f25834daf8507a16bfdcf853cb403b95dea28dff07cfa2afd75c3531571f746df1bb680ae2b6bf295125bdb2307becc136492d62665d275aaa0202f2647e2ae906d3e1632f8e3ea2790d4f1410510c3e2e01fee0c55bfb696bb9f800aabeaa63c48239f8aacbbadb95b4747039e6fd22fbf46b64ccfeac978a4a8905c228b423bc407a99e0cb5f42b6ac7a24886c8cc5dea45c7432999ee6a3c371bbb84259098cd88485d9df65108edca5044d695789d1356110c66cf78271e6a1d7cccec9c787f6e3d2c7c92cec516bb593811c84fc3919b711c5dd9dfda2528003eeac5b498c57d6fea3cb1a54fb204f0028b29137e52432329036c9d71dd26476a77ab84a3142aca0b451070fc0fff301cd84685185082c021944008db6cb13add8af4abf1079fc153fdf02a20651b3266f3bc313455870bfc64171738e603033a50904c4af5d4bd13d4582fac6ff663f9c35ddb5c19fd0e296566d00068a39273d8329b16efafda27cca0c44bd3930ea1334b45ec70d1b05bcb254d10d75707005dc9c21e76898ce55f57f65e555fd21325195263650d7c4f3d286f1f29b410f355c6680dcae0825b4e9c04805373201899fa93ed74a7523d38d37dca1a336a8fd462263fc58d6e989d754dd8306cef33104ad6e9b4fcda27d8478431d9ffa71ba283125679feb609b5628dedb8d77bb0e0587772ebac8bd32718384668e9205662af2364e1aed0c8c3e3527798acb9f91e29472714eccc33c6947130928e392bc3553d57c894b5b7763eb2b46c7e97b62d00080e66e2d42db271f107fcf5fdfd903021bd4e632df6b77513c50cd9e03bb771f4dff81578b606ba6599918f0a069f81e3878a151c262cbba21a0d3f8797ea291fa090f2665ba2cbf7994534e73abf8a8ca691c128ef20625fc06526a0ab68962011e000075af80e5006ed7ca1a0c059d40cab3580cee33e40717439c4166ba4226c2b069a877e4412559c892066ef7a860a9b353514bd6bd563208537de55041308419c160862251e725f94f01bb9a6d4176d42042518cfe8d15794a41772de60261b729919064051f169c015a56d2ed088a7d2c4172b6b5b348f5601a7ca611712c61fa8ed6605c359cd5e2cd0bd2d2371ea00f52c799b29f39b502a2e2d467eab89c051d3d0e51476f236c3bc6e069cfeb6198a7f98bcc5e4f3a262797be769013f603e4fc43ee198c9de15e13e9b99030a20c6f74f0c713ef4c43541a6680304542071d66a4e8fbb1ea21574e6545babe9a3da7eaa8e37b7c6aa0fe99ea8e19474db5cbe722ae4c4be9ca61afacbdd99aa4734e0b687115e86cf6a5974b706567ccbb7bae152b5623a42ab919fe79680d191c4a112351efda4dfc9bce89053fe3ed63ed98a657f1b9cd5d148079f9866d4c9e489c020e8ba5efa16ae36f9bb701393eecc1f6318ba58f6da904d3a01e8c81548ce51e17d07e2750e18e447b1909c3fa07aabe6842ba48d2dd809713f7b19e5fc180c5dea9e71136223f7c667ba3c8c29ebf9c408d309365871c4c3b79132d3de67e1d98d38c5bf282fb9e094f2d71e2cbd76494481d6bb88c2fccb4cb17f51300160a45f473f477a059b7611565e2265ddfc23f01f065d6a5d448994d093b8a23106e4f3a7fe40bb3929d7d98b8274f035edc414cfc8c9fd44a278d6637d8c6c07a5ae3bf0cc28f90310df568ccada44155acbe9666227451686592d2a2a3ab94c2a5deee138f34ea7be95b9c596d49cdd2cf542650cc490b13506a12488997cd4b16d804e89a07f7ac644addf6f3ed995a0bf3a5e99caca9e43a49fc07ffe7ede361f670f24196771a1ea55fb5c47da70e2223b3250f99142d07e332418fa95f4f2fec479140214d11046723332db1af561223da951e968541dc9b3554c397d89d17852db536e5fe3ffa6293c524fb67fcd0d99bac759a6da8c418e5dad46eb921053c8a68105b9e9ccd491d244ef512583fecdf526444dcec47de39a03628281ac402135b9ad660be36a0afe9133b78552cdf316d2645d54fd4906ea7a544162c35603a645101b4a2adf829b6cedfd9ba76bbd943f8738f6eeff2b3f403f42dae63cd4f29bc3bc3ebba1ab29afeb2945ce62dbceeef97d870c3e98bfb2beb4df372fe2d4b869815278de6ca4e8e37f02d9d4cfee8f8768fa2dc59364923932cddca107ea5e8f420344167fe3ecb8d54cff9bd77aed8e3b326120721f77a84264d7af38db4004fcc020c8493a5e4a16debf969c543afe1a6fdbea496f2c0b564ff545c7eddd356dbc126170b93c05b91575e58a2fa36c685c1d32905dd43488dacf999879e357c40f56719db650e7d2330c9442e29dd22fece488d1defa45ed45b110bd17a9df31ce7ef8e0d66c449ee607458d507abdcffc9cd3df6d041d9fc472405d1a920b49494479e1457ad91e4ddaedd7b9c4f262ba6d84f95eb66369278f2e7a98796b8d1d59093f0c673de343b33decbe058e97f99d68030a9c056b70150968cbaa6c9d760f0b6acdd13410a85c8b41940f1a4124466f193c93e4174d227e555743fb2578d113c1d38396ddd736d06aeffbf3ea4307812cf1f5c25b213e3d175af43d6128d918ac8431bf58a720ad0879f252dbb28ae514b4bc07d0700d27e81b5c4cf8d3cfb7f6334932b2712c26bd92d31fcce87edab6353089457545b0f09ee912f4f7386172604483ea5b3b0d70c71832b4818ab13fb0eb83658d8a25306ff6044faadd0933c26768ae6b5b3f9c3dac21da0ee26ee1fae6e3224d2669975cf046168fa95a8b032c7334b86d83900400cc97780582ddc8d124178ee25892bec1b6ac5f81e131389b0e017ec2f67e895616e205149d32cb708ca7297973d6ba02055cb2f56cda6d0813bf242df2c3d526fcbe2928eb297b377a0a092384c1bcc70a2608e7fe19d64b3808ee176f580d5198d13801fa962754c64940eeb9e942acd0214d720aa465e3c8882a8d6ede17597afdcac74c650d6aebb4d19c0df05443dec751098f67c8464015e5beb5be819c6e5dc2eb68a9c35d17cb40da06df6577804ebdd1d23eafc049146d22124ae62e712760387589b3a4d36380f87d70774c209314b2078ca91fdfcdb6d5cd0bf029c0b9bd366d197de7e3865963219d1c6bc0a1059951b3dcb684273112d9d1e71d2539f916ff453e113049e73994a14c7cfaccf00d302f67b5bc5b6cc6ec4204bcb33ef2758b63502516b188e320e6d589567a4b1b0b8c89c619cc6764efa2689443c29dcb0dea35d2f74d0b033fdc489774c9b579b419a8001a5ab87975f06628295a66d39164f1937c617a820ac196e82eae3369f138daa08b5f03981618693600e4f77072b67b16f3d82aaae167ca8a6b3a509f95877510d9116137942e1f99bcf72df40f9056064d356ac4d9b66a3dcc98bb9d8e7564beb4af1e9968b710c8442862a14bd925b9d16657297e3606e16112ed22b7caabbc0ac598c98ddb0b3456bbb6d361689c76ed0ebd8cefca80786cd77758730353d75d54faf9c1300cca07242c7e16e67ad1d7c02fd782bbcf741e74b1ab7da849a406c4564b5b7448f315eb705e5996528185da33ceecb311265fa1f58dc2abab4ee4b02385613322163267e9b32613c1c1e30641844060e9904c7f46fdcee3e85053c8c74ff1b692229f315dfd846c26582ea08294c79d8f9afdfcaac6f8f841437dda2b6f8ac957c4763cd42edfab8f647ef1e5be1545cf4877b5dc748cebc178d91ebcd88739827773895aee997f34556b010bd94a657907bcad0f852d9b9895a26a2cb57229c94f071366e60c4426481e8f287ec1ed21462eeb28b5e32e6d8cc8419d0d70504655529d952e599e8b8d8b9aad7fd2aead61f99532ab9661cf5f30a7e66bb5568778a99616f7041444bab3fd88dfcff84d52a5188498b9f32c61e14c5087528c9ea31279fdcbf79e0b321c701551b96de2e290b970ef85cdbb143d3173c6ec4a34d6835ceeac7293f9b24e3ba6840d269c9bbab50072f635d03f72693f37b141af5780f9015ec26abcfe5d1e78cd2f42a45d5d11c474dc8aeab113dd77d41f2d60a7e0268fc7424f4f9bc56c449cd69432642a6caf601fe9a173b9525a1019c905d5a7e58c3d3ecc1270d03670a963529a14edb524341757e19ef624e498a21f86876e5633bcbdd7a143c4bf98e619f8c6de4888a29db3338604c1901fa52e34b9ee6a2047b2e8f7c04fd44ef85f61b83c4356f21580553d79cb28ec9fd4f5536c7ae84b198dd6f889e6924ca1bc331e6a9f778c4f0e56154e4a201799cfeabc657a86d2a29ce5e5c78779ce6c1df7aa83e11e06c0ee6701e67d3c61ea742f3768d9cb665922685600f90687b63f7a40bd4be47975442c4aac609aef077e1b6a6185d6a0a0188b09f01d964ccdc9fec864dfe9a1cd5527fac11eb58d89e177d7b00a9431e1195eec24bd65588531df3e496021fb40d518c5a2557e7f335c4961caf9990bb76d74f16f5aaeca2dfce760eaa6f6bb751ba517d2b9ca0a9a44791067a24388588ce5edcd8de5a086ad94e412cc923bdc3e197d8d6d9c1b041d418cab221ba18a67a4397e49098ec3e979dedfccd450433a8369c6b7b4e38723618f81467517aaf960e725ff6e66b9d49cfae6ede33dda381e1af2c0dc20ef850f62e64926e79bcfb9ffb0ce33d06777bd0e4654cf9d24cc60b87616feacaa9058fd903677d8b6f1beaef6ac571d5cb46419edea5f425da4759f64fcb1f901478405f716c477b47e49dce5a364dd347aa684e190f1ac6f0d900de0afb56f246061e6c0a17bfdbe9e30b9a791fd95474833bafe3af8ac8fd3c5ef147902e3a1a9f46f067d9095ce09a28b871294adeb08ef240d6c1c633d1f1687b2671b558591e02d44a05fc51e93904ad74e8c811b93d4332365487cd5bb4dde76fa2580cc9a589ebc826fda0c4493fb0a2b24c420f952d4fcc9d0e5ce45cc4a5f5311a1338557ede425dd79865c03f6de19a4d2ec6c06054fddc69e6c38437596367b43754bd85cf239536f5bff1c4e3db6c103368fdc78a87fddb28fc341f794de341f19b6239f3f4d82219bb2938a14bc3255bed6000a102d626f77f91703274b04a80f1808bd081b40d1aae718ca8e1647c4d41e9c58463d70e8747ddd7e88f14e9f3eea0023203c21b357c4ceed90f8d9dfc1cc2faad062bff0ca7aba47480e3fb0a57e0e034468fa98576dde97525ad361f6b01a9c843c9c9e9c9859bf81a3ca3f1502abc03de2d2602bda041f8ccf03e5c11812b0f9d8fbabb441fab99e8853c0f2461bb3449005bfe69bce1b85534769297164b611e3f663fa9e56ce152f4e00a707c0a1d5b5d8fc5b9a04b6c6ec35a7bbb625da010d2535dd43c8c69349f00eab645cd934873493b0e5eb58c12998319f07bcdc739657e53eb19b38aef7ea03fe008fc86d091bba02a90d04d5f90593da6e6a5eb03d7b4887501a152b8dbfde627b2e4576821f3567d716a55dc7dcd7b9eec5476f93caed81c55ecb9d3110776682c146e6bdd65a1a1d07991969adad54f298a76cf826ef568998b44cc76e6d824fad0f48898d146013a108bf35d8c38ee93b6323d511860ea2b91d799372a422e44d4dce7cf56bd5c64ede1d769560120865e1d51df956a125f29d6d761649a301164d4fb7a9a32243507737c13a863990e17d31a24525fde892a1c9254071b7fbe6e72a0fc01119426c47018679345c07f12ae3564fa73414f950ba1a3fab443c5aa3d9f0d5be608a9bde0cc01721d9e98a3ef9eaa6cc53874d5a70e2ed34e3b05ef537d58c7253a965a5f0994916522cb2f2566864f12ef4ecd8a64dadb145eba70c7e4f49a9bdbbef6ba79cff46e695c33b5f9594d41f5f4085b702ed01a47f07515f47d31671a1e5f9dff964c31eca966db89863d8cfac00ddd0d0765c4872e0963d4499c7a95b9aaba2063c5126ce025e343f830b2f45c0d4d077871df40107183feafb3fa07a8d0eb02cb3d449063b82f1775868cd67ce3da53b732245b2b957f207e54a0a1d0d4022246b4f348ff059e2d4f47a7e94c6f4224e6e6ce8802d7d46562da14ce0d4607f29a99b6808d0a7bd152a0d20f7e57a16140a293ec529742186fe7020ee531e5e5937ffdf2c4824f3c52fb7b9a75494a4eb27cf066cbd23bab2944b8a47073ef6b8ecdaddf3557a663b2613fdd5490f12804bf2f5606828d0c9e78b18e7b90f810d16786f0fc485c9ceab582d832188ec8f5d8610a31eb38b179313a3160a5521d41b32e8e90a3af24e59ef0fd4e6929988610f21c624c9e88b17627bfb55c209e01eca163ae316f7930a8d3a85dc7624a2dbf5c22bbc9950542804073aa95d840fb5928cb839faa25d88083f6ea7cc5e2bcf03041ceb4967918f6f61feab70979c49cdab160e7bff43e821bf422254f2f71d6001d8e8ec6dc54a9876a11ac672af91fd84827aeb6986f56db97a7c44051641515a7791c49615eb09688c359785a5d89db7f22c420493eb6748ee4a3181ae366c5257da2695eb9a097a85c554c22cd43140bb00b477ac5c8a3d00d2e12e2607b47f2c5bc5aa02e66eb0d57a3a5106452e3728716dd0d65b0c3d5d766d8307f8f1963159530fa6597c3d3389aa9e94e7774265c355fe0fb36be0efd16d23db3720040723645064c8d238fbc28d198c59daf5a4ed9cc96524a5da2bce4fb54d4770e0aa2f118bbf27af3c5f24dca43ddfd6b9d33e846f9d0e3772dd4ca4fa78de3d08cf660f40174ec417e98b2b3e87247336558aaf87a36d8f371e4e6f8dd8f8b7c6895f6d07d48fdf30b5ccf1218bb5e118f4c6f9b948100f10321e7dcfd601007431c623390945f2a6f8bd4768498dade2716926c7c9db8d0686261eef87ff17a8fcd2a37864e444ad8653614b3410cc733fb57c1f7a15d66074cd97276c37f5a6b0a8922187808aafa326d9b238c1c54983c8a5ea2dd516a33b221977ebefe3dfabb853706a09b574f676181e43e0d9d9ccb064c4b4f9aa174ac628f59c31cff254fc5fd65d29c6f1f9cd415e3b6c51165986fb8abec964ea5ef237ff95dfdd2125eabe6557f2a9b6510f0872ace7c7399209442e52a409254760e0e8cb3182cc67c4d23f27b48a786c2ecfc1dd9657fdbc8b4f052c1191de4c261f1bc722baf36461172483aff039081e21cc6f8cca2354c564c06511a1089f0d48871fbba512bf9b736aa65da4302c0e19eb68f3c025b06553390d76b7e69d14f365fcc671a45df56282fe330c9d5eedf356d9beece2a68e25f6d3881e37b4eb2e5067620d4b830d7428284d3fa46257d3d1619979ecac2283162138d7b4dd84dd95a18bc44753901c000ad8a9cdec2be2351ffe1b6165ec0a5dbdf8852bfa66fd697f09f9095d8d497d2e0ab49842a1751b28fac2ea4661df689886abffb77fd9c3998cc4f7429f5d812d122fa621b1db0eb0edbc52955f8ebff475b1cebad29378304d9d578f3869b0ce43e6a3d77e64dd4048c93575be393feaa678fd0865207a3aaf67881a871262edb37626d7325889195d506eb1ce8af1b20b9829d9b639959078920311a290f53c2463816336dbe7389722d43bd3287eeac7918396d37cd1600571549b0a58a5271de0f76b92a8fb752890d7f4dbd0854f9533e1d9711ecab49b31a8d41581ee9c50b04dc185ffea678f6233c8da827507a20196d1b76a0fcffbd92989c3846cdc8a346dc65e460c9f8761dc805f5b650a3273b4dcdde41392e7d7caece9ce8995b27bd2989c00ccaf2b67facbeb8b3bf1c2775559118dc44111f9834ffdacda891983ac1faa7713dc5128c0fddffc2f429fdc135f875ccc60291e6452b415dc89b39892b2d0558ddc7fe66724d123ed9c4cdc37f56a6c5bc48580d441d0333ccd62c63278dc0281015dd3fcfe73831fdf7395e661705b034eb0a5e6bee0ebebef7b76686271fd4ae8cd4c998b381e682fb180f84cb29650c13662b795c45214fa63c8ff2fb4989599924720a633c4973497415ecb25bf56f5d1a45ae52bf82388d6585305c5493473b7c0156fab94a538125a4be73fea6c7d004df8c838fa020629b76bce8b073b98dc3aff8355a1183b6ac8190fe8cb16fd88d6ec7da71e66bd88f3c40f15d9b3285b188296a022ed8f5bc1b51e06e55c77fdba838a3de185e944fbf5611fd0dc1a35804d487ec67ba018e2a74dc7b54991ca9ac9c59eb2b7b29471f4448700e3807797a825db935c222faf636f88b7e30567f479cb6aad1bbba0bba570e6a6bb8b0d32766bc5eee95c15b562167313ffa008bf550d13ce2341d493b8f6a9b5ece43b60d0173a15b66ad6f037aeafd84a0cab64d223a91467fc18492933bff12c2364183aee946c5344e018f7d3f9f698dcabf8069cd267551e6f9b935b12cf126eb422caf921519ff4296c0cd33efb9e13b6eadf6f97c21739e7ebe6c0bc6582bc3eb6fc84a2bda8571bf6189842af6f5855331402b0fea65555b40e8bba9999dc9538c8f1dc5b45ddccb0d7a4355a4c3991fc76f8155ea6fa8c1879c5fa786bcf59c7652bf7609f163181ee04d0522e277a3ec90c7ca2e923c41ed26e2ed3cff1098bdc038fbb6e608ffd3a14b336f9a1889bb837c6e9f53f5e69ac12c8ce1d3e731271dff501f3c15d82f200970bf6b2d4572e3fe0df999369280346e5a5cba4a2f14900a96275f912fa494c61daba8b8a41020d0baf3213a29aaae5af2a13152e047feff9b7cd9f198dc03316421fb588d4d25821d554907f4afe4c7f521e8d3affcdacd5b50ee1def7ce65d41cbc8e241083b3cd68caf28981e61e5a2a451b41531a48d56f1b36dd57cdd5f15d5ca832173e4f44f1ce218276c8ef2a8e45cc2e3e38bf5b6285e180552fae3983134176521e000aab51052d4dc12e633ea4523fb5510c61a1b77da8768123f52560584496434e530d4b705462908b579a8f6b51cb32d9d92cd9399838348e70271377cb46ade53630d40be8b312b3a325491f408cfffbc373d6da76f9b857e05c37dc0bb19626febde8b45a638219d2808f32626030ff5a9245d4bad2ef38e9f0f59517938108b2fd70be3a71ba272215535b3197a2307e2a7729af26e0f60a544f3b7133fa05ed0e764ae630fcbcd46c385ac608d498d3acfe3bd368f27f7c63244dbc85b379730c88bfad57445ee6265be97a9b89c7b2f3faf8fb6820cac7d7ac9c5a5ed5f9c57351516910c49170d9b20233d717a33073131de762bfadefc9dc2923ea29af92344d4707109d8d69ccb461386788d0081ef24ef40cdf76ba6230b7b8d9a158bcc427e3baca1bc5573648a2c4f76e22bdf6e7617a580cb8ff7d0a68fd31b8806997c876e9c44a3687030defe6e49652e00d9541cb4df5cb80144c4835a5eb95ac4a3950028c702a48592c91599bbb484332c30f78ab3590fc4156cdc0ca88c3c273b6585c5daab492426aa416603d4c84303e977f99c632d885e4369b692cf385ca579a3e0a63466c099f588218c6cb9aa467a5a92950f12ad78c044c1735ec417a5969f91eb4417f655e0c1783928b76e7e64d38ca1f0bb86877991aec8721c7c1e6aaa487264fe2e8b1d8abf942255aa04847dd939122719142d4fa0b70c2d8fec4bf187b6b55660f0df3d7e11712cf7e4e22d6b59acb0cba2a00cc05dc8a0e64502675afc55d3b822295f4a774d53989052e4318924142005505206f02148b38e587803da085e8b779bb62f519d967f4c6b230a3a11dcfb9e67161b2e704b268528dba92bf9dfe3cf012e518c162c932cb2280f65667a1dc25fd1c763caca06f88b4170c8dd5fadf9b58d7c206e6054d7b06a02fc2818289d4871d477d64f68e53436d9da58d9cb98023989905928db47d7715a7677af115c076d0bb867ba3d6f38a7c17dda9c70850c6221326e1fb6895acedabe597a2ce9dcf623a6cf781b6e092b2e5159d3b24a49989391796f551eccc294efd6fe442b46b2205eb76ec726fe4d8e3fbc85470aeaa073970c910276cb8e13787d685274aed2bf366a28788e062b5265136bea227969df2705a774a1f77bb113277d8585924dbfd5a2af3c92517a5448baac42c45b629f210a9412b3eecfc2b550b24734266d876314590b50931411b82f7393ba81cc71a696377607d55558cc78fdea248d03cec3a2f32ab4899f45ddc2a2de2bbbba3fd50c5ca21cb02f3150d3763f591a38ab4f0213ef925171b3c26ef4789afb793c115510afa84555ac556981fd51074104883a1a56f0fe24daa10012603ff70dcbe42d8b57045f0e1094568bc00a4b01c240196d4c48832ec9f40eaf2128057636aa1e4038389b339ab723c32df044029f688e1f52258e9d26f337bd914d43b5064b571dfefc50dac1f128a12b9274ef5156d16529c9d87b602babef3f1b2928daee06ff0d1ae6a6af00512251d8907331e1347af719ec3accf2a0b1f12417bbaeb399201922c89526f0ee17161489fe7c5465e1b540ddcd58b7bf3afab099ff821a5e171ed6cb9d955e851a7624120367c61100660adc593bb45ffb27c640b173638b1ffd651cff87c9bdda3655b946d2b711f8d462b2c57ca4b18e6293a1a070044a43a57c4982401c1e3ea49bf7abe8d2971fefd834f658230c8a2bb6c01b02539f62c587e98ac45c3113cd3a949b07bb9865517c02f7560489095b56a5f4fc72c6ac683532bb707b2d680945fb0cf7198d578c303083f776b02c77161ae2585caa66b320c00a112775f874b3c73237e8e05de034a7d551db06362cf351acf320e43ff8b60b0b9415526c8e41afe280ccb2266ecaad05fb267e8c3fc45b97e21d44d8791ac730f44223ba48a37860d7d776193a9eba9e026388c9b6e8778ea407d85eb6aa32a7b28228051f4792fd73a2b862a8f47751cc2fd36b08a5a16cf1d109d80a4ed677865b3a98f6ff5035a3be231a11fc5553b7b819eb11f27e46a8703e6d4c79389478781329a2a96de618ea9c88452252fa053e8538fd4f92285e3a0206168ce11b91a56f6d1cf334254f36cf244a1082bdad827bca28a73db10db60015d26ab7d3e055d2d1aded362c90ab3d6cae94cc3e38cfdf3e15904e47c41e8f9f71618cb4a0c60bf0136ccf30ba8b185b5c4936c54525b4f90d153c6de495761009c589af58e42f38467eb489723573cb96e472545dbaafa3c79d4553bcf50bc6a4b94c528922edb7bf7e4ad01a23f84f8b2491c458f97427554fbdc6b5593c880d6bf9ce6d3529be0b6ee5835ba22efa32c9100bed3b2088e678965037eac058456250be402745cf63557dadd10a628276112d0d795dd8cf6ccf996840cb7943eb9355878cfcd8b20ba551d7844ffbf776e5da8087f7f07e3b983a8a0ff87998f5b6df3183631d31ec68237b11e106ab6408aed7b8bc80fa69b68ae5900197cf41ecd8a5041656ffdf56b4de4208d58195815401f0775580492745365b2d179c407cb9eef82e2f0b7f583ecdf919fad7486cd24a23b82c8676b035180e4591c84580dc506ce33012398497cbb947d43065c52e66ed3380b3293ba3974417a6474cb669b4eb981925d2892078597375d34f5f46eaccc508116bbdf36ceeddeacd679c210b48e8f47f1f899592566d27fb6c283cd17bbc8bbe2582d95d99e16c7e0f79c8d25ff5ea798091f0063893000bb3155580650797a8aa6dbbf0e88e097fee8156e6f69c0ffc594ce57e80f5fe416b825a3cb03a8a755d59b821172039cb1a1dc76ff19fddddf6c3d58f011bf7f3d2011a0bfbfac658bed25b103465e4afd182c35fd649d1ba3f61d853d9b5b4037afedf033bc2f2e96925aef742b4bf92b70042bc47188d4c003eb34c3d70c1fb52b09bf5c14f51996a3e432c7057a9d5c5ff34fded566e181b164e327a946638d4a67b586636ea89fec3d5695f7e00b989f6bdeb3c9b30aba4406ee9f8b1d4cb49b1bd13fbb40734e30a7c843016e7e416fdfe34575671423918f6780097960b78cdfeda3288c338938b6e62bca3ce5b8dfab896a46898fd8df26adc89bebc85d2d94e9e27b2ecb10c244d216b7e5721569b22260ee45b2d527f18248aaaa440bc7904aee3f6ed05c47f0b94a1b87e460d897afcc6b4ad1960a7ceb0c965209c5be064021d2fe2ee8ab43bf30dcdf5d2a94fd3326a1df2c53178e332959e8eccc933a1cafb16064fa17d7473350c66e49c08bc6e3fb4325943c6e6143dc71bec38dc536dd1c6b84b2ede9f320bfb5c207af2cb7c1ee2c035cbc53f09682c37ad2b497f4ae0e9fda35fd10d79ac1f62382229fe0855ad6f06007ff401c37940f8afa877cc7061cc1c60f421eaa677f2bd17dfd2900a8c1589911eb425463c26907a35cc0bce8e5c71cbde13a1c40ccaae835e7e1bdc407ed027fbabf6636949792411b86f902c730b44551951e378e4f02c53c178fc65cda0be193400ca2d0f2e6cf0da5e36be1d58f9eff00032d0f4f613c470b4d0fe2961cda6fa607e053648bddc1c7891f9a4b5e1d1e47b669a8070711d416475068bdb9a7cda03610103a987c641eeeef798eab1a87b509118185844f494c23851a3dab557ed6ddb2b58ba1dc02e1fa6f2df6ac051321cd0ef17fa92b8dd1b2e498024472fcfe08faaa77f7f5857557887e9a7fb066deae7bb81111d46df1be30a96e8c0cfff99c782dc13f61bb739028d10fb1e50c0556e403e0bb689961ae714221c2bcf48bac4c3848fb881500126bc3d6bf5ad8f79405c42e09c84347e7affe34bbd3c0750c2a95aab76d4c9b9dd6268d3b44eec4708f32670ec5b0042a32111bd8a3c0d18c8f2209246f8d78069f138bcba6e5bf82e4a4d947464d23e1c0418c0af321d17c958f5cf33daecc60cdabd72dfa1e5fa0d8c6252cb54edc84e4cc96637c458a85e8f08c16528cacb4ab9c4207b0fe5978dff12076b243a87894f8db68030361cc0df829e480c9955b025ca6607f2e5ef8aac1f23aec636882a1386a90208668310776392b7f8fa6c434afed1b0b9d73c4975434d469d854dc2e0173f2754b3c309eab1a331206be4424f9eabb2470ef8472eca79105bc293aa9eb96274e1689eaaafa7367672e82a264176d030ef745216bee472b17ebcf60de65236437cf6db928c42b472fbbe213d3c084ff57692eea882b0a19a479207d1624e152441f7e2b055251072d37b1fefd656ea3fe06c56fdf99d8fb8b14b39800d98903bb678b20b94af781425eb5c21758f6cd7a28deefa96faf1b96e1fb2b22ab36aea9337aef2ace3092c0187faf974abc84e733a8c4e859d304b24b45762484cd162663b78e36de7be62c9ad75d67e6158db10e89aadec3dcc99333cc0e15ccb0ed399d40f602c8f2e664061eaa013e2caafd7556e17ec4239963da07ccc80cad1a36a557431cfeaa297a418142bf68a4d37dd1f54ad791a1edfbdde052f5cbccfefd9bd437d52c692bc102e2fc3db0c52ecd7195c87eda5ad6f3559612b41002afc29b4c44c223587a704f7c2136b9a1cd34ec392d84ac22e58c33ec2a3385dd90f80d953be81bb08bda00281a03c7428e6302f94e6a405e4e1fb0dff8ffe5b13b5ea687fd3be53a5cc8f320e530956515a0597638b404e66a291e9c327c39a81389c71e06b3021ca74104acdf95eec0b487384c6865eb5c0fbf0db25e836839827c8cfdd0f7a503c638fc0cc1bf762d65be1f98f1dce4b0a86304158fbb7b4d03516d1e1b6c449213cc2101b8c54b2d63a854615b6999134c586163d9b280b9b27a686b673054cb1f01f7eed502b758ab694fac768298edb21f1c988576dbd8ab2cb20deeb8b350ccc78ae7409cf837cf79b6d920280563772ea7ad1f9cafcc8d3faa2319cbe833c81b2f91943520ff328c22b682f772da6090608d0ad6d4b25c4fa771734d28801c7f2a394e8ea0df9c83f9105c69f7f6015241c88344b877d1827bfb00e7ed2974297d1c3f76f43eaba680b3bcf97aabafc3191e1e9af6e2c2dff1209619f4f97f31f3f23c5e4d725b6e7c81ac36f9fb0f5a56eea1075054c0961b0902f84f5c382a471a3f1e9e7f563c5e38517a8092920f0baba3da1d86cc726dc9dcab51befdbc70c9ba84a8767dfcaecae27c0309903a44566aa930fdc782db27efbdb1c05afa93c72c8e6ff0fcea0cecbd678e850134b7ebe5fb57ab317384de6add04578993605bae3314887eb4c5465e788f89f65140c3863f510f27468143f0bd8ed93bb5991bb449eb1c24a28e1591cba3cbd4e750c3eb496275babe55606119dbbb5a68d503cff4d011915b305e2d6b973996e8148a9dfc9c98b7d937919acbb9318cc2136bdb5859041f05a38bc3cd7528c4ab9eba7adf0e62563183b04899b8cf2c0d08861b7385b8f7a28864f78068a402ccc92e2745b70ca43ccc0ecd7ba6cfde5712215f7201533906309c03d8149ed051f309e239a879f9db7d57f0b0740416db0122ad4ca969a19b70efd98adc90e721df8eb6c857899047dd469eb9942b611236dbefe6916dcb1a6ad67e5d5fa468c5aaecd8555e63c76c58e5dd7013a47362f57828a1a3e5f3ede1277fd65890875a7bbd31dafa60fe7ef58ee728756244e2f10ebf940afe88477fadbdd7c5df91b59b034aed15ddc71d2c34ca8b51ad81b0c7c02e2136dd1c5823b516af9165f8e6f7a29b403c2d0c7bdb3fd17d27bf43b127431ebceeaf7af30ccc00ccabc213dca3a554b0ae6ac143dcc26b1d67fd1fe0463f500c08e57e0e3c5d0c17b0f84ffc748eed1f0e5f90c880e1aeaa11b68a32f4085d51febdd794cf6297ef76cad08a2c063f8120ade973a071457acc83b5e64a95799b457b4f0eb0cf19ff2008a4c9027c6da498e376c23b47a888e5551b03f09c03ffb18778735476660a9ea922b3ed989667921c37ca49010ea1af119f8a26524eb2a468564fc2b7f19ef9211c8f1d5d12d2623c335b027c9f0ca0ba9c0648a8398aefc397928e266ab5315d5a347eb42ceb081ee8c29c73047ce098f4d884a07ae2089bd5b1e47c379e92f6879d040364bdf866d789afabdf43d551172fdc8bc37ab89f64bce7c5fd6141579e8422e8e99ec1a0798f2a25dc9835b0a63a274d2630848324f8dd59e46f03555f8bc3c4b3fb017e4d43471981cee7f58e9421fd3b730c2514a7a3319877f4a3c02a95e9aa3f2064d06a10048067f1a7c0f6c521c3ad5fe4b2d3ab641067cf06040421e73896b62005fc76744c4a7560d8891d751ae261f20ea4cabf1b5cac66b17593b5709b50e547af4b001e21790b7659b838325a57bf6ab2f183e65fdb8ec9791bc61f8539482decb3db6977ddcebf55d27e2e6760fc893d4320328f6ebb229d9a91dd1bed3424dd46845a47d0cfc4faf142920d73b896e18e1ef116e1d6f059c6f4233c6adf469dc20e8bab8f75a5b28864556303e5e40729fecb3a6fb0c5b94c9340ab39282fb9df4c242054037abf61b47faa02a37d6247cbdafb0306ad09a50d70bdbdb13c82c112dafff3018ca9006fba478b45f8ca68833aae2ce7e1e80051316fe1ccb43e76131df49b9e1aee22cdb9d704c9a0589437caff0454f54945abf5038520eecb113c19d0b895c53732c2f21513b7731af8d96d60849fe96cf8c37ead14c8187471fff79d625b3fdd50df6cf5e7c9acb89ade6e2bd53c0913fa32d36cc3a61f09b558b7e438249a6674e98a8703bcb8092cbf7a313555cb884ffd3b88c830117368d205c12fb284c8e2372d760d7ce9e254f6b0e41f469432cb9e3e6eb21e80938cb8cf415be684f052771965a9cdec4097685f98bc370498d56ac8d67408ca216b62ae505a32e30b6f47ad8f0b279ee296273bbf59fcf1962ccd401d5c8ce806385a43bc27c6d214036d63d940114bdaf802aac0df32c7cd333996d371e9ee09933daa33579e5d5d9465a6520c6099e7593c61b3f0c6c856c863bfb0241c73f94a9cae259b45172698bd2418e75d0703382960398eb03f68570973e48f43dc6a7c483fee26234736b0221150cc098ad9b24629755f4eb6603e2892ed7b79d9057053942f0f855398ff527a680dece7260b8ddc95cb75267ee60d5f29aaadda2d6bedff2ab0343837f5444b372c20f348bcde87661e1c3b43edb1bcc737f3b37aab9f3c7eef62e22a92ab608f88718560f516cdf57b3d9d93c72c706991831ee8de754725b616d5f68d319ecb0a55df5ca23c7539f05f5034d733d925517479cd8a4756e6a9ca66f5f79ad573c446639c4779ac373a29b21aef254b2b5120c65e6eb881852475ae8ea82f8647258dfd27cfc34d64a3e26a6eb615a36187d125c7b0edf288bd359dc5681e602684a268ecbe7095f10cc07df11031623380953805a82d029c8931aa757b6f6ddbb66be65a7e2884db76f81c1bd7073778b2d2e4217b9e7e43036c73d0b9606a95403acc2179eff7f6347ddb6db172ba67d04f71081d48afe0303109e1379c97090c9b0f286d90c884be17bd866deaa8981e1beb3441392507c7f8995dab541a857931cbf32cb714ccba9de3d0f4f655a3c893ee1a810b5aa361ab3fc1e59eafd3121048e08c68a686ac9437983b9ca6de07bb5931db754613759b08a4c619c89d44380e3f2bf34544d80d42c47f92dbdbb7e09ffb430c8e65f303242d13d002f86d57c85a491cc9843253479a37d86413df5e8ab6e59e03f7cc130e0a0d76be67b473839b9b5556535744e96300650652de8ec8cb7ce71e27fd8b7c0a6c39231ec5a4aae0a91f82324b0aa52946a1404b7be7de8337ccbed72c0ebd90e0a00480c24af660855a54d5c7f65a852864b2a84ed011b6a429ba622f215f89ff783361148103d1a996f9fb167002acfa54f6d39038fc78f84838b7b6165f6a887c25319c5d5b67970283fad70791bb6d1e2bf34fbb30baefafb7515b625594828a95b1525e2858989979b89b72e6c5a580259d3bcda5d151091d961bf429012aecea9c904820f97d25ce468e1b9b7b50ea5cdf4cbeee9d3e566174a4cef4ad3642c4536a0ec4a051a4369c0b935583ea295b1768b16bc1991459c785972ad6f592a86bf53879c0a3f5b0a0874a9a0d54b32abfa21d229cc4091defc06c0c11e868080000, 'CarlosCar', '1314025741', '990182400', '2004-09-08', 'Jipijapa-Manabi-Ecuador', 1, 38);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `econtre_empleo`
--

CREATE TABLE `econtre_empleo` (
  `id_econtre_empleo` int(11) NOT NULL,
  `nombreEmpresa` varchar(100) DEFAULT NULL,
  `puesto` varchar(150) DEFAULT NULL,
  `descipcion` varchar(1000) DEFAULT NULL,
  `imagen` longblob DEFAULT NULL,
  `fecha` date NOT NULL DEFAULT current_timestamp(),
  `fk_id_usuEstudiantes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `educacion`
--

CREATE TABLE `educacion` (
  `id_educacion` int(11) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `nombre_institucion` varchar(100) DEFAULT NULL,
  `fecha_culminacion` varchar(50) DEFAULT NULL,
  `especializacion` varchar(100) DEFAULT NULL,
  `otros` varchar(100) DEFAULT NULL,
  `fk_id_curriculum` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `educacion`
--

INSERT INTO `educacion` (`id_educacion`, `tipo`, `nombre_institucion`, `fecha_culminacion`, `especializacion`, `otros`, `fk_id_curriculum`) VALUES
(58, 'Universitaria', 'Unesum', '2023-10-05', 'Ingeniero en Redes', NULL, 54),
(59, 'Colegio', 'Replica Manta', '2019-07-09', 'Bachiller en Ciencias', NULL, 54),
(60, 'Universitaria', 'Unesum', '2023-06-14', 'Ingeniero en sistemas', NULL, 55),
(61, 'Curso', 'Unesum', '2022-03-10', 'Modelado DATA BASE', NULL, 54),
(62, 'Colegio', 'Replica Manta', '2020-12-10', 'Bachiller en ciencias ', NULL, 55);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `experiencia`
--

CREATE TABLE `experiencia` (
  `id_experiencia` int(11) NOT NULL,
  `nombre_empresa` varchar(50) DEFAULT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  `tareas_realizadas` varchar(500) NOT NULL,
  `tiempo_trabajo` varchar(50) DEFAULT NULL,
  `fk_id_curriculum` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `experiencia`
--

INSERT INTO `experiencia` (`id_experiencia`, `nombre_empresa`, `cargo`, `tareas_realizadas`, `tiempo_trabajo`, `fk_id_curriculum`) VALUES
(32, 'IESS', 'Tecnico en informatica', 'limpieza de maquinas de computo, instalación de Windows, formateo de maquinas', '4 Meses', 54),
(35, 'Cafedor', 'Pesaje de productos', 'Compra, venta, exportación, pesaje de café', '3 Años', 55),
(36, 'Unesum', 'frontend developer', 'actualizaciones de paginas, rediseño, manejo de base de datos, nuevas funciones en la plataforma', '1 Años', 54),
(37, 'IESS', 'Técnico en Mantenimiento de computadoras', 'Limpieza, formateo, recuperación de archivos, instalación de Windows, reparación de computadoras', '5 Meses', 55);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `idioma`
--

CREATE TABLE `idioma` (
  `id_idioma` int(11) NOT NULL,
  `idioma` varchar(100) DEFAULT NULL,
  `nivel` varchar(50) DEFAULT NULL,
  `fk_id_curriculum` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `idioma`
--

INSERT INTO `idioma` (`id_idioma`, `idioma`, `nivel`, `fk_id_curriculum`) VALUES
(33, 'Ingles', 'A1', 54),
(35, 'Ingles', 'A1', 55);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oferta_trabajo`
--

CREATE TABLE `oferta_trabajo` (
  `id_oferta_trabajo` int(11) NOT NULL,
  `puesto` varchar(50) DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `ubicacion_empleo` varchar(50) DEFAULT NULL,
  `tareas_realizar` varchar(900) DEFAULT NULL,
  `detalle` varchar(1000) DEFAULT NULL,
  `fecha_oferta` date DEFAULT current_timestamp(),
  `estado_oferta` int(11) DEFAULT 1,
  `fk_id_horario` int(11) NOT NULL,
  `fk_id_tipo_oferta` int(11) NOT NULL,
  `fk_id_tipo_lugar_oferta` int(11) NOT NULL,
  `fk_id_carrera` int(11) NOT NULL,
  `fk_id_usuario_empresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `oferta_trabajo`
--

INSERT INTO `oferta_trabajo` (`id_oferta_trabajo`, `puesto`, `precio`, `ubicacion_empleo`, `tareas_realizar`, `detalle`, `fecha_oferta`, `estado_oferta`, `fk_id_horario`, `fk_id_tipo_oferta`, `fk_id_tipo_lugar_oferta`, `fk_id_carrera`, `fk_id_usuario_empresa`) VALUES
(86, 'Diseñador UI/UX', 0, 'Manta', 'asdasd', 'asdasd', '2023-12-16', 1, 14, 28, 13, 1, 19),
(87, 'Programador web', 5, 'Manta editrado', 'taera editado', 'detalle editado', '2023-12-16', 1, 13, 26, 12, 1, 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postula`
--

CREATE TABLE `postula` (
  `id_postula` int(11) NOT NULL,
  `fecha_postulacion` date DEFAULT current_timestamp(),
  `aprobado` tinyint(1) DEFAULT 0,
  `fecha_aprobado` date DEFAULT NULL,
  `estado_noti` int(11) DEFAULT 0,
  `estado_noti_empresa` int(11) DEFAULT 0,
  `fk_id_usuEstudiantes` int(11) DEFAULT NULL,
  `fk_id_usuario_empresa` int(11) DEFAULT NULL,
  `fk_id_oferta_trabajo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `postula`
--

INSERT INTO `postula` (`id_postula`, `fecha_postulacion`, `aprobado`, `fecha_aprobado`, `estado_noti`, `estado_noti_empresa`, `fk_id_usuEstudiantes`, `fk_id_usuario_empresa`, `fk_id_oferta_trabajo`) VALUES
(61, '2023-12-16', 0, NULL, 0, 0, 36, 19, 87),
(62, '2023-12-16', 1, '2023-12-18', 0, 0, 38, 19, 87),
(63, '2023-12-17', 0, NULL, 0, 0, 38, 19, 86),
(64, '2023-12-21', 0, NULL, 0, 0, 36, NULL, 86);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicidad`
--

CREATE TABLE `publicidad` (
  `id_publicidad` int(11) NOT NULL,
  `detalle` varchar(900) NOT NULL,
  `link` varchar(500) NOT NULL,
  `fecha_caducidad` date NOT NULL,
  `fk_id_carrera` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `publicidad`
--

INSERT INTO `publicidad` (`id_publicidad`, `detalle`, `link`, `fecha_caducidad`, `fk_id_carrera`) VALUES
(13, 'Se les informa que la plataforma principal de la bolsa de empleo se ah actualizado, pueden visitarla dándole clic al link', 'http://localhost:8080/BOLSA_EMPLEO_HOSTINGER/index.html', '2023-12-23', 1),
(14, 'este anuncio va para la carrera de agropecuaria', '', '2023-12-23', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `referencia`
--

CREATE TABLE `referencia` (
  `id_referencia` int(11) NOT NULL,
  `nombre_referente` varchar(100) NOT NULL,
  `cargo_referente` varchar(300) NOT NULL,
  `numero_celular` varchar(10) NOT NULL,
  `correo_referente` varchar(50) NOT NULL,
  `fk_id_curriculum` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `referencia`
--

INSERT INTO `referencia` (`id_referencia`, `nombre_referente`, `cargo_referente`, `numero_celular`, `correo_referente`, `fk_id_curriculum`) VALUES
(3, 'Ingeniero David Carvajal', 'Profesor de colegio ', '0990441632', 'david@gmail.com', 54),
(4, 'Lic. Mary Carvajal', 'Distrito', '962580273', 'maricela@gmail.com', 55),
(5, 'Ing. Amy Reyes', 'Secretaria', '990444924', 'amyreyes686@gmail.com', 55),
(6, 'Lic. Maricela Carvajal Ponce', 'Gerente administrativa del distrito de jipijapa', '0962580273', 'maricelacarvajal@gmail.com', 54);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requisitos`
--

CREATE TABLE `requisitos` (
  `id_requisito` int(11) NOT NULL,
  `detalle` varchar(200) NOT NULL,
  `fk_id_oferta_trabajo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `requisitos`
--

INSERT INTO `requisitos` (`id_requisito`, `detalle`, `fk_id_oferta_trabajo`) VALUES
(18, 'requisito de la oferta 1', 86),
(19, 'asdasd asd editado', 87),
(20, 'asdasd asd editado', 87);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_oferta`
--

CREATE TABLE `tipos_oferta` (
  `id_tipo_oferta` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipos_oferta`
--

INSERT INTO `tipos_oferta` (`id_tipo_oferta`, `nombre`) VALUES
(26, 'Oferta de empleo'),
(27, 'Colaborador de proyecto'),
(28, 'Pasantia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_estado_trabajo`
--

CREATE TABLE `tipo_estado_trabajo` (
  `id_tipo_estado_trabajo` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_estado_trabajo`
--

INSERT INTO `tipo_estado_trabajo` (`id_tipo_estado_trabajo`, `nombre`, `estado`) VALUES
(1, 'En busca de empleo', 1),
(2, 'Pasante', 1),
(3, 'Colaborador de proyecto', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_horario_oferta`
--

CREATE TABLE `tipo_horario_oferta` (
  `id_tipo_horario_oferta` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_horario_oferta`
--

INSERT INTO `tipo_horario_oferta` (`id_tipo_horario_oferta`, `nombre`) VALUES
(12, 'Tiempo Completo'),
(13, 'Medio Tiempo'),
(14, 'Tiempo Parcial');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_lugar_oferta`
--

CREATE TABLE `tipo_lugar_oferta` (
  `id_tipo_lugar_oferta` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_lugar_oferta`
--

INSERT INTO `tipo_lugar_oferta` (`id_tipo_lugar_oferta`, `nombre`) VALUES
(11, 'Remoto'),
(12, 'Presencial'),
(13, 'Hibrido');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `totalcedulas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `totalcedulas` (
`cedula` varchar(50)
,`nombre` varchar(50)
,`apellido` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_empresa`
--

CREATE TABLE `usuario_empresa` (
  `id_usuario_empresa` int(11) NOT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `contra` varchar(50) DEFAULT NULL,
  `estado_cuenta` int(11) NOT NULL DEFAULT 1,
  `contra_temporal` varchar(100) DEFAULT NULL,
  `verificado` int(11) NOT NULL DEFAULT 0,
  `fecha_creacion` varchar(50) DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario_empresa`
--

INSERT INTO `usuario_empresa` (`id_usuario_empresa`, `correo`, `contra`, `estado_cuenta`, `contra_temporal`, `verificado`, `fecha_creacion`) VALUES
(19, 'InnoVistaTech@gmail.com', '26551699ab6fee5e42c980704ade76ec3e1b46a1', 1, NULL, 0, '2023-12-11 15:55:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_estudiantes`
--

CREATE TABLE `usuario_estudiantes` (
  `id_usuEstudiantes` int(11) NOT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `contra` varchar(50) DEFAULT NULL,
  `estado_cuenta` varchar(50) NOT NULL DEFAULT '1',
  `contra_temporal` varchar(100) DEFAULT NULL,
  `fecha_creacion` varchar(50) DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario_estudiantes`
--

INSERT INTO `usuario_estudiantes` (`id_usuEstudiantes`, `correo`, `contra`, `estado_cuenta`, `contra_temporal`, `fecha_creacion`) VALUES
(36, 'reyescarvajala@gmail.com', '26551699ab6fee5e42c980704ade76ec3e1b46a1', '1', NULL, '2023-12-08 12:36:10'),
(38, 'carlosantoni.carc@gmail.com', '26551699ab6fee5e42c980704ade76ec3e1b46a1', '1', NULL, '2023-12-10 14:49:32');

-- --------------------------------------------------------

--
-- Estructura para la vista `totalcedulas`
--
DROP TABLE IF EXISTS `totalcedulas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `totalcedulas`  AS SELECT `cedu`.`cedula` AS `cedula`, `datos`.`nombre` AS `nombre`, `datos`.`apellido` AS `apellido` FROM ((`cedula` `cedu` left join `usuario_estudiantes` `usues` on(`usues`.`id_usuEstudiantes` = `cedu`.`fk_id_usuEstudiantes`)) left join `datos_estudiantes` `datos` on(`usues`.`id_usuEstudiantes` = `datos`.`fk_id_usuEstudiantes`)) ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `adminunesum`
--
ALTER TABLE `adminunesum`
  ADD PRIMARY KEY (`id_adminUnesum`);

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD PRIMARY KEY (`id_carrera`);

--
-- Indices de la tabla `cedula`
--
ALTER TABLE `cedula`
  ADD PRIMARY KEY (`id_cedula`),
  ADD KEY `fk_id_usuEstudiantes` (`fk_id_usuEstudiantes`);

--
-- Indices de la tabla `codigo_empresa`
--
ALTER TABLE `codigo_empresa`
  ADD PRIMARY KEY (`id_codigo_empresa`);

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `fk_id_usuEstudiantes` (`fk_id_usuEstudiantes`),
  ADD KEY `fk_id_empresa` (`fk_id_empresa`);

--
-- Indices de la tabla `conocimientos`
--
ALTER TABLE `conocimientos`
  ADD PRIMARY KEY (`id_conocimientos`),
  ADD KEY `fk_id_curriculum` (`fk_id_curriculum`);

--
-- Indices de la tabla `curriculum`
--
ALTER TABLE `curriculum`
  ADD PRIMARY KEY (`id_curriculum`),
  ADD KEY `fk_id_usuEstudiantes` (`fk_id_usuEstudiantes`);

--
-- Indices de la tabla `datos_empresa`
--
ALTER TABLE `datos_empresa`
  ADD PRIMARY KEY (`id_datos_empresa`),
  ADD KEY `fk_id_usuario_empresa` (`fk_id_usuario_empresa`);

--
-- Indices de la tabla `datos_estudiantes`
--
ALTER TABLE `datos_estudiantes`
  ADD PRIMARY KEY (`id_datos_estudiantes`),
  ADD KEY `fk_id_usuEstudiantes` (`fk_id_usuEstudiantes`),
  ADD KEY `fk_id_carrera` (`fk_id_carrera`);

--
-- Indices de la tabla `econtre_empleo`
--
ALTER TABLE `econtre_empleo`
  ADD PRIMARY KEY (`id_econtre_empleo`),
  ADD KEY `fk_id_usuEstudiantes` (`fk_id_usuEstudiantes`);

--
-- Indices de la tabla `educacion`
--
ALTER TABLE `educacion`
  ADD PRIMARY KEY (`id_educacion`),
  ADD KEY `fk_id_curriculum` (`fk_id_curriculum`);

--
-- Indices de la tabla `experiencia`
--
ALTER TABLE `experiencia`
  ADD PRIMARY KEY (`id_experiencia`),
  ADD KEY `fk_id_curriculum` (`fk_id_curriculum`);

--
-- Indices de la tabla `idioma`
--
ALTER TABLE `idioma`
  ADD PRIMARY KEY (`id_idioma`),
  ADD KEY `fk_id_curriculum` (`fk_id_curriculum`);

--
-- Indices de la tabla `oferta_trabajo`
--
ALTER TABLE `oferta_trabajo`
  ADD PRIMARY KEY (`id_oferta_trabajo`),
  ADD KEY `fk_id_usuario_empresa` (`fk_id_usuario_empresa`),
  ADD KEY `fk_id_tipo_oferta` (`fk_id_tipo_oferta`),
  ADD KEY `fk_id_carrera` (`fk_id_carrera`),
  ADD KEY `fk_id_horario` (`fk_id_horario`),
  ADD KEY `fk_id_tipo_lugar_oferta_2` (`fk_id_tipo_lugar_oferta`);

--
-- Indices de la tabla `postula`
--
ALTER TABLE `postula`
  ADD PRIMARY KEY (`id_postula`),
  ADD KEY `fk_id_usuEstudiantes` (`fk_id_usuEstudiantes`),
  ADD KEY `fk_id_oferta_trabajo` (`fk_id_oferta_trabajo`),
  ADD KEY `fk_id_usuario_empresa` (`fk_id_usuario_empresa`);

--
-- Indices de la tabla `publicidad`
--
ALTER TABLE `publicidad`
  ADD PRIMARY KEY (`id_publicidad`),
  ADD KEY `fk_id_carrera` (`fk_id_carrera`);

--
-- Indices de la tabla `referencia`
--
ALTER TABLE `referencia`
  ADD PRIMARY KEY (`id_referencia`),
  ADD KEY `fk_id_curriculum` (`fk_id_curriculum`);

--
-- Indices de la tabla `requisitos`
--
ALTER TABLE `requisitos`
  ADD PRIMARY KEY (`id_requisito`),
  ADD KEY `fk_id_oferta_trabajo` (`fk_id_oferta_trabajo`);

--
-- Indices de la tabla `tipos_oferta`
--
ALTER TABLE `tipos_oferta`
  ADD PRIMARY KEY (`id_tipo_oferta`);

--
-- Indices de la tabla `tipo_estado_trabajo`
--
ALTER TABLE `tipo_estado_trabajo`
  ADD PRIMARY KEY (`id_tipo_estado_trabajo`);

--
-- Indices de la tabla `tipo_horario_oferta`
--
ALTER TABLE `tipo_horario_oferta`
  ADD PRIMARY KEY (`id_tipo_horario_oferta`);

--
-- Indices de la tabla `tipo_lugar_oferta`
--
ALTER TABLE `tipo_lugar_oferta`
  ADD PRIMARY KEY (`id_tipo_lugar_oferta`);

--
-- Indices de la tabla `usuario_empresa`
--
ALTER TABLE `usuario_empresa`
  ADD PRIMARY KEY (`id_usuario_empresa`);

--
-- Indices de la tabla `usuario_estudiantes`
--
ALTER TABLE `usuario_estudiantes`
  ADD PRIMARY KEY (`id_usuEstudiantes`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `adminunesum`
--
ALTER TABLE `adminunesum`
  MODIFY `id_adminUnesum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
  MODIFY `id_carrera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `cedula`
--
ALTER TABLE `cedula`
  MODIFY `id_cedula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `codigo_empresa`
--
ALTER TABLE `codigo_empresa`
  MODIFY `id_codigo_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `conocimientos`
--
ALTER TABLE `conocimientos`
  MODIFY `id_conocimientos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `curriculum`
--
ALTER TABLE `curriculum`
  MODIFY `id_curriculum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT de la tabla `datos_empresa`
--
ALTER TABLE `datos_empresa`
  MODIFY `id_datos_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `datos_estudiantes`
--
ALTER TABLE `datos_estudiantes`
  MODIFY `id_datos_estudiantes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `econtre_empleo`
--
ALTER TABLE `econtre_empleo`
  MODIFY `id_econtre_empleo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `educacion`
--
ALTER TABLE `educacion`
  MODIFY `id_educacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de la tabla `experiencia`
--
ALTER TABLE `experiencia`
  MODIFY `id_experiencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `idioma`
--
ALTER TABLE `idioma`
  MODIFY `id_idioma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `oferta_trabajo`
--
ALTER TABLE `oferta_trabajo`
  MODIFY `id_oferta_trabajo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de la tabla `postula`
--
ALTER TABLE `postula`
  MODIFY `id_postula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de la tabla `publicidad`
--
ALTER TABLE `publicidad`
  MODIFY `id_publicidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `referencia`
--
ALTER TABLE `referencia`
  MODIFY `id_referencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `requisitos`
--
ALTER TABLE `requisitos`
  MODIFY `id_requisito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `tipos_oferta`
--
ALTER TABLE `tipos_oferta`
  MODIFY `id_tipo_oferta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `tipo_estado_trabajo`
--
ALTER TABLE `tipo_estado_trabajo`
  MODIFY `id_tipo_estado_trabajo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_horario_oferta`
--
ALTER TABLE `tipo_horario_oferta`
  MODIFY `id_tipo_horario_oferta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `tipo_lugar_oferta`
--
ALTER TABLE `tipo_lugar_oferta`
  MODIFY `id_tipo_lugar_oferta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `usuario_empresa`
--
ALTER TABLE `usuario_empresa`
  MODIFY `id_usuario_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `usuario_estudiantes`
--
ALTER TABLE `usuario_estudiantes`
  MODIFY `id_usuEstudiantes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cedula`
--
ALTER TABLE `cedula`
  ADD CONSTRAINT `cedula_ibfk_1` FOREIGN KEY (`fk_id_usuEstudiantes`) REFERENCES `usuario_estudiantes` (`id_usuEstudiantes`);

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`fk_id_usuEstudiantes`) REFERENCES `usuario_estudiantes` (`id_usuEstudiantes`) ON UPDATE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`fk_id_empresa`) REFERENCES `usuario_empresa` (`id_usuario_empresa`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `conocimientos`
--
ALTER TABLE `conocimientos`
  ADD CONSTRAINT `conocimientos_ibfk_1` FOREIGN KEY (`fk_id_curriculum`) REFERENCES `curriculum` (`id_curriculum`);

--
-- Filtros para la tabla `curriculum`
--
ALTER TABLE `curriculum`
  ADD CONSTRAINT `curriculum_ibfk_1` FOREIGN KEY (`fk_id_usuEstudiantes`) REFERENCES `usuario_estudiantes` (`id_usuEstudiantes`);

--
-- Filtros para la tabla `datos_empresa`
--
ALTER TABLE `datos_empresa`
  ADD CONSTRAINT `datos_empresa_ibfk_1` FOREIGN KEY (`fk_id_usuario_empresa`) REFERENCES `usuario_empresa` (`id_usuario_empresa`);

--
-- Filtros para la tabla `datos_estudiantes`
--
ALTER TABLE `datos_estudiantes`
  ADD CONSTRAINT `datos_estudiantes_ibfk_1` FOREIGN KEY (`fk_id_usuEstudiantes`) REFERENCES `usuario_estudiantes` (`id_usuEstudiantes`),
  ADD CONSTRAINT `datos_estudiantes_ibfk_2` FOREIGN KEY (`fk_id_carrera`) REFERENCES `carreras` (`id_carrera`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `econtre_empleo`
--
ALTER TABLE `econtre_empleo`
  ADD CONSTRAINT `econtre_empleo_ibfk_1` FOREIGN KEY (`fk_id_usuEstudiantes`) REFERENCES `usuario_estudiantes` (`id_usuEstudiantes`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `educacion`
--
ALTER TABLE `educacion`
  ADD CONSTRAINT `educacion_ibfk_1` FOREIGN KEY (`fk_id_curriculum`) REFERENCES `curriculum` (`id_curriculum`);

--
-- Filtros para la tabla `experiencia`
--
ALTER TABLE `experiencia`
  ADD CONSTRAINT `experiencia_ibfk_1` FOREIGN KEY (`fk_id_curriculum`) REFERENCES `curriculum` (`id_curriculum`);

--
-- Filtros para la tabla `idioma`
--
ALTER TABLE `idioma`
  ADD CONSTRAINT `idioma_ibfk_1` FOREIGN KEY (`fk_id_curriculum`) REFERENCES `curriculum` (`id_curriculum`);

--
-- Filtros para la tabla `oferta_trabajo`
--
ALTER TABLE `oferta_trabajo`
  ADD CONSTRAINT `oferta_trabajo_ibfk_1` FOREIGN KEY (`fk_id_usuario_empresa`) REFERENCES `usuario_empresa` (`id_usuario_empresa`),
  ADD CONSTRAINT `oferta_trabajo_ibfk_5` FOREIGN KEY (`fk_id_carrera`) REFERENCES `carreras` (`id_carrera`) ON UPDATE CASCADE,
  ADD CONSTRAINT `oferta_trabajo_ibfk_6` FOREIGN KEY (`fk_id_horario`) REFERENCES `tipo_horario_oferta` (`id_tipo_horario_oferta`) ON UPDATE CASCADE,
  ADD CONSTRAINT `oferta_trabajo_ibfk_7` FOREIGN KEY (`fk_id_tipo_lugar_oferta`) REFERENCES `tipo_lugar_oferta` (`id_tipo_lugar_oferta`) ON UPDATE CASCADE,
  ADD CONSTRAINT `oferta_trabajo_ibfk_9` FOREIGN KEY (`fk_id_tipo_oferta`) REFERENCES `tipos_oferta` (`id_tipo_oferta`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `postula`
--
ALTER TABLE `postula`
  ADD CONSTRAINT `postula_ibfk_1` FOREIGN KEY (`fk_id_usuEstudiantes`) REFERENCES `usuario_estudiantes` (`id_usuEstudiantes`),
  ADD CONSTRAINT `postula_ibfk_2` FOREIGN KEY (`fk_id_oferta_trabajo`) REFERENCES `oferta_trabajo` (`id_oferta_trabajo`),
  ADD CONSTRAINT `postula_ibfk_3` FOREIGN KEY (`fk_id_usuario_empresa`) REFERENCES `usuario_empresa` (`id_usuario_empresa`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `publicidad`
--
ALTER TABLE `publicidad`
  ADD CONSTRAINT `publicidad_ibfk_1` FOREIGN KEY (`fk_id_carrera`) REFERENCES `carreras` (`id_carrera`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `referencia`
--
ALTER TABLE `referencia`
  ADD CONSTRAINT `referencia_ibfk_1` FOREIGN KEY (`fk_id_curriculum`) REFERENCES `curriculum` (`id_curriculum`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `requisitos`
--
ALTER TABLE `requisitos`
  ADD CONSTRAINT `requisitos_ibfk_1` FOREIGN KEY (`fk_id_oferta_trabajo`) REFERENCES `oferta_trabajo` (`id_oferta_trabajo`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
