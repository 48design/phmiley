<?php
use FortyeightDesign\Phmiley\Phmiley;

if (is_file(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else if (is_file(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
} else {
    throw new Exception("In order to run this demo, either run <code>composer install</code> in this package root or in the parent's package root first.");
}

$Phmiley = new Phmiley();

$testString = 'I could eat 11️⃣ 🍕 right now! 🤤 🧎🏾‍♂️👩🏿';

ob_start();
?>
<!DOCTYPE html>
<meta charset="UTF-8">
<!-- This file is automatically generated by demo/index.php, so we can link to it on a CDN in the README. -->
<style>
body {
    font-family: apple color emoji,segoe ui emoji,noto color emoji,android emoji,emojisymbols,emojione mozilla,twemoji mozilla,segoe ui symbol,sans-serif;
}

table {
    border-collapse: collapse;
}

th, td {
    border: solid #ddd 1px;
    padding: 0.3em 0.6em;
}

th {
    text-align: left;
}

td {
    padding-right: 3em;
}

p {
    font-size: 150%;
}

.hljs {
    white-space: pre-wrap;
    overflow-x: auto;
    max-width: 40vw;
}
</style>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.0.0/styles/monokai-sublime.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.0.0/highlight.min.js"></script>
<script charset="UTF-8"
 src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.0.0/languages/php.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>

<h1>48design/phmiley Demo Code</h1>

<h2>tagGenerator</h2>

<table>
    <tr>
        <th>tagGenerator / config</th>
        <th>Output</th>
    </tr>

    <tr>
        <td>
            none (direct Browser output)
            <pre><code class="php">// The variable $testString in the following code sections
// corresponds to the following string:
"<?= $testString ?>"
</code></pre>
        </td>
        <td><p><?= $testString ?></p></td>
    </tr>

    <tr>
        <td>default (with default preset "twemoji_72")
            <pre><code class="php">$Phmiley->parse($testString)</code></pre>
        </td>
        <td><p><?php
            
            ob_start();
            print $Phmiley->parse($testString);
            $output = ob_get_flush();
            print '</p><pre><code class="php">' . htmlspecialchars($output) . '</code></pre>';
        ?></td>
    </tr>

    <tr>
        <td>default (with preset "twemoji_svg")
            <pre><code class="php">$Phmiley->setPreset("twemoji_svg");
$Phmiley->parse($testString);</code></pre>
        </td>
        <td><p><?php
            ob_start();
            $Phmiley->setPreset("twemoji_svg");
            print $Phmiley->parse($testString);
            $output = ob_get_flush();
            print '</p><pre><code class="php">' . htmlspecialchars($output) . '</code></pre>';
        ?></td>
    </tr>

    <tr>
        <td>default (with preset "openmoji_svg")
            <pre><code class="php">$Phmiley->setPreset("openmoji_svg");
$Phmiley->parse($testString);</code></pre>
        </td>
        <td><p><?php
            ob_start();
            $Phmiley->setPreset("openmoji_svg");
            print $Phmiley->parse($testString);
            $output = ob_get_flush();
            print '</p><pre><code class="php">' . htmlspecialchars($output) . '</code></pre>';
        ?></td>
    </tr>

    <tr>
        <td>default (with preset "openmoji_72")
            <pre><code class="php">$Phmiley->setPreset("openmoji_72");
$Phmiley->parse($testString);</code></pre>
        </td>
        <td><p><?php
            ob_start();
            $Phmiley->setPreset("openmoji_72");
            print $Phmiley->parse($testString);
            $output = ob_get_flush();
            print '</p><pre><code class="php">' . htmlspecialchars($output) . '</code></pre>';
        ?></td>
    </tr>

    <tr>
        <td>default (with preset "openmoji_618")
            <pre><code class="php">$Phmiley->setPreset("openmoji_618");
$Phmiley->parse($testString);</code></pre>
        </td>
        <td><p><?php
            ob_start();
            $Phmiley->setPreset("openmoji_618");
            print $Phmiley->parse($testString);
            $output = ob_get_flush();
            print '</p><pre><code class="php">' . htmlspecialchars($output) . '</code></pre>';
        ?></td>
    </tr>

    <tr>
        <td>default (with custom options, e.g. local emoji images)
            <pre><code class="php">$Phmiley->imgBase = "./example-images/";
$Phmiley->imgExt = "svg";
$Phmiley->codeUppercase = true;
$Phmiley->parse($testString);</code></pre>
        </td>
        <td><p><?php
            ob_start();
            $Phmiley->imgBase = "./example-images/";
            $Phmiley->imgExt = "svg";
            $Phmiley->codeUppercase = true;

            print $Phmiley->parse($testString);
            $output = ob_get_flush();
            print '</p><pre><code class="php">' . htmlspecialchars($output) . '</code></pre>';
        ?></td>
    </tr>

    <?php
        $Phmiley->tagGenerator = function($data) {
            return $data['emoji'] . ' => <span style="outline: solid red 1px;">' . $data['code'] . '</span>';
        };
    ?>
    
    <tr>
        <td>
            custom
            <pre><code class="php">$Phmiley->tagGenerator = function($data) {
    return $data['emoji']
            . ' => &lt;span style=&quot;outline: solid red 1px;&quot;&gt;'
            . $data['code']
            . '&lt;/span&gt;';
};</code></pre>
        </td>
        <td><p><?php
            ob_start();
            print $Phmiley->parse($testString);

            $output = ob_get_flush();
            print '</p><pre><code class="php">' . htmlspecialchars($output) . '</code></pre>';
        ?></td>
    </tr>

    <?php
        $Phmiley->tagGenerator = function($data) use ($Phmiley) {
            $code = $Phmiley->codeUppercase ? strtoupper($data['code']) : $data['code'];
            return '
<img
    src="' . $Phmiley->imgBase . $code . '.' . $Phmiley->imgExt . '"
    style="height:' . ($Phmiley->imgHeight ?? '1em') . ';width:auto;vertical-align:text-bottom;"
    onerror="this.parentNode.replaceChild(document.createTextNode(\'[missing: ' . $data['emoji'] . ']\'), this);"
    >
';        
        };
    ?>

    <tr>
        <td>
            custom (replace images that are not found)
            <pre><code class="php">$Phmiley->tagGenerator = function($data) use ($Phmiley) {
    $code = $Phmiley->codeUppercase ? strtoupper($data['code']) : $data['code'];
    return '&lt;img
        src="' . $Phmiley->imgBase . $code . '.' . $Phmiley->imgExt . '"
        style="height:' . ($Phmiley->imgHeight ?? '1em') . ';width:auto;vertical-align:text-bottom;"
        onerror="this.parentNode.replaceChild(document.createTextNode(\'[missing: ' . $data['emoji'] . ']\'), this);"
        &gt;';        
};
</code></pre>
        </td>
        <td><p><?php
            ob_start();
            $Phmiley->imgBase = "./example-images/";
            $Phmiley->imgExt = "svg";
            $Phmiley->codeUppercase = true;

            print $Phmiley->parse($testString);
            $output = ob_get_flush();
            print '</p><pre><code class="php">' . htmlspecialchars($output) . '</code></pre>';
        ?></td>
    </tr>

    <?php
        $Phmiley->tagGenerator = function($data) use ($Phmiley) {
            $code = $Phmiley->codeUppercase ? strtoupper($data['code']) : $data['code'];
            return '
<img
    src="' . $Phmiley->imgBase . $code . '.' . $Phmiley->imgExt . '"
    style="height:' . ($Phmiley->imgHeight ?? '1em') . ';width:auto;vertical-align:text-bottom;"
    onerror="this.remove()"
    >
';        
        };
    ?>

    <tr>
        <td>
            custom (remove images that are not found)
            <pre><code class="php">$Phmiley->tagGenerator = function($data) use ($Phmiley) {
    $code = $Phmiley->codeUppercase ? strtoupper($data['code']) : $data['code'];
    return '&lt;img
        src="' . $Phmiley->imgBase . $code . '.' . $Phmiley->imgExt . '"
        style="height:' . ($Phmiley->imgHeight ?? '1em') . ';width:auto;vertical-align:text-bottom;"
        onerror="this.remove();"
        &gt;';        
};
</code></pre>
        </td>
        <td><p><?php
            ob_start();
            $Phmiley->imgBase = "./example-images/";
            $Phmiley->imgExt = "svg";
            $Phmiley->codeUppercase = true;

            print $Phmiley->parse($testString);
            $output = ob_get_flush();
            print '</p><pre><code class="php">' . htmlspecialchars($output) . '</code></pre>';
        ?></td>
    </tr>

    <?php
        $Phmiley->tagGenerator = function($data) use ($Phmiley) {
            $code = $Phmiley->codeUppercase ? strtoupper($data['code']) : $data['code'];
            $emojiFile = $Phmiley->imgBase . $code . '.' . $Phmiley->imgExt;
            if (!is_file($emojiFile)) {
                return $data['emoji'];
            }

            return '
<img
    src="' . $emojiFile . '"
    style="height:' . ($Phmiley->imgHeight ?? '1em') . ';width:auto;vertical-align:text-bottom;"
    >
';        
        };
    ?>

    <tr>
        <td>
            custom (only replace if local image exists)
            <pre><code class="php">$Phmiley->tagGenerator = function($data) use ($Phmiley) {
    $code = $Phmiley->codeUppercase ? strtoupper($data['code']) : $data['code'];
    $emojiFile = $Phmiley->imgBase . $code . '.' . $Phmiley->imgExt;
    if (!is_file($emojiFile)) {
        return $data['emoji'];
    }

    return '&lt;img
    src="' . $emojiFile . '"
    style="height:' . ($Phmiley->imgHeight ?? '1em') . ';width:auto;vertical-align:text-bottom;"
    &gt;';        
};
</code></pre>
        </td>
        <td><p><?php
            ob_start();
            $Phmiley->imgBase = "./example-images/";
            $Phmiley->imgExt = "svg";
            $Phmiley->codeUppercase = true;

            print $Phmiley->parse($testString);
            $output = ob_get_flush();
            print '</p><pre><code class="php">' . htmlspecialchars($output) . '</code></pre>';
        ?></td>
    </tr>
</table>

<footer>
    <ul>
        <li><a href="https://github.com/twitter/twemoji" rel="noopener noreferrer">Twemoji</a> Copyright (c) 2018 Twitter, Inc and other contributors. License: <a href="https://creativecommons.org/licenses/by/4.0/" rel="noopener noreferrer">CC-BY 4.0</a></li>
        <li><a href="https://openmoji.org/" rel="noopener noreferrer">OpenMoji</a> – the open-source emoji and icon project. License: <a href="https://creativecommons.org/licenses/by-sa/4.0/#" rel="noopener noreferrer">CC BY-SA 4.0</a></li>
    </ul>
</footer><?php
$demopage = ob_get_flush();
file_put_contents(__DIR__ . '/index.html', $demopage);
?>