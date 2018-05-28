<?php

/**
 * ilReportingConfig
 */
class ilReportingConfig extends ActiveRecord {

	const TABLE_NAME = "uihkreporting_c";
	const RESTRICTED_NONE = 0;
	const RESTRICTED_BY_LOCAL_READABILITY = 1;
	const RESTRICTED_BY_ORG_UNITS = 2;


	/**
	 * @return string
	 */
	public function getConnectorContainerName() {
		return self::TABLE_NAME;
	}


	/**
	 * @return string
	 * @deprecated
	 */
	public static function returnDbTableName() {
		return self::TABLE_NAME;
	}


	/**
	 * @param string $key
	 *
	 * @return ilReportingConfig|null
	 */
	protected static function getConfig($key) {
		/**
		 * @var ilReportingConfig|null $config
		 */

		$config = self::where([
			"config_key" => $key
		])->first();

		return $config;
	}


	/**
	 * @param string $key
	 *
	 * @return string|false
	 */
	public static function getValue($key) {
		$config = self::getConfig($key);

		if ($config !== NULL) {
			return $config->getConfigValue();
		} else {
			return false;
		}
	}


	/**
	 * @param string $key
	 * @param string $value
	 */
	public static function setValue($key, $value) {
		$config = self::getConfig($key);

		if ($config === NULL) {
			$config = new self();

			$config->setConfigKey($key);
		}

		$config->setConfigValue($value);

		$config->store();
	}


	/**
	 * @var string
	 *
	 * @con_has_field    true
	 * @con_fieldtype    text
	 * @con_length       128
	 * @con_is_notnull   true
	 * @con_is_primary   true
	 */
	protected $config_key = "";
	/**
	 * @var string
	 *
	 * @con_has_field   true
	 * @con_fieldtype   clob
	 * @con_is_notnull  false
	 */
	protected $config_value = NULL;


	/**
	 * @return string
	 */
	public function getConfigKey() {
		return $this->config_key;
	}


	/**
	 * @param string $config_key
	 */
	public function setConfigKey($config_key) {
		$this->config_key = $config_key;
	}


	/**
	 * @return string
	 */
	public function getConfigValue() {
		return $this->config_value;
	}


	/**
	 * @param string $config_value
	 */
	public function setConfigValue($config_value) {
		$this->config_value = $config_value;
	}
}
