<?php
/**
 * @author    Janek "ozzyfant" Ostendorf <ozzy@ozzyfant.de>
 * @copyright Copyright (c) 2014 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace minecraftAccounts\cache;

use minecraftAccounts\Profile;
use minecraftAccounts\UUID;

/**
 * Class CacheEntry
 * @package minecraftAccounts\cache
 */
class CacheEntry {

	const DATE_FORMAT = 'Y-m-d H:i:s O';
	const CACHE_TIME = '+1 month';

	/**
	 * @var \minecraftAccounts\Profile
	 */
	protected $profile = null;

	/**
	 * @var \DateTime
	 */
	protected $expirationTime = null;

	/**
	 * @param array $json
	 */
	public function __construct($json) {
		$this->profile = new Profile();
		$this->profile->setUserName($json['name']);
		$this->profile->setUuid(UUID::fromString($json['uuid']));
		$this->expirationTime = \DateTime::createFromFormat(self::DATE_FORMAT, $json['expiresOn']);
	}

	/**
	 * Is this cache entry expired?
	 * @return bool
	 */
	public function isExpired() {
		return ($this->expirationTime < (new \DateTime()));
	}

	/**
	 * @param Profile $profile
	 */
	public function update(Profile $profile = null) {
		// Profile has been updated directly
		if($profile !== null)
			$this->profile = $profile;
		$this->expirationTime = new \DateTime(self::CACHE_TIME);
	}

	/**
	 * @return Profile
	 */
	public function getProfile() {
		return $this->profile;
	}

	/**
	 * @return \DateTime
	 */
	public function getExpirationTime() {
		return $this->expirationTime;
	}

	/**
	 * @return array
	 */
	public function toArray() {
		return [
			'uuid' => $this->profile->getUuid()->getFormatted(),
			'name' => $this->profile->getUserName(),
			'expiresOn' => $this->expirationTime->format(self::DATE_FORMAT)
		];
	}
} 