<?php
// controllers/RequerimientoController.php
require_once 'models/Requerimiento.php';
require_once 'models/Avance.php';
require_once 'models/Notificacion.php';
require_once 'models/Usuario.php';

class RequerimientoController {
    private $requerimientoModel;
    private $avanceModel;
    private $notificacionModel;
    private $usuarioModel;
    private $anexoModel; 
    
    public function __construct() {
        $this->requerimientoModel = new Requerimiento();
        $this->avanceModel = new Avance();
        $this->notificacionModel = new Notificacion();
        $this->usuarioModel = new Usuario();
        $this->anexoModel = new Anexo();
    }
    
    // Listar requerimientos
    public function index() {
        // Verificar si el usuario está logueado
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        // Procesar filtros si existen
        $filtros = [];
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['filtrar'])) {
            if (!empty($_GET['estado'])) {
                $filtros['estado'] = sanitize($_GET['estado']);
            }
            if (!empty($_GET['prioridad'])) {
                $filtros['prioridad'] = sanitize($_GET['prioridad']);
            }
            if (!empty($_GET['fecha_desde'])) {
                $filtros['fecha_desde'] = sanitize($_GET['fecha_desde']);
            }
            if (!empty($_GET['fecha_hasta'])) {
                $filtros['fecha_hasta'] = sanitize($_GET['fecha_hasta']);
            }
        }
        
        // Obtener requerimientos según el rol
        if (isAdmin()) {
            $requerimientos = $this->requerimientoModel->listarTodos($filtros);
        } else {
            $requerimientos = $this->requerimientoModel->listarPorArea($_SESSION['area_id'], $filtros);
        }
        
        // Mostrar la vista
        include 'views/requerimientos/index.php';
    }
    
    // Mostrar formulario para crear requerimiento
    public function crear() {
        // Verificar si el usuario está logueado
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar el formulario
            
            // Sanitizar datos básicos
            $titulo = sanitize($_POST['titulo']);
            $descripcion = sanitize($_POST['descripcion']);
            $prioridad = sanitize($_POST['prioridad']);
            $nombre_solicitante = sanitize($_POST['nombre_solicitante']);
            $cargo = sanitize($_POST['cargo']);
            $email = sanitize($_POST['email']);
            $telefono = sanitize($_POST['telefono']);
            $objetivo = sanitize($_POST['objetivo']);
            $resultados = sanitize($_POST['resultados']);
            $funcionalidades = sanitize($_POST['funcionalidades']);
            $tecnologia = sanitize($_POST['tecnologia']);
            $usuarios = sanitize($_POST['usuarios']);
            $recursos = sanitize($_POST['recursos']);
            $restricciones = sanitize($_POST['restricciones']);
            $impacto = sanitize($_POST['impacto']);
            $kpi = sanitize($_POST['kpi']);
            $fecha_inicio = sanitize($_POST['fecha_inicio']);
            $fecha_entrega = sanitize($_POST['fecha_entrega']);
            $anexos = sanitize($_POST['anexos']);
            
            // Validar datos
            $errores = [];
            
            if (empty($titulo)) {
                $errores[] = 'El nombre del proyecto es obligatorio';
            }
            
            if (empty($descripcion)) {
                $errores[] = 'La descripción del problema es obligatoria';
            }
            
            if (empty($prioridad)) {
                $errores[] = 'La prioridad es obligatoria';
            }
            
            if (empty($nombre_solicitante)) {
                $errores[] = 'El nombre del solicitante es obligatorio';
            }
            
            if (empty($cargo)) {
                $errores[] = 'El cargo del solicitante es obligatorio';
            }
            
            if (empty($email)) {
                $errores[] = 'El correo electrónico es obligatorio';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'El correo electrónico no es válido';
            }
            
            if (empty($objetivo)) {
                $errores[] = 'El objetivo del desarrollo es obligatorio';
            }
            
            if (empty($resultados)) {
                $errores[] = 'Los resultados esperados son obligatorios';
            }
            
            if (empty($funcionalidades)) {
                $errores[] = 'Las funcionalidades clave son obligatorias';
            }
            
            if (empty($usuarios)) {
                $errores[] = 'La información de usuarios finales es obligatoria';
            }
            
            if (empty($impacto)) {
                $errores[] = 'El impacto esperado es obligatorio';
            }
            
            // Validar archivos si se han subido
            $tiposPermitidos = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'image/jpeg', 'image/png', 'image/gif', 'text/plain'];
            $tamanoMaximo = 10485760; // 10MB en bytes
            $archivosValidos = true;
            $archivosInfo = [];
            
            if (isset($_FILES['archivos_anexos']) && !empty($_FILES['archivos_anexos']['name'][0])) {
                $titulos = $_POST['anexos_titulos'] ?? [];
                
                for ($i = 0; $i < count($_FILES['archivos_anexos']['name']); $i++) {
                    if ($_FILES['archivos_anexos']['error'][$i] === UPLOAD_ERR_OK) {
                        $tipoArchivo = $_FILES['archivos_anexos']['type'][$i];
                        $tamanoArchivo = $_FILES['archivos_anexos']['size'][$i];
                        
                        if (!in_array($tipoArchivo, $tiposPermitidos)) {
                            $errores[] = 'El tipo de archivo "' . $_FILES['archivos_anexos']['name'][$i] . '" no está permitido';
                            $archivosValidos = false;
                        }
                        
                        if ($tamanoArchivo > $tamanoMaximo) {
                            $errores[] = 'El archivo "' . $_FILES['archivos_anexos']['name'][$i] . '" excede el tamaño máximo permitido';
                            $archivosValidos = false;
                        }
                        
                        if (empty($titulos[$i])) {
                            $errores[] = 'Debes proporcionar un título para cada archivo';
                            $archivosValidos = false;
                        }
                        
                        // Si el archivo es válido, guardar la información para procesarla después
                        if ($archivosValidos) {
                            $archivosInfo[] = [
                                'nombre' => $_FILES['archivos_anexos']['name'][$i],
                                'tmp_name' => $_FILES['archivos_anexos']['tmp_name'][$i],
                                'tipo' => $tipoArchivo,
                                'tamano' => $tamanoArchivo,
                                'titulo' => $titulos[$i]
                            ];
                        }
                    } else if ($_FILES['archivos_anexos']['error'][$i] !== UPLOAD_ERR_NO_FILE) {
                        // Si hay un error en la carga (excepto si no se seleccionó archivo)
                        $errores[] = 'Error al cargar el archivo "' . $_FILES['archivos_anexos']['name'][$i] . '"';
                        $archivosValidos = false;
                    }
                }
            }
            
            // Si no hay errores, guardar requerimiento
            if (empty($errores)) {
                $data = [
                    'titulo' => $titulo,
                    'descripcion' => $descripcion,
                    'prioridad' => $prioridad,
                    'area_id' => $_SESSION['area_id'],
                    'creado_por' => $_SESSION['user_id'],
                    'nombre_solicitante' => $nombre_solicitante,
                    'cargo' => $cargo,
                    'email' => $email,
                    'telefono' => $telefono,
                    'objetivo' => $objetivo,
                    'resultados' => $resultados,
                    'funcionalidades' => $funcionalidades,
                    'tecnologia' => $tecnologia,
                    'usuarios' => $usuarios,
                    'recursos' => $recursos,
                    'restricciones' => $restricciones,
                    'impacto' => $impacto,
                    'kpi' => $kpi,
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_entrega' => $fecha_entrega,
                    'anexos' => $anexos
                ];
                
                $requerimiento_id = $this->requerimientoModel->crear($data);
                
                if ($requerimiento_id) {
                    // Si hay archivos anexos, procesarlos
                    if (!empty($archivosInfo)) {
                        $directorioDestino = 'uploads/anexos/';
                        
                        // Crear el directorio si no existe
                        if (!is_dir($directorioDestino)) {
                            mkdir($directorioDestino, 0777, true);
                        }
                        
                        foreach ($archivosInfo as $archivo) {
                            $extension = pathinfo($archivo['nombre'], PATHINFO_EXTENSION);
                            $nombreArchivo = uniqid() . '_' . time() . '.' . $extension;
                            $rutaArchivo = $directorioDestino . $nombreArchivo;
                            
                            if (move_uploaded_file($archivo['tmp_name'], $rutaArchivo)) {
                                $dataAnexo = [
                                    'requerimiento_id' => $requerimiento_id,
                                    'titulo' => $archivo['titulo'],
                                    'nombre_archivo' => $archivo['nombre'],
                                    'ruta_archivo' => $rutaArchivo,
                                    'tipo_archivo' => $archivo['tipo'],
                                    'tamanio_archivo' => $archivo['tamano'],
                                    'usuario_id' => $_SESSION['user_id']
                                ];
                                
                                $this->anexoModel->crear($dataAnexo);
                            }
                        }
                    }
                    
                    setMessage('success', 'Solicitud de desarrollo digital creada correctamente');
                    redirect('requerimientos');
                } else {
                    setMessage('error', 'Ocurrió un error al crear la solicitud');
                }
            } else {
                // Si hay errores, mostrar de nuevo el formulario con los errores
                include 'views/requerimientos/crear.php';
            }
        } else {
            // Mostrar formulario
            include 'views/requerimientos/crear.php';
        }
    }
    // Ver detalle de requerimiento
    public function ver($id) {
        // Verificar si el usuario está logueado
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        // Obtener requerimiento
        $requerimiento = $this->requerimientoModel->obtenerPorId($id);
        
        // Verificar si existe el requerimiento
        if (!$requerimiento) {
            setMessage('error', 'La solicitud no existe');
            redirect('requerimientos');
        }
        
        // Verificar permisos (solo puede verlo el admin o usuarios de la misma área)
        if (!isAdmin() && $requerimiento['area_id'] != $_SESSION['area_id']) {
            setMessage('error', 'No tienes permisos para ver esta solicitud');
            redirect('requerimientos');
        }
        
        // Obtener avances del requerimiento
        $avances = $this->avanceModel->listarPorRequerimiento($id);

         // Obtener anexos del requerimiento
        $anexos = $this->anexoModel->obtenerPorRequerimiento($id);
        
        // Mostrar vista
        include 'views/requerimientos/ver.php';
    }
    
    // Cambiar estado del requerimiento
    public function cambiarEstado($id) {
        // Verificar si el usuario está logueado y es administrador
        if (!isLoggedIn() || !isAdmin()) {
            redirect('login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Obtener y sanitizar el nuevo estado
            $estado = sanitize($_POST['estado']);
            
            // Obtener requerimiento actual para verificar si cambió a finalizado
            $requerimientoActual = $this->requerimientoModel->obtenerPorId($id);
            $estadoAnterior = $requerimientoActual['estado'];
            
            // Actualizar estado
            if ($this->requerimientoModel->actualizarEstado($id, $estado)) {
                // Si el estado cambió a "finalizado", crear notificación
                if ($estado == 'finalizado' && $estadoAnterior != 'finalizado') {
                    // Crear notificación para el creador del requerimiento
                    $notificacion = [
                        'usuario_id' => $requerimientoActual['creado_por'],
                        'mensaje' => 'Tu solicitud "' . $requerimientoActual['titulo'] . '" ha sido marcada como finalizada.',
                        'requerimiento_id' => $id
                    ];
                    $this->notificacionModel->crear($notificacion);
                }
                
                setMessage('success', 'Estado actualizado correctamente');
            } else {
                setMessage('error', 'Ocurrió un error al actualizar el estado');
            }
            
            redirect('requerimientos/ver/' . $id);
        } else {
            redirect('requerimientos');
        }
    }
    
    // Editar requerimiento
    public function editar($id) {
        // Verificar si el usuario está logueado
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        // Obtener requerimiento
        $requerimiento = $this->requerimientoModel->obtenerPorId($id);
        
        // Verificar si existe el requerimiento
        if (!$requerimiento) {
            setMessage('error', 'La solicitud no existe');
            redirect('requerimientos');
        }
        
        // Verificar permisos (solo pueden editarlo el admin o el creador)
        if (!isAdmin() && $requerimiento['creado_por'] != $_SESSION['user_id']) {
            setMessage('error', 'No tienes permisos para editar esta solicitud');
            redirect('requerimientos');
        }
        
        // Obtener anexos existentes
        $anexos = $this->anexoModel->obtenerPorRequerimiento($id);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Procesar formulario
            
            // Sanitizar datos básicos
            $titulo = sanitize($_POST['titulo']);
            $descripcion = sanitize($_POST['descripcion']);
            $prioridad = sanitize($_POST['prioridad']);
            $nombre_solicitante = sanitize($_POST['nombre_solicitante']);
            $cargo = sanitize($_POST['cargo']);
            $email = sanitize($_POST['email']);
            $telefono = sanitize($_POST['telefono']);
            $objetivo = sanitize($_POST['objetivo']);
            $resultados = sanitize($_POST['resultados']);
            $funcionalidades = sanitize($_POST['funcionalidades']);
            $tecnologia = sanitize($_POST['tecnologia']);
            $usuarios = sanitize($_POST['usuarios']);
            $recursos = sanitize($_POST['recursos']);
            $restricciones = sanitize($_POST['restricciones']);
            $impacto = sanitize($_POST['impacto']);
            $kpi = sanitize($_POST['kpi']);
            $fecha_inicio = sanitize($_POST['fecha_inicio']);
            $fecha_entrega = sanitize($_POST['fecha_entrega']);
            $anexos_desc = sanitize($_POST['anexos']);
            
            // Validar datos
            $errores = [];
            
            if (empty($titulo)) {
                $errores[] = 'El nombre del proyecto es obligatorio';
            }
            
            if (empty($descripcion)) {
                $errores[] = 'La descripción del problema es obligatoria';
            }
            
            if (empty($prioridad)) {
                $errores[] = 'La prioridad es obligatoria';
            }
            
            if (empty($nombre_solicitante)) {
                $errores[] = 'El nombre del solicitante es obligatorio';
            }
            
            if (empty($cargo)) {
                $errores[] = 'El cargo del solicitante es obligatorio';
            }
            
            if (empty($email)) {
                $errores[] = 'El correo electrónico es obligatorio';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'El correo electrónico no es válido';
            }
            
            if (empty($objetivo)) {
                $errores[] = 'El objetivo del desarrollo es obligatorio';
            }
            
            if (empty($resultados)) {
                $errores[] = 'Los resultados esperados son obligatorios';
            }
            
            if (empty($funcionalidades)) {
                $errores[] = 'Las funcionalidades clave son obligatorias';
            }
            
            if (empty($usuarios)) {
                $errores[] = 'La información de usuarios finales es obligatoria';
            }
            
            if (empty($impacto)) {
                $errores[] = 'El impacto esperado es obligatorio';
            }
            
            // Validar archivos si se han subido
            $tiposPermitidos = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'image/jpeg', 'image/png', 'image/gif', 'text/plain'];
            $tamanoMaximo = 10485760; // 10MB en bytes
            $archivosValidos = true;
            $archivosInfo = [];
            
            if (isset($_FILES['archivos_anexos']) && !empty($_FILES['archivos_anexos']['name'][0])) {
                $titulos = $_POST['anexos_titulos'] ?? [];
                
                for ($i = 0; $i < count($_FILES['archivos_anexos']['name']); $i++) {
                    if ($_FILES['archivos_anexos']['error'][$i] === UPLOAD_ERR_OK) {
                        $tipoArchivo = $_FILES['archivos_anexos']['type'][$i];
                        $tamanoArchivo = $_FILES['archivos_anexos']['size'][$i];
                        
                        if (!in_array($tipoArchivo, $tiposPermitidos)) {
                            $errores[] = 'El tipo de archivo "' . $_FILES['archivos_anexos']['name'][$i] . '" no está permitido';
                            $archivosValidos = false;
                        }
                        
                        if ($tamanoArchivo > $tamanoMaximo) {
                            $errores[] = 'El archivo "' . $_FILES['archivos_anexos']['name'][$i] . '" excede el tamaño máximo permitido';
                            $archivosValidos = false;
                        }
                        
                        if (empty($titulos[$i])) {
                            $errores[] = 'Debes proporcionar un título para cada archivo';
                            $archivosValidos = false;
                        }
                        
                        // Si el archivo es válido, guardar la información para procesarla después
                        if ($archivosValidos) {
                            $archivosInfo[] = [
                                'nombre' => $_FILES['archivos_anexos']['name'][$i],
                                'tmp_name' => $_FILES['archivos_anexos']['tmp_name'][$i],
                                'tipo' => $tipoArchivo,
                                'tamano' => $tamanoArchivo,
                                'titulo' => $titulos[$i]
                            ];
                        }
                    } else if ($_FILES['archivos_anexos']['error'][$i] !== UPLOAD_ERR_NO_FILE) {
                        // Si hay un error en la carga (excepto si no se seleccionó archivo)
                        $errores[] = 'Error al cargar el archivo "' . $_FILES['archivos_anexos']['name'][$i] . '"';
                        $archivosValidos = false;
                    }
                }
            }
            
            // Si no hay errores, actualizar requerimiento
            if (empty($errores)) {
                $data = [
                    'id' => $id,
                    'titulo' => $titulo,
                    'descripcion' => $descripcion,
                    'prioridad' => $prioridad,
                    'nombre_solicitante' => $nombre_solicitante,
                    'cargo' => $cargo,
                    'email' => $email,
                    'telefono' => $telefono,
                    'objetivo' => $objetivo,
                    'resultados' => $resultados,
                    'funcionalidades' => $funcionalidades,
                    'tecnologia' => $tecnologia,
                    'usuarios' => $usuarios,
                    'recursos' => $recursos,
                    'restricciones' => $restricciones,
                    'impacto' => $impacto,
                    'kpi' => $kpi,
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_entrega' => $fecha_entrega,
                    'anexos' => $anexos_desc
                ];
                
                if ($this->requerimientoModel->actualizar($data)) {
                    // Si hay archivos anexos nuevos, procesarlos
                    if (!empty($archivosInfo)) {
                        $directorioDestino = 'uploads/anexos/';
                        
                        // Crear el directorio si no existe
                        if (!is_dir($directorioDestino)) {
                            mkdir($directorioDestino, 0777, true);
                        }
                        
                        foreach ($archivosInfo as $archivo) {
                            $extension = pathinfo($archivo['nombre'], PATHINFO_EXTENSION);
                            $nombreArchivo = uniqid() . '_' . time() . '.' . $extension;
                            $rutaArchivo = $directorioDestino . $nombreArchivo;
                            
                            if (move_uploaded_file($archivo['tmp_name'], $rutaArchivo)) {
                                $dataAnexo = [
                                    'requerimiento_id' => $id,
                                    'titulo' => $archivo['titulo'],
                                    'nombre_archivo' => $archivo['nombre'],
                                    'ruta_archivo' => $rutaArchivo,
                                    'tipo_archivo' => $archivo['tipo'],
                                    'tamanio_archivo' => $archivo['tamano'],
                                    'usuario_id' => $_SESSION['user_id']
                                ];
                                
                                $this->anexoModel->crear($dataAnexo);
                            }
                        }
                    }
                    
                    setMessage('success', 'Solicitud actualizada correctamente');
                    redirect('requerimientos/ver/' . $id);
                } else {
                    setMessage('error', 'Ocurrió un error al actualizar la solicitud');
                }
            } else {
                // Si hay errores, mostrar de nuevo el formulario con los errores
                include 'views/requerimientos/editar.php';
            }
        } else {
            // Mostrar formulario
            include 'views/requerimientos/editar.php';
        }
    }
    
    // Eliminar requerimiento
    public function eliminar($id) {
        // Verificar si el usuario está logueado y es administrador
        if (!isLoggedIn() || !isAdmin()) {
            redirect('login');
        }
        
        // Intentar eliminar
        if ($this->requerimientoModel->eliminar($id)) {
            setMessage('success', 'Solicitud eliminada correctamente');
        } else {
            setMessage('error', 'Ocurrió un error al eliminar la solicitud');
        }
        
        redirect('requerimientos');
    }
}
?>