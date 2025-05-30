<?php
header("Content-Type: application/json");

// Validar que se haya enviado el parámetro "url"
if (!isset($_GET['url']) || empty($_GET['url'])) {
    echo json_encode(["error" => "No se proporcionó la URL."]);
    exit;
}

$url = $_GET['url'];

// Validar que la URL tenga un formato correcto
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode(["error" => "URL inválida."]);
    exit;
}

// Inicializar cURL para obtener el contenido de la URL con las cabeceras
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$result = curl_exec($ch);

if ($result === false) {
    echo json_encode(["error" => "Error al obtener el contenido de la URL."]);
    exit;
}

// Obtener el tamaño de la cabecera para separar los headers del body
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headers = substr($result, 0, $headerSize);
$body = substr($result, $headerSize);
curl_close($ch);

// Inicializar el array que contendrá las cookies
$cookies = [];

// Extraer las cookies de las cabeceras (Set-Cookie)
preg_match_all('/^Set-Cookie:\s*([^;]+)=([^;\r\n]+)/mi', $headers, $matches, PREG_SET_ORDER);
foreach ($matches as $match) {
    // $match[1] es el nombre y $match[2] es el valor de la cookie
    $cookies[$match[1]] = $match[2];
}

// Buscar asignaciones de cookies en el código JavaScript presente en el body (document.cookie)
preg_match_all('/document\.cookie\s*=\s*[\'"]([^\'"]+)[\'"]/', $body, $matchesJs);
if (isset($matchesJs[1])) {
    foreach ($matchesJs[1] as $cookieStr) {
        // Separa posibles múltiples cookies separadas por punto y coma
        $parts = explode(";", $cookieStr);
        foreach ($parts as $part) {
            $part = trim($part);
            if (strpos($part, "=") !== false) {
                list($name, $value) = explode("=", $part, 2);
                $cookies[trim($name)] = trim($value);
            }
        }
    }
}

// Responder en formato JSON
echo json_encode([
    "url" => $url,
    "cookies" => $cookies
]);
?>
