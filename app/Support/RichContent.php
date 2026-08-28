<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

class RichContent
{
    public static function render(?string $html): HtmlString
    {
        $html = (string) $html;

        if (self::containsHtml($html)) {
            $html = self::normalizeHtmlTextLineBreaks($html);
        } else {
            $html = self::plainTextToHtml($html);
        }

        $html = self::normalizeStorageUrls($html);
        $html = self::stripUnsafeHtml($html);

        return new HtmlString($html);
    }

    private static function containsHtml(string $html): bool
    {
        return (bool) preg_match('/<\s*[a-z][^>]*>/i', $html);
    }

    private static function plainTextToHtml(string $text): string
    {
        $text = trim(str_replace(["\r\n", "\r"], "\n", $text));

        if ($text === '') {
            return '';
        }

        $paragraphs = preg_split("/\n{2,}/", $text) ?: [];

        return collect($paragraphs)
            ->map(fn (string $paragraph): string => '<p>' . nl2br(e(trim($paragraph)), false) . '</p>')
            ->implode('');
    }

    private static function normalizeHtmlTextLineBreaks(string $html): string
    {
        $html = str_replace(["\r\n", "\r"], "\n", $html);
        $parts = preg_split('/(<[^>]+>)/', $html, -1, PREG_SPLIT_DELIM_CAPTURE) ?: [];

        return collect($parts)
            ->map(function (string $part): string {
                if ($part === '' || str_starts_with($part, '<') || trim($part) === '') {
                    return $part;
                }

                return preg_replace("/\n+/", '<br>', $part) ?? $part;
            })
            ->implode('');
    }

    private static function normalizeStorageUrls(string $html): string
    {
        return preg_replace_callback(
            '/\b(src|href)=(["\'])(?:https?:\/\/[^\/"\']+)?\/storage\/([^"\']+)\2/i',
            fn (array $matches): string => $matches[1] . '=' . $matches[2] . asset('storage/' . ltrim($matches[3], '/')) . $matches[2],
            $html
        );
    }

    private static function stripUnsafeHtml(string $html): string
    {
        $html = preg_replace('/<\s*(script|style|iframe|object|embed)\b[^>]*>.*?<\s*\/\s*\1\s*>/is', '', $html);
        $html = preg_replace('/\s+on[a-z]+\s*=\s*(["\']).*?\1/is', '', $html);
        $html = preg_replace('/\s+(src|href)\s*=\s*(["\'])\s*javascript:.*?\2/is', '', $html);

        return $html;
    }
}
