-- 1.  Listar los jugadores con el EMMAC vencido, mostrando su teléfono para avisarles.
SELECT j.nombre, j.apellido, j.telefono, e.fecha_vencimiento
FROM jugador j
INNER JOIN emmac e ON j.id_jugador = e.id_jugador
WHERE e.fecha_vencimiento < CURDATE();

-- 2. (Simple) Mostra el plantel completo con nombre, apellido, posición y número de camiseta, ordenado por número.
SELECT j.nombre, j.apellido, p.nombre AS posicion, j.nro_camiseta
FROM jugador j
INNER JOIN posicion p ON j.id_posicion = p.id_posicion
ORDER BY j.nro_camiseta ASC;

-- Avanzada) Listar los jugadores que faltaron a más de 3 entrenamientos en el último mes.
SELECT j.nombre, j.apellido, COUNT(e.numero) AS cantidad_faltas
FROM jugador j
CROSS JOIN entrenamiento e
LEFT JOIN asistencia a ON j.id_jugador = a.id_jugador AND e.numero = a.numero_entrenamiento
WHERE a.numero_entrenamiento IS NULL 
  AND MONTH(e.fecha) = MONTH(CURDATE()) 
  AND YEAR(e.fecha) = YEAR(CURDATE())
GROUP BY j.id_jugador, j.nombre, j.apellido
HAVING COUNT(e.numero) > 3;

-- 4. (Simple) Mostrar todos los cascos y hombreras que están con estado "Disponible" en el club.
SELECT tipo, marca, nro_serie
FROM equipamiento
WHERE estado = 'Disponible' 
  AND tipo IN ('Casco', 'Hombrera')
ORDER BY tipo ASC, marca ASC;

-- (Avanzada) Mostrar los préstamos de equipamiento que llevan más de 15 días de demora y qué jugador los retuvo.
SELECT j.nombre, j.apellido, p.nro_serie, p.fecha_retiro, DATEDIFF(CURDATE(), p.fecha_retiro) AS dias_demora
FROM prestamo p
INNER JOIN jugador j ON p.id_jugador = j.id_jugador
WHERE p.fecha_devolucion IS NULL 
  AND DATEDIFF(CURDATE(), p.fecha_retiro) > 15;

-- (Avanzada) Cruzar los datos de los jugadores que pesan 100 kilos o más con los cascos que tienen prestados para verificar que los talles sean correctos.
SELECT j.nombre, j.apellido, j.peso, eq.nro_serie, eq.marca, p.fecha_retiro
FROM jugador j
INNER JOIN prestamo p ON j.id_jugador = p.id_jugador
INNER JOIN equipamiento eq ON p.nro_serie = eq.nro_serie
WHERE j.peso >= 100.00 
  AND eq.tipo = 'Casco' 
  AND p.fecha_devolucion IS NULL;

-- (Avanzada) Buscar el número de serie de un equipo específico y ver el historial de todos los jugadores que lo usaron en el año.
SELECT eq.nro_serie, eq.tipo, j.nombre, j.apellido, p.fecha_retiro, p.fecha_devolucion
FROM prestamo p
INNER JOIN equipamiento eq ON p.nro_serie = eq.nro_serie
INNER JOIN jugador j ON p.id_jugador = j.id_jugador
WHERE eq.nro_serie = 'CASCO-01' -- Reemplazar con el número de serie a consultar
  AND YEAR(p.fecha_retiro) = YEAR(CURDATE())
ORDER BY p.fecha_retiro DESC;

-- Avanzada) Listar a los jugadores que no tienen registrada la cuota del mes actual para pasarlos a la lista de inhabilitados.
SELECT j.nombre, j.apellido, j.dni
FROM jugador j
WHERE j.id_jugador NOT IN (
    SELECT p.id_jugador
    FROM pagos p
    INNER JOIN cuota c ON p.id_cuota = c.id_cuota
    WHERE c.mes = MONTH(CURDATE()) 
      AND c.año = YEAR(CURDATE())
);

-- (Avanzada) Calcular cuánta plata ingresó al club por cobro de cuotas en lo que va del año, agrupado mes a mes.
SELECT c.mes, SUM(c.valor) AS total_ingresos
FROM pagos p
INNER JOIN cuota c ON p.id_cuota = c.id_cuota
WHERE c.año = YEAR(CURDATE())
GROUP BY c.mes;

-- (Simple) Mostrar los días, horarios y la dirección exacta de todos los entrenamientos programados para esta semana.
SELECT fecha, hora, ubicacion_calle, ubicacion_numero, ubicacion_ciudad
FROM entrenamiento
WHERE YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1);