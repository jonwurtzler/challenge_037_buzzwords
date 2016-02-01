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

$buzzwordReader = new BuzzwordReader();

$test = "Text to win-win campaigns-free increase best of breed by offering-free an incredible eyeballs. Usually, this isn’t your-free standard buy one get one free campaign-free. It offers something a home more valuable. But, because of this, these win-win messages always see high engagement win-win rates.";
$buzzwords  = $buzzwordReader->readTextContent($test);
$totalWords = $buzzwordReader->getTotalWordCount();

echo("Total Words: $totalWords\n");

foreach ($buzzwords as $word => $count) {
  $percent = (round(($count / $totalWords), 2) * 100) . "%";
  echo("$word: ($count - $percent)\n");
}
