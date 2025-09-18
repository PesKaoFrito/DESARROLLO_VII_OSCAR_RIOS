<?php
    // Clase Desarrollador: depende de Empleado y Evaluable. Las inclusiones se gestionan desde index.php
    class Desarrollador extends Empleado implements Evaluable{
        private $lenguajePrincipal;
        private $nivelExperiencia;

        public function __construct($nombre, $idEmpleado, $salarioBase,$lenguajePrincipal,$nivelExperiencia){
            parent::__construct($nombre, $idEmpleado, $salarioBase);
            $this->setLenguajePrincipal($lenguajePrincipal);
            $this->setNivelExperiencia($nivelExperiencia);
        }

        public function getNivelExperiencia(){
            return $this->nivelExperiencia;
        }

        
        public function getLenguajePrincipal(){
            return $this->lenguajePrincipal;
        }
        public function setLenguajePrincipal($lenguajePrincipal){
            $this->lenguajePrincipal=$lenguajePrincipal;
        }

        public function setNivelExperiencia($nivelExperiencia){
            $this->nivelExperiencia=$nivelExperiencia;
        }
        public function evaluarDesempenio(){
            if($this->nivelExperiencia >= 5){
                return "Excelente";
            } elseif ($this->nivelExperiencia >= 3) {
                return "Bueno";
            } else {
                return "Regular";
            }
        }
    }
?>