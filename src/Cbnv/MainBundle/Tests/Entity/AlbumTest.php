<?php

namespace Cbnv\MainBundle\Tests\Entity;

use Cbnv\MainBundle\Entity\Album;

class AlbumTest extends \PHPUnit_Framework_TestCase
{
    public function testSetTitle() {
    	$album = new Album();
    	$result = $album->setTitle('titre');

    	$this->assertEquals('titre', $result->getTitle());
    }
}
