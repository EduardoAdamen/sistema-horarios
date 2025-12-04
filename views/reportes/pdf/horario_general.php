<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario General - <?php echo $info['carrera_nombre']; ?></title>
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
            margin-bottom: 12px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            font-size: 9pt;
            margin-bottom: 5px;
        }
        
        .info-item {
            text-align: center;
        }
        
        .info-item strong {
            font-weight: bold;
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
            border-left: 3px solid #2563eb;
            background: #f8fafc;
        }
        
        .block-subject {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 3px;
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
        <h1>Horario General</h1>
        <h2><?php echo $info['carrera_nombre']; ?></h2>
        <div class="info-grid">
            <div class="info-item">
                <strong>Período:</strong> <?php echo $info['periodo_nombre']; ?>
            </div>
            <div class="info-item">
                <strong>Semestre:</strong> <?php echo $info['semestre_nombre']; ?>
            </div>
            <div class="info-item">
                <strong>Fecha:</strong> <?php echo date('d/m/Y'); ?>
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
            <?php 
            $dias_keys = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
            
            if (isset($matriz_horarios['horas']) && is_array($matriz_horarios['horas'])):
                foreach ($matriz_horarios['horas'] as $hora): 
            ?>
                <tr>
                    <td class="hour-cell">
                        <?php echo substr($hora, 0, 5); ?>
                    </td>

                    <?php foreach ($dias_keys as $dia_key): ?>
                        <td>
                            <?php 
                            $horario = $matriz_horarios['matriz'][$dia_key][$hora] ?? null;

                            if ($horario): 
                            ?>
                                <div class="schedule-block">
                                    <div class="block-subject">
                                        <?php echo $horario['materia_clave']; ?>
                                    </div>
                                    <div class="block-name">
                                        <?php echo substr($horario['materia_nombre'], 0, 35); ?>
                                    </div>
                                    <div class="block-info">
                                        <strong>Gpo:</strong> <?php echo $horario['grupo_clave']; ?>
                                    </div>
                                    <div class="block-info">
                                        <strong>Prof:</strong> <?php echo substr($horario['docente'], 0, 18); ?>
                                    </div>
                                    <div class="block-info">
                                        <strong>Aula:</strong> <?php echo $horario['aula'] ?? 'S/A'; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php 
                endforeach; 
            endif; 
            ?>
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Generado el <?php echo date('d/m/Y H:i'); ?></strong></p>
        <p>Este horario es oficial y ha sido conciliado por el Jefe de Departamento</p>
    </div>

    <script>
        // Auto-imprimir al cargar
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>

</body>
</html>