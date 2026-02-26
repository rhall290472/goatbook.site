<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // or restrict to your domain

$owner = 'rhall290472';
$repo  = 'goatbook.site';          // ← change if needed
$ref   = 'main';

$token = getenv('GITHUB_TOKEN'); // Best: set in server env / .env / hosting panel
// Or hard-code temporarily for testing (but remove before going live!)
// $token = 'ghp_YourFineGrainedOrClassicTokenHere';

if (!$token) {
    http_response_code(500);
    echo json_encode(['error' => 'Server not configured']);
    exit;
}

$opts = [
    'http' => [
        'method' => 'GET',
        'header' => [
            "Authorization: Bearer $token",
            "Accept: application/vnd.github+json",
            "User-Agent: YourSite/1.0" // GitHub requires this
        ]
    ]
];
$context = stream_context_create($opts);

// 1. Get current commit SHA
$refUrl = "https://api.github.com/repos/$owner/$repo/git/ref/heads/$ref";
$refJson = @file_get_contents($refUrl, false, $context);

if ($refJson === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch ref']);
    exit;
}

$refData = json_decode($refJson, true);
$sha = $refData['object']['sha'] ?? null;

if (!$sha) {
    http_response_code(500);
    echo json_encode(['error' => 'No SHA found']);
    exit;
}

$shortSha = substr($sha, 0, 7);

// 2. Get tags (try to find matching one)
$tagsUrl = "https://api.github.com/repos/$owner/$repo/tags?per_page=100";
$tagsJson = @file_get_contents($tagsUrl, false, $context);
$tags = $tagsJson ? json_decode($tagsJson, true) : [];

$tag = null;
foreach ($tags as $t) {
    if ($t['commit']['sha'] === $sha) {
        $tag = $t['name'];
        break;
    }
}

$version = $tag ?: $shortSha;
$commitUrl = "https://github.com/$owner/$repo/commit/$sha";
$date = date('M j, Y'); // or use your preferred format

echo json_encode([
    'version'   => $version,
    'shortSha'  => $shortSha,
    'commitUrl' => $commitUrl,
    'date'      => $date
]);