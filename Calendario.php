<?php
    /** 
     * Ejercicio del calendario.
     * 
     * @author Andrea Solís Tejada
     */

    $dia = intval(date("d"));
    $mes = intval(date("m"));
    $anno = intval(date("Y"));
    $errorDia=$errorMes=$errorAnno = false; 
    $diaMes = 0;
    $nombreMes = "";
    $color = "";
    $diasTotales = array();
    $festivosAutonomicos = array();
    $festivosNacionales = array();
    $festivosLocales = array();
    $bisiesto = false;

    // Validación formulario
    if (isset($_POST["enviar"])) {
        if (gettype(intval($_POST["mes"])) == "integer" && $_POST["mes"] >= 1 && $_POST["mes"] <= 12) {
            $mes = $_POST["mes"];
            $errorMes = false;
        } else {
            $errorMes = true;
        }
        if (gettype(intval($_POST["anno"])) == "integer" && $_POST["anno"] >= 1 && $_POST["mes"] <= 2999) {
            $anno = $_POST["anno"];
            $errorAnno = false;
        } else {
            $errorAnno = true;
        }
    }
    
    // Formulario
    echo ('<form method="post" action="" . $SERVER["PHP_SELF"] . "">');
        if ($errorMes) {
            echo ("<input type=\"number\" name=\"mes\" style=\"border: 1px solid red;\" value=\"" . $mes . "\" min=\"1\" max=\"12\">");
        } else {
            echo ("<input type=\"number\" name=\"mes\" value=\"" . $mes . "\" min=\"1\" max=\"12\">");
        }
        if ($errorAnno) {
            echo ("<input type=\"number\" name=\"anno\" style=\"border: 1px solid red;\" value=\"" . $anno . "\" min=\"1000\" max=\"3000\">");
        } else {
            echo ("<input type=\"number\" name=\"anno\" value=\"" . $anno . "\" min=\"1000\" max=\"3000\">");
        }
    echo ("<input type=\"submit\" name=\"enviar\" value=\"Enviar\">");

    
    // Días de cada mes
    if ($mes == 1 || $mes == 3 || $mes == 5 || $mes == 7 || $mes == 8 || $mes == 10 || $mes ==12 ) {
        $diaMes = 31;
        $diasTotales = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
    } else if ($mes == 2) {
        // Si es febrero calculo si el año es bisiesto
        if (($anno % 4 == 0 && $anno % 100 != 0) || $anno % 400 == 0){
            $bisiesto = true;
            $diaMes = 29;
            $diasTotales = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29);
        } else {
            $bisiesto = false;
            $diaMes = 28;
            $diasTotales = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28);
        }
    } else {
        $diaMes = 30;
        $diasTotales = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30);
    }
    
    $diaPascua = date("d-m-Y", easter_date($anno));
    $semanaSanta = array(date("d", strtotime("-3 days $diaPascua")), date("d", strtotime("-2 days $diaPascua")));
    $mesSemanaSanta = [];

    switch ($mes) {
        case 1 :
            $nombreMes = "enero";
            $festivosNacionales = array(1, 6);
            break;
        case 2:
            $nombreMes = "febrero";
            $festivosAutonomicos = array(28);
            break;
        case 3:
            $nombreMes = "marzo";
            if (date("m", easter_date($anno)) == 03) {
                $mesSemanaSanta = $semanaSanta;
            }
            break;
        case 4:
            $nombreMes = "abril";
            if (date("m", easter_date($anno)) == 04) {
                $mesSemanaSanta = $semanaSanta;
            }
            break;
        case 5:
            $nombreMes = "mayo";
            $festivosNacionales = array(1);
            break;
        case 6:
            $nombreMes = "junio";
            break;
        case 7:
            $nombreMes = "julio";
            break;
        case 8:
            $nombreMes = "agosto";
            $festivosNacionales = array(15);
            break;
        case 9:
            $nombreMes = "septiembre";
            $festivosLocales = array(8);
            break;
        case 10:
            $nombreMes = "octubre";
            $festivosNacionales = array(12);
            $festivosLocales = array(24);
            break;
        case 11:
            $nombreMes = "noviembre";
            $festivosNacionales = array(1);
            break;
        case 12:
            $nombreMes = "diciembre";
            $festivosNacionales = array(6, 8, 25);
            break;
        default:
            $nombreMes = "enero";
            $festivosNacionales = array(1, 6);
            $mes = 1;
            break;
    }
    
    // Primer día de la semana
    $primerdia = date("w", mktime(0, 0, 0, $mes, 1, $anno));
    
    
    $j = 0; // Con esta variable controlo en que posición del calendario estoy
    
    echo("<table width='100%' height='100%' border='1' style='background-color:#D5AAFF'><tr><th colspan='7' style='background-color:#F8B195'>". $nombreMes . " ". $anno . "</th></tr>");
    echo("<tr style='background-color:#AFF8DB'>
            <th>L</th>
            <th>M</th>
            <th>X</th>
            <th>J</th>
            <th>V</th>
            <th>S</th>
            <th>D</th>
        </tr>");
    
    // Si el primer día del mes cae en domingo $primerdia es 0, lo hago a parte para dejar 6 huecos y no 0
    if ($primerdia == 0) {
        for ($a = 1; $a < 7; $a++) {
            if ($j == 0) {
                echo("<tr>");
            }
        
            echo("<td></td>");
        
            // Si el número llega a 7 acabo la fila
            if ($j >= 7) {
                echo("</tr>");
                $j = 0;
            }
            $j++;
        }
    } else {
        for ($a = 1; $a < $primerdia; $a++) { // Dejo tantos huecos como la posición del día de la semana
            if ($j == 0) {
                echo("<tr>");
            }
        
            echo("<td></td>");
        
            // Si el número llega a 7 acabo la fila
            if ($j >= 7) {
                echo("</tr>");
                $j = 0;
            }
            $j++;
        }
    }

    for ($i = 1; $i <= $diaMes; $i++) {
        
        //echo '<a href=\"fecha.php?fecha=".$i."/".$mes."/".$anno." \" class=\"enlaceFecha\">".$diaMes."</a>';

        if (in_array($i, $diasTotales)) {
            echo '<a href=\"fecha.php?fecha=".$i."/".$mes."/".$anno." \" class=\"enlaceFecha\"></a>';
        }

        // Color por defecto
        $color = "#D5AAFF";
    
        // Festivo nacional (España)
        if (in_array($i, $festivosNacionales)) {
            $color = "#C82A54";
        }
    
        // Festivo autonómico (Andalucía)
        if (in_array($i, $festivosAutonomicos)) {
            $color = "#E36B2C";
        }
    
        // Festivo local (Córdoba)
        if (in_array($i, $festivosLocales)) {
            $color = "#23BAC4";
        }

        // Semana Santa
        if (in_array($i, $mesSemanaSanta)) {
            $color = "#C82Ad4";
        }

        // Día actual
        if ($mes != intval(date("m")) || $anno != intval(date("Y"))) {
            $mes = false;
            $anno = false;
        } else {
            $mes = true;
            $anno = true;
        }
        if ($i == $dia && $mes && $anno) {
            $color = "#71d772";
        }
    
        // Empiezo la fila
        if ($j == 0) {
            echo("<tr>");
        }
        
        echo("<td align='center' style='background-color:" . $color . ";'>" . $i . "</td>");
        $j++;
    
        // Si el número llega a 7 se acaba la fila
        if ($j >= 7) {
            echo("</tr>");
            $j = 0;
        }
    }
    echo("</table>")
    
?>