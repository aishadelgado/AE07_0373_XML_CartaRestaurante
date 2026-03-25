<?php
// Cargar el archivo XML
$xml = simplexml_load_file('./xml/carta.xml');
if ($xml === false) {
    die('Error al cargar el archivo XML');
}

// Obtener todos los platos
$platos = $xml->plato;

// Definir los tipos de plato que aparecerán en la carta (según tu XML)
$tipos = [
    'tapas',
    'bocadillos_calientes',
    'bocadillos_frios',
    'platos_combinados',
    'bandejas',
    'torradas'
];

$infoTipos = [
    'tapas' => ['icono' => 'fa-mug-hot', 'nombre' => 'Tapas'],
    'bocadillos_calientes' => ['icono' => 'fa-bread-slice', 'nombre' => 'Bocadillos Calientes'],
    'bocadillos_frios' => ['icono' => 'fa-sandwich', 'nombre' => 'Bocadillos Fríos'],
    'platos_combinados' => ['icono' => 'fa-utensils', 'nombre' => 'Platos Combinados'],
    'bandejas' => ['icono' => 'fa-cheese', 'nombre' => 'Bandejas para Compartir'],
    'torradas' => ['icono' => 'fa-baguette', 'nombre' => 'Torradas']
];

$iconosCaracteristicas = [
    'Vegano' => 'fa-leaf',
    'Sin gluten' => 'fa-wheat-alt',
    'Picante' => 'fa-pepper-hot',
    'Contiene lácteos' => 'fa-cheese',
    'Contiene huevo' => 'fa-egg',
    'Contiene molúscos' => 'fa-fish',
    'Contiene pescado' => 'fa-fish',
    'Contiene molúsco' => 'fa-fish',
    'Puede ser picante' => 'fa-pepper-hot',
    'Puede contener lácteos' => 'fa-cheese'
];

function mostrarCaracteristicas($caracteristicas, $iconos) {
    $html = '';
    foreach ($caracteristicas->item as $item) {
        $caracteristica = trim((string)$item);
        $icono = isset($iconos[$caracteristica]) ? $iconos[$caracteristica] : 'fa-tag';
        $html .= '<span class="tag"><i class="fas ' . $icono . '"></i> ' . htmlspecialchars($caracteristica) . '</span>';
    }
    return $html;
}

// Función para verificar si un plato es destacado (según algún criterio, por ejemplo, precio > 15€ o características especiales)
function esDestacado($plato) {
    // Marcar como destacado si es una bandeja especial o tiene un precio superior a 15€
    if ((float)$plato->precio >= 15.00) {
        return true;
    }
    // También marcar algunos platos específicos como destacados
    $nombresDestacados = ['Pulpo Gallego', 'Ibéricos variados', 'Mariscos variados'];
    if (in_array((string)$plato->nombre, $nombresDestacados)) {
        return true;
    }
    return false;
}
?>



</body>
</html>