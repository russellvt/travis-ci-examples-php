<?php

# Ref: https://phpunit.de/
#require_once ('PHPUnit/Framework/TestCase.php');

##
## PHPUnit 3.5 Autoloader
##  @requires PHPUnit >=3.5
#function testRequiringNewerPHPUnit() {
  #require_once 'PHPUnit/Autoload.php';
#}

// Polyfill PHPUnit 6.0 both ways
// Ref: https://github.com/symfony/symfony/issues/21534#issuecomment-278278352
if (!class_exists('\PHPUnit\Framework\TestCase', true)) {
  class_alias('\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
} elseif (!class_exists('\PHPUnit_Framework_TestCase', true)) {
  class_alias('\PHPUnit\Framework\TestCase', '\PHPUnit_Framework_TestCase');
}

class HelloWorldTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PDO
     */
    private $pdo;

    public function setUp()
    {
	print "PHPUnit Version: " . PHPUnit_Runner_Version::id() . "\n";
        $this->pdo = new PDO($GLOBALS['db_dsn'], $GLOBALS['db_username'], $GLOBALS['db_password']);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->query("CREATE TABLE hello (what VARCHAR(50) NOT NULL)");
    }

    public function tearDown()
    {
        $this->pdo->query("DROP TABLE hello");
    }

    public function testHelloWorld()
    {
        $helloWorld = new HelloWorld($this->pdo);

        $this->assertEquals('Hello World', $helloWorld->hello());
    }

    public function testHello()
    {
        $helloWorld = new HelloWorld($this->pdo);

        $this->assertEquals('Hello Bar', $helloWorld->hello('Bar'));
    }

    public function testWhat()
    {
        $helloWorld = new HelloWorld($this->pdo);

        $this->assertFalse($helloWorld->what());

        $helloWorld->hello('Bar');

        $this->assertEquals('Bar', $helloWorld->what());
    }
}

