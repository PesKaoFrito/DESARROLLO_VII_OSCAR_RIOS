<?php
    class Empleado{
        private $nombre;
        private $idEmpleado;
        private $salarioBase;
        public function __construct($nombre,$idEmpleado,$salarioBase){
            $this->nombre=$nombre;
            $this->idEmpleado=$idEmpleado;
            $this->salarioBase=$salarioBase;
        }
        function getNombre(){
            return $this->nombre;
        }
        function setNombre($nombre){
            $this->nombre=trim($nombre);
        }  
        function getIdEmpleado(){
            return $this->idEmpleado;
        }
        function setIdEmpleado($idEmpleado){
            $this->idEmpleado=trim($idEmpleado);
        }         
        function getSalarioBase (){
            return $this->salarioBase;
        }

        function setSalarioBase($salarioBase){
            $this->salarioBase=trim($salarioBase);
        }
    }
?>