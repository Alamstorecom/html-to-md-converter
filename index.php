<?php
/* 
HTML to Markdown Converter (single-file PHP solution)
Repo: https://github.com/Alamstorecom/html-to-md-converter
*/

// ================== CONVERSION FUNCTIONS ================== //
function html_to_markdown($html) {
    // Basic conversions
    $replacements = [
        '/<h1>(.*?)<\/h1>/' => '# $1',
        '/<h2>(.*?)<\/h2>/' => '## $1',
        '/<h3>(.*?)<\/h3>/' => '### $1',
        '/<b>(.*?)<\/b>/' => '**$1**',
        '/<strong>(.*?)<\/strong>/' => '**$1**',
        '/<i>(.*?)<\/i>/' => '*$1*',
        '/<em>(.*?)<\/em>/' => '*$1*',
        '/<code>(.*?)<\/code>/' => '`$1`',
        '/<pre>(.*?)<\/pre>/s' => "```\n$1\n```",
        '/<a href="(.*?)">(.*?)<\/a>/' => '[$2]($1)',
        '/<img src="(.*?)" alt="(.*?)"\s*\/?>/' => '![$2]($1)',
        '/<li>(.*?)<\/li>/' => '- $1',
        '/<hr\s*\/?>/' => '---',
        '/<br\s*\/?>/' => "  \n"
    ];

    // Remove scripts/styles
    $html = preg_replace('/<script.*?>.*?<\/script>/is', '', $html);
    $html = preg_replace('/<style.*?>.*?<\/style>/is', '', $html);
    
    // Apply replacements
    foreach($replacements as $pattern => $replacement) {
        $html = preg_replace($pattern, $replacement, $html);
    }
    
    // Remove remaining HTML tags
    $html = strip_tags($html);
    
    return trim($html);
}

// ================== CLI/WEB INTERFACE ================== //
if(php_sapi_name() === 'cli') {
    // Command-line mode
    if(isset($argv[1]) {
        $input = $argv[1];
        $output = $argv[2] ?? 'output.md';
        
        if(!file_exists($input)) die("Input file not found!");
        
        $html = file_get_contents($input);
        $md = html_to_markdown($html);
        file_put_contents($output, $md);
        echo "Converted $input to $output\n";
    } else {
        echo "Usage: php converter.php input.html [output.md]\n";
    }
} else {
    // Web interface
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $html = $_POST['html'] ?? '';
        $md = html_to_markdown($html);
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>HTML to Markdown Converter</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        textarea { width: 100%; height: 200px; margin: 10px 0; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h1>HTML to Markdown Converter</h1>
        <form method="post">
            <textarea name="html" placeholder="Paste HTML here..."><?= htmlspecialchars($_POST['html'] ?? '') ?></textarea>
            <button type="submit">Convert</button>
        </form>
        <?php if(isset($md)): ?>
        <h2>Markdown Result:</h2>
        <pre><?= htmlspecialchars($md) ?></pre>
        <?php endif; ?>
    </div>
</body>
</html>
<?php } ?>
