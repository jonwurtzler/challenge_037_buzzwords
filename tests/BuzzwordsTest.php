<?php

use Buzzwords\BuzzwordLoader;
use Buzzwords\BuzzwordReader;

class BuzzwordsTest extends PHPUnit_Framework_TestCase
{

  /**
   * Test we get some buzzwords loaded from the wiki page.
   *
   * @return void
   */
  public function testBuzzwordLoad() {
    $loader = new BuzzwordLoader();
    $this->assertNotEmpty($loader->loadBuzzwords());
  }

  /**
   * Test Text Content reader.
   *
   * @return void
   */
  public function testTextReader() {
    $reader = new BuzzwordReader();
    $words  = $reader->readTextContent("win-win, synergy, agile");
    $this->assertCount(3, $words);
  }

  /**
   * Test total word count from text reader.
   *
   * @return void
   */
  public function testWordCount() {
    $reader    = new BuzzwordReader();
    $reader->readTextContent("win-win, synergy, agile, test some more");
    $wordCount = $reader->getTotalWordCount();
    $this->assertEquals(6, $wordCount);
  }

  /**
   * Test File Content reader.
   *
   * @return void
   */
  public function testFileReader() {
    $reader = new BuzzwordReader();
    $words  = $reader->readFileContent("testfile.txt");
    $this->assertCount(8, $words);
  }

  /**
   * Test Url Content reader.
   *
   * @return void
   */
  public function testUrlReader() {
    $reader = new BuzzwordReader();
    $words  = $reader->readUrlContent("https://nerdery.com");
    $this->assertCount(3, $words);
  }

}
