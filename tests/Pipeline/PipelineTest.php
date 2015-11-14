<?php

namespace Tests\Pipeline;

use Pipeline\Pipeline;
use Tests\Resources\Author;

class PipelineTest extends \PHPUnit_Framework_TestCase
{

    public function testFilter()
    {
        $authors = $this->createAuthors();
        $value =
            (new Pipeline(['authors' => $authors]))
            ->filter('authors.name == Hi')
            ->filter('authors.age > 19')
            ->take('authors');

        $this->assertEquals([1 => $authors[1], 4 => $authors[4]], $value->getItems());
    }

    public function testNegativeExpression()
    {
        $authors = $this->createAuthors();
        $value =
            (new Pipeline(['authors' => $authors]))
            ->filter('authors.name !== Hi')
            ->take('authors');

        $this->assertEquals([0 => $authors[0], 3 => $authors[3]], $value->getItems());
    }

    public function testFilterCallback()
    {
        $authors = $this->createAuthors();
        $collection =
            (new Pipeline(['authors' => $this->createAuthors()]))
            ->filterCallback('authors', function(Author $item){
                if($item->getName() == 'Asd10'){
                    return false;
                }
                return true;
            })
            ->take('authors', 3);

        $this->assertEquals([0 => $authors[0], 1 => $authors[1], 2 => $authors[2]], $collection->getItems());
    }

    public function testSort()
    {
        $authors = $this->createAuthors();
        $collection =
            (new Pipeline(['authors' => $authors]))
            ->sort('authors.age', 'ASC')
            ->take('authors');

        $this->assertEquals([$authors[3], $authors[1], $authors[0], $authors[4], $authors[2]], $collection->getItems());
    }

    /**
     * @return array
     */
    private function createAuthors()
    {
        $author0 = new Author();
        $author0->setName('Hello');
        $author0->setAge(26);

        $author1 = new Author();
        $author1->setName('Hi');
        $author1->setAge(34);

        $author2 = new Author();
        $author2->setName('Hi');
        $author2->setAge(19);

        $author3 = new Author();
        $author3->setName('Asd10');
        $author3->setAge(60);

        $author4 = new Author();
        $author4->setName('Hi');
        $author4->setAge(20);

        return [$author0, $author1, $author2, $author3, $author4];
    }
}
