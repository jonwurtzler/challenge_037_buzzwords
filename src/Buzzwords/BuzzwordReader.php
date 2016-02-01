<?php

namespace Buzzwords;

use Exception;

class BuzzwordReader
{

  /**
   * @var array
   */
  protected $buzzwords = [];

  /**
   * @var array
   */
  protected $foundWords = [];

  /**
   * @var int
   */
  protected $totalWordCount = 0;

  public function __construct() {
    $buzzwordLoader  = new BuzzwordLoader();
    $this->buzzwords = $buzzwordLoader->loadBuzzwords();
  }

  /**
   * Total word count of last read text.
   *
   * @return int
   */
  public function getTotalWordCount()
  {
    return $this->totalWordCount;
  }

  /**
   * Get a buzzword count based on simple text.
   *
   * @param string $text
   *
   * @return array
   */
  public function readTextContent($text)
  {
    $this->totalWordCount = str_word_count($text);
    $lowercaseText        = strtolower($text);

    if ($this->totalWordCount > 0) {
      foreach ($this->buzzwords as $word) {
        $count = substr_count($lowercaseText, strtolower($word));
        if ($count > 0) {
          $this->foundWords[$word] = $count;
        }
      }
    }

    arsort($this->foundWords);

    return $this->foundWords;
  }

  /**
   * Read the contents of the file and read it as normal text.
   *
   * @param string $filename
   *
   * @return array
   * @throws Exception
   */
  public function readFileContent($filename)
  {
    $content = @file_get_contents($filename);

    if (false === $content) {
      throw new Exception("Invalid File");
    }

    return $this->readTextContent($content);
  }

  /**
   * Read the content from a passed url.
   *
   * @param string $url
   *
   * @return array
   */
  public function readUrlContent($url)
  {
    $buzzwordUrlLoader = new BuzzwordUrlLoader();
    $content = $buzzwordUrlLoader->loadUrl($url);

    return $this->readTextContent($content);
  }

}
