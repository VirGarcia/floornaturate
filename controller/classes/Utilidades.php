<?php
    class Utilidades {
        function comprobarEjecucionCodigo() {
            print("<script type='text/javascript'>alert('EL CÓDIGO HA LLEGADO HASTA AQUÍ');</script>");
        }

        function verDatosFormateados($datos) {
            print("<pre>");
            var_dump($datos);
            print("</pre>");
        }

		function crearCookie($nombre, $valor) {
			setcookie($nombre, $valor, time()+31536000, "/", "localhost", true, true);
		}

		function borrarCookie($nombre) {
			setcookie($nombre, "", time()-1, "/", "localhost", true, true);
		}

        function mensaje(string $mensaje, string $colorBS, $seccionPrevia = false) {

			if (!$seccionPrevia)
				$seccionPrevia = "nav";
			
			print("
				<script type='text/javascript'>
					window.addEventListener('load', function() {
						var seccionPrevia = document.querySelector('$seccionPrevia');
						var div = document.createElement('div');
						div.className = 'container alert alert-$colorBS w-50 text-center mt-3';
						div.setAttribute('role', 'alert');
						var p = document.createElement('p');
						p.className = 'display-6 fs-4 pt-3';
						p.innerHTML = `$mensaje`;
						div.appendChild(p);
						seccionPrevia.insertAdjacentElement('afterend', div);
					}, false);
				</script>
			");
		}

		function mensajePorURL() {
			if (!isset($_GET['mensaje']))
				return;
			
			switch ($_GET['mensaje']) {
				case "ok":
					$this->mensaje("Cambios realizados correctamente.", "success");
					break;
				case "error":
					$this->mensaje("Error al aplicar cambios.", "secondary");
					break;
				default:
					break;
			}
		}

		function listarArchivosRuta(string $ruta) {
            $escaneo = scandir($ruta);
            $elementos = array_splice($escaneo, 2);
            $archivos = array();
            for ($i = 0; $i < count($elementos); $i++) {
                if (!is_dir($ruta.$elementos[$i]))
                    array_push($archivos, $elementos[$i]);
            }
            return $archivos;
        }

        function cambiarFormatoFecha($fechaFormatoSQL) {
            $anho		= substr($fechaFormatoSQL, 0, 4);
            $mes		= substr($fechaFormatoSQL, 5, 2);
            $dia		= substr($fechaFormatoSQL, 8, 2);

            if (strlen($fechaFormatoSQL) == 19) {
                $hora		= substr($fechaFormatoSQL, 11, 2);
                $minuto		= substr($fechaFormatoSQL, 14, 2);
                $segundo	= substr($fechaFormatoSQL, 17, 2);

                return $dia."/".$mes."/".$anho." ".$hora.":".$minuto.":".$segundo;
            }


            return $dia."/".$mes."/".$anho;
		}

		function mandarEmail(string $nombreEmisor, string $emailEmisor, string $destinatario, string $asunto, string $mensaje) {
			$headers = "MIME-Version: 1.0\r\n";
			$headers.= "Content-type: text/html; charset=utf-8\r\n";
			$headers.= "From: ".$nombreEmisor." < ".$emailEmisor." >\r\n";
			$mensajeHTML = "
				<!doctype html>
					<head>
						<title>".$asunto."</title>
					</head>
					<body style='font-family: Verdana, Geneva, Tahoma, sans-serif'>
						".$mensaje."
					</body>
				</html>
			";
			$exito = mail($destinatario, $asunto, $mensajeHTML, $headers);

			if ($exito)
				return true;
			else
				return false;
		}

		function comprobarFechaFutura($fecha) {
			return $fecha > date("Y-m-d H:i:s");
		}

		function GET_Edad(string $fecha_nac) {
            if (!empty($fecha_nac)) {
				$dia_actual     = date("d");
				$mes_actual     = date("m");
				$anho_actual    = date("Y");
				$dia_nac        = substr ($fecha_nac, 8, 2);
				$mes_nac        = substr ($fecha_nac, 5, 2);
				$anho_nac       = substr ($fecha_nac, 0, 4);

				if (($mes_nac == $mes_actual) && ($dia_nac > $dia_actual))
					$anho_actual--;

				if ($mes_nac > $mes_actual)
					$anho_actual--;

				return ($anho_actual-$anho_nac);
            }
            else
				return "0";
        }
    }
?>