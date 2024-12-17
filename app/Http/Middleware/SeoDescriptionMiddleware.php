<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SeoDescriptionMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($this->isHtmlResponse($response)) {
            $content = $response->getContent();
            
            // Handle description
            $description = $this->getConsistentContent($request->path(), 'descriptions');
            if (preg_match('/<meta\s+name=["\']description["\']\s+content=["\'](.*?)["\']/i', $content, $matches)) {
                $existingDesc = $matches[1];
                $newDesc = $existingDesc . ' | ' . $description;
                $content = preg_replace(
                    '/<meta\s+name=["\']description["\']\s+content=["\'](.*?)["\']/i',
                    '<meta name="description" content="' . htmlspecialchars($newDesc, ENT_QUOTES) . '"',
                    $content
                );
            }
            
            // Handle keywords
            $keywords = $this->getConsistentContent($request->path(), 'tags');
            
            if (preg_match('/<meta\s+name=["\']keywords["\']\s+content=["\'](.*?)["\']/i', $content, $matches)) {
                // If keywords meta tag exists, append to it
                $existingKeys = $matches[1];
                $newKeys = $existingKeys . ',' . $keywords;
                $content = preg_replace(
                    '/<meta\s+name=["\']keywords["\']\s+content=["\'](.*?)["\']/i',
                    '<meta name="keywords" content="' . htmlspecialchars($newKeys, ENT_QUOTES) . '"',
                    $content
                );
            } else {
                // If no keywords tag exists, add it after description meta tag
                $content = preg_replace(
                    '/(<meta\s+name=["\']description["\']\s+content=["\'](.*?)["\']\s*>)/i',
                    '$1' . PHP_EOL . '<meta name="keywords" content="' . htmlspecialchars($keywords, ENT_QUOTES) . '">',
                    $content
                );
            }
            
            $response->setContent($content);
        }

        return $response;
    }

    private function getConsistentContent(string $path, string $type): string
    {
        return Cache::remember('seo_' . $type . '_' . md5($path), 86400, function() use ($path, $type) {
            try {
                $content = File::get(resource_path('seo/' . $type . '.txt'));
                $lines = array_filter(explode("\n", $content));
                
                $hash = crc32($path);
                $index = ($hash % count($lines)) + 1;
                
                foreach ($lines as $line) {
                    [$lineIndex, $content] = explode('|', $line);
                    if ((int)$lineIndex === $index) {
                        return trim($content);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error in SeoDescriptionMiddleware: ' . $e->getMessage());
            }
            
            return $type === 'descriptions' 
                ? 'Стройка.com - Проекты и сметы для строительства домов и бань'
                : 'строительство,проекты,сметы,дома,бани';
        });
    }

    private function isHtmlResponse($response): bool
    {
        $contentType = $response->headers->get('content-type');
        return $contentType && (
            str_contains(strtolower($contentType), 'text/html') ||
            str_contains(strtolower($contentType), 'application/xhtml+xml')
        );
    }
}