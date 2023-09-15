<?php

/**
* 
* @package view
* @author  dc.To
* @version 20230826
* @copyright ©2023 dc team all rights reserved.
*/
namespace VM\View\Exception;


class ViewException extends \VM\Exception\Exception
{
    protected $status = 500;

    protected $message = 'View Exception';
}