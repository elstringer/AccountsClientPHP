<?php
/**
 * @author    Janek "ozzyfant" Ostendorf <ozzy@ozzyfant.de>
 * @copyright Copyright (c) 2014 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

namespace minecraftAccounts\cache;

use minecraftAccounts\Converter;
use minecraftAccounts\UUID;

/**
 * Reads a minecraft server cache file and updates it for new queries
 * @package minecraftAccounts
 */
class Cache {

	/**
	 * Path to cache file
	 * @var string
	 */
	protected $filePath = '';

	/**
	 * @var array
	 */
	protected $json = [];

	/**
	 * @var CacheEntry[]
	 */
	protected $entries = [];

	public function __construct($filePath) {
		$this->filePath = $filePath;
		$this->read();
	}

	/**
	 * @return $this
	 */
	public function read() {
		$this->json = json_decode(file_get_contents($this->filePath), true);
		foreach($this->json as $json) {
			$this->entries[] = new CacheEntry($json);
		}
		return $this;
	}

	/**
	 * Write updated cache entries to file
	 * @return $this
	 */
	public function write() {
		var_dump($this->json);
		// Gather info
		$this->json = [];
		foreach($this->entries as $entry) {
			$this->json[] = $entry->toArray();
		}
		var_dump($this->json);
		$fh = fopen($this->filePath, 'w+');
		fwrite($fh, json_encode($this->json));
		return $this;
	}

	/**
	 * Updates all cached profiles. Does not write to cache file
	 * @return $this
	 */
	public function refreshAll() {
		foreach($this->entries as $entry) {
			$entry->update(Converter::completeProfile($entry->getProfile()));
		}
		return $this;
	}

	/**
	 * @param $username
	 * @return bool|CacheEntry
	 */
	public function getEntryByUserName($username) {
		foreach($this->entries as $entry) {
			if($entry->getProfile()->getUserName() == $username) {
				return $entry;
			}
		}
		return false;
	}

	/**
	 * @param UUID $uuid
	 * @return bool|CacheEntry
	 */
	public function getEntryByUUID(UUID $uuid) {
		foreach($this->entries as $entry) {
			if($entry->getProfile()->getUuid() == $uuid) {
				return $entry;
			}
		}
		return false;
	}
} 