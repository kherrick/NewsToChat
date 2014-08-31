<?php
namespace NewsToChat\Service;

use NewsToChat\Entity\Article;

class Database
{

    private $entityManager;
    private $data;

    public function __construct($entityManager = null, $data = null)
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

    public function select($id, $getter)
    {
        $article = $this->entityManager->find('NewsToChat\Entity\Article', $id);

        if ($article === null) {
            echo "No article found.\n";
        }

        return $article->{"get$getter"}();
    }

    public function insert()
    {
        $article = new Article;

        $article->setDate($this->data->getDate());
        $article->setTime($this->data->getTime());
        $article->setUrl($this->data->getUrl());
        $article->setDescription($this->data->getDescription());
        $article->setExpired($this->data->getExpired());

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $article->getId();
    }

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

    public function show()
    {
        $articleRepository = $this->entityManager->getRepository('NewsToChat\Entity\Article');
        $articles = $articleRepository->findAll();
        $results = [];

        foreach ($articles as $article) {
            $id = $article->getId();
            $date = $article->getDate();
            $time = $article->getTime();
            $url = $article->getUrl();
            $description = $article->getDescription();
            $expired = $article->getExpired();

            $content = [
                $id => [
                    'DATE' => $date,
                    'TIME' => $time,
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
