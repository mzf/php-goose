<?php

namespace Goose\Tests\Modules\Extractors;

use Goose\Article;

class MetaExtractorTest extends \PHPUnit_Framework_TestCase
{
    use \Goose\Tests\Harness\TestTrait;

    private static $CLASS_NAME = '\Goose\Modules\Extractors\MetaExtractor';

    /**
     * @dataProvider getTitleProvider
     */
    public function testGetTitle($expected, $article, $message)
    {
        $article->setOpenGraph([
            'site_name' => 'Example Website',
        ]);
        $article->setDomain('www.example.com');

        $this->setArticle($article);

        $this->assertSame(
            $expected,
            $this->call('getTitle'),
            $message
        );
    }

    public function getTitleProvider() {
        return [
            ['Ut venenatis rutrum ex, eu feugiat dolor', $this->generate('<html><head><title>Ut venenatis rutrum ex, eu feugiat dolor</title></head></html>'), 'No splitter'],
            ['Ut venenatis | rutrum ex, eu feugiat dolor', $this->generate('<html><head><title>Ut venenatis | rutrum ex, eu feugiat dolor | Example Website</title></head></html>'), 'Pipe splitter'],
            ['Ut venenatis - rutrum ex, eu feugiat dolor', $this->generate('<html><head><title>Ut venenatis - rutrum ex, eu feugiat dolor - www.example.com</title></head></html>'), 'Dash splitter'],
            ['Ut venenatis : rutrum ex, eu feugiat dolor', $this->generate('<html><head><title>Ut venenatis : rutrum ex, eu feugiat dolor : www.example.com </title></head></html>'), 'Colon splitter'],
            // libxml will automatically place <title> inside <head>.
            ['Ut venenatis rutrum ex, eu feugiat dolor', $this->generate('<html><title>Ut venenatis rutrum ex, eu feugiat dolor</title></html>'), 'Title tag not in head tag'],
            ['', $this->generate('<html></html>'), 'No title tag'],
            ['Ut venenatis rutrum ex, eu feugiat dolor', $this->generate('<html><head><title>Ut venenatis rutrum ex, eu feugiat dolor |</title></head></html>'), 'Splitter as last character'],
            ['|Ut venenatis rutrum ex, eu feugiat dolor', $this->generate('<html><head><title>|Ut venenatis rutrum ex, eu feugiat dolor</title></head></html>'), 'Splitter as first character'],
            ['', $this->generate('<html><head><title>|</title></head></html>'), 'Splitter as only character'],
        ];
    }
}