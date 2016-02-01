<?php

namespace Buzzwords;

use DOMDocument;
use DOMElement;

class BuzzwordUrlLoader
{
  /**
   * Load text from the url's body tag.
   *
   * @param string $url
   *
   * @return string
   */
  public function loadUrl($url)
  {
    $doc  = $this->getDOMDoc($url);
    $body = $doc->getElementsByTagName("body")->item(0);
    $body = $this->clearScriptTags($body);

    return $body->textContent;
  }

  /**
   * Load html from the url as DOMDocument.
   *
   * @return DOMDocument
   */
  private function getDOMDoc($url)
  {
    $html = @file_get_contents($url);

    $doc = new DOMDocument();
    @$doc->loadHTML($html);

    return $doc;
  }

  /**
   * @param DOMElement $body
   *
   * @return DOMElement
   */
  private function clearScriptTags($body)
  {
    while (($script = $body->getElementsByTagName("script")) && $script->length) {
      $script->item(0)->parentNode->removeChild($script->item(0));
    }

    return $body;
  }

}
