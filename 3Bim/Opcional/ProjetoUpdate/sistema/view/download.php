<?php
$pastaOrigem = realpath(__DIR__ . '/../../sistema');
$arquivoZip  = sys_get_temp_dir() . '/sistema.zip';

if (!$pastaOrigem || !is_dir($pastaOrigem)) { 
    echo "Pasta sistema não encontrada."; 
    exit; 
}

$zip = new ZipArchive();
if ($zip->open($arquivoZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) { 
    echo "Erro ao criar o arquivo zip."; 
    exit; 
}

$arquivos = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($pastaOrigem), 
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($arquivos as $arquivo) {
    if (!$arquivo->isDir()) {
        $caminhoReal = $arquivo->getRealPath();
        $caminhoRelativo = 'sistema/' . substr($caminhoReal, strlen($pastaOrigem) + 1);
        $caminhoRelativo = str_replace('\\', '/', $caminhoRelativo);
        
        $zip->addFile($caminhoReal, $caminhoRelativo);
    }
}
$zip->close();

if (file_exists($arquivoZip)) {
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="sistema.zip"');
    header('Content-Length: ' . filesize($arquivoZip));
    header('Pragma: no-cache');
    header('Expires: 0');
    
    ob_clean();
    flush();
    
    readfile($arquivoZip);
    unlink($arquivoZip);
    exit;
}