<?php
// Temp deploy - pull controller change + clear caches. Delete after.
error_reporting(E_ALL);
ini_set('display_errors', '1');
header('Content-Type: text/plain');

function runCmd(array $argv, $cwd = null) {
    $spec = [1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
    $p = proc_open($argv, $spec, $pipes, $cwd);
    if (!is_resource($p)) return '(proc_open failed)';
    $out = stream_get_contents($pipes[1]);
    $err = stream_get_contents($pipes[2]);
    fclose($pipes[1]); fclose($pipes[2]);
    proc_close($p);
    return trim($out . "\n" . $err);
}

$repo = '/home/aborganicfirm/organic-store';
echo "GIT fetch\n" . runCmd(['/usr/bin/git', '-C', $repo, 'fetch', 'origin', 'main']) . "\n\n";
echo "GIT reset\n" . runCmd(['/usr/bin/git', '-C', $repo, 'reset', '--hard', 'origin/main']) . "\n\n";
echo "HEAD " . runCmd(['/usr/bin/git', '-C', $repo, 'rev-parse', '--short', 'HEAD']) . "\n\n";
echo "view:clear\n" . runCmd(['/usr/bin/php', $repo . '/artisan', 'view:clear']) . "\n\n";
echo "config:clear\n" . runCmd(['/usr/bin/php', $repo . '/artisan', 'config:clear']) . "\n";
