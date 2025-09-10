<?php
include '../../conexion/bd.php';

// Establecer cabeceras para descarga de archivo CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=alumnos_corporativo_' . date('Y-m-d_H-i-s') . '.csv');
header('Pragma: no-cache');
header('Expires: 0');

// Crear el output para CSV
$output = fopen('php://output', 'w');

// Agregar BOM para UTF-8 (para que Excel reconozca correctamente los acentos)
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// No agregar cabeceras para este formato (según el ejemplo no las tiene)

try {
    // Consulta SQL para obtener los datos
    $sql = "SELECT id, nombres, apellido_pa, apellido_ma, email, direccion, 
                   puesto_trabajo, centro_estudio, numero_documento 
            FROM alumno WHERE estado = 1 ORDER BY nombres ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Procesar cada alumno
    foreach ($alumnos as $alumno) {
        
        // Limpiar y preparar datos básicos
        $nombres = trim($alumno['nombres']);
        $apellido_pa = trim($alumno['apellido_pa']);
        $apellido_ma = trim($alumno['apellido_ma']);
        $apellidos_completos = trim($apellido_pa . ' ' . $apellido_ma);
        $usuario_completo = trim($nombres . ' ' . $apellidos_completos);
        
        // Generar email corporativo basado en nombre y apellido
        // Limpiar caracteres especiales y convertir a minúsculas
        $nombre_email = strtolower(trim($nombres));
        $apellido_email = strtolower(trim($apellido_pa));
        
        // Remover acentos y caracteres especiales
        $nombre_email = preg_replace('/[áàäâ]/u', 'a', $nombre_email);
        $nombre_email = preg_replace('/[éèëê]/u', 'e', $nombre_email);
        $nombre_email = preg_replace('/[íìïî]/u', 'i', $nombre_email);
        $nombre_email = preg_replace('/[óòöô]/u', 'o', $nombre_email);
        $nombre_email = preg_replace('/[úùüû]/u', 'u', $nombre_email);
        $nombre_email = preg_replace('/[ñ]/u', 'n', $nombre_email);
        
        $apellido_email = preg_replace('/[áàäâ]/u', 'a', $apellido_email);
        $apellido_email = preg_replace('/[éèëê]/u', 'e', $apellido_email);
        $apellido_email = preg_replace('/[íìïî]/u', 'i', $apellido_email);
        $apellido_email = preg_replace('/[óòöô]/u', 'o', $apellido_email);
        $apellido_email = preg_replace('/[úùüû]/u', 'u', $apellido_email);
        $apellido_email = preg_replace('/[ñ]/u', 'n', $apellido_email);
        
        // Remover espacios y caracteres no alfanuméricos
        $nombre_email = preg_replace('/[^a-z0-9]/i', '', $nombre_email);
        $apellido_email = preg_replace('/[^a-z0-9]/i', '', $apellido_email);
        
        // Crear email corporativo (tomar solo el primer nombre si hay varios)
        $primer_nombre = explode(' ', $nombres)[0];
        $primer_nombre = strtolower(preg_replace('/[^a-z0-9]/i', '', $primer_nombre));
        $primer_nombre = preg_replace('/[áàäâ]/u', 'a', $primer_nombre);
        $primer_nombre = preg_replace('/[éèëê]/u', 'e', $primer_nombre);
        $primer_nombre = preg_replace('/[íìïî]/u', 'i', $primer_nombre);
        $primer_nombre = preg_replace('/[óòöô]/u', 'o', $primer_nombre);
        $primer_nombre = preg_replace('/[úùüû]/u', 'u', $primer_nombre);
        $primer_nombre = preg_replace('/[ñ]/u', 'n', $primer_nombre);
        
        $email_corporativo = $primer_nombre . $apellido_email . '@fundacionweperu.onmicrosoft.com';
        
        // Datos del puesto y departamento
        $puesto_trabajo = !empty($alumno['puesto_trabajo']) ? $alumno['puesto_trabajo'] : 'Alumno';
        $centro_estudio = !empty($alumno['centro_estudio']) ? $alumno['centro_estudio'] : 'Fundación We Peru';
        
        // Código empleado (usar ID o documento)
        $codigo_empleado = !empty($alumno['numero_documento']) ? $alumno['numero_documento'] : $alumno['id'];
        
        // Teléfonos (valores por defecto ya que no están en la BD)
        $telefono1 = '123-555-1211';
        $telefono2 = '123-555-6641';  
        $telefono3 = '123-555-9821';
        
        // Email personal (el que tienen registrado)
        $email_personal = !empty($alumno['email']) ? $alumno['email'] : $email_corporativo;
        
        // Procesar dirección (dividir en componentes)
        $direccion_completa = !empty($alumno['direccion']) ? $alumno['direccion'] : '1 Microsoft way';
        
        // Intentar extraer componentes de la dirección
        // Por defecto usar datos de ejemplo si no hay dirección
        $direccion_linea1 = $direccion_completa;
        $ciudad = 'Lima';
        $estado_provincia = 'Lima';
        $codigo_postal = '15001';
        $pais = 'Perú';
        
        // Si la dirección contiene información más específica, intentar parsearla
        if (strpos($direccion_completa, ',') !== false) {
            $partes_direccion = explode(',', $direccion_completa);
            $direccion_linea1 = trim($partes_direccion[0]);
            if (isset($partes_direccion[1])) {
                $ciudad = trim($partes_direccion[1]);
            }
        }
        
        // Crear array con todos los datos en el orden requerido
        $row = array(
            $email_corporativo,           // 1. Email corporativo
            $nombres,                     // 2. Nombres
            $apellidos_completos,         // 3. Apellidos
            $usuario_completo,            // 4. Usuario completo
            $puesto_trabajo,              // 5. Puesto de trabajo
            $centro_estudio,              // 6. Departamento/Centro
            $codigo_empleado,             // 7. Código empleado
            $telefono1,                   // 8. Teléfono 1
            $telefono2,                   // 9. Teléfono 2
            $telefono3,                   // 10. Teléfono 3
            $email_personal,              // 11. Email personal
            $direccion_linea1,            // 12. Dirección
            $ciudad,                      // 13. Ciudad
            $estado_provincia,            // 14. Estado/Provincia
            $codigo_postal,               // 15. Código postal
            $pais                         // 16. País
        );
        
        // Escribir la fila en el CSV (usar coma como delimitador)
        fputcsv($output, $row, ',');
    }
    
} catch (Exception $e) {
    // En caso de error, escribir una fila de error
    $error_row = array('Error al exportar datos: ' . $e->getMessage());
    for ($i = 1; $i < 16; $i++) {
        $error_row[] = '';
    }
    fputcsv($output, $error_row, ',');
}

// Cerrar el output
fclose($output);

// Terminar el script
exit();
?>