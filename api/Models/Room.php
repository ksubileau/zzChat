<?php
namespace ZZChat\Models;

/**
 * Room model.
 *
 * @package    ZZChat
 * @author     Kévin Subileau
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPLv3 (also in /LICENSE)
 * @link       https://github.com/ksubileau/zzChat
 */
class Room extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	const STORAGE_DIR = '/Rooms';

	/**
	 * The room's name (required).
	 *
	 * @var string
	 */
	public $name;


    function __construct($props = NULL) {
    	parent::__construct($props);
    }

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Check if the room's data are valid.
	 *
	 * @return bool
	 */
	public function validate()
	{
		if ( empty($this->name) ) {
			return false;
		}
		return true;
	}

    protected static function getProperties() {
        return array_merge (
        		parent::getProperties(),
        		array ('name')
	        );
    }

}
