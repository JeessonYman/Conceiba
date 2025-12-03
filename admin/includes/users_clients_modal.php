<!-- Modal de Clientes -->
<div class="modal fade" id="clientsModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b>Lista de Clientes</b></h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="clientsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Correo electrónico</th>
                                <th>Nombre</th>
                                <th>Estado</th>
                                <th>Fecha Agregada</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $conn = $pdo->open();
                                try{
                                    $stmt = $conn->prepare("SELECT * FROM users WHERE type=:type ORDER BY created_on DESC");
                                    $stmt->execute(['type'=>0]);
                                    foreach($stmt as $row){
                                        $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                                        $status = ($row['status']) ? '<span class="label label-success">Activo</span>' : '<span class="label label-danger">Inactivo</span>';
                                        
                                        echo "
                                            <tr>
                                                <td class='text-center'>
                                                    <img src='".$image."' height='40px' width='40px' class='img-circle'>
                                                </td>
                                                <td>".$row['email']."</td>
                                                <td>".$row['firstname'].' '.$row['lastname']."</td>
                                                <td class='text-center'>".$status."</td>
                                                <td>".date('d/m/Y', strtotime($row['created_on']))."</td>
                                                <td class='text-center'>
                                                    <button class='btn btn-warning btn-sm promote-admin-btn' data-id='".$row['id']."' data-name='".htmlspecialchars($row['firstname'].' '.$row['lastname'])."' title='Promover a Administrador'>
                                                        <i class='fa fa-star'></i> Hacer Admin
                                                    </button>
                                                </td>
                                            </tr>
                                        ";
                                    }
                                }
                                catch(PDOException $e){
                                    echo "<tr><td colspan='6' class='text-center text-danger'>Error: ".$e->getMessage()."</td></tr>";
                                }
                                $pdo->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">
                    <i class="fa fa-close"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Promover a Admin -->
<div class="modal fade" id="promoteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b>Promover a Administrador</b></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="promote_user_id">
                <div class="text-center">
                    <p>¿Está seguro de que desea promover a este usuario a administrador?</p>
                    <h3 class="bold" id="promote_user_name"></h3>
                    <p class="text-muted">Esta acción le dará permisos administrativos completos.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">
                    <i class="fa fa-close"></i> Cancelar
                </button>
                <button type="button" class="btn btn-warning btn-flat" id="confirmPromoteBtn">
                    <i class="fa fa-star"></i> Promover a Admin
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos responsivos para el modal de clientes */
@media (max-width: 767px) {
    .modal-dialog.modal-lg {
        width: 95%;
        margin: 10px auto;
    }
    
    #clientsTable {
        font-size: 12px;
    }
    
    #clientsTable th,
    #clientsTable td {
        padding: 8px 4px;
    }
    
    #clientsTable img {
        height: 30px !important;
        width: 30px !important;
    }
    
    .btn-sm {
        padding: 3px 6px;
        font-size: 11px;
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .modal-dialog.modal-lg {
        width: 90%;
    }
}

@media (min-width: 992px) {
    .modal-dialog.modal-lg {
        width: 900px;
    }
}

@media (max-width: 767px) and (orientation: landscape) {
    .modal-dialog {
        width: 95%;
    }
    
    .modal-body {
        max-height: 300px;
        overflow-y: auto;
    }
}

@media (min-width: 768px) and (max-width: 1024px) {
    #clientsTable {
        font-size: 13px;
    }
}
</style>

<script>
// IMPORTANTE: Esperar a que jQuery esté completamente cargado
if (typeof jQuery === 'undefined') {
    console.error('❌ jQuery no está cargado aún. Esperando...');
    document.addEventListener('DOMContentLoaded', function() {
        initClientModal();
    });
} else {
    $(document).ready(function() {
        initClientModal();
    });
}

