<?php
namespace NewsToChat\Command;

use PHPUnit_Framework_TestCase;
use Mockery;

class ClassNameTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return null
     */
    public function tearDown()
    {
        Mockery::close();
    }

    private function getEntityManagerMock()
    {
        return Mockery::mock('Doctrine\ORM\EntityManager');
    }

    private function getRuntimeFixture()
    {
        return '1970-01-01 @ 00:00:01';
    }

    private function getSourcesFixture()
    {
        return [
            'RSS' => [
                'http://example.com/feeds/one',
                'http://example.com/feeds/two',
            ]
        ];
    }

    public function testConfigureSetsName()
    {
        $command = new PullNews(
            $this->getEntityManagerMock(),
            $this->getRuntimeFixture(),
            $this->getSourcesFixture()
        );

        $this->assertEquals('pullnews', $command->getName(), "The pullnews command name was not set properly.\n");
    }

    public function testConfigureSetsDescription()
    {
        $command = new PullNews(
            $this->getEntityManagerMock(),
            $this->getRuntimeFixture(),
            $this->getSourcesFixture()
        );

        $this->assertEquals('Pull the news', $command->getDescription(), "The pullnews command description was not set properly.\n");
    }

    public function testConfigureSetsHelp()
    {
        $command = new PullNews(
            $this->getEntityManagerMock(),
            $this->getRuntimeFixture(),
            $this->getSourcesFixture()
        );

        $this->assertEquals('e.g. ./newstochat.php pullnews', $command->getHelp(), "The pullnews command help was not set properly.\n");
    }
}
