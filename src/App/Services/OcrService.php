<?php

namespace App\Services;

use App\Services\CategoryService;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrService
{
    public function __construct(private CategoryService $categoryService) {}

    public function processReceipt(string $imagePath, int $userId): array
    {
        $processedPath = $this->preprocessImage($imagePath);

        try {
            $tesseract = new TesseractOCR($processedPath);
            $text = $tesseract
                ->lang('pol')
                ->psm(6)
                ->oem(1)
                ->run();

            if (empty(trim($text))) {
                return [
                    'raw_text' => '',
                    'amount' => '0.00',
                    'date' => date('Y-m-d'),
                    'category_id' => null
                ];
            }

            $amount = $this->parseAmount($text);

            return [
                'raw_text' => $text,
                'amount' => number_format($amount, 2, '.', ''),
                'date' => $this->parseDate($text),
                'category_id' => $this->detectCategory($text, $userId)
            ];
        } finally {
            if (file_exists($processedPath) && $processedPath !== $imagePath) {
                unlink($processedPath);
            }
        }
    }


    private function preprocessImage(string $sourcePath): string
    {
        $img = @imagecreatefromjpeg($sourcePath);
        if (!$img) return $sourcePath;

        imagefilter($img, IMG_FILTER_GRAYSCALE);
        imagefilter($img, IMG_FILTER_BRIGHTNESS, 40);
        imagefilter($img, IMG_FILTER_CONTRAST, -25);

        $destPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'ocr_tmp_' . uniqid() . '.jpg';
        imagejpeg($img, $destPath, 85);
        imagedestroy($img);

        return $destPath ?: $sourcePath;
    }

    private function parseAmount(string $text): float
    {

        if (preg_match('/SUMA\s*PLN[^\d]*([\d]+[\.,]\d{1,2})/i', $text, $m)) {
            return (float)str_replace(',', '.', $m[1]);
        }

        if (preg_match('/SUMA\s*PLN[^\d]*([0-9]{2,5})\b(?![.,])/i', $text, $m)) {
            $raw = (int)$m[1];

            if ($raw >= 100 && $raw < 1000) {
                return $raw / 10.0;      // 416 -> 41.6
            }
            if ($raw >= 1000 && $raw < 100000) {
                return $raw / 100.0;     // 4165 -> 41.65
            }

            return (float)$raw;
        }

        if (preg_match('/(KARTA|GOTÓWKA|BLIK)[^\d]*([\d]+[\.,]\d{1,2})/i', $text, $m)) {
            return (float)str_replace(',', '.', $m[2]);
        }

        $clean = preg_replace('/(\d+[\.,]\d+)\s?(l|ml|g|kg|szt)/i', '', $text);
        $clean = preg_replace('/(\d+[\.,]\d{2})[A-Z]/', '$1', $clean);
        $clean = str_replace(['O', 'o'], '0', $clean);
        $clean = str_replace('g', '9', $clean);

        preg_match_all('/(\d+[\.,]\d{1,2})/', $clean, $matches);
        $values = [];

        foreach ($matches[1] ?? [] as $m) {
            $v = (float)str_replace(',', '.', $m);
            if ($v > 0.5 && $v < 10000) {
                $values[] = $v;
            }
        }

        if (empty($values) && preg_match('/SUMA[^\d]*([\d]+[\.,]\d{1,2})/i', $text, $m)) {
            return (float)str_replace(',', '.', $m[1]);
        }

        return !empty($values) ? max($values) : 0.0;
    }




    private function parseDate(string $text): string
    {
        $t = str_replace(['O', 'o'], '0', $text);
        $t = str_replace(['l'], '1', $t);
        $t = preg_replace('/[^0-9\.\-\/ ]/', ' ', $t);
        $t = preg_replace('/\s+/', ' ', $t);
        $t = trim($t);

        $currentYear = (int)date('Y');

        // YYYY-MM-DD
        if (preg_match('/\b(20[0-9]{2})[.\-\/](0[1-9]|1[0-2])[.\-\/]([0-2][0-9]|3[01])\b/', $t, $m)) {
            $year = (int)$m[1];
            if ($year < $currentYear - 2 || $year > $currentYear + 1) {
                $year = $currentYear;
            }
            return sprintf('%04d-%02d-%02d', $year, $m[2], $m[3]);
        }

        // DD-MM-YYYY
        if (preg_match('/\b([0-2][0-9]|3[01])[.\-\/](0[1-9]|1[0-2])[.\-\/](20[0-9]{2})\b/', $t, $m)) {
            $year = (int)$m[3];
            if ($year < $currentYear - 2 || $year > $currentYear + 1) {
                $year = $currentYear;
            }
            return sprintf('%04d-%02d-%02d', $year, $m[2], $m[1]);
        }

        //  "13 05 2026"
        if (preg_match('/\b([0-2][0-9]|3[01])\s+(0[1-9]|1[0-2])\s+(20[0-9]{2})\b/', $t, $m)) {
            $year = (int)$m[3];
            if ($year < $currentYear - 2 || $year > $currentYear + 1) {
                $year = $currentYear;
            }
            return sprintf('%04d-%02d-%02d', $year, $m[2], $m[1]);
        }

        //  (np. "202", "2076", "2010")
        if (preg_match('/\b(20[0-9]{1,3})\b/', $t, $m)) {
            $year = (int)$m[1];

            // "202" → "2026"
            if ($year < 2000) {
                $year = $currentYear;
            }

            //2010, 2076
            if ($year < $currentYear - 2 || $year > $currentYear + 1) {
                $year = $currentYear;
            }

            return sprintf('%04d-%02d-%02d', $year, date('m'), date('d'));
        }

        return date('Y-m-d');
    }



    private function detectCategory(string $text, int $userId): ?int
    {
        $map = [
            'Shopping' => ['biedronka', 'lidl', 'rossmann', 'hebe', 'auchan', 'żabka', 'homla'],
            'Entertainment' => ['kino', 'restauracja', 'pub', 'netflix'],
            'Bills' => ['orange', 'tauron', 'pge', 'gaz']
        ];

        $t = mb_strtolower($text);
        foreach ($map as $name => $words) {
            foreach ($words as $w) {
                if (str_contains($t, $w)) {
                    return $this->categoryService->getExpenseCategoryIdByName($userId, $name);
                }
            }
        }

        return $this->categoryService->getExpenseCategoryIdByName($userId, 'Other');
    }
}
