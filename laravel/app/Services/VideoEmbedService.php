<?php

declare(strict_types=1);

namespace App\Services;

class VideoEmbedService
{
    public function resolve(?string $url, array $options = []): ?array
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        $autoplay = (bool) ($options['autoplay'] ?? false);
        $muted = (bool) ($options['muted'] ?? false);
        $loop = (bool) ($options['loop'] ?? false);
        $background = (bool) ($options['background'] ?? false);

        if ($youtubeId = $this->youtubeId($url)) {
            $query = [
                'autoplay' => $autoplay ? '1' : '0',
                'mute' => $muted ? '1' : '0',
                'loop' => $loop ? '1' : '0',
                'playsinline' => '1',
                'rel' => '0',
            ];

            if ($background) {
                $query['controls'] = '0';
                $query['modestbranding'] = '1';
                $query['playlist'] = $youtubeId;
            }

            return [
                'type' => 'iframe',
                'provider' => 'youtube',
                'src' => 'https://www.youtube.com/embed/'.$youtubeId.'?'.http_build_query($query),
            ];
        }

        if ($vimeoId = $this->vimeoId($url)) {
            $query = [
                'autoplay' => $autoplay ? '1' : '0',
                'muted' => $muted ? '1' : '0',
                'loop' => $loop ? '1' : '0',
                'background' => $background ? '1' : '0',
                'title' => '0',
                'byline' => '0',
                'portrait' => '0',
            ];

            return [
                'type' => 'iframe',
                'provider' => 'vimeo',
                'src' => 'https://player.vimeo.com/video/'.$vimeoId.'?'.http_build_query($query),
            ];
        }

        if ($this->isDirectVideo($url)) {
            return [
                'type' => 'video',
                'provider' => 'file',
                'src' => $url,
                'mime' => $this->detectMimeType($url),
            ];
        }

        return null;
    }

    public function youtubeId(string $url): ?string
    {
        if (preg_match('/(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?(?:.*&)?v=))([\w-]{11})/i', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function vimeoId(string $url): ?string
    {
        if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/i', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function isDirectVideo(string $url): bool
    {
        $path = (string) parse_url($url, PHP_URL_PATH);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($extension, ['mp4', 'webm', 'ogg'], true);
    }

    private function detectMimeType(string $url): string
    {
        $path = (string) parse_url($url, PHP_URL_PATH);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return match ($extension) {
            'webm' => 'video/webm',
            'ogg' => 'video/ogg',
            default => 'video/mp4',
        };
    }
}
