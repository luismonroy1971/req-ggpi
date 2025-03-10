<?php
// controllers/AnexoController.php
require_once 'models/Anexo.php';
require_once 'models/Requerimiento.php';

class AnexoController {
    private $anexoModel;
    private $requerimientoModel;
    
    // Tipos de archivo permitidos y tamaño máximo
    private $tiposPermitidos = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                           'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                           'image/jpeg', 'image/png', 'image/gif', 'text/plain'];
    private $tamanoMaximo = 10485760; // 10MB en bytes
    
    public function __construct() {
        $this->anexoModel = new Anexo();
        $this->requerimientoModel = new Requerimiento();
    }
    
    // Subir un nuevo anexo
    public function subir($requerimiento_id) {
        // Verificar si el usuario está logueado
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        // Obtener requerimiento para verificar permisos
        $requerimiento = $this->requerimientoModel->obtenerPorId($requerimiento_id);
        
        // Verificar si existe el requerimiento
        if (!$requerimiento) {
            setMessage('error', 'El requerimiento no existe');
            redirect('requerimientos');
        }
        
        // Verificar permisos (solo pueden añadir anexos el admin o el creador)
        if (!isAdmin() && $requerimiento['creado_por'] != $_SESSION['user_id']) {
            setMessage('error', 'No tienes permisos para añadir anexos a este requerimiento');
            redirect('requerimientos/ver/' . $requerimiento_id);
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitizar datos
            $titulo = sanitize($_POST['titulo']);
            
            // Validar datos
            $errores = [];
            
            if (empty($titulo)) {
                $errores[] = 'El título del anexo es obligatorio';
            }
            
            // Verificar si se ha subido un archivo
            if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] != UPLOAD_ERR_OK) {
                $errores[] = 'Debes seleccionar un archivo';
            } else {
                // Validar tipo de archivo
                $tipoArchivo = $_FILES['archivo']['type'];
                if (!in_array($tipoArchivo, $this->tiposPermitidos)) {
                    $errores[] = 'El tipo de archivo no está permitido. Se permiten: PDF, Word, Excel, imágenes y texto.';
                }
                
                // Validar tamaño de archivo
                if ($_FILES['archivo']['size'] > $this->tamanoMaximo) {
                    $errores[] = 'El archivo es demasiado grande. El tamaño máximo es de 10MB.';
                }
            }
            
