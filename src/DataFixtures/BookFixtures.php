<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Book;
use DateTime;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $androidCategory = $this->getReference(BookCategoryFixtures::ANDROID_CATEGORY);
        $deviceCategory = $this->getReference(BookCategoryFixtures::DEVICES_CATEGORY);

        $book = (new Book())
            ->setTitle('RXJAVA for Android Developer ')
            ->setPublicationDate(new DateTimeImmutable('2019-04-20'))
            ->setMeap(false)
            ->setIsbn('12345')
            ->setDescription('test descriptions')
            ->setAuthors(['Tino Timberts'])
            ->setSlug('rxjava-for-android-developer')
            ->setCategories(new ArrayCollection([$androidCategory, $deviceCategory]))
            ->setImage('https://images.manning.com/360/480/resize/book/b/bc57fb7-b239-4bf5-bbf2-886be8936951/Tuominen-RxJava-HI.png');
        $manager->persist($book);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BookCategoryFixtures::class,
        ];
    }
}
