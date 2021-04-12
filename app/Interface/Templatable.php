<?php


namespace App\Interfaces;


interface Templatable
{
    /**
     * Templatable constructor.
     * Run Every Time on App Boot
     * Register Styles,Scripts
     */
    public function __construct();

    /**
     * @param string $content
     * @param array $params
     * @return string
     * Get Blank Template (With Framework)
     */
    public function blank($content = "", $params = []);

    /**
     * @param $params
     * @return string
     * Get Header View
     */
    public function header($params = []);

    /**
     * @param $params
     * @return string
     * Get Footer View
     */
    public function footer($params = []);

    /**
     * @return string
     * Get Template Title
     */
    public static function getTemplateTitle();
}