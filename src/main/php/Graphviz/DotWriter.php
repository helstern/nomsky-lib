<?php namespace Helstern\Nomsky\Graphviz;

class DotWriter
{
    /** @var DotFile */
    protected $dotFile;

    /**
     * @param DotFile $dotFile
     */
    public function __construct(DotFile $dotFile)
    {
        $this->dotFile = $dotFile;
    }

    /**
     * @return DotWriter
     */
    public function startGraph()
    {
        $this->dotFile->add('digraph {');
        $lineTerminator = $this->dotFile->getLineTerminator();
        $this->dotFile->add($lineTerminator);

        return $this;
    }

    /**
     * @return DotWriter
     */
    public function closeGraph()
    {
        $this->dotFile->add('}');
        $lineTerminator = $this->dotFile->getLineTerminator();
        $this->dotFile->add($lineTerminator);

        return $this;
    }

    /**
     * @param array $attributesMap
     * @return DotWriter
     */
    public function writeAttributes(array $attributesMap)
    {
        $attributesString = '[';

        $attributeSeparator = '';
        $valueDelimiter     = '"';
        foreach($attributesMap as $key => $value) {
            $valueString         = $valueDelimiter . $value . $valueDelimiter;
            $attributesString   .= $attributeSeparator . $key . '=' . $valueString;
            $attributeSeparator  = ', ';
        }

        $attributesString .= ']';

        $this->dotFile->add($attributesString);
        return $this;
    }

    /**
     * @param int $size
     * @return DotWriter
     */
    public function writeWhitespace($size = 1)
    {
        $indentString = str_repeat(' ', $size);
        $this->dotFile->add($indentString);

        return $this;
    }

    /**
     * @param string $lhs
     * @param string $rhs
     *
     * @return DotWriter
     */
    public function writeEdgeStatement($lhs, $rhs)
    {
        $this->dotFile->add($lhs . ' -> ' . $rhs);

        return $this;
    }

    /**
     * @param string $nodeId
     *
     * @return DotWriter
     */
    public function writeNode($nodeId)
    {
        $this->dotFile->add($nodeId);

        return $this;
    }

    /**
     * @return DotWriter
     */
    public function writeStatementTerminator()
    {
        $this->dotFile->add(';');

        $lineTerminator = $this->dotFile->getLineTerminator();
        $this->dotFile->add($lineTerminator);

        return $this;
    }
}
