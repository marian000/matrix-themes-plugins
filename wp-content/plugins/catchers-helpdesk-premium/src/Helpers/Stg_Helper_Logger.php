<?php
namespace StgHelpdesk\Helpers;

/**
 * Class Stg_Helper_Logger
 * @package StgHelpdesk\Helpers
 */

class Stg_Helper_Logger
{
    /** Rotate the log file once it grows past this size (bytes). */
    const MAX_FILE_SIZE = 5242880; // 5 MB

    private $_logsPath;
    protected static $loggers = array();

    protected $name;
    protected $file;
    protected $fp;

    private $_enabled = false;

    public function __construct($name, $file = null)
    {
        // Logging is opt-in. Add to wp-config.php to enable:
        //     define('STG_HELPDESK_LOG', true);
        //
        // The original activation was a query-string secret (?stglen=<salt>),
        // which let anyone able to guess the salt turn on logging from the
        // front end. Replaced with a server-side constant.
        $this->_enabled = defined('STG_HELPDESK_LOG') && STG_HELPDESK_LOG;

        if(!$this->_enabled)
            return;

        $this->name = $name;
        $this->file = $file;
        $this->_logsPath = STG_HELPDESK_ROOT . "logs";

        $this->_protectLogsDir();
        $this->_rotate();
        $this->open();

        // A log file we cannot write to must never break email processing.
        if (!is_resource($this->fp)) {
            $this->_enabled = false;
        }
    }

    public function open()
    {
        $this->fp = @fopen($this->_getFilePath(), 'a+');
    }

    /**
     * Absolute path of the file this logger writes to.
     *
     * @return string
     */
    protected function _getFilePath()
    {
        return $this->_logsPath . '/' . ($this->file == null ? $this->name . '.log' : $this->file);
    }

    /**
     * Make sure the logs directory exists and is not served over HTTP.
     */
    protected function _protectLogsDir()
    {
        if (!is_dir($this->_logsPath)) {
            @mkdir($this->_logsPath, 0755, true);
        }

        $htaccess = $this->_logsPath . '/.htaccess';
        if (!file_exists($htaccess)) {
            @file_put_contents($htaccess, "Order deny,allow\nDeny from all\n\n<IfModule mod_authz_core.c>\n\tRequire all denied\n</IfModule>\n");
        }

        $index = $this->_logsPath . '/index.php';
        if (!file_exists($index)) {
            @file_put_contents($index, "<?php // Silence is golden\n");
        }
    }

    /**
     * Keep one previous generation of the log and start a fresh file when the
     * current one gets too big. Without this the mailbox log grows unbounded.
     */
    protected function _rotate()
    {
        $path = $this->_getFilePath();

        if (!file_exists($path) || filesize($path) < self::MAX_FILE_SIZE) {
            return;
        }

        @rename($path, $path . '.1');
    }


    /**
     * @param string $name
     * @param null $file
     * @return Stg_Helper_Logger
     */
    public static function getLogger($name = 'root', $file = null)
    {
        if (!isset(self::$loggers[$name])) {
            self::$loggers[$name] = new Stg_Helper_Logger($name, $file);
        }

        return self::$loggers[$name];
    }

    public function log($message)
    {
        if(!$this->_enabled)
            return;

        if (!is_string($message)) {
            $this->logPrint($message);
            return;
        }

        $log = '';

        $log .= '[' . date('D M d H:i:s Y', time()) . '] ';
        if (func_num_args() > 1) {
            $params = func_get_args();

            $message = call_user_func_array('sprintf', $params);
        }

        $log .= $message;
        $log .= "\n";

        $this->_write($log);
    }

    public function logPrint($obj)
    {
        ob_start();

        print_r($obj);

        $ob = ob_get_clean();
        $this->log($ob);
    }

    protected function _write($string)
    {
        if (!is_resource($this->fp))
            return;

        fwrite($this->fp, $string);
    }

    public function __destruct()
    {
        if(!$this->_enabled || !is_resource($this->fp))
            return;

        fclose($this->fp);
    }
}
