<?php

namespace App\Rag;

use Symfony\Component\Finder\Finder;

final class SyntheticDocumentLoader
{
    public function __construct(
        private readonly string $syntheticDir,
        private readonly DocumentChunker $chunker,
    ) {
    }

    public function load(): array
    {
        $docs = [];

        foreach ($this->findMarkdownFiles() as $file) {
            $relative = $this->toRelativePath($file->getPathname());
            $raw      = $file->getContents();

            foreach ($this->chunker->chunk($raw) as $i => $chunk) {
                $docs[] = [
                    'id'     => $relative . '#' . $i,
                    'text'   => $chunk,
                    'source' => $relative,
                    'type'   => $this->detectType($relative),
                    'tenant' => $this->detectTenant($raw),
                ];
            }
        }

        return $docs;
    }

    private function findMarkdownFiles(): Finder
    {
        return (new Finder())
            ->files()
            ->in($this->syntheticDir)
            ->name('*.md');
    }

    private function toRelativePath(string $fullPath): string
    {
        $relative = str_replace(
            [$this->syntheticDir . '/', $this->syntheticDir . '\\'],
            '',
            $fullPath,
        );

        return str_replace('\\', '/', $relative);
    }

    private function detectType(string $relativePath): string
    {
        return str_contains($relativePath, '/guides/') ? 'guide' : 'finding';
    }

    private function detectTenant(string $content): string
    {
        $marker = '**Tenant:**';

        foreach (explode("\n", $content) as $line) {
            $line = trim($line);

            if (str_starts_with($line, $marker)) {
                $value = trim(substr($line, strlen($marker)));
                return $value !== '' ? $value : 'global';
            }
        }

        return 'global';
    }
}
