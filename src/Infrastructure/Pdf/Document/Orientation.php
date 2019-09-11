<?php
declare(strict_types=1);

namespace App\Infrastructure\Pdf\Document;

/**
 * Document Orientation
 */
class Orientation
{
    const DEFAULT = self::PORTRAIT;
    const PORTRAIT = 'portrait';
    const LANDSCAPE = 'landscape';
}
