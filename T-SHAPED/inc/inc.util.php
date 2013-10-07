<?php
    /**
     * Transforma uma data para o formato ISO
     * @param string $strData Data em formato texto
     * @param string $strFormat Formato de entrada da data ($strData)
     * @param string $outputFormat Formato de saida (date ou dateTime) default = "dateTime"
     * @return string
     */
    function formatISO($strData, $strFormat, $outputFormat="dateTime") {
        $timezone = new DateTimeZone('UTC');
        if ($dt = DateTime::createFromFormat($strFormat, $strData, $timezone)) {
                if ($outputFormat == "date")
                        return $dt->format("Y-m-d");
                elseif ($outputFormat == "dateTime")
                        return $dt->format("Y-m-d H:i:s");
                else
                        return null;
        }
        else {
                return null;
        }
    }
    
?>
