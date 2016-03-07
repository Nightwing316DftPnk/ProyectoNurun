<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct(){
		parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('mdl_general');
        $this->load->library("pagination");
	}

    public function register(){

        $this->load->library('facebook');
        $user = $this->facebook->getUser();
        if ($user) {
            try {
                $data['user_profile'] = $this->facebook->api('/me');

                $urlImagen = "https://graph.facebook.com/".$data['user_profile']['id']."/picture?type=large";
                $nuevaImg = "imagenNueva".$data['user_profile']['id'].".jpg";
                $nuevaImagen = "resources/".$nuevaImg;
                $originalImagen = "resources/originales/".$nuevaImg;

                if (!copy($urlImagen, $originalImagen)) {
                    echo "Error al copiar $urlImagen...\n";
                }

                if (!copy($urlImagen, $nuevaImagen)) {
                    echo "Error al copiar $urlImagen...\n";
                }else{
                    $im = imagecreatefromjpeg($nuevaImagen);

                    if($im && imagefilter($im, IMG_FILTER_GRAYSCALE)){
                            imagejpeg($im, $nuevaImagen);
                        }else{
                            echo 'La conversión a escala de grises falló.';
                        }
                }

                $datos = $this->mdl_general->obtener_usuario ($data['user_profile']);
                
                $data['user_profile']['url'] = $nuevaImagen;
                
                
                if(empty($datos)){
                    $respuesta = $this->mdl_general->insertar($data['user_profile']);
                }
                $data['datos'] = $datos;

            } catch (FacebookApiException $e) {
                $user = null;
            }
        }else {
            $this->facebook->destroySession();
        }

        if ($user) {
            $data['logout_url'] = site_url('welcome/logout'); 
            $data['other_log_out']='';
        } else {
            $data['login_url'] = $this->facebook->getLoginUrl(array(
                'redirect_uri' => site_url('welcome/index'), 
                'scope' => array("email") // permissions here
            ));
            $data['register_url'] = $this->facebook->getLoginUrl(array(
                'redirect_uri' => site_url('welcome/register'), 
                'scope' => array("email") // permissions here
            ));

        }

        $config = array();
        $config["base_url"] = base_url() . "welcome/index";
        $config["total_rows"] = $this->mdl_general->record_count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $registros = $this->mdl_general->obtenerRegistros($data['user_profile']['id'],$config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $data['registros'] = $registros;

        $this->load->view('inicio/header');
        $this->load->view('login',$data);
        $this->load->view('inicio/footer');
    }

    public function changePassword(){
        if(isset($_POST['id']) && !empty($_POST['id'])){
            $id = $_POST['id'];
        }if(isset($_POST['password']) && !empty($_POST['password'])){
            $pass = $_POST['password'];
        }

        $datos = array(
            'id' => $id,
            'password' => $pass,
            'login_inicio' => 1
        );

        $data = $this->mdl_general->obtener_usuarioPass($datos['id']);

        if($data){
            $respuesta = $this->mdl_general->cambiar_pass($datos);

            if($respuesta){
                echo '<div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        Contraseña modificada correctamente
                    </div>';
            }else{
                echo '<div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        Error al cambiar la contraseña
                    </div>';
            }

        }else{
             echo '<div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        Debe tener un usuario para poder cambiar su contraseña
                    </div>';
        }
    }

    public function borrarReg(){
        if(isset($_POST['identi']) && !empty($_POST['identi'])){
            $identi = $_POST['identi'];
        }

        $respuesta = $this->mdl_general->borrar_reg($identi);

        if($respuesta){
            echo '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    Registro borrado correctamente
                </div>';
        }else{
            echo '<div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    Error al borrar registro
                </div>';
        }
    }

    public function capturarCifra(){
        if(isset($_POST['usr']) && !empty($_POST['usr'])){
            $id = $_POST['usr'];
        }if(isset($_POST['cifra']) && !empty($_POST['cifra'])){
            $cifra = $_POST['cifra'];
        }

        $datos = array(
            'cifra' => $cifra,
            'usr' => $id
        );

        $cifra = $this->mdl_general->obtenerCifras($cifra);
           if (empty($cifra)) {
              $respuesta = $this->mdl_general->insertarCifra($datos);

                if($respuesta){
                    echo '<div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            Cifra guardada correctamente.
                        </div>';
                }else{
                    echo '<div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            Error al guardar la cifra.
                        </div>';
                }
           }else{
                    echo '<div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            La cifra ya a sido registrada.
                        </div>';
           }
        
    }


	public function index(){

		$this->load->library('facebook');
		$user = $this->facebook->getUser();
        if ($user) {
            try {
                $data['user_profile'] = $this->facebook->api('/me');

                $urlImagen = "https://graph.facebook.com/".$data['user_profile']['id']."/picture?type=large";
                $nuevaImg = "imagenNueva".$data['user_profile']['id'].".jpg";
                $nuevaImagen = "resources/".$nuevaImg;

                $originalImagen = "resources/originales/".$nuevaImg;

                if (!copy($urlImagen, $originalImagen)) {
                    echo "Error al copiar $urlImagen...\n";
                }

                if (!copy($urlImagen, $nuevaImagen)) {
                    echo "Error al copiar $urlImagen...\n";
                }else{
                    $im = imagecreatefromjpeg($nuevaImagen);

                    if($im && imagefilter($im, IMG_FILTER_GRAYSCALE)){
                            imagejpeg($im, $nuevaImagen);
                        }else{
                            echo 'La conversión a escala de grises falló.';
                        }
                }
                $data['user_profile']['url'] = $nuevaImagen;
            } catch (FacebookApiException $e) {
                $user = null;
            }
        }else {
            $this->facebook->destroySession();
        }

        if ($user) {
            $data['logout_url'] = site_url('welcome/logout'); 
            $data['other_log_out'] = '';
        } else {
            $data['login_url'] = $this->facebook->getLoginUrl(array(
                'redirect_uri' => site_url('welcome/index'), 
                'scope' => array("email") // permissions here
            ));
            $data['register_url'] = $this->facebook->getLoginUrl(array(
                'redirect_uri' => site_url('welcome/register'), 
                'scope' => array("email") // permissions here
            ));
        }

        if(isset($data['user_profile'])){
            $config = array();
            $config["base_url"] = base_url() . "welcome/index";
            $config["total_rows"] = $this->mdl_general->record_count();
            $config["per_page"] = 10;
            $config["uri_segment"] = 3;

            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $registros = $this->mdl_general->obtenerRegistros($data['user_profile']['id'],$config["per_page"], $page);
            $data["links"] = $this->pagination->create_links();
            $data['registros'] = $registros;
        }
            

        $this->load->view('inicio/header');
        $this->load->view('login',$data);
        $this->load->view('inicio/footer');
	}

    public function loginNormal(){
         $nombre = $this->input->post('usr');
         $contrasena = $this->input->post('pass');

         $usuario = $this->mdl_general->usuarios($nombre, $contrasena);

         if ($usuario) {
            $usuario_data = array(
               'id' => $usuario->id,
               'nombre' => $usuario->name,
               'logueado' => TRUE
            );
            $this->session->set_userdata($usuario_data);
            redirect('welcome/logueado');
         } else {
            redirect('welcome/index');
         }
    }


   public function logueado() {
      if($this->session->userdata('logueado')){

         $data = array();

         $data['user_profile']['name'] = $this->session->userdata('nombre');
         $data['user_profile']['id'] = $this->session->userdata('id');
         $data['logout_url']='';
         $data['other_log_out'] = site_url('welcome/cerrar_sesion');
         $data['login_url']='';
         $data['register_url']='';
         $this->load->view('inicio/header');
         $this->load->view('login', $data);
         $this->load->view('inicio/footer');
      }else{
         redirect('welcome/iniciar_sesion');
      }
   }

    public function logout(){
        $this->load->library('facebook');
        $this->facebook->destroySession();
        redirect('welcome/index');
    }

 

}

