<?php

namespace Buzzwords;

use DOMDocument;
use DOMElement;
use DOMXPath;

class BuzzwordLoader
{

  /**
   * @var string
   */
  protected $buzzwordsUrl = "https://en.wikipedia.org/wiki/List_of_buzzwords";

  /**
   * @var array
   */
  protected $buzzwords = [];

  /**
   * Parse the buzzwords from the wiki page HTML
   */
  public function loadBuzzwords()
  {
    $doc   = $this->getWikiDOMDoc();
    $xpath = new DOMXpath($doc);

    $base = $doc->getElementById("mw-content-text");

    $wordElements = $xpath->query("div[@class=\"div-col columns column-count column-count-4\"]/ul/li", $base);

    if (!is_null($wordElements)) {
      foreach ($wordElements as $word) {
        $this->processWordElement($word);
      }
    } else {
      die("Wrong stuff man");
    }

    return $this->buzzwords;
  }

  /**
   * Load html from the wiki page as DOMDocument.
   *
   * @return DOMDocument
   */
  private function getWikiDOMDoc()
  {
    $buzzwordHtml = file_get_contents($this->buzzwordsUrl);

    $doc = new DOMDocument();
    $doc->loadHTML($buzzwordHtml);

    return $doc;
  }

  /**
   * @param DOMElement $word
   */
  private function processWordElement($word)
  {
    // Remove sup tags
    $word = $this->removeSupTags($word);

    // Remove and process any additional text.
    $word = $this->removeExtraDescriptions($word);

    // Add word to master list.
    $this->buzzwords[] = trim($word);
  }

  /**
   * Remove any reference tags as they are unneeded.
   *
   * @param DOMElement $word
   *
   * @return DOMElement
   */
  private function removeSupTags($word)
  {
    $supNodes = $word->getElementsByTagName("sup");

    if ($supNodes) {
      for ($i = $supNodes->length; --$i >= 0; ) {
        $word->removeChild($supNodes->item($i));
      }
    }

    return $word;
  }

  /**
   * Remove any extra information from a passed word.
   *
   * @param DOMElement $word
   *
   * @return string
   */
  private function removeExtraDescriptions($word)
  {
    $text     = $word->textContent;
    $baseWord = $this->checkForDouble($text);
    $baseWord = $this->checkForExtra($baseWord);

    return $baseWord;
  }

  /**
   * Check for two sets of words in the same definition.
   *   Currently only happens with 'Information superhighway / Information highway'
   *
   * @param string $word
   *
   * @return string
   */
  private function checkForDouble($word)
  {
    $word = preg_replace('(\s/\s)', "|", $word);

    if (strpos($word, "|") > -1) {
      list ($word, $double) = array_map("trim", explode("|", $word));

      $this->buzzwords[] = $double;
    }

    return $word;
  }

  /**
   * Determine if there is anything extra in the word.
   *
   * @param string $word
   *
   * @return string
   */
  private function checkForExtra($word)
  {
    $pattern = '((\-\s)|(\–\s)|(\s\-)|(\s\–)|(\()|(, as)|(real estate usage))';
    $word    = preg_replace($pattern, "|", $word);

    if (strpos($word, "|") > -1) {
      list($word, $extra) = array_map("trim", explode("|", $word));

      $this->checkForAkaWords($extra);
    }

    return $word;
  }

  /**
   * Check the 'extra' text for potential aka buzzwords.
   *
   * @param string $word
   */
  private function checkForAkaWords($word)
  {
    if (strpos($word, "also") > -1) {
      $pattern = '/([\"\.\)]|(also known as )|(also)|,.*)/';
      $word    = trim(preg_replace($pattern, " ", $word));

      $this->buzzwords[] = $word;
    }

  }

}
