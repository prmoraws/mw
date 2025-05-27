<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações do DomPDF
    |--------------------------------------------------------------------------
    |
    | Define valores padrão para o DomPDF. Pode-se configurar opções disponíveis
    | em dompdf_config.inc.php ou sobrescrever o arquivo inteiro.
    |
    */
    'show_warnings' => false, // Não lançar exceções em avisos do DomPDF
    'public_path' => null,   // Sobrescreve o caminho público, se necessário
    'convert_entities' => true, // Desativa conversão de entidades (ex.: €, £) para evitar problemas com fontes

    'options' => [
        'isRemoteEnabled' => true, // Permite acesso a arquivos remotos (imagens/CSS)
        'enable_local_file_access' => true, // Permite acesso a arquivos locais
        'dpi' => 72, // Resolução padrão para imagens e fontes
        'chroot' => public_path('storage'), // Diretório base para acesso a arquivos, deve ser absoluto
        'font_dir' => storage_path('fonts'), // Diretório para fontes, deve ser gravável
        'font_cache' => storage_path('fonts'), // Diretório para cache de fontes
        'temp_dir' => sys_get_temp_dir(), // Diretório temporário, deve ser gravável
        'allowed_protocols' => [ // Protocolos permitidos para URIs
            'data://' => ['rules' => []],
            'file://' => ['rules' => []],
            'http://' => ['rules' => []],
            'https://' => ['rules' => []],
        ],
        'log_output_file' => null, // Arquivo de log, null para desativar
        'enable_font_subsetting' => false, // Desativa subsetting de fontes
        'pdf_backend' => 'CPDF', // Backend de renderização (CPDF é padrão)
        'default_media_type' => 'screen', // Tipo de mídia padrão
        'default_paper_size' => 'a4', // Tamanho de papel padrão
        'default_paper_orientation' => 'portrait', // Orientação padrão
        'default_font' => 'serif', // Fonte padrão
        'enable_php' => true, // Habilita PHP embutido (com cuidado, risco de segurança)
        'enable_javascript' => true, // Habilita JavaScript para PDF (ex.: numeração de páginas)
        'font_height_ratio' => 1.1, // Ajuste da altura da fonte
        'enable_html5_parser' => true, // Parser HTML5 (sempre ativo no DomPDF 2.x)
    ],
];
