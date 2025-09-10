<?php
require '../../vendor/autoload.php'; // Asegúrate de tener PhpSpreadsheet instalado

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

// Crear nueva hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Configurar el título de la hoja
$sheet->setTitle('Plantilla Alumnos');

// CABECERAS
$headers = [
    'A1' => 'NOMBRES*',
    'B1' => 'APELLIDO_PATERNO*', 
    'C1' => 'APELLIDO_MATERNO',
    'D1' => 'TIPO_DOCUMENTO*',
    'E1' => 'NUMERO_DOCUMENTO*',
    'F1' => 'EMAIL',
    'G1' => 'DIRECCION',
    'H1' => 'ESTADO_CIVIL',
    'I1' => 'TIPO_ALUMNO*',
    'J1' => 'CENTRO_ESTUDIO',
    'K1' => 'PUESTO_TRABAJO',
    'L1' => 'FECHA_NACIMIENTO',
    'M1' => 'ESTADO*'
];

// Escribir cabeceras
foreach ($headers as $cell => $header) {
    $sheet->setCellValue($cell, $header);
}

// DATOS DE EJEMPLO
$ejemplos = [
    ['Juan Carlos', 'García', 'López', '1', '12345678', 'juan.garcia@email.com', 'Av. Principal 123, Lima', 'SOLTERO', 'UNIVERSITARIO', 'Universidad Nacional Mayor de San Marcos', '', '1990-05-15', '1'],
    ['María Elena', 'Rodríguez', 'Vargas', '1', '87654321', 'maria.rodriguez@email.com', 'Jr. Los Olivos 456, San Miguel', 'CASADO', 'TRABAJADOR', '', 'Analista de Sistemas', '1985-12-03', '1'],
    ['Luis Fernando', 'Mendoza', 'Torres', '2', 'CE001234567', 'luis.mendoza@email.com', 'Calle Las Flores 789, Miraflores', 'SOLTERO', 'COLEGIO', 'Colegio San Patricio', '', '2005-08-22', '1'],
    ['Ana Sofía', 'Quispe', 'Mamani', '1', '11223344', 'ana.quispe@email.com', 'Av. Universitaria 321, Los Olivos', 'VIUDO', 'UNIVERSITARIO', 'Universidad San Martín de Porres', 'Secretaria', '1992-03-10', '1']
];

// Escribir ejemplos
$row = 2;
foreach ($ejemplos as $ejemplo) {
    $col = 'A';
    foreach ($ejemplo as $valor) {
        $sheet->setCellValue($col . $row, $valor);
        $col++;
    }
    $row++;
}

// ESTILOS PARA CABECERAS
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
        'size' => 11
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4472C4']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ]
];

// Aplicar estilo a cabeceras
$sheet->getStyle('A1:M1')->applyFromArray($headerStyle);

// ESTILOS PARA DATOS DE EJEMPLO
$dataStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => 'CCCCCC']
        ]
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'F8F9FA']
    ]
];

// Aplicar estilo a datos
$sheet->getStyle('A2:M6')->applyFromArray($dataStyle);

// AJUSTAR ANCHO DE COLUMNAS
$sheet->getColumnDimension('A')->setWidth(15); // NOMBRES
$sheet->getColumnDimension('B')->setWidth(15); // APELLIDO_PATERNO
$sheet->getColumnDimension('C')->setWidth(15); // APELLIDO_MATERNO
$sheet->getColumnDimension('D')->setWidth(12); // TIPO_DOCUMENTO
$sheet->getColumnDimension('E')->setWidth(15); // NUMERO_DOCUMENTO
$sheet->getColumnDimension('F')->setWidth(25); // EMAIL
$sheet->getColumnDimension('G')->setWidth(30); // DIRECCION
$sheet->getColumnDimension('H')->setWidth(12); // ESTADO_CIVIL
$sheet->getColumnDimension('I')->setWidth(15); // TIPO_ALUMNO
$sheet->getColumnDimension('J')->setWidth(35); // CENTRO_ESTUDIO
$sheet->getColumnDimension('K')->setWidth(20); // PUESTO_TRABAJO
$sheet->getColumnDimension('L')->setWidth(15); // FECHA_NACIMIENTO
$sheet->getColumnDimension('M')->setWidth(8);  // ESTADO

// CREAR HOJA DE INSTRUCCIONES
$instructionsSheet = $spreadsheet->createSheet();
$instructionsSheet->setTitle('INSTRUCCIONES');

