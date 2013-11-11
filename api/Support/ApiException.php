<?php
namespace ZZChat\Support;

/**
 * Special exception for raising API errors that can be used in API methods.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */
class ApiException extends \Exception
{
    /**
     * HTTP status codes
     *
     * @var array
     */
    public static $codes = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        429 => 'Too Many Requests',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    );

    private $details;

    /**
     * @param string      $httpStatusCode http status code
     * @param string|null $errorMessage   error message
     * @param array       $details        any extra detail about the exception
     * @param Exception   $previous       previous exception if any
     */
    public function __construct($httpStatusCode, $errorMessage = null, array $details = array(), Exception $previous = null)
    {
        $this->details = $details;
        parent::__construct($errorMessage, $httpStatusCode, $previous);
    }

    /**
     * Get extra details about the exception
     *
     * @return array details array
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Get error message about the exception
     *
     * @return string
     */
    public function getErrorMessage()
    {
        $statusCode = $this->getCode();
        $message = $this->getMessage();
        if (isset(ApiException::$codes[$statusCode])) {
            $message = ApiException::$codes[$statusCode] .
                (empty($message) ? '' : ': ' . $message);
        }
        return $message;
    }

    /**
     * Get HTTP Status
     *
     * @return string
     */
    public function getHttpStatus()
    {
        return $this->getCode();
    }
}