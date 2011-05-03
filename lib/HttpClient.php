<?php

require_once('Logger.php');

/**
 * Http Client class.
 *
 * @author Adrian Gaudebert - adrian@gaudebert.fr
 */
class HttpClient
{
    private $m_headers = array();
    private $m_no_cache = false;
    private $m_response_headers = array();

    /**
     * Default constructor
     */
    public function __construct()
    {
    }

    /**
     * Force the next HTTP requests to avoid cache
     *
     * @param $no_cache Bool If true, cached results will not be accepted
     */
    public function set_no_cache($no_cache = true)
    {
        $this->m_no_cache = $no_cache;
    }

    /**
     * Get content from an URI
     */
    public function get($uri, $data = null)
    {
        Logger::log('HTTP GET request asked to URI : ' . $uri);
        return $this->http_request($uri, $data, 'get');
    }

    /**
     * Post content to an URI
     */
    public function post($uri, $data)
    {
        Logger::log('HTTP POST request asked to URI : ' . $uri);
        $this->header('Content-Type', 'text/turtle');
        return $this->http_request($uri, $data, 'post');
    }

    /**
     * Put content to an URI
     */
    public function put($uri, $data, $etag = null)
    {
        Logger::log('HTTP PUT request asked to URI : ' . $uri);
        if (!empty($etag))
            $this->header('If-None-Match', $etag);
        return $this->http_request($uri, $data, 'put');
    }

    /**
     * Add informations to HTTP headers
     *
     * @param header_name HTTP header's name
     * @param header_content HTTP header's content
     * @return none
     */
    public function header($header_name, $header_content)
    {
        Logger::log('HTTP Set header : ' . $header_name . ' => ' . $header_content);
        $this->m_headers[] = $header_name . ': ' . $header_content;
    }

    /**
     * Get the last response's headers
     *
     * @return Array header name => header value
     */
    public function get_response_headers()
    {
        return $this->m_response_headers;
    }

    /**
     * Process HTTP request.
     *
     * @param string URI
     * @param array Post data
     * @return if error: false, if GET: String containing the data, if POST: URI to the created object
     */
    private function http_request($uri, $data = null, $type = 'get')
    {
        if ($this->m_no_cache)
            $this->header('Cache-Control', 'No-cache');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $uri);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->m_headers);

        if ($data)
        {
            if ($type == 'put')
            {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                $data = (is_array($data)) ? http_build_query($data) : $data;
                $this->header('Content-Length', strlen($data));
            }
            else
                curl_setopt($curl, CURLOPT_POST, true);

            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   // no echo, just return result
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);   // follow redirections

        $result = curl_exec($curl);
        //debug($result);

        $result = explode("\r\n\r\n", $result);
        //debug($result);
        $nb = count($result);
        --$nb;
        $return = $result[ $nb ];
        unset($result[ $nb ]);
        $headers = end($result);
        $this->m_response_headers = self::parse_headers($headers);

        $ok = curl_errno($curl) === 0;
        if ($data && $type != 'get')
            $ok &= curl_getinfo($curl, CURLINFO_HTTP_CODE) === 201;
        else
            $ok &= curl_getinfo($curl, CURLINFO_HTTP_CODE) === 200;

        if (!$ok) {
            Logger::log('HTTP request failed, HTTP code #' . curl_getinfo($curl, CURLINFO_HTTP_CODE));
            Logger::log('HTTP request failed, error #' . curl_errno($curl) . ' : ' . curl_error($curl));
            curl_close($curl);
            return false;
        }

        Logger::log('HTTP request succeeded');
        curl_close($curl);
        return $return;
    }

    /**
     * Parse headers from string to array
     *
     * @param $headers String HTTP headers
     * @return Array header_name => header_value
     */
    private static function parse_headers($headers)
    {
        $headers = explode("\r\n", $headers);
        unset($headers[0]);

        $res_headers = array();
        foreach ($headers as $h)
        {
            $header = explode(':', $h, 2);
            $res_headers[ $header[0] ] = trim($header[1]);
        }
        return $res_headers;
    }
}
