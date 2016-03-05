<?php

class Mdl_general extends CI_Model {
    public function __construct() {
            parent::__construct();
            $this->load->database();
    }

    public function insertar($datos) {
        $this->db->insert('usuarios', $datos);
        $respuesta = $this->db->insert_id();
        if ($respuesta)
            return $respuesta;
        else
            return FALSE;
    }

     public function insertarCifra($datos) {
        $this->db->insert('cifras', $datos);
        $respuesta = $this->db->insert_id();
        if ($respuesta)
            return $respuesta;
        else
            return FALSE;
    }

    public function obtenerCifras($cifra){
          $this->db->select('cifra');
          $this->db->from('cifras');
          $this->db->where('cifra', $cifra);
          $consulta = $this->db->get();
          $resultado = $consulta->row();
          return $resultado;
    }

    public function record_count() {
        return $this->db->count_all("cifras");
    }

    public function obtenerRegistros($id,$limit,$start){
          $this->db->select('*');
          $this->db->from('cifras');
          $this->db->join('usuarios', 'usuarios.id = cifras.usr');
          $this->db->where('usr', $id);
          $this->db->order_by('fecha','desc');
          $this->db->limit($limit, $start);
          $consulta = $this->db->get();
          $resultado = $consulta->result();

          if ($consulta ->num_rows() > 0) {
            foreach ($resultado as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }


    public function obtener_usuario($datos){
    $query = ("select id,login_inicio from usuarios where id =".$datos['id']);
        $respuesta = $this->db->query($query);
        if ($respuesta->num_rows() > 0) {
            return $respuesta->result();
        } else
            return FALSE;
    }

    public function usuarios($nom,$pass){
         $this->db->select('id, name');
          $this->db->from('usuarios');
          $this->db->where('name', $nom);
          $this->db->where('password', $pass);
          $consulta = $this->db->get();
          $resultado = $consulta->row();
          return $resultado;
    }

    public function cambiar_pass($datos){
        $this->db->where('id', $datos['id']);
        $this->db->set('password',$datos['password']);
        $this->db->set('login_inicio',$datos['login_inicio']);
        $respuesta = $this->db->update('usuarios'); 

        if ($respuesta)
            return $respuesta;
        else
            return FALSE;
    }
}

?>