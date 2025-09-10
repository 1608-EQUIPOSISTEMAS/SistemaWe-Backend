<?php
include '../../conexion/bd.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // Validar campos obligatorios
    $required_fields = ['nombres', 'apellido_pa', 'tipo_documento', 'numero_documento', 'tipo_alumno', 'estado'];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("El campo " . str_replace('_', ' ', $field) . " es obligatorio");
        }
    }

    // Sanitizar y validar datos
    $nombres = trim($_POST['nombres']);
    $apellido_pa = trim($_POST['apellido_pa']);
    $apellido_ma = trim($_POST['apellido_ma'] ?? '');
    $tipo_documento = intval($_POST['tipo_documento']);
    $numero_documento = trim($_POST['numero_documento']);
    $email = trim($_POST['email'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $estado_civil = $_POST['estado_civil'] ?? null;
    $tipo_alumno = $_POST['tipo_alumno'];
    $puesto_trabajo = trim($_POST['puesto_trabajo'] ?? '');
    $centro_estudio = trim($_POST['centro_estudio'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $estado = intval($_POST['estado']);

    // Validaciones específicas
    if (!in_array($tipo_documento, [1, 2, 3])) {
        throw new Exception('Tipo de documento inválido');
    }

    if (!in_array($tipo_alumno, ['UNIVERSITARIO', 'COLEGIO', 'TRABAJADOR'])) {
        throw new Exception('Tipo de alumno inválido');
    }

    if ($estado_civil && !in_array($estado_civil, ['SOLTERO', 'CASADO', 'VIUDO', 'DIVORCIADO'])) {
        throw new Exception('Estado civil inválido');
    }

    if (!in_array($estado, [0, 1])) {
        throw new Exception('Estado inválido');
    }

    // Validar email si se proporciona
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email inválido');
    }

    // Validar fecha si se proporciona
    if (!empty($fecha_nacimiento)) {
        $date = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
        if (!$date || $date->format('Y-m-d') !== $fecha_nacimiento) {
            throw new Exception('Fecha de nacimiento inválida');
        }
    }

    // Verificar si el documento ya existe
    $check_sql = "SELECT COUNT(*) FROM alumno WHERE numero_documento = ? AND tipo_documento = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->execute([$numero_documento, $tipo_documento]);
    
    if ($check_stmt->fetchColumn() > 0) {
        throw new Exception('Ya existe un alumno con este número de documento');
    }

    // Preparar consulta de inserción
    $sql = "INSERT INTO alumno (
                nombres, apellido_pa, apellido_ma, tipo_documento, numero_documento, 
                email, direccion, estado_civil, tipo_alumno, puesto_trabajo, 
                centro_estudio, fecha_nacimiento, estado
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    
    $params = [
        $nombres,
        $apellido_pa,
        $apellido_ma ?: null,
        $tipo_documento,
        $numero_documento,
        $email ?: null,
        $direccion ?: null,
        $estado_civil,
        $tipo_alumno,
        $puesto_trabajo ?: null,
        $centro_estudio ?: null,
        $fecha_nacimiento ?: null,
        $estado
    ];

    if ($stmt->execute($params)) {
        echo json_encode([
            'success' => true,
            'message' => 'Alumno agregado exitosamente',
            'id' => $conn->lastInsertId()
        ]);
    } else {
        throw new Exception('Error al insertar el alumno en la base de datos');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>