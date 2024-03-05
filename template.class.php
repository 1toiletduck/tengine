<?php

class Template {
    protected array $data = [];
    protected string $filename;
    protected bool $gzip;
    protected string $ext;
    protected string $cachePath;
    protected string $templatePath;
    protected bool $compressCssJs;
    protected array $cssFiles = [];
    protected array $jsFiles = [];
    protected ?string $combinedName = null;

    public function __construct(
        protected array $config
    ) {
        $this->gzip = $config['gzip'] ?? true;
        $this->ext = $config['ext'] ?? '.html';
        $this->cachePath = $config['cache'] ?? 'cache/';
        $this->templatePath = $config['root'] ?? 'template/';
        $this->compressCssJs = $config['compress'] ?? false;
    }

    public function display(string $title, string $filename, array $cssFiles = [], array $jsFiles = [], ?string $combinedName = null): void {
        $this->filename = $filename;
        $this->cssFiles = $cssFiles;
        $this->jsFiles = $jsFiles;
        $this->combinedName = $combinedName;

        $cacheFile = $this->getCacheFileName($filename);
        if (!$this->isCacheValid($filename, $cacheFile)) {
            $this->compileTemplate($filename);
        }

        $this->renderPage($title);
    }

    protected function getCacheFileName(string $filename): string {
        return $this->cachePath . str_replace(['/', $this->ext], ['.', ''], $filename) . '.php';
    }

    protected function isCacheValid(string $templateFile, string $cacheFile): bool {
        $templateFullPath = $this->templatePath . $templateFile . $this->ext;
        return file_exists($cacheFile) && filemtime($cacheFile) >= filemtime($templateFullPath);
    }

    protected function compileTemplate(string $filename): void {
        // Implementation for template compilation, including handling loops, conditionals, etc.
    }

    protected function renderPage(string $title): void {
        if ($this->gzip) {
            ob_start('ob_gzhandler');
        } else {
            ob_start();
        }

        $this->injectData(['PAGE_TITLE' => $title]);
        $this->includeCssJs();
        require $this->getCacheFileName($this->filename);
        ob_end_flush();
    }

    protected function injectData(array $vars): void {
        foreach ($vars as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    protected function includeCssJs(): void {
        // Implementation for including or combining CSS and JS files
        // Consider using <link> and <script> tags for individual files or combined ones
    }

    // Additional utility methods like handling loops, setting variables, etc.
}
