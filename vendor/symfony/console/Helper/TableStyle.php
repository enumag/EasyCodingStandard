<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ECSPrefix20210508\Symfony\Component\Console\Helper;

use ECSPrefix20210508\Symfony\Component\Console\Exception\InvalidArgumentException;
use ECSPrefix20210508\Symfony\Component\Console\Exception\LogicException;
/**
 * Defines the styles for a Table.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Саша Стаменковић <umpirsky@gmail.com>
 * @author Dany Maillard <danymaillard93b@gmail.com>
 */
class TableStyle
{
    private $paddingChar = ' ';
    private $horizontalOutsideBorderChar = '-';
    private $horizontalInsideBorderChar = '-';
    private $verticalOutsideBorderChar = '|';
    private $verticalInsideBorderChar = '|';
    private $crossingChar = '+';
    private $crossingTopRightChar = '+';
    private $crossingTopMidChar = '+';
    private $crossingTopLeftChar = '+';
    private $crossingMidRightChar = '+';
    private $crossingBottomRightChar = '+';
    private $crossingBottomMidChar = '+';
    private $crossingBottomLeftChar = '+';
    private $crossingMidLeftChar = '+';
    private $crossingTopLeftBottomChar = '+';
    private $crossingTopMidBottomChar = '+';
    private $crossingTopRightBottomChar = '+';
    private $headerTitleFormat = '<fg=black;bg=white;options=bold> %s </>';
    private $footerTitleFormat = '<fg=black;bg=white;options=bold> %s </>';
    private $cellHeaderFormat = '<info>%s</info>';
    private $cellRowFormat = '%s';
    private $cellRowContentFormat = ' %s ';
    private $borderFormat = '%s';
    private $padType = \STR_PAD_RIGHT;
    /**
     * Sets padding character, used for cell padding.
     *
     * @return $this
     * @param string $paddingChar
     */
    public function setPaddingChar($paddingChar)
    {
        $paddingChar = (string) $paddingChar;
        if (!$paddingChar) {
            throw new \ECSPrefix20210508\Symfony\Component\Console\Exception\LogicException('The padding char must not be empty.');
        }
        $this->paddingChar = $paddingChar;
        return $this;
    }
    /**
     * Gets padding character, used for cell padding.
     *
     * @return string
     */
    public function getPaddingChar()
    {
        return $this->paddingChar;
    }
    /**
     * Sets horizontal border characters.
     *
     * <code>
     * ╔═══════════════╤══════════════════════════╤══════════════════╗
     * 1 ISBN          2 Title                    │ Author           ║
     * ╠═══════════════╪══════════════════════════╪══════════════════╣
     * ║ 99921-58-10-7 │ Divine Comedy            │ Dante Alighieri  ║
     * ║ 9971-5-0210-0 │ A Tale of Two Cities     │ Charles Dickens  ║
     * ║ 960-425-059-0 │ The Lord of the Rings    │ J. R. R. Tolkien ║
     * ║ 80-902734-1-6 │ And Then There Were None │ Agatha Christie  ║
     * ╚═══════════════╧══════════════════════════╧══════════════════╝
     * </code>
     * @return $this
     * @param string $outside
     * @param string $inside
     */
    public function setHorizontalBorderChars($outside, $inside = null)
    {
        $outside = (string) $outside;
        $inside = (string) $inside;
        $this->horizontalOutsideBorderChar = $outside;
        $this->horizontalInsideBorderChar = isset($inside) ? $inside : $outside;
        return $this;
    }
    /**
     * Sets vertical border characters.
     *
     * <code>
     * ╔═══════════════╤══════════════════════════╤══════════════════╗
     * ║ ISBN          │ Title                    │ Author           ║
     * ╠═══════1═══════╪══════════════════════════╪══════════════════╣
     * ║ 99921-58-10-7 │ Divine Comedy            │ Dante Alighieri  ║
     * ║ 9971-5-0210-0 │ A Tale of Two Cities     │ Charles Dickens  ║
     * ╟───────2───────┼──────────────────────────┼──────────────────╢
     * ║ 960-425-059-0 │ The Lord of the Rings    │ J. R. R. Tolkien ║
     * ║ 80-902734-1-6 │ And Then There Were None │ Agatha Christie  ║
     * ╚═══════════════╧══════════════════════════╧══════════════════╝
     * </code>
     * @return $this
     * @param string $outside
     * @param string $inside
     */
    public function setVerticalBorderChars($outside, $inside = null)
    {
        $outside = (string) $outside;
        $inside = (string) $inside;
        $this->verticalOutsideBorderChar = $outside;
        $this->verticalInsideBorderChar = isset($inside) ? $inside : $outside;
        return $this;
    }
    /**
     * Gets border characters.
     *
     * @internal
     * @return mixed[]
     */
    public function getBorderChars()
    {
        return [$this->horizontalOutsideBorderChar, $this->verticalOutsideBorderChar, $this->horizontalInsideBorderChar, $this->verticalInsideBorderChar];
    }
    /**
     * Sets crossing characters.
     *
     * Example:
     * <code>
     * 1═══════════════2══════════════════════════2══════════════════3
     * ║ ISBN          │ Title                    │ Author           ║
     * 8'══════════════0'═════════════════════════0'═════════════════4'
     * ║ 99921-58-10-7 │ Divine Comedy            │ Dante Alighieri  ║
     * ║ 9971-5-0210-0 │ A Tale of Two Cities     │ Charles Dickens  ║
     * 8───────────────0──────────────────────────0──────────────────4
     * ║ 960-425-059-0 │ The Lord of the Rings    │ J. R. R. Tolkien ║
     * ║ 80-902734-1-6 │ And Then There Were None │ Agatha Christie  ║
     * 7═══════════════6══════════════════════════6══════════════════5
     * </code>
     *
     * @param string      $cross          Crossing char (see #0 of example)
     * @param string      $topLeft        Top left char (see #1 of example)
     * @param string      $topMid         Top mid char (see #2 of example)
     * @param string      $topRight       Top right char (see #3 of example)
     * @param string      $midRight       Mid right char (see #4 of example)
     * @param string      $bottomRight    Bottom right char (see #5 of example)
     * @param string      $bottomMid      Bottom mid char (see #6 of example)
     * @param string      $bottomLeft     Bottom left char (see #7 of example)
     * @param string      $midLeft        Mid left char (see #8 of example)
     * @param string $topLeftBottom Top left bottom char (see #8' of example), equals to $midLeft if null
     * @param string $topMidBottom Top mid bottom char (see #0' of example), equals to $cross if null
     * @param string $topRightBottom Top right bottom char (see #4' of example), equals to $midRight if null
     * @return $this
     */
    public function setCrossingChars($cross, $topLeft, $topMid, $topRight, $midRight, $bottomRight, $bottomMid, $bottomLeft, $midLeft, $topLeftBottom = null, $topMidBottom = null, $topRightBottom = null)
    {
        $cross = (string) $cross;
        $topLeft = (string) $topLeft;
        $topMid = (string) $topMid;
        $topRight = (string) $topRight;
        $midRight = (string) $midRight;
        $bottomRight = (string) $bottomRight;
        $bottomMid = (string) $bottomMid;
        $bottomLeft = (string) $bottomLeft;
        $midLeft = (string) $midLeft;
        $topLeftBottom = (string) $topLeftBottom;
        $topMidBottom = (string) $topMidBottom;
        $topRightBottom = (string) $topRightBottom;
        $this->crossingChar = $cross;
        $this->crossingTopLeftChar = $topLeft;
        $this->crossingTopMidChar = $topMid;
        $this->crossingTopRightChar = $topRight;
        $this->crossingMidRightChar = $midRight;
        $this->crossingBottomRightChar = $bottomRight;
        $this->crossingBottomMidChar = $bottomMid;
        $this->crossingBottomLeftChar = $bottomLeft;
        $this->crossingMidLeftChar = $midLeft;
        $this->crossingTopLeftBottomChar = isset($topLeftBottom) ? $topLeftBottom : $midLeft;
        $this->crossingTopMidBottomChar = isset($topMidBottom) ? $topMidBottom : $cross;
        $this->crossingTopRightBottomChar = isset($topRightBottom) ? $topRightBottom : $midRight;
        return $this;
    }
    /**
     * Sets default crossing character used for each cross.
     *
     * @see {@link setCrossingChars()} for setting each crossing individually.
     * @return $this
     * @param string $char
     */
    public function setDefaultCrossingChar($char)
    {
        $char = (string) $char;
        return $this->setCrossingChars($char, $char, $char, $char, $char, $char, $char, $char, $char);
    }
    /**
     * Gets crossing character.
     *
     * @return string
     */
    public function getCrossingChar()
    {
        return $this->crossingChar;
    }
    /**
     * Gets crossing characters.
     *
     * @internal
     * @return mixed[]
     */
    public function getCrossingChars()
    {
        return [$this->crossingChar, $this->crossingTopLeftChar, $this->crossingTopMidChar, $this->crossingTopRightChar, $this->crossingMidRightChar, $this->crossingBottomRightChar, $this->crossingBottomMidChar, $this->crossingBottomLeftChar, $this->crossingMidLeftChar, $this->crossingTopLeftBottomChar, $this->crossingTopMidBottomChar, $this->crossingTopRightBottomChar];
    }
    /**
     * Sets header cell format.
     *
     * @return $this
     * @param string $cellHeaderFormat
     */
    public function setCellHeaderFormat($cellHeaderFormat)
    {
        $cellHeaderFormat = (string) $cellHeaderFormat;
        $this->cellHeaderFormat = $cellHeaderFormat;
        return $this;
    }
    /**
     * Gets header cell format.
     *
     * @return string
     */
    public function getCellHeaderFormat()
    {
        return $this->cellHeaderFormat;
    }
    /**
     * Sets row cell format.
     *
     * @return $this
     * @param string $cellRowFormat
     */
    public function setCellRowFormat($cellRowFormat)
    {
        $cellRowFormat = (string) $cellRowFormat;
        $this->cellRowFormat = $cellRowFormat;
        return $this;
    }
    /**
     * Gets row cell format.
     *
     * @return string
     */
    public function getCellRowFormat()
    {
        return $this->cellRowFormat;
    }
    /**
     * Sets row cell content format.
     *
     * @return $this
     * @param string $cellRowContentFormat
     */
    public function setCellRowContentFormat($cellRowContentFormat)
    {
        $cellRowContentFormat = (string) $cellRowContentFormat;
        $this->cellRowContentFormat = $cellRowContentFormat;
        return $this;
    }
    /**
     * Gets row cell content format.
     *
     * @return string
     */
    public function getCellRowContentFormat()
    {
        return $this->cellRowContentFormat;
    }
    /**
     * Sets table border format.
     *
     * @return $this
     * @param string $borderFormat
     */
    public function setBorderFormat($borderFormat)
    {
        $borderFormat = (string) $borderFormat;
        $this->borderFormat = $borderFormat;
        return $this;
    }
    /**
     * Gets table border format.
     *
     * @return string
     */
    public function getBorderFormat()
    {
        return $this->borderFormat;
    }
    /**
     * Sets cell padding type.
     *
     * @return $this
     * @param int $padType
     */
    public function setPadType($padType)
    {
        $padType = (int) $padType;
        if (!\in_array($padType, [\STR_PAD_LEFT, \STR_PAD_RIGHT, \STR_PAD_BOTH], \true)) {
            throw new \ECSPrefix20210508\Symfony\Component\Console\Exception\InvalidArgumentException('Invalid padding type. Expected one of (STR_PAD_LEFT, STR_PAD_RIGHT, STR_PAD_BOTH).');
        }
        $this->padType = $padType;
        return $this;
    }
    /**
     * Gets cell padding type.
     *
     * @return int
     */
    public function getPadType()
    {
        return $this->padType;
    }
    /**
     * @return string
     */
    public function getHeaderTitleFormat()
    {
        return $this->headerTitleFormat;
    }
    /**
     * @return $this
     * @param string $format
     */
    public function setHeaderTitleFormat($format)
    {
        $format = (string) $format;
        $this->headerTitleFormat = $format;
        return $this;
    }
    /**
     * @return string
     */
    public function getFooterTitleFormat()
    {
        return $this->footerTitleFormat;
    }
    /**
     * @return $this
     * @param string $format
     */
    public function setFooterTitleFormat($format)
    {
        $format = (string) $format;
        $this->footerTitleFormat = $format;
        return $this;
    }
}