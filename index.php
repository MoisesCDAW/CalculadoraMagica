<!DOCTYPE html>
<html>
    <head>
        <title>Inicio</title>
        <style>
            .numeros {
                width: 310px;
            }
        </style>
    </head>
    <body>
        <form action="index.php" method="post">
            <h3>Calculadora Basica</h3>
            <input type="text" class="numeros" value="<?php if(isset($_POST["num1"])){echo $_POST["num1"];}?>"  name="num1" placeholder="Ingresa el primer numero, usa '.' para flotantes: ">
            <input type="text" class="numeros" value="<?php if(isset($_POST["num2"])){echo $_POST["num2"];}?>"  name="num2" placeholder="Ingresa el segunda numero, usa '.' para flotantes: " >
            <br>
            <input type="text" class="numeros" value="<?php if(isset($_POST["num3"])){echo $_POST["num3"];}?>"  name="num3" placeholder="Ingresa numero OPCIONAL, usa '.' para flotantes: " >
            
            <p>Ahora selecciona la operación que quieres realizar:</p>
            <input type="radio" value="sumar" name="operador" <?php if(isset($_POST["operador"])){ if($_POST["operador"]=="sumar"){echo "checked";}}?>>sumar
            <input type="radio" value="restar" name="operador" <?php if(isset($_POST["operador"])){ if($_POST["operador"]=="restar"){echo "checked";}}?>>restar
            <input type="radio" value="multiplicar" name="operador" <?php if(isset($_POST["operador"])){ if($_POST["operador"]=="multiplicar"){echo "checked";}}?>>multiplicar
            <input type="radio" value="dividir" name="operador" <?php if(isset($_POST["operador"])){ if($_POST["operador"]=="dividir"){echo "checked";}}?>>dividir
            <br><br>
            <input type="submit" value="basica" name="opcion">
            <br>
        </form>

        <form action="index.php" method="post">
            <hr>
            <h3>Calculadora Magica</h3>
            <input type="txt" value="<?php if(isset($_POST["conjunto"])){echo $_POST["conjunto"];}?>" class="numeros" placeholder="Ingresa una operacion, Ej: 2+2" name="conjunto">
            <br><br>
            <input type="submit" value="magica" name="opcion">
            <br><br>
            <hr>
        </form>

        <?php
            
            function inicio(){

                $opcion = "";

                if(isset($_POST["opcion"])){
                    $opcion = $_POST["opcion"];           
                }else {
                    setcookie("pruebaCookie", json_encode(array()));
                }

                getHistorial();

                if ($opcion=="basica") {
                    if (isset($_POST["operador"])) {
                        $operador = $_POST["operador"];
                        basica($operador);
                    }  
                    
                }else if ($opcion=="magica") {
                    if (isset($_POST["conjunto"])) {
                        magica();
                    }
                }
            }

            inicio();
            
            
            function sumar($n1, $n2): float{
                return $n1 + $n2;
            }


            function restar($n1, $n2): float{
                return $n1 - $n2;
            }


            function multiplicar($n1, $n2): float{
                return $n1 * $n2;
            }


            function dividir($n1, $n2): float{
                if ($n1==0.0 || $n2==0.0) {
                    return 0.0;
                }
                return $n1 / $n2;
            }


            function basica($operador){
                $result_basico="";
                $n1 = 0;
                $n2 = 0;
                $n3 = "";


                if (isset($_POST["num1"])) {
                    if (is_numeric($_POST["num1"])) {
                        $n1 = $_POST["num1"];
                    }  
                }

                if (isset($_POST["num2"])) {
                    if (is_numeric($_POST["num2"])) {
                        $n2 = $_POST["num2"];
                    }
                }

                if (isset($_POST["num3"])) {
                    if (is_numeric($_POST["num3"])) {
                        $n3 = $_POST["num3"];
                    } 
                }              
                
                if ($n1!="" && $n2!="") {

                    switch ($operador) {
                        case "sumar":
                            if ($n3!="") {
                                $result_basico = "$n1 + $n2 + $n3 = " . sumar($n1, $n2)+$n3;
                            }else {
                                $result_basico = "$n1 + $n2 = " . sumar($n1, $n2);
                            }
                            break;

                        case "restar":
                            if ($n3!="") {
                                $result_basico = "$n1 - $n2 - $n3 = " . restar($n1, $n2)-$n3;     
                            }else {
                                $result_basico = "$n1 - $n2 =".restar($n1, $n2);
                            }
                            break;

                        case "multiplicar":
                            if ($n3!="") {
                                $result_basico = "$n1 * $n2 * $n3 = " . multiplicar($n1, $n2)*$n3;
                            }else {
                                $result_basico = "$n1 * $n2 = " . multiplicar($n1, $n2);
                            }
                            break;

                        case "dividir":

                            if ($n1=="0" || $n2=="0" || $n3=="0") {
                            }else {
                                if ($n3!="") {
                                    $result_basico = "$n1 / $n2 / $n3 = " . round(dividir($n1, $n2)/$n3, 2);   
                                }else {
                                    $result_basico = "$n1 / $n2 = " . round(dividir($n1, $n2), 2);
                                }
                            }
                            
                            
                            break;   
                    }

                    if (!isset($GLOBALS["historial_array"])) {
                        $GLOBALS["historial_array"] = array($result_basico);
                        setcookie("historial", json_encode($GLOBALS["historial_array"]));
                    }else {
                        array_push($GLOBALS["historial_array"], $result_basico);
                        setcookie("historial", json_encode($GLOBALS["historial_array"]));
                    }

                }

                if ($n1==0 && $n2==0 && $n3==0){
                    $result_basico = "0";
                }

                if (!isset($_COOKIE["pruebaCookie"])) {
                    $GLOBALS["resultado"] = "Cookies desactivadas. Si las activas puedes disfrutar del historial de operaciones";
                }else {
                    $GLOBALS["resultado"] = $result_basico; 
                }
                
            }


            function magica(){
                $resultado_magico = 0;
                $str = $_POST["conjunto"];
                $str = str_replace(" ", "", $str);
                $str = trim($str);

                $conjunto = [];
                $num="";
                $bucle = strlen($str);

                $contador=1;
                
                if (!is_numeric(substr($str, 0, 1))) {
                    $resultado_magico = "Inválido";
                }else {
                // Se toma el str del usuario y se pasa a un conjunto en forma de array
                    for ($j=0; $j<=$bucle; $j++){
                        if (is_numeric(substr($str, $j, 1))) {
                            $num = $num . substr($str, $j, 1);
                        }else if(substr($str, $j, 1)=="."){
                            $num = $num . substr($str, $j, 1);
                        }else {
                            $conjunto[$contador-1] = $num;
                            $num="";

                            if ($j<$bucle) {
                                $conjunto[$contador] = substr($str, $j, 1);
                                $contador+=2;
                            }
                        }


                    }                          

                    
                    // Se resuelve el conjunto
                    if ($conjunto[0]!=null) {
                        $valido = true;
                        
                        $resultado_magico=$conjunto[0]; // Guarda el primer valor del conjunto y va operando apartir de el
                        
                        for ($i=0; $i < count($conjunto); $i++) { 
                            if (!is_numeric($conjunto[$i])) {
                                if(is_numeric($conjunto[$i+1])){
                                    $resultado_magico = comprobadorMagico($conjunto[$i], $resultado_magico, $conjunto[$i+1]);
                                }else {
                                    $valido = false;
                                    $resultado_magico = "Inválido";
                                    break;
                                }
                                
                            }
                        }
                        
                        

                        if ($valido==true) {
                            $resultado_magico = round($resultado_magico, 2);
                            

                            // Se guarda el resultado en las cookies del navegador del cliente
                            if (!isset($GLOBALS["historial_array"])) {
                                $GLOBALS["historial_array"] = array($str." = ".$resultado_magico);
                                setcookie("historial", json_encode($GLOBALS["historial_array"]));
                            }else {
                                array_push($GLOBALS["historial_array"], $str." = ".$resultado_magico);
                                setcookie("historial", json_encode($GLOBALS["historial_array"]));
                            }

                            if (!isset($_COOKIE["pruebaCookie"])) {
                                $GLOBALS["resultado"] = "Cookies desactivadas. Si las activas puedes disfrutar del historial de operaciones";
                            }else {
                                $GLOBALS["resultado"] = $resultado_magico;
                            }
                        }

                    }
                }         
            }

            function comprobadorMagico($operador, $n1, $n2){
                switch ($operador) {
                    case '+':
                        return sumar($n1, $n2);
                        break;
                    case '-':
                        return restar($n1, $n2);
                        break;
                    case '*':
                        return multiplicar($n1, $n2);
                        break;
                    case '/':
                        return dividir($n1, $n2);
                        break;
                }
            }


            function getHistorial(){

                if (isset($_COOKIE["historial"])) {

                    $GLOBALS["historial_array"] = json_decode($_COOKIE["historial"]);

                    if (count($GLOBALS["historial_array"])>10) {
                        array_shift($GLOBALS["historial_array"]);
                    }

                    return $GLOBALS["historial_array"];
                }else {
                    return null;
                }
            }

            function borrarHistorial(){
                setcookie("historial", "");
            }
        ?>

        <h3>Resultado: <?php 
            if (isset($GLOBALS["resultado"])) {
                echo $GLOBALS["resultado"];
            }
            ?>
        </h3>

        <h3>Historial de operaciones</h3>

        <form action="index.php" method="post" >
            <input type="submit" value="Limpiar Historial" name="limpiar">
        </form>

        <p><?php

            if (isset($_POST["limpiar"])) {
                borrarHistorial();
            }else { 
            
                if (getHistorial()!=null) {  
                    foreach(getHistorial() as $valor){
                        echo $valor."<br>";
                        
                    }
                }
            }


        ?></p>
    </body>
</html>