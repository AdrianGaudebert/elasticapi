<?php

//* ============================================== *//
//* Originaly coded for http://www.pechmerle.fr
//*
//* Link : libs/Logger.class.php
//* Author : Adrian Gaudebert - adrian.gaudebert@gmail.com
//* Creation date : 23/12/2008
//* Last modification : 23/12/2008
//*
//* Description :
//* * This class manages and displays logs.
//*
//* ============================================== *//

/**
* class Logger
* @author Adrian Gaudebert - adrian@gaudebert.fr
*/
class Logger
{
    private static $m_logs = array();

    /**
    * Add a message to the log list.
    */
    public static function log($message)
    {
        array_push(self::$m_logs, $message);
    }

    /**
    * Display all the logs.
    */
    public static function display_logs()
    {
        foreach (self::$m_logs as $log)
        {
            echo $log . '<br />' . "\n";
        }
    }
}

?>
