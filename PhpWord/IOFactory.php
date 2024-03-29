<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Reader\ReaderInterface;
use PhpOffice\PhpWord\Writer\WriterInterface;

abstract class IOFactory
{
    /**
     * Create new writer
     *
     * @param PhpWord $phpWord
     * @param string $name
     * @return WriterInterface
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public static function createWriter(PhpWord $phpWord, $name = 'Word2007')
    {
        if ($name !== 'WriterInterface' && !in_array($name, array('ODText', 'RTF', 'Word2007', 'HTML', 'PDF'), true)) {
            throw new Exception("\"{$name}\" is not a valid writer.");
        }

        $fqName = "PhpOffice\\PhpWord\\Writer\\{$name}";

        return new $fqName($phpWord);
    }

    /**
     * Create new reader
     *
     * @param string $name
     * @return ReaderInterface
     * @throws Exception
     */
    public static function createReader($name = 'Word2007')
    {
        return self::createObject('Reader', $name);
    }

    /**
     * Create new object
     *
     * @param string $type
     * @param string $name
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     * @return \PhpOffice\PhpWord\Writer\WriterInterface|\PhpOffice\PhpWord\Reader\ReaderInterface
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    private static function createObject($type, $name, $phpWord = null)
    {
        $class = "PhpOffice\\PhpWord\\{$type}\\{$name}";
        if (class_exists($class) && self::isConcreteClass($class)) {
            return new $class($phpWord);
        } else {
            throw new Exception("\"{$name}\" is not a valid {$type}.");
        }
    }
    /**
     * Loads PhpWord from file
     *
     * @param string $filename The name of the file
     * @param string $readerName
     * @return \PhpOffice\PhpWord\PhpWord $phpWord
     */
    public static function load($filename, $readerName = 'Word2007')
    {
        /** @var \PhpOffice\PhpWord\Reader\ReaderInterface $reader */
        $reader = self::createReader($readerName);
        return $reader->load($filename);
    }
    /**
     * Check if it's a concrete class (not abstract nor interface)
     *
     * @param string $class
     * @return bool
     */
    private static function isConcreteClass($class)
    {
        $reflection = new \ReflectionClass($class);
        return !$reflection->isAbstract() && !$reflection->isInterface();
    }
}
