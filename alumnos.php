<?php
include 'conexion/bd.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title></title>
  <meta
    content="width=device-width, initial-scale=3.0, shrink-to-fit=no"
    name="viewport" />
  <link
    rel="icon"
    href="assets/img/kaiadmin/favicon.ico"
    type="image/x-icon" />

  <!-- Fonts and icons -->
  <script src="assets/js/plugin/webfont/webfont.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@33"></script>
  <script>
    WebFont.load({
      google: {
        families: ["Public Sans:300,400,500,600,700"]
      },
      custom: {
        families: [
          "Font Awesome 5 Solid",
          "Font Awesome 5 Regular",
          "Font Awesome 5 Brands",
          "simple-line-icons",
        ],
        urls: ["assets/css/fonts.min.css"],
      },
      active: function() {
        sessionStorage.fonts = true;
      },
    });
  </script>

  <!-- CSS Files -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/plugins.min.css" />
  <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link rel="stylesheet" href="assets/css/demo.css" />
</head>

<body>
  <div class="wrapper">
    <?php
    include 'includes/sidebar.php'
    ?>

    <div class="main-panel">
      <?php
      include 'includes/header.php'
      ?>

      <div class="container">
        <div class="page-inner">
          <div class="page-header">
            <h3 class="fw-bold mb-3">Alumnos</h3>
            <ul class="breadcrumbs mb-3">
              <li class="nav-home">
                <a href="index.php">
                  <i class="icon-home"></i>
                </a>
              </li>
              <li class="separator">
                <i class="icon-arrow-right"></i>
              </li>
              <li class="nav-item">
                <a href="#">Gestión</a>
              </li>
              <li class="separator">
                <i class="icon-arrow-right"></i>
              </li>
              <li class="nav-item">
                <a href="#">Alumnos</a>
              </li>
            </ul>
          </div>
          <div class="row">
            <div class="col-md-32">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h4 class="card-title">Lista de Alumnos</h4>
                  <div>
                    <button type="button" class="btn btn-success btn-sm me-2" id="btn-descargar-csv">
                      <i class="icon-cloud-download"></i> Descargar CSV
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAgregarAlumno">
                      <i class="icon-plus"></i> Agregar Alumno
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table
                      id="multi-filter-select"
                      class="display table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>Nombres</th>
                          <th>Apellido Paterno</th>
                          <th>Apellido Materno</th>
                          <th>Documento</th>
                          <th>Email</th>
                          <th>Tipo Alumno</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                          <th>Id</th>
                          <th>Nombres</th>
                          <th>Apellido Paterno</th>
                          <th>Apellido Materno</th>
                          <th>Documento</th>
                          <th>Email</th>
                          <th>Tipo Alumno</th>
                          <th>Acciones</th>
                        </tr>
                      </tfoot>
                      <tbody>
                        <?php
                        $sql = "SELECT id, nombres, apellido_pa, apellido_ma, tipo_documento, numero_documento, 
                                       email, direccion, estado_civil, tipo_alumno, puesto_trabajo, centro_estudio, 
                                       fecha_nacimiento, estado
                                FROM alumno";

                        $stmt = $conn->prepare($sql);
                        $stmt->execute();

                        $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($alumnos as $alumno) {
                          echo "<tr>";
                          echo "<td>{$alumno['id']}</td>";
                          echo "<td>{$alumno['nombres']}</td>";
                          echo "<td>{$alumno['apellido_pa']}</td>";
                          echo "<td>{$alumno['apellido_ma']}</td>";
                          
                          // Mostrar tipo de documento
                                                      $tipo_doc = '';
                            switch($alumno['tipo_documento']) {
                            case 1: $tipo_doc = 'DNI'; break;
                            case 2: $tipo_doc = 'Carnet Ext.'; break;
                            case 3: $tipo_doc = 'Pasaporte'; break;
                            default: $tipo_doc = 'N/A'; break;
                            }
                            echo "<td>{$tipo_doc} - {$alumno['numero_documento']}</td>";
                          echo "<td>{$alumno['email']}</td>";
                          
                          // Mostrar tipo de alumno
                          $tipo_alumno = '';
                          switch($alumno['tipo_alumno']) {
                            case 'UNIVERSITARIO': $tipo_alumno = 'Universitario'; break;
                            case 'COLEGIO': $tipo_alumno = 'Colegio'; break;
                            case 'TRABAJADOR': $tipo_alumno = 'Trabajador'; break;
                            default: $tipo_alumno = $alumno['tipo_alumno']; break;
                          }
                          echo "<td>{$tipo_alumno}</td>";
                          
                          // Botones de acciones
                          echo "<td style='white-space: nowrap;'>";
                          
                          // Botón Ver detalles
                          echo "<button type='button' data-id='" . $alumno['id'] . "' class='btn btn-sm btn-info btn-ver-alumno me-1' title='Ver detalles'><i class='icon-eye'></i></button>";
                          
                          // Botón Editar
                          echo "<button type='button' data-id='" . $alumno['id'] . "' class='btn btn-sm btn-warning btn-editar-alumno me-1' title='Editar alumno'><i class='icon-pencil'></i></button>";
                          
                          // Botón Eliminar
                          echo "<button type='button' data-id='" . $alumno['id'] . "' class='btn btn-sm btn-danger btn-eliminar-alumno' title='Eliminar alumno'><i class='icon-trash'></i></button>";
                          
                          echo "</td>";
                          echo "</tr>";
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php
    include 'includes/colores.php'
    ?>
    
    <!-- MODALES -->
    <?php 
    include 'modals/alumno/agregar.php'
    ?>
    <?php 
    include 'modals/alumno/editar.php'
    ?>
    <?php 
    include 'modals/alumno/ver.php'
    ?>
    <?php 
    include 'modals/alumno/eliminar.php'
    ?>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!--   Core JS Files   -->
  <script src="assets/js/core/jquery-3.7.1.min.js"></script>
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>

  <!-- jQuery Scrollbar -->
  <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

  <!-- Chart JS -->
  <script src="assets/js/plugin/chart.js/chart.min.js"></script>

  <!-- jQuery Sparkline -->
  <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

  <!-- Chart Circle -->
  <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

  <!-- Datatables -->
  <script src="assets/js/plugin/datatables/datatables.min.js"></script>

  <!-- jQuery Vector Maps -->
  <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
  <script src="assets/js/plugin/jsvectormap/world.js"></script>

  <!-- Sweet Alert -->
  <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

  <!-- Kaiadmin JS -->
  <script src="assets/js/kaiadmin.min.js"></script>

  <!-- Kaiadmin DEMO methods, don't include it in your project! -->
  <script src="assets/js/setting-demo.js"></script>
  <script src="assets/js/demo.js"></script>

  <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
  
  <script>
    $(document).ready(function() {
      // INICIALIZACIÓN DE DATATABLES
      var table = $("#multi-filter-select").DataTable({
        pageLength: 5,
        initComplete: function() {
          this.api().columns().every(function(index) {
            var column = this;
            // Si la columna es la de acciones, evitamos poner filtro
            if (index === 9) { // la última columna (Acciones)
              return;
            }

            var select = $('<select class="form-select"><option value="">Todos</option></select>')
              .appendTo($(column.footer()).empty())
              .on("change", function() {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                column.search(val ? "^" + val + "$" : "", true, false).draw();
              });

            // Para el resto de columnas, extraemos los valores únicos
            var valueSet = new Set();
            column.nodes().each(function(cell) {
              // Obtenemos solo el texto de la celda, sin HTML
              var textValue = $(cell).text().trim();
              if (textValue && textValue !== 'N/A') {
                valueSet.add(textValue);
              }
            });

            // Convertimos el Set a Array, ordenamos y agregamos como opciones
            Array.from(valueSet).sort().forEach(function(text) {
              select.append('<option value="' + text + '">' + text + '</option>');
            });
          });
        },
        language: {
          "sProcessing": "Procesando...",
          "sLengthMenu": "Mostrar _MENU_ registros",
          "sZeroRecords": "No se encontraron resultados",
          "sEmptyTable": "Ningún dato disponible en esta tabla",
          "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
          "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
          "sInfoPostFix": "",
          "sSearch": "Buscar:",
          "sUrl": "",
          "sInfoThousands": ",",
          "sLoadingRecords": "Cargando...",
          "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Último",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
          },
          "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
          }
        }
      });

      // EVENTOS PARA LOS BOTONES
      
      // Descargar CSV
      $(document).on('click', '#btn-descargar-csv', function() {
        window.location.href = 'acciones/alumnos/alumnos_csv.php';
      });
      
      // Ver detalles del alumno
      $(document).on('click', '.btn-ver-alumno', function() {
        var id = $(this).data('id');
        // Aquí puedes agregar la lógica para cargar los datos en el modal de ver
        $('#modalVerAlumno').modal('show');
      });

      // Editar alumno
      $(document).on('click', '.btn-editar-alumno', function() {
        var id = $(this).data('id');
        // Aquí puedes agregar la lógica para cargar los datos en el modal de editar
        $('#modalEditarAlumno').modal('show');
      });

      // Eliminar alumno
      $(document).on('click', '.btn-eliminar-alumno', function() {
        var id = $(this).data('id');
        
        Swal.fire({
          title: '¿Estás seguro?',
          text: "No podrás revertir esta acción",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            // Aquí puedes agregar la lógica para eliminar el alumno
            // Por ejemplo, una petición AJAX para eliminar
            console.log('Eliminar alumno con ID: ' + id);
          }
        });
      });
      
    });
  </script>
</body>

</html>