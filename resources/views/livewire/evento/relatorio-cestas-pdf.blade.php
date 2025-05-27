<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Cestas Básicas</title>
    <style>
        @page {
            margin: 1cm;

            @bottom-center {
                content: "Página " counter(page) " de " counter(pages);
                font-family: Arial, sans-serif;
                font-size: 10px;
                color: #000;
                text-align: center;
            }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .no-photo {
            color: #999;
        }

        .last-page {
            page-break-before: always;
            text-align: center;
            margin-top: 10px;
        }

        img {
            object-fit: cover;
            width: 3cm;
            height: 2cm;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Relatório de Cestas Básicas</h2>
        <p>Data de Impressão: {{ $printDate }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>NOME</th>
                <th>INSTITUIÇÃO</th>
                <th>CESTAS</th>
                <th>FOTO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cestas as $cesta)
                <tr>
                    <td>{{ $cesta->nome }}</td>
                    <td>{{ $cesta->terreiro }}</td>
                    <td>{{ $cesta->cestas }}</td>
                    <td>
                        @if ($cesta->foto)
                            @php
                                $imagePath = storage_path('app/public/' . $cesta->foto);
                                $imageUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($cesta->foto);
                                \Log::info('Verificando imagem no PDF', [
                                    'imagePath' => $imagePath,
                                    'imageUrl' => $imageUrl,
                                    'fileUri' => 'file://' . $imagePath,
                                    'exists' => file_exists($imagePath),
                                    'readable' => is_readable($imagePath),
                                    'permissions' => file_exists($imagePath)
                                        ? substr(sprintf('%o', fileperms($imagePath)), -4)
                                        : 'N/A',
                                ]);
                            @endphp
                            @if (file_exists($imagePath) && is_readable($imagePath))
                                <img src="file://{{ $imagePath }}" alt="Foto">
                            @else
                                <span class="no-photo">Sem foto</span>
                            @endif
                        @else
                            <span class="no-photo">Sem foto</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="last-page">
        <p><strong>Relatório de Cestas Básicas</strong></p>
        <p>{{ $printDate }}</p>
        <p>Vídeos e fotos disponíveis no link: <a
                href="https://igrejauniversaldorei-my.sharepoint.com/:f:/g/personal/jmmneto_universal_org/EmvDLp6E-blGgDkGaw8kZfcBLB17W2sUuToJ12_GLOFIIg?e=NTTE5A">https://igrejauniversaldorei-my.sharepoint.com/:f:/g/personal/jmmneto_universal_org/EmvDLp6E-blGgDkGaw8kZfcBLB17W2sUuToJ12_GLOFIIg?e=NTTE5A</a>
        </p>
    </div>
    <div class="footer">
        Página <span class="pageNumber"></span> de <span class="totalPages"></span>
    </div>
</body>

</html>