function initClientModal() {
    'use strict';
    
    console.log('🔧 [MODAL CLIENTES] Inicializando...');
    
    var clientsTableInitialized = false;
    
    // EVENTO: Abrir modal de clientes
    $('#clientsModal').on('shown.bs.modal', function () {
        console.log('📂 [MODAL CLIENTES] Modal abierto');
        
        if (!clientsTableInitialized) {
            console.log('⚙️ [DATATABLE] Inicializando DataTable de clientes...');
            
            try {
                var table = $('#clientsTable').DataTable({
                    responsive: true,
                    autoWidth: false,
                    pageLength: 10,
                    order: [[4, "desc"]],
                    language: {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron clientes",
                        "sEmptyTable": "No hay clientes registrados",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ clientes",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 clientes",
                        "sInfoFiltered": "(filtrado de _MAX_ clientes)",
                        "sSearch": "Buscar:",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        }
                    }
                });
                
                clientsTableInitialized = true;
                console.log('✅ [DATATABLE] Inicializado correctamente');
            } catch(e) {
                console.error('❌ [DATATABLE] Error:', e);
            }
        }
    });
    
    // Función para mostrar alertas en la página (usa el contenedor #ajax-alerts)
    function showPageAlert(type, message) {
        try {
            var alertType = (type === 'success') ? 'alert-success' : 'alert-danger';
            var $container = $('#ajax-alerts');
            if ($container.length === 0) {
                // Si no existe el contenedor, crear uno en la parte superior del contenido
                $container = $('<div id="ajax-alerts" style="margin:10px 0;"></div>');
                $('.content').first().prepend($container);
            }

            var $alert = $('<div class="alert ' + alertType + ' alert-dismissible" role="alert">'
                + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
                + message + '</div>');

            $container.prepend($alert);

            // Auto-dismiss después de 6 segundos
            setTimeout(function() { $alert.alert('close'); }, 6000);
        } catch (e) {
            console.error('Error mostrando alerta en la página:', e);
        }
    }
    
    // EVENTO: Cerrar modal de clientes
    $('#clientsModal').on('hidden.bs.modal', function () {
        console.log('🔒 [MODAL CLIENTES] Modal cerrado');
        
        if (clientsTableInitialized) {
            try {
                $('#clientsTable').DataTable().destroy();
                clientsTableInitialized = false;
                console.log('🗑️ [DATATABLE] Destruido correctamente');
            } catch(e) {
                console.error('⚠️ [DATATABLE] Error al destruir:', e);
            }
        }
    });
    
    // EVENTO: Click en botón "Hacer Admin"
    $(document).on('click', '.promote-admin-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var userId = $(this).data('id');
        var userName = $(this).data('name');
        
        console.log('⭐ [PROMOVER] Iniciando promoción');
        console.log('   - User ID:', userId);
        console.log('   - User Name:', userName);
        
        if (!userId) {
            alert('❌ Error: No se pudo obtener el ID del usuario');
            console.error('❌ [PROMOVER] ID de usuario no válido');
            return false;
        }
        
        // Guardar datos
        $('#promote_user_id').val(userId);
        $('#promote_user_name').text(userName);
        
        // Cambiar de modal
        $('#clientsModal').modal('hide');
        
        setTimeout(function() {
            $('#promoteModal').modal('show');
            console.log('✅ [MODAL CONFIRMAR] Abierto');
        }, 500);
        
        return false;
    });
    
    // EVENTO: Confirmar promoción
    $(document).on('click', '#confirmPromoteBtn', function(e) {
        e.preventDefault();
        
        var userId = $('#promote_user_id').val();
        var button = $(this);
        
        console.log('🚀 [AJAX] Confirmando promoción para user ID:', userId);
        
        if (!userId) {
            alert('❌ Error: ID de usuario no encontrado');
            console.error('❌ [AJAX] ID vacío');
            return;
        }
        
        // Deshabilitar botón
        button.prop('disabled', true);
        button.html('<i class="fa fa-spinner fa-spin"></i> Procesando...');
        
        $.ajax({
            type: 'POST',
            url: 'includes/users_promote.php',
            data: { id: userId },
            dataType: 'json',
            timeout: 10000,
            success: function(response) {
                    console.log('✅ [AJAX] Respuesta recibida:', response);

                    button.prop('disabled', false);
                    button.html('<i class="fa fa-star"></i> Promover a Admin');

                    if (response.success) {
                        $('#promoteModal').modal('hide');
                        showPageAlert('success', response.message);

                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        showPageAlert('danger', response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ [AJAX] Error completo:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText,
                        error: error
                    });

                    button.prop('disabled', false);
                    button.html('<i class="fa fa-star"></i> Promover a Admin');

                    var errorMsg = 'Error al promover el usuario.';
                    if (xhr.status === 404) {
                        errorMsg = 'Archivo no encontrado (404). Verifica: admin/includes/users_promote.php';
                    } else if (xhr.status === 500) {
                        errorMsg = 'Error del servidor (500). Revisa el log de debug.';
                    } else if (xhr.status === 0) {
                        errorMsg = 'No se pudo conectar al servidor. Verifica tu conexión.';
                    } else {
                        errorMsg = 'Status: ' + xhr.status + ' ' + xhr.statusText;
                    }

                    showPageAlert('danger', errorMsg);
                }
        });
    });
    
    // EVENTO: Al cerrar modal de confirmación
    $('#promoteModal').on('hidden.bs.modal', function () {
        console.log('🔒 [MODAL CONFIRMAR] Cerrado');
        
        setTimeout(function() {
            $('#clientsModal').modal('show');
        }, 500);
    });
    
    console.log('✅ [MODAL CLIENTES] Event listeners registrados');

};
</script>