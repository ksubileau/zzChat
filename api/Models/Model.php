<?php
namespace ZZChat\Models;

use \ZZChat\Support;

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

    function __construct() {
        $this->id = Support\generate_token(ZC_ID_LENGTH);
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
     * @return int
     */
    protected static function getTimeForID($id, $timefile) {
        $timefile = static::getStoragePathForID($id) . '/' .$reference;

        if (!file_exists($timefile))
            return false;

        if(($timestring = file_get_contents($timefile)) === false) {
            return false;
        }

        return intval($timestring);
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
     * Return the model's data file path.
     *
     * @return string
     */
    protected static function getStoragePathForID($id) {
        if (!$id)
            return false;

        return ZC_STORAGE_DIR . static::STORAGE_DIR . '/' . $id;
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

    abstract public function save();

}