<?php
namespace NewsToChat\Command;

use Cilex\Command\Command;
use Doctrine\ORM\EntityManager;
use NewsToChat\Service\HipChat;
use NewsToChat\Service\Database;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PushNews extends Command
{
    private $entityManager;
    private $token;

    public function __construct(EntityManager $entityManager, $token)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->token = $token;
    }

    protected function configure()
    {
        $expireOptionText = 'Mark the article returned as expired. Requires passing ‘true’ as the parameter.';

        $this
            ->setName('pushnews')
            ->setDescription('Push the news to hipchat')
            ->setHelp('e.g. ./newstochat.php pushnews -e true')
            ->addOption('expire', '-e', 4, $expireOptionText, null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $expire = $input->getOption('expire');
        $container = new \Pimple();
        $container['entityManager'] = $this->entityManager;

        $container['database'] = $container->share(function ($c) {
            return new Database(
                $c['entityManager']
            );
        });

        $query = 'SELECT news FROM NewsToChat\Entity\Article news WHERE news.expired=0';
        $results = $container['database']->query($query);

        if ($results) {
            $id = $results[0]->getId();
            $date  = $results[0]->getDate();
            $time  = date('h:i:s a', strtotime($results[0]->getTime()));
            $url  = $results[0]->getUrl();
            $description  = $results[0]->getDescription();

            if ($expire === "true") {
                $output->writeln("Expiring the news article with ID: $id");
                $container['database']->update($id, 'expired', true);
            }

            $prefix = '[NewsToChat - found on $date at $time]: ';

            $userContent = "\"$description\" @ $url";
            // $roomContent = "\"$description\" @ <a href=\"$url\">$url</a>.";

            $hipchat = new HipChat($this->token);

            $hipchat->sendUserMessage('@UserEmailOrId', $userContent);
            // $hipchat->sendRoomMessage('000000', $roomContent);

            $output->writeln($userContent);
            // $output->writeln($roomContent);
        }

        if (!$results) {
            $output->writeln('No results...');
        }
    }
}
