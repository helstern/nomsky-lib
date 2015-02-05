<?php namespace Helstern\Nomsky\TextMatch;

class CharacterPosition {

    /** @var  int */
    protected $column;

    /** @var  int */
    protected $line;

    /** @var int */
    protected $byteIndex;

    /**
     * @param int $byteIndex
     * @param int $column
     * @param int $line
     */
    public function __construct($byteIndex, $column, $line) {
        $this->byteIndex = $byteIndex;
        $this->column = $column;
        $this->line   = $line;
    }

    /**
     * @return int
     */
    public function getByteIndex()
    {
        return $this->byteIndex;
    }

    /**
     * @return int
     */
    public function getColumn() {
        return $this->column;
    }

    /**
     * @return int
     */
    public function getLine() {
        return $this->line;
    }

    /**
     * @param CharacterPosition $offset
     * @return CharacterPosition
     */
    public function offsetRight(CharacterPosition $offset)
    {
        $offsetLine = $offset->getLine();
        if ($offsetLine > 0) {
            $newLine = $this->getLine() + $offsetLine;
        } else {
            $newLine = $this->getLine();
        }

        if ($offsetLine > 0) {
            $newColumn = $offset->getColumn();
        } else {
            $newColumn = $this->getColumn() + $offset->getColumn();
        }

        $newByteIndex = $this->getByteIndex() + $offset->getByteIndex();

        return new CharacterPosition($newByteIndex, $newColumn, $newLine);
    }
}
