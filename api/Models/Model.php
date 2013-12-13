<?php
namespace ZZChat\Models;

use \ZZChat\Support;
use \ZZChat\Support\ApiException;

/**
 * Model abstract class.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */
abstract class Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    const STORAGE_DIR = '/Defaults';

    /**
     * The unique identifier.
     *
     * @var string
     */
    public $id;

    function __construct($props = NULL) {
        if($props == NULL) {
            $this->id = Support\generate_token(ZC_ID_LENGTH);
        }
        else {
            // Object load from file
            // All fields of the child class will be filled.
            foreach ($props as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Get the unique identifier for this model instance.
     *
     * @return string
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Check if the passed ID corresponds to an existing model instance.
     *
     * @return bool
     */
    public static function exists($id)
    {
        if (!$id)
            return false;

        return file_exists(static::getStoragePathForID($id));
    }

    /**
     * Return the last modification or creation time.
     *
     * @return boolean|int
     */
    protected static function getTimeForID($id, $timefile) {
        $timefile = static::getStoragePathForID($id) . '/' .$timefile;

        if (!file_exists($timefile))
            return false;

        if(($timestring = file_get_contents($timefile)) === false) {
            return false;
        }

        return intval($timestring);
    }

    /**
     * Set the last modification or creation time.
     *
     * @return bool
     */
    protected static function setTimeForID($id, $timefile, $timestamp = NULL) {
        if($timestamp === NULL) {
            $timestamp = time();
        }

        $timefile = static::getStoragePathForID($id) . '/' .$timefile;

        if(file_put_contents($timefile, $timestamp) === false) {
            return false;
        }

        return true;
    }

    /**
     * Return the last modification time.
     *
     * @return int
     */
    protected static function getModificationTimeForID($id) {
        return static::getTimeForID($id, 'mtime');
    }

    /**
     * Return the creation time.
     *
     * @return int
     */
    protected static function getCreationTimeForID($id) {
        return static::getTimeForID($id, 'ctime');
    }

    /**
     * Return the creation time.
     *
     * @return int
     */
    protected function getCreationTime() {
        return static::getCreationTimeForID($this->id);
    }

    /**
     * Return the last modification time.
     *
     * @return int
     */
    protected function getModificationTime() {
        return static::getModificationTimeForID($this->id);
    }

    /**
     * Return true if a new model was created since the passed time reference.
     *
     * @return boolean
     */
    public static function hasNewEntry($timeref)
    {
        foreach (glob(static::getStorageBasePath() . '/*') as $id) {
            $ctime = static::getCreationTimeForID(basename($id));

            if ($ctime !== false && $ctime >= $timeref) {
                return basename($id);
            }
        }
        return false;
    }

    /**
     * Return the model's entries path.
     *
     * @return string
     */
    protected static function getStorageBasePath() {
        return ZC_STORAGE_DIR . static::STORAGE_DIR;
    }

    /**
     * Return the model's data file path.
     *
     * @return string
     */
    protected static function getStoragePathForID($id) {
        if (!$id)
            return false;

        return static::getStorageBasePath() . '/' . $id;
    }

    /**
     * Return the model's data file path.
     *
     * @return string
     */
    protected function getStoragePath() {
        return static::getStoragePathForID($this->id);
    }

    protected function validate() {
        return true;
    }

    protected static function getProperties() {
        return array ('id');
    }

    /**
     * Load the model's data.
     *
     * @return Object
     */
    public static function load($id)
    {
        $filepath = static::getStoragePathForID($id);

        if (!$filepath || !file_exists($filepath . '/properties')) {
            return false;
        }

        if(($data = file_get_contents($filepath . '/properties')) === false) {
            throw new ApiException(500, "Unable to read from file. Please check permissions.");
        }

        $props = json_decode($data, true);
        if ($props === NULL) {
            throw new ApiException(500, "Failed to decode the data file.", Support\json_last_error_msg().'.');
        }
        if(array_keys($props) != static::getProperties()) {
            throw new ApiException(500, "Failed to decode the data file.", "Invalid model data.");
        }

        $class = get_called_class();
        $obj = new $class($props);

        return $obj;
    }

    /**
     * Load all instances.
     *
     * @return array
     */
    public static function loadAll()
    {
        $objs = array();

        foreach (glob(static::getStorageBasePath() . '/*') as $filename) {
            if (is_readable($filename)) {
                $obj = static::load(basename($filename));
                if ($obj) {
                    array_push($objs, $obj);
                }
            }
        }

        return $objs;
    }

    /**
     * Save the model's data.
     *
     * @return bool
     */
    public function save()
    {
        if ( ! $this->validate()) {
            return false;
        }

        $filepath = $this->getStoragePath();

        if (!file_exists($filepath)) {
            mkdir($filepath, ZC_STORAGE_DIR_PERM, true);
        }

        $propKeys = static::getProperties();
        $props = array();
        foreach ($propKeys as $key) {
            $props[$key] = $this->$key;
        }

        $data = json_encode($props, ZC_STORAGE_JSON_PRETTY_PRINT?JSON_PRETTY_PRINT:0);

        if($data === NULL) {
            throw new ApiException(500, "Failed to encode data.", Support\json_last_error_msg().'.');
        }

        if(file_put_contents($filepath . '/properties', $data) === false) {
            throw new ApiException(500, "Unable to write file. Please check permissions.");
        }

        // Set create time if it's a new instance.
        if (!file_exists($filepath . '/ctime')) {
            if(static::setTimeForID($this->id, 'ctime') === false) {
                throw new ApiException(500, "Unable to write create time. Please check file permissions.");
            }
        }

        // Update modification time
        if(static::setTimeForID($this->id, 'mtime') === false) {
            throw new ApiException(500, "Unable to write modification time. Please check file permissions.");
        }

        return true;
    }

    /**
     * Permanently deletes the model's data.
     *
     * @return bool
     */
    public function delete()
    {
        return static::deleteByID($this->id);
    }

    /**
     * Permanently deletes the model's data.
     *
     * @return bool
     */
    public static function deleteByID($id)
    {
        $targetpath = static::getStoragePathForID($id);

        if (empty($id) || !file_exists($targetpath)) {
            return false;
        }

        // Delete model's data
        if(Support\rrmdir($targetpath) === false) {
            throw new ApiException(500, "Unable to delete data directory. Please check file permissions.");
        }

        return true;
    }

}