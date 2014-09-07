<?php
namespace NewsToChat\Command;

use Cilex\Command\Command;
use Doctrine\ORM\EntityManager;
use NewsToChat\Service\HipChat;
use NewsToChat\Service\Database;
use Pimple;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PushNews extends Command
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
     * @var string
     */
    private $token;

    public function __construct(EntityManager $entityManager, $runtime, $token)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->runtime = $runtime;
        $this->token = $token;
        $this->commandName = $this->getName();
    }

    /**
     * @return null
     */
    protected function configure()
    {
        $expireOptionText = 'Mark the article returned as expired. Requires passing ‘true’ as the parameter.';

        $this
            ->setName('pushnews')
            ->setDescription('Push the news to hipchat')
            ->setHelp('e.g. ./newstochat.php pushnews -e true')
            ->addOption('expire', '-e', 4, $expireOptionText, null);
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Running $this->commandName on $this->runtime...");

        $expire = $input->getOption('expire');
        $container = new Pimple();
        $container['entityManager'] = $this->entityManager;

        $container['database'] = $container->share(function ($c) {
            return new Database(
                $c['entityManager']
            );
        });

        $articles = $this->getUnexpiredArticles($container);

        if ($articles) {
            $id = $articles[0]->getId();
            $dateTime  = $articles[0]->getDateTime();
            $url  = $articles[0]->getUrl();
            $description  = $articles[0]->getDescription();

            if ($expire === "true") {
                $output->writeln("Expiring the news article with ID: $id");
                $container['database']->update($id, 'expired', true);
            }

            $prefix = "[NewsToChat - found on $dateTime]: ";

            $userContent = "\"$description\" @ $url";
            // $roomContent = "\"$description\" @ <a href=\"$url\">$url</a>.";

            $hipchat = new HipChat($this->token);

            $hipchat->sendUserMessage('@UserEmailOrId', $userContent);
            // $hipchat->sendRoomMessage('000000', $roomContent);

            $output->writeln($userContent);
            // $output->writeln($roomContent);

            return;
        }

        $output->writeln('No results...');
    }

    /**
     * @param  Pimple $container
     * @return array|null
     */
    private function getUnexpiredArticles($container)
    {
        $query = 'SELECT news FROM NewsToChat\Entity\Article news WHERE news.expired=0';
        $results = $container['database']->query($query);

        return $results ?: null;
    }
}
