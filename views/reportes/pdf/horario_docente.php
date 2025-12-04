<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario Docente - <?php echo $docente['nombre'] . ' ' . $docente['apellido_paterno']; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000;
            padding: 15mm;
        }
        
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        
        .header-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }
        
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 14pt;
            color: #2563eb;
            margin-bottom: 12px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            font-size: 9pt;
        }
        
        .info-item strong {
            font-weight: bold;
        }
        
        .hours-box {
            background: #2563eb;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .hours-value {
            font-size: 24pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .hours-label {
            font-size: 10pt;
            font-weight: bold;
        }
        
        .hours-detail {
            font-size: 8pt;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid rgba(255,255,255,0.3);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 8pt;
        }
        
        thead th {
            background: #0f172a;
            color: #fff;
            padding: 8px 4px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #000;
            font-size: 9pt;
        }
        
        tbody td {
            border: 1px solid #666;
            padding: 0;
            vertical-align: top;
            height: 60px;
        }
        
        .hour-cell {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
            padding: 8px 4px;
            width: 50px;
            vertical-align: middle;
        }
        
        .schedule-block {
            padding: 4px;
            height: 100%;
            border-left: 3px solid #10b981;
            background: #d1fae5;
        }
        
        .block-subject {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 3px;
            color: #065f46;
        }
        
        .block-name {
            font-size: 7pt;
            margin-bottom: 3px;
            line-height: 1.2;
        }
        
        .block-info {
            font-size: 6.5pt;
            margin-bottom: 1px;
        }
        
        .detail-section {
            margin-top: 25px;
            page-break-before: auto;
        }
        
        .detail-section h3 {
            font-size: 12pt;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #000;
        }
        
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 8pt;
        }
        
        .detail-table thead th {
            background: #f0f0f0;
            padding: 6px;
            text-align: left;
            font-size: 8pt;
            border: 1px solid #666;
        }
        
        .detail-table tbody td {
            padding: 6px;
            border: 1px solid #666;
            vertical-align: top;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #666;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
        
        @media print {
            body {
                padding: 10mm;
            }
            
            .schedule-block {
                page-break-inside: avoid;
            }
            
            .detail-section {
                page-break-before: auto;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-grid">
            <div>
                <h1>Horario del Docente</h1>
                <h2><?php echo $docente['nombre'] . ' ' . $docente['apellido_paterno'] . ' ' . ($docente['apellido_materno'] ?? ''); ?></h2>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>No. Empleado:</strong> <?php echo $docente['numero_empleado']; ?>
                    </div>
                    <div class="info-item">
                        <strong>Tipo:</strong> <?php echo ucfirst(str_replace('_', ' ', $docente['tipo'])); ?>
                    </div>
                    <div class="info-item">
                        <strong>Período:</strong> <?php echo $periodo['nombre']; ?>
                    </div>
                    <div class="info-item">
                        <strong>Fecha:</strong> <?php echo date('d/m/Y'); ?>
                    </div>
                </div>
            </div>
            
            <div class="hours-box">
                <div class="hours-value"><?php echo round($total_horas, 1); ?></div>
                <div class="hours-label">HORAS SEMANALES</div>
                <div class="hours-detail">
                    Máximo: <?php echo $docente['horas_max_semana']; ?> hrs<br>
                    <?php 
                    $porcentaje = ($docente['horas_max_semana'] > 0) 
                        ? round(($total_horas / $docente['horas_max_semana']) * 100, 1) 
                        : 0;
                    echo $porcentaje . '% de carga';
                    ?>
                </div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">HORA</th>
                <th>LUNES</th>
                <th>MARTES</th>
                <th>MIÉRCOLES</th>
                <th>JUEVES</th>
                <th>VIERNES</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($matriz_horarios['horas'] as $hora): ?>
                <tr>
                    <td class="hour-cell">
                        <?php echo $hora; ?>
                    </td>
                    <?php foreach ($matriz_horarios['dias'] as $dia): ?>
                        <td>
                            <?php 
                            $horario = $matriz_horarios['matriz'][$dia][$hora];
                            if ($horario): 
                            ?>
                                <div class="schedule-block">
                                    <div class="block-subject">
                                        <?php echo $horario['materia_clave']; ?>
                                    </div>
                                    <div class="block-name">
                                        <?php echo substr($horario['materia_nombre'], 0, 28); ?>
                                    </div>
                                    <div class="block-info">
                                        <strong>Grupo:</strong> <?php echo $horario['grupo_clave']; ?>
                                    </div>
                                    <div class="block-info">
                                        <strong>Aula:</strong> <?php echo $horario['aula'] ?? 'Sin asignar'; ?>
                                    </div>
                                    <div class="block-info">
                                        <strong>Carrera:</strong> <?php echo substr($horario['carrera'], 0, 18); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (!empty($horarios)): ?>
    <div class="detail-section">
        <h3>Detalle de Materias Asignadas</h3>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Horario</th>
                    <th>Materia</th>
                    <th>Grupo</th>
                    <th>Aula</th>
                    <th>Carrera</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($horarios as $horario): ?>
                <tr>
                    <td><?php echo ucfirst($horario['dia']); ?></td>
                    <td>
                        <?php echo substr($horario['hora_inicio'], 0, 5); ?> - 
                        <?php echo substr($horario['hora_fin'], 0, 5); ?>
                    </td>
                    <td>
                        <strong><?php echo $horario['materia_clave']; ?></strong><br>
                        <small><?php echo $horario['materia_nombre']; ?></small>
                    </td>
                    <td><?php echo $horario['grupo_clave']; ?></td>
                    <td><?php echo $horario['aula'] ?? 'Sin asignar'; ?></td>
                    <td><?php echo $horario['carrera']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <div class="footer">
        <p><strong>Reporte de Horario por Docente</strong></p>
        <p>Generado el <?php echo date('d/m/Y H:i'); ?> | Total de horas semanales: <?php echo round($total_horas, 1); ?></p>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>

</body>
</html>