            // Si no hay errores, procesar el archivo
            if (empty($errores)) {
                // Obtener información del archivo
                $nombreOriginal = $_FILES['archivo']['name'];
                $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                $nombreArchivo = uniqid() . '_' . time() . '.' . $extension;
                
                // Definir directorio de destino
                $directorio = 'uploads/anexos/';
                
                // Crear el directorio si no existe
                if (!is_dir($directorio)) {
                    mkdir($directorio, 0777, true);
                }
                
                $rutaArchivo = $directorio . $nombreArchivo;
                
                // Mover el archivo subido al directorio destino
                if (move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaArchivo)) {
                    // Crear registro en la base de datos
                    $data = [
                        'requerimiento_id' => $requerimiento_id,
                        'titulo' => $titulo,
                        'nombre_archivo' => $nombreOriginal,
                        'ruta_archivo' => $rutaArchivo,
                        'tipo_archivo' => $tipoArchivo,
                        'tamanio_archivo' => $_FILES['archivo']['size'],
                        'usuario_id' => $_SESSION['user_id']
                    ];
                    
                    if ($this->anexoModel->crear($data)) {
                        setMessage('success', 'Anexo subido correctamente');
                    } else {
                        // Si falla la creación del registro, eliminar el archivo
                        if (file_exists($rutaArchivo)) {
                            unlink($rutaArchivo);
                        }
                        setMessage('error', 'Ocurrió un error al guardar el anexo');
                    }
                } else {
                    setMessage('error', 'Error al subir el archivo');
                }
            } else {
                // Si hay errores, mostrar mensaje
                setMessage('error', implode('<br>', $errores));
            }
            
            redirect('requerimientos/ver/' . $requerimiento_id);
        } else {
            // Si no es POST, redirigir a la vista del requerimiento
            redirect('requerimientos/ver/' . $requerimiento_id);
        }
    }
    
    // Descargar un anexo
    public function descargar($id) {
        // Verificar si el usuario está logueado
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        // Obtener anexo
        $anexo = $this->anexoModel->obtenerPorId($id);
        
        // Verificar si existe el anexo
        if (!$anexo) {
            setMessage('error', 'El anexo no existe');
            redirect('requerimientos');
        }
        
        // Verificar si el archivo existe
        if (!file_exists($anexo['ruta_archivo'])) {
            setMessage('error', 'El archivo no se encuentra en el servidor');
            redirect('requerimientos/ver/' . $anexo['requerimiento_id']);
        }
        
        // Obtener requerimiento para verificar permisos
        $requerimiento = $this->requerimientoModel->obtenerPorId($anexo['requerimiento_id']);
        
        // Verificar permisos (solo pueden ver anexos los usuarios de la misma área o admin)
        if (!isAdmin() && $requerimiento['area_id'] != $_SESSION['area_id']) {
            setMessage('error', 'No tienes permisos para descargar este anexo');
            redirect('requerimientos');
        }
        
        // Enviar el archivo al navegador
        header('Content-Type: ' . $anexo['tipo_archivo']);
        header('Content-Disposition: attachment; filename="' . $anexo['nombre_archivo'] . '"');
        header('Content-Length: ' . $anexo['tamanio_archivo']);
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        readfile($anexo['ruta_archivo']);
        exit;
    }
    
    // Eliminar un anexo
    public function eliminar($id) {
        // Verificar si el usuario está logueado
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        // Obtener anexo
        $anexo = $this->anexoModel->obtenerPorId($id);
        
        // Verificar si existe el anexo
        if (!$anexo) {
            setMessage('error', 'El anexo no existe');
            redirect('requerimientos');
        }
        
        // Obtener requerimiento para verificar permisos
        $requerimiento = $this->requerimientoModel->obtenerPorId($anexo['requerimiento_id']);
        
        // Verificar permisos (solo pueden eliminar anexos el admin o el creador)
        if (!isAdmin() && $requerimiento['creado_por'] != $_SESSION['user_id']) {
            setMessage('error', 'No tienes permisos para eliminar este anexo');
            redirect('requerimientos/ver/' . $anexo['requerimiento_id']);
        }
        
        // Eliminar anexo
        if ($this->anexoModel->eliminar($id)) {
            setMessage('success', 'Anexo eliminado correctamente');
        } else {
            setMessage('error', 'Ocurrió un error al eliminar el anexo');
        }
        
        redirect('requerimientos/ver/' . $anexo['requerimiento_id']);
    }
    
    // Editar un anexo (sólo el título)
    public function editar($id) {
        // Verificar si el usuario está logueado
        if (!isLoggedIn()) {
            redirect('login');
        }
        
        // Obtener anexo
        $anexo = $this->anexoModel->obtenerPorId($id);
        
        // Verificar si existe el anexo
        if (!$anexo) {
            setMessage('error', 'El anexo no existe');
            redirect('requerimientos');
        }
        
        // Obtener requerimiento para verificar permisos
        $requerimiento = $this->requerimientoModel->obtenerPorId($anexo['requerimiento_id']);
        
        // Verificar permisos (solo pueden editar anexos el admin o el creador)
        if (!isAdmin() && $requerimiento['creado_por'] != $_SESSION['user_id']) {
            setMessage('error', 'No tienes permisos para editar este anexo');
            redirect('requerimientos/ver/' . $anexo['requerimiento_id']);
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitizar datos
            $titulo = sanitize($_POST['titulo']);
            
            // Validar datos
            if (empty($titulo)) {
                setMessage('error', 'El título del anexo es obligatorio');
                redirect('requerimientos/ver/' . $anexo['requerimiento_id']);
            }
            
            // Actualizar anexo
            $data = [
                'id' => $id,
                'titulo' => $titulo
            ];
            
            if ($this->anexoModel->actualizar($data)) {
                setMessage('success', 'Anexo actualizado correctamente');
            } else {
                setMessage('error', 'Ocurrió un error al actualizar el anexo');
            }
            
            redirect('requerimientos/ver/' . $anexo['requerimiento_id']);
        } else {
            // Si no es POST, redirigir a la vista del requerimiento
            redirect('requerimientos/ver/' . $anexo['requerimiento_id']);
        }
    }
}
?>