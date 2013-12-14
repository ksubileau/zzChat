<?php
namespace ZZChat\Controllers;

use \ZZChat\Models\User;
use \ZZChat\Models\Room;

/**
 * Server Side Events controller.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */
class SSEController extends Controller
{
    // The event ID (last event timestamp)
    private $id = 0;

    // Seconds to sleep after the data has been sent
    public $sleep_time = ZC_SSE_SLEEP_TIME;

    // The time limit of the script in seconds
    public $exec_limit = ZC_SSE_EXEC_LIMIT;

    // The interval in seconds of sending a keep alive signal
    public $keep_alive_delay = ZC_SSE_KEEP_ALIVE_DELAY;

    // The time to reconnect after connection has lost in seconds
    public $reconnect_time = ZC_SSE_RECONNECT_TIME;


    public function __construct(){
        // If the header 'Last-Event-ID' is set, it's a reconnect from the client
        if(isset($_SERVER['HTTP_LAST_EVENT_ID'])){
            $this->id = intval($_SERVER['HTTP_LAST_EVENT_ID']);
            $this->is_reconnect = true;
        }
        else if(isset($_GET['lastEventId'])){
            $this->id = intval($_GET['lastEventId']);
            $this->is_reconnect = true;
        }
        else if(isset($_GET['timeref'])) {
            $this->id = intval($_GET['timeref']);
        }
        else {
            $this->id = time();
        }
    }

    public function start()
    {
        // Set infinite time limit
        @set_time_limit(0);

        header('Content-Type: text/event-stream');
        header("Cache-Control: no-cache");

        // Disable all buffers
        if(function_exists('apache_setenv')){
                @apache_setenv('no-gzip',1);
        }
        @ini_set('zlib.output_compression',0);
        @ini_set('implicit_flush',1);
        for($i = 0; $i < ob_get_level(); $i++){
                ob_end_flush();
        }
        ob_implicit_flush(1);

        $startedAt = time();
        $lastCheck = $this->id;
        $lastSent = time();

        // Set the client's retry interval
        echo 'retry: '.($this->reconnect_time*1000)."\n\n";

        // Send 2 kB padding for IE
        echo ":" . str_repeat(" ", 2048) . "\n\n";

        while(true) {
            // Check for updates
            $events = $this->checkEvents($lastCheck);

            // Get current timestamp
            $now = time();

            // Update last check time
            $lastCheck = $now;

            if(!empty($events)) {
                // Output events
                foreach($events as $eventkey => $data) {
                    echo $this->sseBlock($data, $eventkey, $now);
                }
                $lastSent = $now;
            } else {
                // No events for now, check keep alive delay
                if((time() - $lastSent) > $this->keep_alive_delay){
                    echo ": keepalive\n\n";
                    $lastSent = $now;
                }
            }

            // Flush the data to the client
            @ob_flush();
            @flush();

            // Cap connections if the time exceed the limit
            if($this->exec_limit && ($now - $startedAt) > $this->exec_limit)
                break;

            // Good work, you can sleep now !
            usleep($this->sleep_time*1000000);
        }
    }

    /**
     * Compute a list of available events
     *
     * @param $timeref
     */
    private function checkEvents($timeref) {
        $events = array();

        if(User::hasNewEntry($timeref)) {
            $events['user_new'] = '';
        }

        if(($res = Room::checkEvents($timeref))) {
            $events = array_merge($events, array('rooms' => $res));
        }

        return $events;
    }

    /**
     * Make strings SSE compliant
     * @param $str the data to be processed
     */
    private static function sseData($str){
        return 'data: '.str_replace("\n","\ndata: ",$str);
    }

    /**
     * Return a SSE compliant data block
     * @param $data the event data
     * @param $event the event name
     * @param $id the event ID
     */
    private function sseBlock($data, $event = NULL, $id = NULL) {
        if(!$id) {
            $id = $this->id++;
        } else {
            $this->id = $id;
        }
        $sse = 'id: '.$id."\n";
        $dataArray = array('data' => $data);
        if ($event) {
 //           $sse .= 'event: '.$event."\n"; //Don't use event key because the jquery plugin doesn't support it.
            $dataArray['event'] = $event;
        }
        $sse .= self::sseData(json_encode($dataArray))."\n\n";
        return $sse;
    }
}