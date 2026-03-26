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
    'bocadillos_frios' => ['icono' => 'fa-bread-slice', 'nombre' => 'Bocadillos Fríos'],
    'platos_combinados' => ['icono' => 'fa-utensils', 'nombre' => 'Platos Combinados'],
    'bandejas' => ['icono' => 'fa-cheese', 'nombre' => 'Bandejas para Compartir'],
    'torradas' => ['icono' => 'fa-bread-slice', 'nombre' => 'Torradas']
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Bar La Tasca | Carta Tradicional</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:wght@700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <span class="logo-icon"><i class="fas fa-utensils"></i></span>
    <h1>Bar La Tasca</h1>
    <div class="subtitulo">Sabor Tradicional</div>
    <div class="info-bar">
        <span><i class="fas fa-map-marker-alt"></i> Calle del Sabor, 12 · Barcelona</span>
        <span><i class="fas fa-phone-alt"></i> +34 910 123 456</span>
        <span><i class="fas fa-clock"></i> Lun-Dom 12:00 - 00:00</span>
    </div>
</header>

<nav class="categorias">
    <ul>
        <li><a href="index.php"><i class="fas fa-utensil-spoon"></i> Todos</a></li>
    </ul>
</nav>

<main>
    <div class="leyenda">
        <h3><i class="fas fa-info-circle"></i> Leyenda de características</h3>
        <div class="leyenda-items">
            <div class="leyenda-item"><i class="fas fa-leaf"></i> Vegano</div>
            <div class="leyenda-item"><i class="fas fa-wheat-alt"></i> Sin gluten</div>
            <div class="leyenda-item"><i class="fas fa-pepper-hot"></i> Picante</div>
            <div class="leyenda-item"><i class="fas fa-cheese"></i> Contiene lácteos</div>
            <div class="leyenda-item"><i class="fas fa-egg"></i> Contiene huevo</div>
            <div class="leyenda-item"><i class="fas fa-fish"></i> Pescado / Marisco</div>
            <div class="leyenda-item"><i class="fas fa-star"></i> Especialidad de la casa</div>
        </div>
    </div>

    <?php
    // Mostrar secciones de platos
    $tiposMostrar = $tipos;
    
    foreach ($tiposMostrar as $tipoActual):
        // Agrupar platos por tipo
        $platosTipo = [];
        foreach ($platos as $plato) {
            if (strtolower((string)$plato['tipo']) === $tipoActual) {
                $platosTipo[] = $plato;
            }
        }
        
        if (count($platosTipo) == 0) continue;
    ?>
        <section class="seccion">
            <div class="seccion-header">
                <div class="icono-seccion">
                    <i class="fas <?php echo $infoTipos[$tipoActual]['icono']; ?>"></i>
                </div>
                <h2><?php echo $infoTipos[$tipoActual]['nombre']; ?></h2>
            </div>
            
            <div class="platos-grid">
                <?php foreach ($platosTipo as $plato): 
                    $destacado = esDestacado($plato);
                ?>
                <div class="plato-card <?php echo $destacado ? 'destacado' : ''; ?>">
                    <div class="plato-cabecera">
                        <h3 class="plato-nombre"><?php echo htmlspecialchars($plato->nombre); ?></h3>
                        <span class="plato-precio"><?php echo number_format((float)$plato->precio, 2); ?> €</span>
                    </div>
                    <?php if ($destacado) { ?>
                    <div class="badge-destacado"><i class="fas fa-star"></i> Especialidad</div>
                    <?php } ?>
                    <p class="plato-descripcion"><?php echo htmlspecialchars($plato->descripcion); ?></p>
                    <div class="plato-meta">
                        <span class="plato-calorias"><i class="fas fa-fire"></i> <?php echo (int)$plato->calorias; ?> kcal</span>
                        <div class="plato-tags">
                            <?php echo mostrarCaracteristicas($plato->caracteristicas, $iconosCaracteristicas); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>
    
    <?php if (empty($filtro) && $platos->count() == 0): ?>
        <div class="sin-resultados">
            <i class="fas fa-utensils"></i> No hay platos disponibles en este momento.
        </div>
    <?php endif; ?>
</main>

<footer>
    <div class="footer-nombre">Bar La Tasca</div>
    <div class="footer-info">
        <span><i class="fas fa-map-marker-alt"></i> Calle del Sabor, 12 · Barcelona</span>
        <span><i class="fas fa-phone-alt"></i> +34 910 123 456</span>
        <span><i class="fas fa-envelope"></i> info@barlatasca.es</span>
    </div>
    <hr class="footer-divider">
    <div class="footer-copy">
        &copy; <?php echo date('Y'); ?> Bar La Tasca · Todos los derechos reservados
    </div>
</footer>

</body>
</html>