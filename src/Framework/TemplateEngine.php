<?php

declare(strict_types=1);

namespace Framework;

class TemplateEngine
{
    private array $globalTemplateData = [];

    public function __construct(private string $basePath) {}

    public function render(string $template, array $data = [])
    {
        // FIXME - AI CR - [W13 WARNING][Bezpieczeństwo] extract() wypłaszcza tablice do zmiennych lokalnych. Klucze kontrolowane przez użytkownika mogą nadpisać zmienne (np. 'this', 'basePath'). EXTR_SKIP pomaga, ale rozważ jawne przekazywanie lub blacklistę kluczy.
        extract($data, EXTR_SKIP);
        extract($this->globalTemplateData, EXTR_SKIP);

        ob_start();

        include $this->resolve($template);

        $output = ob_get_contents();

        ob_end_clean();

        return $output;
    }

    public function resolve(string $path)
    {
        return "{$this->basePath}/{$path}";
    }

    public function addGlobal(string $key, mixed $value)
    {
        $this->globalTemplateData[$key] = $value;
    }
}
