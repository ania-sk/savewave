<?php

declare(strict_types=1);

namespace Framework;

class TemplateEngine
{
    private const RESERVED_KEYS = ['__data', '__path', '__output', 'this', 'basePath'];

    private array $globalTemplateData = [];

    public function __construct(private string $basePath) {}

    public function render(string $template, array $data = []): string
    {
        // render() locals ($output, $template, $basePath) są bezpieczne —
        // extract() dzieje się w osobnym scope _sandbox()
        $mergedData = array_merge($this->globalTemplateData, $data);

        ob_start();
        $this->_sandbox($this->resolve($template), $mergedData);
        $output = ob_get_clean();

        return $output === false ? '' : $output;
    }

    /**
     * Isolated render scope. Called only by render().
     * All local variable names are __ prefixed to avoid collisions with template data.
     * Keys matching RESERVED_KEYS are stripped before extract().
     */
    private function _sandbox(string $__path, array $__data): void
    {
        // Usuń zarezerwowane klucze zanim wpadną do scope szablonu
        foreach (self::RESERVED_KEYS as $__reserved) {
            unset($__data[$__reserved]);
        }

        extract($__data, EXTR_SKIP);

        include $__path;
    }

    public function resolve(string $path): string
    {
        return "{$this->basePath}/{$path}";
    }

    public function addGlobal(string $key, mixed $value): void
    {
        if (in_array($key, self::RESERVED_KEYS, true)) {
            throw new \InvalidArgumentException(
                "Key \"{$key}\" is reserved and cannot be used as a template variable."
            );
        }

        $this->globalTemplateData[$key] = $value;
    }
}
