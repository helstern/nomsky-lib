<?php namespace Helstern\Nomsky\Lexers\NomskyLexer;

use Helstern\Nomsky\Lexers\TestResources;
use Helstern\Nomsky\Tokens\Token;

class TokenStreamLexerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $fileName
     * @return string
     */
    static public function getResourceFilePath($fileName)
    {
        $resource = new TestResources(__FILE__);
        return $resource->getResourceFilePath($fileName);
    }

    /**
     * @small
     * @group small
     */
    public function testTokenizeNomskyIsoEbnf()
    {
        $grammarFile = self::getResourceFilePath('nomsky.iso.ebnf');
        $lexer = (new TokenStreamLexerFactory())->fromFile($grammarFile);

        $actualTokens = [];

        $token = $lexer->currentToken();
        while ($token instanceof Token) {
            $actualTokens[] = array(
                'value' => $token->getValue(),
                'type' => $token->getType()
            );

            if ($lexer->nextToken()) {
                $token = $lexer->currentToken();
            } else {
                $token = null;
            }
        }

        $this->assertNotEmpty(
            $actualTokens,
            'lexer failed to match any token from file nomsky.iso.ebnf (' . $grammarFile . ')'
        );

        $expectedNrOfTokens = 267;
        $actualNrOfTokens = count($actualTokens);
        $this->assertEquals($expectedNrOfTokens, $actualNrOfTokens, 'Incorrect number of tokens');

        $this->markTestIncomplete('test should also provide the full list of tokens in that file');
    }
}
