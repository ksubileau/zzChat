<?php

class User extends Model {

	/**
	 * The unique user identifier.
	 *
	 * @var string
	 */
	public $uid;

	/**
	 * The user's nickname (required).
	 *
	 * @var string
	 */
	public $nick;

	/**
	 * The user's age (required).
	 *
	 * @var int
	 */
	public $age;

	/**
	 * The user's gender (1 for men, 2 for women) (required).
	 *
	 * @var int
	 */
	public $sex;


    function __construct() {
    	// TODO Define constant in config for the uid string lenght
        $this->uid = generate_token(32);
    }

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	public function getNick() {
		return $this->nickname;
	}

	public function setNick($nickname) {
		$this->nick = $nickname;
	}

	public function getAge() {
		return $this->age;
	}

	public function setAge($age) {
		$this->age = $age;
	}

	public function getSex() {
		return $this->sex;
	}

	public function setSex($sex) {
		$this->sex = $sex;
	}

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return bool
	 */
	public function validate()
	{
		if ( ! isset($this->nick) || ! isset($this->age) || ! isset($this->sex)) {
			return false;
		}
		if( ! is_int($this->age) || $this->age <= 0 || $this->age >= 130) {
			return false;
		}
		if($this->sex !== 1 && $this->sex !== 2) {
			return false;
		}
		return true;
	}

	/**
	 * Save the user's data.
	 *
	 * @return bool
	 */
	public function save()
	{
		if ( ! $this->validate()) {
			return false;
		}

		$storage_dir = ZC_STORAGE_DIR.'/users';
		if (!file_exists($storage_dir)) {
		    mkdir($storage_dir, 0777, true);
		}

		if(file_put_contents($storage_dir.'/'.$this->uid, serialize($this)) === false) {
			return false;
		}

		return true;
	}

}