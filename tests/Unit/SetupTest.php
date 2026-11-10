<?php declare(strict_types=1);

use Model\Setup;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SetupTest extends TestCase
{
	public function testSplitsOnSemicolonsOutsideQuotesAndComments(): void
	{
		$sql = <<<'SQL'
-- a comment; with a semicolon
CREATE TABLE `a;b` (id int);
/* block; comment */
INSERT INTO t VALUES ('it''s; fine', "double; quoted", 'esc\'aped; too');
# hash comment;
/*!40101 SET NAMES utf8mb4 */;
UPDATE t SET x = 1;COMMIT;
SQL;

		$this->assertSame(array(
			'CREATE TABLE `a;b` (id int)',
			"INSERT INTO t VALUES ('it''s; fine', \"double; quoted\", 'esc\\'aped; too')",
			'/*!40101 SET NAMES utf8mb4 */',
			'UPDATE t SET x = 1',
			'COMMIT',
		), Setup::split_sql($sql));
	}

	public function testShippedSchemaSplitsIntoValidStatements(): void
	{
		$statements = Setup::split_sql((string) file_get_contents(Setup::schema_path()));

		$this->assertGreaterThan(50, count($statements));
		foreach ($statements as $statement) {
			$this->assertMatchesRegularExpression('/^(CREATE TABLE|INSERT INTO|ALTER TABLE|COMMIT)\b/', $statement);
		}
		$this->assertCount(23, preg_grep('/^CREATE TABLE/', $statements));
		$this->assertContains('COMMIT', $statements);
	}

	public function testGeneratedConfigEscapesUserInput(): void
	{
		$db = array('host' => 'db', 'port' => 3306, 'name' => 'sr', 'user' => 'root', 'password' => "x'); system('id'); //");
		$config = Setup::db_config($db);

		$exported = var_export($config, true);
		$this->assertSame($config, eval('return ' . $exported . ';'));
		$this->assertSame('mysql:host=db;port=3306;dbname=sr;charset=utf8mb4', $config['default']['connection']['dsn']);
	}

	public function testValidDatabaseInputIsNormalised(): void
	{
		$db = Setup::validate_db_input(array('host' => ' 127.0.0.1 ', 'port' => '3307', 'name' => 'secret_republic', 'user' => 'root', 'password' => ''));

		$this->assertSame('127.0.0.1', $db['host']);
		$this->assertSame(3307, $db['port']);
	}

	public static function invalidDatabaseInput(): array
	{
		return array(
			'dsn injection in host' => array(array('host' => 'db;dbname=other', 'port' => '3306', 'name' => 'sr', 'user' => 'root')),
			'non numeric port'      => array(array('host' => 'db', 'port' => '33a', 'name' => 'sr', 'user' => 'root')),
			'port out of range'     => array(array('host' => 'db', 'port' => '70000', 'name' => 'sr', 'user' => 'root')),
			'bad database name'     => array(array('host' => 'db', 'port' => '3306', 'name' => 'sr;drop', 'user' => 'root')),
			'missing user'          => array(array('host' => 'db', 'port' => '3306', 'name' => 'sr', 'user' => '')),
		);
	}

	#[DataProvider('invalidDatabaseInput')]
	public function testInvalidDatabaseInputIsRejected(array $input): void
	{
		$this->expectException(InvalidArgumentException::class);
		Setup::validate_db_input($input);
	}
}
