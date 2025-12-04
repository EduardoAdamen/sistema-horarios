<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ocupación de Aula - <?php echo $aula['edificio'] . '-' . $aula['numero']; ?></title>
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
        
        .occupation-box {
            background: #f59e0b;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .occupation-value {
            font-size: 28pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .occupation-label {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .occupation-detail {
            font-size: 8pt;
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
        }
        
        .schedule-block.occupied {
            background: #fef3c7;
            border-left: 3px solid #f59e0b;
        }
        
        .schedule-block.free {
            background: #d1fae5;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #10b981;
            font-weight: bold;
            font-size: 8pt;
        }
        
        .block-subject {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 3px;
            color: #92400e;
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
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-grid">
            <div>
                <h1>Ocupación de Aula</h1>
                <h2><?php echo $aula['edificio'] . '-' . $aula['numero']; ?></h2>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Período:</strong> <?php echo $periodo['nombre']; ?>
                    </div>
                    <div class="info-item">
                        <strong>Tipo:</strong> <?php echo ucfirst($aula['tipo']); ?>
                    </div>
                    <div class="info-item">
                        <strong>Capacidad:</strong> <?php echo $aula['capacidad']; ?> personas
                    </div>
                    <div class="info-item">
                        <strong>Fecha:</strong> <?php echo date('d/m/Y'); ?>
                    </div>
                </div>
            </div>
            
            <div class="occupation-box">
                <div class="occupation-value"><?php echo $porcentaje_ocupacion; ?>%</div>
                <div class="occupation-label">OCUPACIÓN</div>
                <div class="occupation-detail">
                    <?php echo $bloques_ocupados; ?> de 70 bloques<br>
                    semanales ocupados
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
                        <?php 
                        $horario = $matriz_horarios['matriz'][$dia][$hora];
                        ?>
                        <td>
                            <?php if ($horario): ?>
                                <div class="schedule-block occupied">
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
                                        <strong>Docente:</strong> <?php echo substr($horario['docente'], 0, 18); ?>
                                    </div>
                                    <div class="block-info">
                                        <strong>Carrera:</strong> <?php echo substr($horario['carrera'], 0, 18); ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="schedule-block free">
                                    LIBRE
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
        <h3>Detalle de Asignaciones</h3>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Horario</th>
                    <th>Materia</th>
                    <th>Grupo</th>
                    <th>Docente</th>
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
                    <td><?php echo $horario['docente']; ?></td>
                    <td><?php echo $horario['carrera']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <div class="footer">
        <p><strong>Reporte de Ocupación de Aula</strong></p>
        <p>Generado el <?php echo date('d/m/Y H:i'); ?> | Total de bloques semanales: 70 (5 días × 14 horas)</p>
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