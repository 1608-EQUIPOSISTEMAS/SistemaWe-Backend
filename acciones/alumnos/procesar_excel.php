<?php
require '../../vendor/autoload.php'; // PhpSpreadsheet
include '../../conexion/bd.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    if (!isset($_FILES['archivo_excel']) || $_FILES['archivo_excel']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No se pudo cargar el archivo');
    }

    $file = $_FILES['archivo_excel'];
    
    // Validar tamaño (máximo 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception('El archivo es demasiado grande (máximo 5MB)');
    }

    // Validar extensión
    $allowed_extensions = ['xlsx', 'xls'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_extension, $allowed_extensions)) {
        throw new Exception('Formato de archivo no válido. Use .xlsx o .xls');
    }

    // Cargar el archivo Excel
    $spreadsheet = IOFactory::load($file['tmp_name']);
    $sheet = $spreadsheet->getActiveSheet();
    $data = $sheet->toArray();

    if (empty($data) || count($data) < 2) {
        throw new Exception('El archivo está vacío o no tiene datos válidos');
    }

    // Validar cabeceras esperadas
    $expected_headers = [
        'NOMBRES', 'APELLIDO_PATERNO', 'APELLIDO_MATERNO', 'TIPO_DOCUMENTO', 
        'NUMERO_DOCUMENTO', 'EMAIL', 'DIRECCION', 'ESTADO_CIVIL', 
        'TIPO_ALUMNO', 'CENTRO_ESTUDIO', 'PUESTO_TRABAJO', 'FECHA_NACIMIENTO', 'ESTADO'
    ];

    $headers = array_map('trim', array_map('strtoupper', $data[0]));
    
    // Verificar que las cabeceras principales existan
    $required_headers = ['NOMBRES', 'APELLIDO_PATERNO', 'TIPO_DOCUMENTO', 'NUMERO_DOCUMENTO', 'TIPO_ALUMNO', 'ESTADO'];
    
    foreach ($required_headers as $required_header) {
        if (!in_array($required_header, $headers)) {
            throw new Exception("Falta la cabecera requerida: $required_header");
        }
    }

    $conn->beginTransaction();

    $processed = 0;
    $errors = [];
    $skipped_rows = [];

    // Procesar datos desde la fila 2 (saltando cabeceras)
    for ($i = 1; $i < count($data); $i++) {
        $row_number = $i + 1;
        $row = $data[$i];

        try {
            // Saltar filas vacías
            if (empty(array_filter($row))) {
                continue;
            }

            // Mapear datos por posición de cabecera
            $row_data = [];
            foreach ($headers as $index => $header) {
                $row_data[$header] = isset($row[$index]) ? trim($row[$index]) : '';
            }

            // Validar campos obligatorios
            if (empty($row_data['NOMBRES'])) {
                throw new Exception("Nombres es obligatorio");
            }
            if (empty($row_data['APELLIDO_PATERNO'])) {
                throw new Exception("Apellido paterno es obligatorio");
            }
            if (empty($row_data['TIPO_DOCUMENTO'])) {
                throw new Exception("Tipo de documento es obligatorio");
            }
            if (empty($row_data['NUMERO_DOCUMENTO'])) {
                throw new Exception("Número de documento es obligatorio");
            }
            if (empty($row_data['TIPO_ALUMNO'])) {
                throw new Exception("Tipo de alumno es obligatorio");
            }

            // Validar y convertir datos
            $nombres = $row_data['NOMBRES'];
            $apellido_pa = $row_data['APELLIDO_PATERNO'];
            $apellido_ma = $row_data['APELLIDO_MATERNO'] ?? '';
            
            $tipo_documento = intval($row_data['TIPO_DOCUMENTO']);
            if (!in_array($tipo_documento, [1, 2, 3])) {
                throw new Exception("Tipo de documento inválido (debe ser 1, 2 o 3)");
            }

            $numero_documento = $row_data['NUMERO_DOCUMENTO'];
            $email = !empty($row_data['EMAIL']) ? $row_data['EMAIL'] : null;
            $direccion = !empty($row_data['DIRECCION']) ? $row_data['DIRECCION'] : null;
            
            $estado_civil = null;
            if (!empty($row_data['ESTADO_CIVIL'])) {
                $estado_civil = strtoupper($row_data['ESTADO_CIVIL']);
                if (!in_array($estado_civil, ['SOLTERO', 'CASADO', 'VIUDO', 'DIVORCIADO'])) {
                    throw new Exception("Estado civil inválido");
                }
            }

            $tipo_alumno = strtoupper($row_data['TIPO_ALUMNO']);
            if (!in_array($tipo_alumno, ['UNIVERSITARIO', 'COLEGIO', 'TRABAJADOR'])) {
                throw new Exception("Tipo de alumno inválido");
            }

            $centro_estudio = !empty($row_data['CENTRO_ESTUDIO']) ? $row_data['CENTRO_ESTUDIO'] : null;
            $puesto_trabajo = !empty($row_data['PUESTO_TRABAJO']) ? $row_data['PUESTO_TRABAJO'] : null;
            
            $fecha_nacimiento = null;
            if (!empty($row_data['FECHA_NACIMIENTO'])) {
                $fecha = $row_data['FECHA_NACIMIENTO'];
                // Intentar diferentes formatos de fecha
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
                    $fecha_nacimiento = $fecha;
                } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fecha)) {
                    $date_obj = DateTime::createFromFormat('d/m/Y', $fecha);
                    if ($date_obj) {
                        $fecha_nacimiento = $date_obj->format('Y-m-d');
                    }
                }
                
                if (!$fecha_nacimiento) {
                    throw new Exception("Fecha de nacimiento inválida (use YYYY-MM-DD o DD/MM/YYYY)");
                }
            }

            $estado = isset($row_data['ESTADO']) ? intval($row_data['ESTADO']) : 1;
            if (!in_array($estado, [0, 1])) {
                throw new Exception("Estado inválido (debe ser 0 o 1)");
            }

            // Validar email si se proporciona
            if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email inválido");
            }

            // Verificar si el documento ya existe
            $check_sql = "SELECT COUNT(*) FROM alumno WHERE numero_documento = ? AND tipo_documento = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->execute([$numero_documento, $tipo_documento]);
            
            if ($check_stmt->fetchColumn() > 0) {
                throw new Exception("Ya existe un alumno con este documento");
            }

            // Insertar el alumno
            $sql = "INSERT INTO alumno (
                        nombres, apellido_pa, apellido_ma, tipo_documento, numero_documento, 
                        email, direccion, estado_civil, tipo_alumno, puesto_trabajo, 
                        centro_estudio, fecha_nacimiento, estado
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $params = [
                $nombres, $apellido_pa, $apellido_ma, $tipo_documento, $numero_documento,
                $email, $direccion, $estado_civil, $tipo_alumno, $puesto_trabajo,
                $centro_estudio, $fecha_nacimiento, $estado
            ];

            if ($stmt->execute($params)) {
                $processed++;
            } else {
                throw new Exception("Error al insertar en la base de datos");
            }

        } catch (Exception $e) {
            $errors[] = "Fila $row_number: " . $e->getMessage();
            $skipped_rows[] = $row_number;
            continue;
        }
    }

    $conn->commit();

    $response = [
        'success' => true,
        'message' => "Procesados $processed alumnos exitosamente",
        'processed' => $processed,
        'errors' => $errors,
        'total_rows' => count($data) - 1 // Excluir cabecera
    ];

    if (!empty($errors)) {
        $response['message'] .= ". Se encontraron " . count($errors) . " errores";
    }

    echo json_encode($response);

} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>