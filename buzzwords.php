<?php
/**
 * Challenge Yourselph - 037
 * Determine how buzzwordy a given input is.
 *
 * Write a PHP program that given some text will count the number of buzzwords that appear in it, based on this list
 * of buzzwords from Wikipedia expressed as a percentage of the total number of words in the text. It is important
 * to know how buzzwordy text is, and your program will be a boon to readers everywhere. Extra credit if your buzzword
 * meter can take a URL and return the buzzword metric.
 *
 * Usage: php buzzwords.php
 *
 * @author Jon Wurtzler <jon.wurtzler@gmail.com>
 * @date 01/31/2016
 */

use Buzzwords\BuzzwordReader;

require_once __DIR__ . '/vendor/autoload.php';

$buzzwords      = [];
$buzzwordReader = new BuzzwordReader();

// parse command options
$shortOpts = "h";
$longOpts  = ["file::", "url::"];
$options = getopt($shortOpts, $longOpts);

// Read from file
if (isset($options['file'])) {
  $file = "testfile.txt"; // Default state.
  if ($options['file']) {
    $file = $options['file'];
  }

  $buzzwords = $buzzwordReader->readFileContent($file);
} elseif(isset($options['url'])) {
  $url = "https://nerdery.com"; // Default state.
  if ($options['url']) {
    $url = $options['url'];
  }

  $buzzwords = $buzzwordReader->readUrlContent($url);
} else {
  $buzzwordText = (string) isset($argv[1]) ? $argv[1] : false;

  if ($buzzwordText) {
    $buzzwords = $buzzwordReader->readTextContent($buzzwordText);
  }
}

$help = (boolean) isset($options['h']) ? true : false;
if ($help || (count($buzzwords) < 1)) {
echo <<<HELP
  Usage:
    $ php buzzwords.php <text>
    $ php buzzwords.php --file[=<file>]
    $ php buzzwords.php --url[=<url>]

    -h             Help
    --file=<file>  Parse file as having a box dimension on each line
    --url=<url>    Parse file as having a box dimension on each line


HELP;
}

$totalWords = $buzzwordReader->getTotalWordCount();

if ($totalWords > 0) {
  echo("Total Words: $totalWords\n");

  foreach ($buzzwords as $word => $count) {
    // Make sure to adjust the percentage to include how many words are in each phrase.
    $adjCount = $count * str_word_count($word);
    $percent  = (round(($adjCount / $totalWords), 2) * 100) . "%";

    echo("$word: ($count - $percent)\n");
  }
}
