<?php

namespace addons\swiftfin\helpers;

/**
 * Description of DictBankReaderInterface
 *
 * @author nikolaenko
 */
interface DictBankReaderInterface
{
    function openFile($file);

    function getRecord();

    function close();

    function getType();
}