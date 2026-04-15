<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Fallback for hosts where symlink() and exec() are disabled.
 * Copies storage/app/public/** → public/storage/** using PHP copy().
 * Run: php artisan storage:copy
 */
class StoragePublicCopy extends Command
{
    protected $signature = 'storage:copy {--clean : Remove destination files not present in source}';

    protected $description = 'Copy storage/app/public to public/storage (symlink-free alternative for restricted hosts)';

    public function handle(): int
    {
        $src = storage_path('app/public');
        $dest = public_path('storage');

        if (! is_dir($src)) {
            $this->error("Source directory does not exist: {$src}");

            return self::FAILURE;
        }

        if (! is_dir($dest)) {
            mkdir($dest, 0755, true);
        }

        $copied = $this->copyDirectory($src, $dest);

        $this->info("Done. {$copied} file(s) synced to public/storage/.");

        return self::SUCCESS;
    }

    private function copyDirectory(string $src, string $dest): int
    {
        $count = 0;

        foreach (new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($src, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        ) as $item) {
            $relative = substr($item->getPathname(), strlen($src) + 1);
            $target = $dest.DIRECTORY_SEPARATOR.$relative;

            if ($item->isDir()) {
                if (! is_dir($target)) {
                    mkdir($target, 0755, true);
                }
            } else {
                // Only copy if destination is missing or source is newer
                if (! file_exists($target) || filemtime($item->getPathname()) > filemtime($target)) {
                    copy($item->getPathname(), $target);
                    $count++;
                }
            }
        }

        return $count;
    }
}
