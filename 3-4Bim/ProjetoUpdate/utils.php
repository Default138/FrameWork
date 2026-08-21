<?php
class Utils{
    function converterTipoPHP(string $tipoSQL): string
    {
        $tipoSQL = strtolower($tipoSQL);
        $tipoSQL = preg_replace('/\(.*\)/', '', $tipoSQL);
        return match($tipoSQL){
            'int', 'bigint', 'smallint', 'tinyint' => 'int',
            'varchar', 'char', 'text', 'longtext' => 'string',
            'decimal', 'float', 'double' => 'float',
            'date', 'datetime', 'timestamp' => 'string',
            'bool', 'boolean' => 'bool',
            default => 'mixed'
        };
    }
    function converterTipoPHPForm(string $tipoSQL): string
    {
        $tipoSQL = strtolower($tipoSQL);
        $tipoSQL = preg_replace('/\(.*\)/', '', $tipoSQL);
        return match($tipoSQL){
            'int', 'bigint', 'smallint', 'tinyint' => 'number',
            'varchar', 'char', 'text', 'longtext',
            'decimal', 'float', 'double'=> 'text',
            'date', 'datetime', 'timestamp' => 'date',
        };
    }
}
