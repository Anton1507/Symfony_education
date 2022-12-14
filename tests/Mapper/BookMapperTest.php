<?php

namespace App\Tests\Mapper;

use App\Entity\Book;
use App\Mapper\BookMapper;
use App\Model\BookDetails;
use App\Tests\AbstractClassTest;

class BookMapperTest extends AbstractClassTest
{
    public function testMap()
    {
        $book = (new Book())->setTitle('title')->setSlug('slug')->setImage('123')->setAuthors(['tester'])->setMeap(true)->setPublicationDate(new \DateTimeImmutable('2020-10-10'));

        $this->setEntityId($book, 1);
        $expected = (new BookDetails())->setId(1)->setTitle('title')->setSlug('slug')->setImage('123')->setAuthors(['tester'])->setMeap(true)->setPublicationDate(1602280800);

        $this->assertEquals($expected, BookMapper::map($book, new BookDetails()));
    }
}
