<?php

namespace App\Helpers;

class NumerosEnLetras
{
    /**
     * Convierte un número a su representación en letras en español
     *
     * @param float $numero
     * @param string $moneda
     * @param bool $centavos
     * @return string
     */
    public static function convertir($numero, $moneda = 'Bolivianos', $centavos = false)
    {
        $numero = number_format($numero, 2, '.', '');
        $partes = explode('.', $numero);
        $entero = (int) $partes[0];
        $decimales = (int) $partes[1];
        
        $resultado = self::numeroALetras($entero);
        
        if ($entero == 1) {
            $resultado .= ' ' . rtrim($moneda, 's'); // Singular
        } else {
            $resultado .= ' ' . $moneda; // Plural
        }
        
        if ($decimales > 0 && $centavos) {
            $resultado .= ' con ' . self::numeroALetras($decimales) . ' centavos';
        } else if ($decimales > 0) {
            $resultado .= ' con ' . str_pad($decimales, 2, '0', STR_PAD_LEFT) . '/100';
        }
        
        return ucfirst($resultado);
    }
    
    /**
     * Convierte un número entero a letras
     *
     * @param int $numero
     * @return string
     */
    private static function numeroALetras($numero)
    {
        if ($numero == 0) {
            return 'cero';
        }
        
        $unidades = [
            '', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve',
            'diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve'
        ];
        
        $decenas = [
            '', '', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'
        ];
        
        $centenas = [
            '', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos',
            'seiscientos', 'setecientos', 'ochocientos', 'novecientos'
        ];
        
        if ($numero < 20) {
            return $unidades[$numero];
        }
        
        if ($numero < 100) {
            $decena = intval($numero / 10);
            $unidad = $numero % 10;
            
            if ($numero >= 21 && $numero <= 29) {
                return 'veinti' . ($unidad > 0 ? $unidades[$unidad] : '');
            }
            
            return $decenas[$decena] . ($unidad > 0 ? ' y ' . $unidades[$unidad] : '');
        }
        
        if ($numero < 1000) {
            $centena = intval($numero / 100);
            $resto = $numero % 100;
            
            $resultado = '';
            if ($numero == 100) {
                $resultado = 'cien';
            } else {
                $resultado = $centenas[$centena];
            }
            
            if ($resto > 0) {
                $resultado .= ' ' . self::numeroALetras($resto);
            }
            
            return $resultado;
        }
        
        if ($numero < 1000000) {
            $miles = intval($numero / 1000);
            $resto = $numero % 1000;
            
            $resultado = '';
            if ($miles == 1) {
                $resultado = 'mil';
            } else {
                $resultado = self::numeroALetras($miles) . ' mil';
            }
            
            if ($resto > 0) {
                $resultado .= ' ' . self::numeroALetras($resto);
            }
            
            return $resultado;
        }
        
        if ($numero < 1000000000) {
            $millones = intval($numero / 1000000);
            $resto = $numero % 1000000;
            
            $resultado = '';
            if ($millones == 1) {
                $resultado = 'un millón';
            } else {
                $resultado = self::numeroALetras($millones) . ' millones';
            }
            
            if ($resto > 0) {
                $resultado .= ' ' . self::numeroALetras($resto);
            }
            
            return $resultado;
        }
        
        return 'número demasiado grande';
    }
}