<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga Horaria de Docentes - <?php echo $periodo['nombre']; ?></title>
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
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 14pt;
            color: #2563eb;
            margin-bottom: 8px;
        }
        
        .header p {
            font-size: 9pt;
            color: #666;
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
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #000;
            font-size: 8pt;
        }
        
        thead th.text-center {
            text-align: center;
        }
        
        tbody td {
            padding: 6px;
            border: 1px solid #666;
            vertical-align: middle;
        }
        
        tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        tfoot td {
            background: #f0f0f0;
            font-weight: bold;
            padding: 8px 6px;
            border: 1px solid #666;
        }
        
        .badge-type {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            background: #e5e7eb;
            color: #374151;
            display: inline-block;
        }
        
        .progress-bar {
            width: 100%;
            height: 16px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid #999;
            position: relative;
        }
        
        .progress-fill {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 7pt;
            color: #fff;
        }
        
        .progress-fill.bg-secondary { background: #94a3b8; }
        .progress-fill.bg-info { background: #3b82f6; }
        .progress-fill.bg-success { background: #10b981; }
        .progress-fill.bg-warning { background: #f59e0b; }
        .progress-fill.bg-danger { background: #ef4444; }
        
        .badge-estado {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            display: inline-block;
        }
        
        .badge-estado.bg-secondary { background: #e5e7eb; color: #374151; }
        .badge-estado.bg-info { background: #dbeafe; color: #1e40af; }
        .badge-estado.bg-success { background: #d1fae5; color: #065f46; }
        .badge-estado.bg-warning { background: #fef3c7; color: #92400e; }
        .badge-estado.bg-danger { background: #fee2e2; color: #991b1b; }
        
        .legend {
            margin-top: 20px;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 8pt;
        }
        
        .legend h3 {
            font-size: 10pt;
            margin-bottom: 8px;
        }
        
        .legend-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .legend ul {
            list-style: none;
            padding: 0;
        }
        
        .legend ul li {
            margin-bottom: 4px;
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
            
            thead {
                display: table-header-group;
            }
            
            tbody tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Reporte de Carga Horaria de Docentes</h1>
        <h2><?php echo $periodo['nombre']; ?></h2>
        <p><strong>Fecha de generación:</strong> <?php echo date('d/m/Y H:i'); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No. Empleado</th>
                <th>Docente</th>
                <th>Tipo</th>
                <th class="text-center">Hrs Máx</th>
                <th class="text-center">Hrs Asig</th>
                <th class="text-center">Bloques</th>
                <th class="text-center">Materias</th>
                <th class="text-center">% Carga</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_docentes = 0;
            $total_horas_asignadas = 0;
            $total_horas_maximas = 0;
            
            foreach ($docentes as $docente): 
                $horas_asignadas = $docente['horas_asignadas'] ?? 0;
                $porcentaje = ($docente['horas_max_semana'] > 0) 
                    ? ($horas_asignadas / $docente['horas_max_semana']) * 100 
                    : 0;
                
                $total_docentes++;
                $total_horas_asignadas += $horas_asignadas;
                $total_horas_maximas += $docente['horas_max_semana'];
                
                $color_barra = 'secondary';
                $estado_texto = 'Sin asignar';
                if ($porcentaje >= 90) {
                    $color_barra = 'danger';
                    $estado_texto = 'Sobrecargado';
                } elseif ($porcentaje >= 70) {
                    $color_barra = 'warning';
                    $estado_texto = 'Carga alta';
                } elseif ($porcentaje >= 40) {
                    $color_barra = 'success';
                    $estado_texto = 'Carga normal';
                } elseif ($porcentaje > 0) {
                    $color_barra = 'info';
                    $estado_texto = 'Carga baja';
                }
            ?>
            <tr>
                <td><strong><?php echo $docente['numero_empleado']; ?></strong></td>
                <td><?php echo $docente['docente']; ?></td>
                <td>
                    <span class="badge-type">
                        <?php echo ucfirst(str_replace('_', ' ', $docente['tipo'])); ?>
                    </span>
                </td>
                <td style="text-align: center;"><?php echo $docente['horas_max_semana']; ?></td>
                <td style="text-align: center;">
                    <strong><?php echo $horas_asignadas; ?></strong>
                </td>
                <td style="text-align: center;"><?php echo $docente['num_bloques']; ?></td>
                <td style="text-align: center;"><?php echo $docente['num_materias']; ?></td>
                <td style="text-align: center;">
                    <div class="progress-bar">
                        <div class="progress-fill bg-<?php echo $color_barra; ?>" 
                             style="width: <?php echo min($porcentaje, 100); ?>%">
                            <?php echo round($porcentaje, 1); ?>%
                        </div>
                    </div>
                </td>
                <td style="text-align: center;">
                    <span class="badge-estado bg-<?php echo $color_barra; ?>">
                        <?php echo $estado_texto; ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>TOTALES:</strong></td>
                <td style="text-align: center;"><strong><?php echo $total_horas_maximas; ?></strong></td>
                <td style="text-align: center;"><strong><?php echo $total_horas_asignadas; ?></strong></td>
                <td colspan="4"></td>
            </tr>
        </tfoot>
    </table>

    <div class="legend">
        <h3>Leyenda de Estados de Carga</h3>
        <div class="legend-grid">
            <div>
                <strong>Estados:</strong>
                <ul>
                    <li><span class="badge-estado bg-secondary">Sin asignar</span> - 0% de carga</li>
                    <li><span class="badge-estado bg-info">Carga baja</span> - Menos del 40%</li>
                    <li><span class="badge-estado bg-success">Carga normal</span> - 40% a 69%</li>
                    <li><span class="badge-estado bg-warning">Carga alta</span> - 70% a 89%</li>
                    <li><span class="badge-estado bg-danger">Sobrecargado</span> - 90% o más</li>
                </ul>
            </div>
            <div>
                <strong>Tipos de Contratación:</strong>
                <ul>
                    <li><strong>Tiempo Completo:</strong> 40 horas semanales</li>
                    <li><strong>Medio Tiempo:</strong> 20 horas semanales</li>
                    <li><strong>Asignatura:</strong> Horas variables</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="footer">
        <p><strong>Total de docentes activos:</strong> <?php echo $total_docentes; ?> | 
        <strong>Horas asignadas:</strong> <?php echo $total_horas_asignadas; ?> / <?php echo $total_horas_maximas; ?> 
        (<?php 
        $porcentaje_global = ($total_horas_maximas > 0) 
            ? round(($total_horas_asignadas / $total_horas_maximas) * 100, 1) 
            : 0;
        echo $porcentaje_global;
        ?>% de ocupación global)</p>
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