<?php
namespace NewsToChat\Command;

use Cilex\Command\Command;
use Doctrine\ORM\EntityManager;
use NewsToChat\Service\Database;
use Pimple;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Maintenance extends Command
{
    /**
     * @var string
     */
    private $commandName;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $runtime;

    /**
     * @param EntityManager $entityManager
     * @param string        $runtime
     */
    public function __construct(EntityManager $entityManager, $runtime)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->runtime = $runtime;
        $this->commandName = $this->getName();
    }

    /**
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('maintenance')
            ->setDescription('Do some routine maintenance on the database.')
            ->setHelp('e.g. ./newstochat.php maintenance');
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Running $this->commandName on $this->runtime...");

        $container = new Pimple();
        $container['entityManager'] = $this->entityManager;

        $container['database'] = $container->share(function ($c) {
            return new Database(
                $c['entityManager']
            );
        });

        $articles = $this->getUnexpiredArticles($container);

        if ($articles) {
            $this->expireOlderArticles($container, $articles, $output);

            return;
        }

        $output->writeln("No old articles expired...");
    }

    /**
     * @param  Pimple          $container
     * @param  array           $articles
     * @param  OutputInterface $output
     * @return null
     */
    private function expireOlderArticles(Pimple $container, array $articles, OutputInterface $output)
    {
        //300 articles per 5 minutes = one every 5 minutes for 25 hours
        $articleCount = count($articles)-1;

        if ($articleCount >= 300) {
            $offset = $articleCount - 300;

            for ($i = 0; $i < $offset; $i++) {
                $articleID = $articles[$i]->getId();
                $container['database']->update($articleID, 'expired', true);
                $output->writeln("Expiring old news article with ID: $articleID");
            }
        }
    }

    /**
     * @param  Pimple $container
     * @return array|null
     */
    private function getUnexpiredArticles(Pimple $container)
    {
        $query = 'SELECT news FROM NewsToChat\Entity\Article news WHERE news.expired=0';
        $results = $container['database']->query($query);

        return $results ?: null;
    }

    /**
     * @param  Pimple $container
     * @param  string $start
     * @param  string $finish
     * @return array|null
     */
    private function getUnexpiredArticlesByDateRange(Pimple $container, $start, $finish)
    {
        $query = "SELECT news FROM NewsToChat\Entity\Article news WHERE news.expired=0 AND news.dateTime>='"
        . $start . "' AND news.dateTime<='" . $finish . "'";
        $results = $container['database']->query($query);

        return $results ?: null;
    }

    /**
     * @param  Pimple $container
     * @param  string $dateTime
     * @return array|null
     */
    private function getUnexpiredArticlesBeforeDateTime(Pimple $container, $dateTime)
    {
        $query = "SELECT news FROM NewsToChat\Entity\Article news WHERE news.expired=0 AND news.dateTime<'"
        . $dateTime . "'";
        $results = $container['database']->query($query);

        return $results ?: null;
    }

    /**
     * @param  Pimple $container
     * @param  string $start
     * @param  string $finish
     * @return array|null
     */
    private function getArticlesByDateRange(Pimple $container, $start, $finish)
    {
        $query = "SELECT news FROM NewsToChat\Entity\Article news WHERE news.dateTime>='"
        . $start . "' AND news.dateTime<='" . $finish . "'";
        $results = $container['database']->query($query);

        return $results ?: null;
    }
}
