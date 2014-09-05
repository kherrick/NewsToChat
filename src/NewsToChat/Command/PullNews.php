<?php
namespace NewsToChat\Command;

use Cilex\Command\Command;
use Doctrine\ORM\EntityManager;
use NewsToChat\Entity\Article;
use NewsToChat\NewsGrabber;
use NewsToChat\Service\Database;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PullNews extends Command
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var array
     */
    private $sources;

    /**
     * @param EntityManager $entityManager
     * @param array         $sources
     */
    public function __construct(EntityManager $entityManager, array $sources)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->sources = $sources;
    }

    /**
     * setup the configuration options for the command
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('pullnews')
            ->setDescription('Put the news')
            ->setHelp('e.g. ./newstochat.php pullnews');
    }

    /**
     * kick off hte execution of the command
     * @param  InputInterface  $input  [description]
     * @param  OutputInterface $output [description]
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $newsGrabber = new NewsGrabber;
        $news = $newsGrabber($this->sources);

        for ($i=0; $i < count($news); $i++) {
            $this->unbundleNewsSourceAndAppendToDatabase($news[$i], $output);
        }
    }

    /**
     * Unbundle the news source, check to make sure the article isn't in the database, and append
     * @param  array           $news
     * @param  OutputInterface $output
     * @return null
     */
    private function unbundleNewsSourceAndAppendToDatabase(array $news, OutputInterface $output)
    {
        for ($i=0; $i < count($news); $i++) {
            $url = $news[$i]['url'];
            $container = new \Pimple();
            $container['entityManager'] = $this->entityManager;
            $container['data'] = new Article(
                $news[$i]['dateTime'],
                $url,
                $news[$i]['description'],
                false
            );

            $container['database'] = $container->share(function ($c) {
                return new Database(
                    $c['entityManager'],
                    $c['data']
                );
            });

            $query = 'SELECT news FROM NewsToChat\Entity\Article news WHERE news.url=\'' . $url . '\'';
            $result = $container['database']->query($query);

            if (!$result) {
                try {
                    $id = $container['database']->insert();
                    $output->writeln('Record inserted into the database as ID: ' . $id);
                } catch (Exception $e) {
                    $output->writeln('An error occurred: ' . $e);
                }
            }
        }
    }
}
