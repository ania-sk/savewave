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

        $text = (new TesseractOCR($processedPath))
            ->lang('pol')
            ->allowlist(range('a', 'z'), range('A', 'Z'), range(0, 9), '.,- ')
            ->run();


        if (file_exists($processedPath) && $processedPath !== $imagePath) {
            unlink($processedPath);
        }

        if (empty(trim($text))) {
            throw new \Exception("Nie wykryto tekstu.");
        }

        return [
            'raw_text' => $text,
            'amount' => $this->parseAmount($text),
            'date' => $this->parseDate($text),
            'category_id' => $this->detectCategory($text, $userId)
        ];
    }

    private function preprocessImage(string $sourcePath): string
    {
        $destPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'ocr_' . bin2hex(random_bytes(5)) . '.jpg';
        $img = imagecreatefromjpeg($sourcePath);

        if ($img) {
            imagefilter($img, IMG_FILTER_GRAYSCALE);
            imagefilter($img, IMG_FILTER_CONTRAST, -15);
            imagejpeg($img, $destPath, 85);
            imagedestroy($img);
            return $destPath;
        }

        return $sourcePath;
    }

    private function parseAmount(string $text): float
    {
        $cleanText = str_replace(' , ', ',', $text);
        if (
            preg_match('/(\d+[\s,.]+\d{2})\s*PLN/i', $cleanText, $matches) ||
            preg_match('/SUMA\s+(?!PTU)(?:PLN\s*)?(\d+[\s,.]+\d{2})/i', $cleanText, $matches)
        ) {
            return (float)str_replace([',', ' '], ['.', ''], $matches[1]);
        }
        return 0.0;
    }

    private function parseDate(string $text): string
    {
        if (preg_match('/(\d{2}[-. ]\d{2}[-. ]\d{4})/', $text, $matches)) {
            $dateRaw = str_replace(['.', ' '], '-', $matches[1]);
            return date('Y-m-d', strtotime($dateRaw));
        }
        return date('Y-m-d');
    }

    private function detectCategory(string $text, int $userId): ?int
    {
        $keywordsMap = [
            'Shopping' => ['biedronka', 'lidl', 'rossmann', 'hebe', 'auchan', 'żabka'],
            'Entertainment' => ['kino', 'restauracja', 'pub', 'netflix'],
            'Bills' => ['orange', 'tauron', 'pge', 'gaz']
        ];

        $text = mb_strtolower($text);
        foreach ($keywordsMap as $name => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($text, $kw)) {
                    return $this->categoryService->getExpenseCategoryIdByName($userId, $name);
                }
            }
        }
        return $this->categoryService->getExpenseCategoryIdByName($userId, 'Other');
    }
}
