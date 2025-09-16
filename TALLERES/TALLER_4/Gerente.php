<?php
    include 'Empleado.php';

    class Gerente extends Empleado{
        
        private $departamento;

        public function __construct($nombre, $idEmpleado, $salarioBase, $departamento){
            parent::__construct($nombre,$idEmpleado,$salarioBase);
            $this->setDepartamento($departamento);
        }

        public function getDepartamento(){
            return $this->departamento;
        }
        public function setDepartamento($departamento){
            $this->departamento=$departamento;
        }
        public function asignarBonos($salarioBase,$departamento){
            if ($departamento=="ventas"){
                return $salarioBase*0.1;
            }
            else {
                return $salarioBase*0.08;
            }
        }
    }
?>