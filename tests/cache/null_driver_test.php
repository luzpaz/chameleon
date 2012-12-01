<?php
/**
*
* @package testing
* @copyright (c) 2012 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

class phpbb_cache_null_driver_test extends phpbb_database_test_case
{
	protected $driver;

	public function getDataSet()
	{
		return $this->createXMLDataSet(dirname(__FILE__) . '/fixtures/config.xml');
	}

	protected function setUp()
	{
		parent::setUp();

		$this->driver = new phpbb_cache_driver_null;
	}

	public function test_null_cache_sql()
	{
		global $db, $cache;
		$db = $this->new_dbal();
		$cache = new phpbb_cache_service($this->driver);

		$sql = "SELECT * FROM phpbb_config
			WHERE config_name = 'foo'";
		$result = $db->sql_query($sql, 300);
		$first_result = $db->sql_fetchrow($result);
		$expected = array('config_name' => 'foo', 'config_value' => '23', 'is_dynamic' => 0);
		$this->assertEquals($expected, $first_result);

		$sql = 'DELETE FROM phpbb_config';
		$result = $db->sql_query($sql);

		// As null cache driver does not actually cache,
		// this should return no results
		$sql = "SELECT * FROM phpbb_config
			WHERE config_name = 'foo'";
		$result = $db->sql_query($sql, 300);

		$this->assertSame(false, $db->sql_fetchrow($result));

		$db->sql_close();
	}
}