$instructions = [
    ['PLANTILLA PARA CARGA MASIVA DE ALUMNOS'],
    [''],
    ['INSTRUCCIONES:'],
    ['1. Los campos marcados con * son OBLIGATORIOS'],
    ['2. No modifique las cabeceras (fila 1)'],
    ['3. Complete sus datos a partir de la fila 7 (después de los ejemplos)'],
    ['4. Respete los formatos especificados abajo'],
    ['5. Guarde el archivo como Excel (.xlsx)'],
    [''],
    ['FORMATOS REQUERIDOS:'],
    [''],
    ['TIPO_DOCUMENTO:'],
    ['  • 1 = DNI'],
    ['  • 2 = Carnet de Extranjería'],
    ['  • 3 = Pasaporte'],
    [''],
    ['ESTADO_CIVIL (opcional):'],
    ['  • SOLTERO'],
    ['  • CASADO'],
    ['  • VIUDO'],
    ['  • DIVORCIADO'],
    [''],
    ['TIPO_ALUMNO:'],
    ['  • UNIVERSITARIO'],
    ['  • COLEGIO'],
    ['  • TRABAJADOR'],
    [''],
    ['FECHA_NACIMIENTO:'],
    ['  • Formato: YYYY-MM-DD (ejemplo: 1990-05-15)'],
    ['  • También acepta formato DD/MM/YYYY'],
    [''],
    ['ESTADO:'],
    ['  • 1 = Activo'],
    ['  • 0 = Inactivo'],
    [''],
    ['NOTAS IMPORTANTES:'],
    ['• El sistema validará que no existan documentos duplicados'],
    ['• Los emails deben tener formato válido'],
    ['• Los campos opcionales pueden dejarse vacíos'],
    ['• Máximo 500 registros por archivo'],
    [''],
    ['¿NECESITAS AYUDA?'],
    ['Contacta al administrador del sistema']
];

// Escribir instrucciones
$row = 1;
foreach ($instructions as $instruction) {
    $instructionsSheet->setCellValue('A' . $row, $instruction[0]);
    $row++;
}

// Estilo para título de instrucciones
$instructionsSheet->getStyle('A1')->applyFromArray([
    'font' => [
        'bold' => true,
        'size' => 14,
        'color' => ['rgb' => '4472C4']
    ]
]);

// Estilo para subtítulos
$instructionsSheet->getStyle('A3')->applyFromArray([
    'font' => ['bold' => true, 'size' => 12]
]);
$instructionsSheet->getStyle('A10')->applyFromArray([
    'font' => ['bold' => true, 'size' => 12]
]);

// Ajustar ancho de columna
$instructionsSheet->getColumnDimension('A')->setWidth(50);

// Volver a la primera hoja
$spreadsheet->setActiveSheetIndex(0);

// AGREGAR COMENTARIOS/VALIDACIONES A LA HOJA PRINCIPAL

// Comentario en TIPO_DOCUMENTO
$sheet->getComment('D1')->getText()->createTextRun('1=DNI, 2=Carnet Extranjería, 3=Pasaporte');

// Comentario en ESTADO_CIVIL
$sheet->getComment('H1')->getText()->createTextRun('SOLTERO, CASADO, VIUDO, DIVORCIADO');

// Comentario en TIPO_ALUMNO
$sheet->getComment('I1')->getText()->createTextRun('UNIVERSITARIO, COLEGIO, TRABAJADOR');

// Comentario en FECHA_NACIMIENTO
$sheet->getComment('L1')->getText()->createTextRun('Formato: YYYY-MM-DD (ejemplo: 1990-05-15)');

// Comentario en ESTADO
$sheet->getComment('M1')->getText()->createTextRun('1=Activo, 0=Inactivo');

// VALIDACIONES (para columnas específicas en filas de datos)
for ($row = 7; $row <= 500; $row++) { // Desde fila 7 hasta 500
    
    // Validación para TIPO_DOCUMENTO (columna D)
    $validation = $sheet->getCell('D' . $row)->getDataValidation();
    $validation->setType(DataValidation::TYPE_LIST);
    $validation->setFormula1('"1;2;3"');
    $validation->setAllowBlank(false);
    $validation->setShowDropDown(true);
    
    // Validación para ESTADO_CIVIL (columna H)
    $validation = $sheet->getCell('H' . $row)->getDataValidation();
    $validation->setType(DataValidation::TYPE_LIST);
    $validation->setFormula1('"SOLTERO;CASADO;VIUDO;DIVORCIADO"');
    $validation->setAllowBlank(true);
    $validation->setShowDropDown(true);
    
    // Validación para TIPO_ALUMNO (columna I)
    $validation = $sheet->getCell('I' . $row)->getDataValidation();
    $validation->setType(DataValidation::TYPE_LIST);
    $validation->setFormula1('"UNIVERSITARIO;COLEGIO;TRABAJADOR"');
    $validation->setAllowBlank(false);
    $validation->setShowDropDown(true);
    
    // Validación para ESTADO (columna M)
    $validation = $sheet->getCell('M' . $row)->getDataValidation();
    $validation->setType(DataValidation::TYPE_LIST);
    $validation->setFormula1('"1;0"');
    $validation->setAllowBlank(false);
    $validation->setShowDropDown(true);
}

// Configurar cabeceras HTTP para descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Plantilla_Alumnos_' . date('Y-m-d') . '.xlsx"');
header('Cache-Control: max-age=0');

// Crear writer y guardar
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit;
?>