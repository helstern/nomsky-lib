<?php

$dir = dirname(__FILE__);
$composerDir = realpath($dir . '/../../composer/autoload.php');
include $composerDir;


use Helstern\Nomsky\Grammars\Ebnf\Graphviz\AstWriter;
use Helstern\Nomsky\Grammars\Ebnf\IsoEbnfLexerFactory;
use Helstern\Nomsky\Grammars\Ebnf\IsoEbnfParser;
use Helstern\Nomsky\Parser\Errors\ParseAssertions;
use Helstern\Nomsky\Tokens\TokenPredicates;

$grammarFilePath = realpath($dir . '/../../src/main/resources/ebnf.iso.ebnf');
$lexer = (new IsoEbnfLexerFactory())->fromFile($grammarFilePath);

$assertions = new ParseAssertions(new TokenPredicates);
$parser = new IsoEbnfParser($assertions);

$syntaxNode = $parser->parse($lexer);

$tmpFile = tempnam(sys_get_temp_dir(), 'nomsky_');
$tempFileObject = new SplFileObject($tmpFile);

$astWriter = new AstWriter();
$astWriter->write($syntaxNode, $tempFileObject->getFileInfo());

echo $tempFileObject->getPathname();
