<?php
// Las dependencias (Desarrollador, Gerente, Empleado, Evaluable) se cargan desde index.php
    class Empresa implements Evaluable{
        private $empleados;
        public function __construct(){
            $this->empleados=array();
        }
        public function agregarEmpleado($empleado){
            $this->empleados[]=$empleado;
        }

        public function listarEmpleados(){
            foreach($this->empleados as $empleado){
                echo "Nombre: ".$empleado->getNombre().", ID: ".$empleado->getIdEmpleado().", Salario Base: ".$empleado->getSalarioBase();
                if ($empleado instanceof Desarrollador){
                    echo ", Lenguaje Principal: ".$empleado->getLenguajePrincipal().", Nivel de Experiencia: ".$empleado->getNivelExperiencia();
                } elseif ($empleado instanceof Gerente){
                    echo ", Departamento: ".$empleado->getDepartamento();
                }
                if ($empleado instanceof Evaluable){
                    echo ", Evaluación de Desempeño: ".$empleado->evaluarDesempenio();
                }
                echo "<br>";
            }
        }
        public function calcularNominaTotal(){
            $total=0;
            foreach($this->empleados as $empleado){
                $total+=$empleado->getSalarioBase();
            }
            return $total;
        }
        public function evaluarDesempenio(){
            return "N/A";
        }
    }
?>