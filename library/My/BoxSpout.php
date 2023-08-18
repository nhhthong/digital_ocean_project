<?php
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;

class My_BoxSpout
{
    public static function excelBuilder($header = array(), $contents = array(), $filename = 'data') { // contents truyền vào mảng đa chiều nhé.
       
        require_once APPLICATION_PATH.'/../library/box-spout/src/Spout/Autoloader/autoload.php';
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->setShouldUseInlineStrings(true); // default (and recommended) value
        
        $writer->openToBrowser($filename);

        $basicStyles = (new StyleBuilder())
        ->setShouldWrapText(false)
        ->setFontSize(11)
        ->build();

        $rowFromHeader = WriterEntityFactory::createRowFromArray($header, $basicStyles);
        $writer->addRow($rowFromHeader); // Save header

        foreach ($contents as $content) {
            $rowFromContent = WriterEntityFactory::createRowFromArray($content, $basicStyles);
            $writer->addRow($rowFromContent);
        } // Save Content
        $writer->close();
        exit();
    }
}