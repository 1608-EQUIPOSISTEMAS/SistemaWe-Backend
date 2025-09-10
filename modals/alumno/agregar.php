<!-- Modal Agregar Alumno -->
<div class="modal fade" id="modalAgregarAlumno" tabindex="-1" aria-labelledby="modalAgregarAlumnoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAgregarAlumnoLabel">Agregar Alumno</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <!-- Pestañas -->
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-individual-tab" data-bs-toggle="pill" data-bs-target="#pills-individual" type="button" role="tab">
              <i class="icon-user"></i> Individual
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-masivo-tab" data-bs-toggle="pill" data-bs-target="#pills-masivo" type="button" role="tab">
              <i class="icon-cloud-upload"></i> Carga Masiva (Excel)
            </button>
          </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
          
          <!-- TAB INDIVIDUAL -->
          <div class="tab-pane fade show active" id="pills-individual" role="tabpanel">
            <form id="formAgregarAlumno">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="nombres" class="form-label">Nombres <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombres" name="nombres" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="apellido_pa" class="form-label">Apellido Paterno <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="apellido_pa" name="apellido_pa" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="apellido_ma" class="form-label">Apellido Materno</label>
                    <input type="text" class="form-control" id="apellido_ma" name="apellido_ma">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group mb-3">
                    <label for="tipo_documento" class="form-label">Tipo Documento <span class="text-danger">*</span></label>
                    <select class="form-select" id="tipo_documento" name="tipo_documento" required>
                      <option value="">Seleccionar...</option>
                      <option value="1">DNI</option>
                      <option value="2">Carnet Extranjería</option>
                      <option value="3">Pasaporte</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group mb-3">
                    <label for="numero_documento" class="form-label">Número de Documento <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="numero_documento" name="numero_documento" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="estado_civil" class="form-label">Estado Civil</label>
                    <select class="form-select" id="estado_civil" name="estado_civil">
                      <option value="">Seleccionar...</option>
                      <option value="SOLTERO">Soltero(a)</option>
                      <option value="CASADO">Casado(a)</option>
                      <option value="VIUDO">Viudo(a)</option>
                      <option value="DIVORCIADO">Divorciado(a)</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group mb-3">
                    <label for="tipo_alumno" class="form-label">Tipo de Alumno <span class="text-danger">*</span></label>
                    <select class="form-select" id="tipo_alumno" name="tipo_alumno" required>
                      <option value="">Seleccionar...</option>
                      <option value="UNIVERSITARIO">Universitario</option>
                      <option value="COLEGIO">Colegio</option>
                      <option value="TRABAJADOR">Trabajador</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group mb-3">
                    <label for="centro_estudio" class="form-label">Centro de Estudio</label>
                    <input type="text" class="form-control" id="centro_estudio" name="centro_estudio">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group mb-3">
                    <label for="puesto_trabajo" class="form-label">Puesto de Trabajo</label>
                    <input type="text" class="form-control" id="puesto_trabajo" name="puesto_trabajo">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group mb-3">
                    <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                    <select class="form-select" id="estado" name="estado" required>
                      <option value="1" selected>Activo</option>
                      <option value="0">Inactivo</option>
                    </select>
                  </div>
                </div>
              </div>
            </form>
          </div>

          <!-- TAB CARGA MASIVA -->
          <div class="tab-pane fade" id="pills-masivo" role="tabpanel">
            <div class="row">
              <div class="col-md-12">
                
                <!-- Instrucciones -->
                <div class="alert alert-info">
                  <h6 class="alert-heading">
                    <i class="icon-info"></i> Instrucciones para carga masiva:
                  </h6>
                  <ol class="mb-2">
                    <li>Descarga la plantilla de Excel haciendo clic en el botón "Descargar Plantilla"</li>
                    <li>Llena los datos siguiendo el formato de ejemplo</li>
                    <li>Guarda el archivo y súbelo usando el botón "Seleccionar archivo"</li>
                    <li>Haz clic en "Procesar archivo" para importar los datos</li>
                  </ol>
                  <small class="text-muted">
                    <strong>Nota:</strong> Los campos marcados con * son obligatorios
                  </small>
                </div>

                <!-- Botón descargar plantilla -->
                <div class="mb-3">
                  <button type="button" class="btn btn-success" id="btn-descargar-plantilla">
                    <i class="icon-cloud-download"></i> Descargar Plantilla Excel
                  </button>
                </div>

                <!-- Upload de archivo -->
                <form id="formCargaMasiva" enctype="multipart/form-data">
                  <div class="form-group mb-3">
                    <label for="archivo_excel" class="form-label">Seleccionar archivo Excel <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" id="archivo_excel" name="archivo_excel" accept=".xlsx,.xls" required>
                    <small class="form-text text-muted">Solo archivos .xlsx o .xls (máximo 5MB)</small>
                  </div>

                  <!-- Progress bar (oculta inicialmente) -->
                  <div class="progress mb-3" id="progress-bar" style="display: none;">
                    <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                  </div>

                  <!-- Resultados (oculto inicialmente) -->
                  <div id="resultados-carga" style="display: none;">
                    <div class="alert alert-success" id="exito-carga" style="display: none;">
                      <h6><i class="icon-check"></i> Carga exitosa</h6>
                      <p id="mensaje-exito"></p>
                    </div>
                    <div class="alert alert-warning" id="errores-carga" style="display: none;">
                      <h6><i class="icon-exclamation-triangle"></i> Se encontraron errores</h6>
                      <div id="lista-errores"></div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          
        </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        
        <!-- Botones según la pestaña activa -->
        <button type="button" class="btn btn-primary" id="btn-guardar-individual">
          <i class="icon-check"></i> Guardar Alumno
        </button>
        
        <button type="button" class="btn btn-primary" id="btn-procesar-excel" style="display: none;">
          <i class="icon-cloud-upload"></i> Procesar Archivo
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<!-- JavaScript del Modal -->
<script>
  $(document).ready(function() {
    
    // Cambiar botones según la pestaña activa
    $('#pills-tab button').on('click', function() {
      if ($(this).attr('id') === 'pills-individual-tab') {
        $('#btn-guardar-individual').show();
        $('#btn-procesar-excel').hide();
      } else {
        $('#btn-guardar-individual').hide();
        $('#btn-procesar-excel').show();
      }
    });

    // Descargar plantilla Excel
    $('#btn-descargar-plantilla').click(function() {
      window.location.href = 'acciones/alumnos/descargar_plantilla.php';
    });

    // Guardar alumno individual
    $('#btn-guardar-individual').click(function() {
      if ($('#formAgregarAlumno')[0].checkValidity()) {
        // Aquí va la lógica AJAX para guardar
        var formData = new FormData($('#formAgregarAlumno')[0]);
        
        $.ajax({
          url: 'acciones/alumnos/agregar_individual.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          dataType: 'json', // Forzar interpretación como JSON
          success: function(response) {
            console.log('Respuesta del servidor:', response); // Debug
            console.log('Tipo de respuesta:', typeof response); // Debug
            
            try {
              // Si la respuesta ya es un objeto, no necesita JSON.parse
              var result = typeof response === 'string' ? JSON.parse(response) : response;
              
              if (result.success) {
                Swal.fire('Éxito', result.message, 'success').then(() => {
                  $('#modalAgregarAlumno').modal('hide');
                  location.reload(); // Recargar la página
                });
              } else {
                Swal.fire('Error', result.message, 'error');
              }
            } catch (e) {
              console.error('Error parseando JSON:', e);
              console.error('Respuesta completa:', response);
              Swal.fire('Error', 'Respuesta del servidor inválida. Ver consola para detalles.', 'error');
            }
          },
          error: function(xhr, status, error) {
            console.error('Error AJAX:', error);
            console.error('Status:', status);
            console.error('Response:', xhr.responseText);
            Swal.fire('Error', 'Ocurrió un error al procesar la solicitud: ' + error, 'error');
          }
        });
      } else {
        $('#formAgregarAlumno')[0].reportValidity();
      }
    });

    // Procesar archivo Excel
    $('#btn-procesar-excel').click(function() {
      var archivo = $('#archivo_excel')[0].files[0];
      if (!archivo) {
        Swal.fire('Atención', 'Debe seleccionar un archivo Excel', 'warning');
        return;
      }

      var formData = new FormData();
      formData.append('archivo_excel', archivo);

      $('#progress-bar').show();
      $('#resultados-carga').hide();

      $.ajax({
        url: 'acciones/alumnos/procesar_excel.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json', // Forzar interpretación como JSON
        xhr: function() {
          var xhr = new window.XMLHttpRequest();
          xhr.upload.addEventListener("progress", function(evt) {
            if (evt.lengthComputable) {
              var percentComplete = evt.loaded / evt.total * 100;
              $('.progress-bar').css('width', percentComplete + '%');
            }
          }, false);
          return xhr;
        },
        success: function(response) {
          $('#progress-bar').hide();
          console.log('Respuesta Excel:', response); // Debug
          console.log('Tipo respuesta Excel:', typeof response); // Debug
          
          try {
            // Si la respuesta ya es un objeto, no necesita JSON.parse
            var result = typeof response === 'string' ? JSON.parse(response) : response;
            
            $('#resultados-carga').show();
            
            if (result.success) {
              $('#mensaje-exito').text(result.message);
              $('#exito-carga').show();
              
              if (result.errores && result.errores.length > 0) {
                $('#lista-errores').html('<ul>' + result.errores.map(e => '<li>' + e + '</li>').join('') + '</ul>');
                $('#errores-carga').show();
              }
              
              setTimeout(() => {
                $('#modalAgregarAlumno').modal('hide');
                location.reload();
              }, 3000);
            } else {
              Swal.fire('Error', result.message, 'error');
            }
          } catch (e) {
            console.error('Error parseando JSON Excel:', e);
            console.error('Respuesta Excel completa:', response);
            Swal.fire('Error', 'Respuesta del servidor inválida. Ver consola para detalles.', 'error');
          }
        },
        error: function() {
          $('#progress-bar').hide();
          Swal.fire('Error', 'Ocurrió un error al procesar el archivo', 'error');
        }
      });
    });

    // Limpiar formulario al cerrar modal
    $('#modalAgregarAlumno').on('hidden.bs.modal', function() {
      $('#formAgregarAlumno')[0].reset();
      $('#formCargaMasiva')[0].reset();
      $('#resultados-carga').hide();
      $('#progress-bar').hide();
      $('.progress-bar').css('width', '0%');
    });
  });
</script>