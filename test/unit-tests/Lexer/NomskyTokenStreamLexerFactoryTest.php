<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\Tokens\Token;

class NomskyTokenStreamLexerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @small
     * @group small
     */
    public function testTokenizeNomskyIsoEbnf()
    {
        $grammarFile = TestResources::getResourceFilePath('nomsky.iso.ebnf');
        $lexer = (new NomskyTokenStreamLexerFactory())->fromFile($grammarFile);

        $actualTokens = [];

        $token = $lexer->currentToken();
        while ($token instanceof Token) {
            $actualTokens[] = array(
                'value' => $token->getValue(),
                'type' => $token->getType()
            );

            if ($lexer->hasNextToken()) {
                $lexer->nextToken();
                $token = $lexer->currentToken();
            } else {
                $token = null;
            }
        }

        $this->assertNotEmpty(
            $actualTokens,
            'lexer failed to match any token from file nomsky.iso.ebnf (' . $grammarFile . ')'
        );

        $this->markTestIncomplete('test should also provide the full list of tokens in that file');
    }
}
