<?php
namespace NewsToChat\Service;

use Doctrine\ORM\EntityManager;
use NewsToChat\Entity\Article;

class Database
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Article
     */
    private $data;

    /**
     * @param EntityManager $entityManager
     * @param Article       $data
     */
    public function __construct(EntityManager $entityManager = null, Article $data = null)
    {
        $this->entityManager = $entityManager;
        $this->data = $data;
    }

    /**
     * send a query to the entity manager
     * @param  string $string
     * @return array
     */
    public function query($string)
    {
        $query = $this->entityManager->createQuery($string);
        $result = $query->getResult();

        return $result;
    }

    /**
     * @param  integer $id
     * @param  string  $getter
     * @return mixed|null
     */
    public function select($id, $getter)
    {
        $article = $this->entityManager->find('NewsToChat\Entity\Article', $id);

        if ($article === null) {
            echo "No article found.\n";
        }

        return $article->{"get$getter"}();
    }

    /**
     * @return integer
     */
    public function insert()
    {
        $article = new Article;

        $article->setDateTime($this->data->getDateTime());
        $article->setUrl($this->data->getUrl());
        $article->setDescription($this->data->getDescription());
        $article->setExpired($this->data->getExpired());

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $article->getId();
    }

    /**
     * @param  integer $id
     * @return boolean
     */
    public function drop($id)
    {
        $entity = $this->entityManager->find('NewsToChat\Entity\Article', $id);

        try {
            $this->entityManager->remove($entity);
        } catch (Doctrine\ORM\ORMInvalidArgumentException $e) {
            return false;
        }

        $this->entityManager->flush();

        return true;
    }

    /**
     * @param  integer $id
     * @param  string  $setter
     * @param  string  $value
     * @return boolean
     */
    public function update($id, $setter, $value)
    {
        $article = $this->entityManager->find('NewsToChat\Entity\Article', $id);

        if ($article === null) {
            echo "Article $id does not exist.\n";

            return false;
        }

        $article->{"set$setter"}($value);

        $this->entityManager->flush();

        return true;
    }

    /**
     * @return array
     */
    public function show()
    {
        $articleRepository = $this->entityManager->getRepository('NewsToChat\Entity\Article');
        $articles = $articleRepository->findAll();
        $results = [];

        foreach ($articles as $article) {
            $id = $article->getId();
            $date = $article->getDateTime();
            $url = $article->getUrl();
            $description = $article->getDescription();
            $expired = $article->getExpired();

            $content = [
                $id => [
                    'DATETIME' => $date,
                    'URL' => $url,
                    'DESCRIPTION' => $description,
                    'EXPIRED' => $expired,
                ]
            ];

            $results[] = $content;
        }

        return $results;
    }
}
