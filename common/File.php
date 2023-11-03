<?php

namespace common;

class File {

    /**
     * Returns the formatted size
     *
     * @param  integer $size
     * @param  integer $precision
     * @return string
     */
    public static function toByteString($size, $precision = 2) {
        $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        for ($i = 0; $size > 1024 && $i < 9; $i++) {
            $size /= 1024;
        }
        return number_format($size, $precision, '.', '') . $sizes[$i];
    }

    /**
     * Returns the unformatted size
     *
     * @param  string $size
     * @return integer
     */
    public static function fromByteString($size) {
        if (is_numeric($size)) {
            return (int)$size;
        }

        $type  = trim(substr($size, -2));
        $value = substr($size, 0, -2);
        switch (strtoupper($type)) {
            case 'YB':
                $value *= (1024 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024);
                break;
            case 'ZB':
                $value *= (1024 * 1024 * 1024 * 1024 * 1024 * 1024 * 1024);
                break;
            case 'EB':
                $value *= (1024 * 1024 * 1024 * 1024 * 1024 * 1024);
                break;
            case 'PB':
                $value *= (1024 * 1024 * 1024 * 1024 * 1024);
                break;
            case 'TB':
                $value *= (1024 * 1024 * 1024 * 1024);
                break;
            case 'GB':
                $value *= (1024 * 1024 * 1024);
                break;
            case 'MB':
                $value *= (1024 * 1024);
                break;
            case 'KB':
                $value *= 1024;
                break;
            default:
                break;
        }

        return $value;
    }
}
