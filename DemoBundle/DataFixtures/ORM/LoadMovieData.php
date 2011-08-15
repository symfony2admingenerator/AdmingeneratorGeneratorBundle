<?php

namespace Admingenerator\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Admingenerator\DemoBundle\Entity\Movie;

class LoadMovieData implements FixtureInterface
{
    public function load($manager)
    {
        $movies = array(
        //Title, Year, Producer
	    	'City of God, 2002, Katia Lund',
	    	'Rain, 2001, Christine Jeffs',
	    	'2001: A Space Odyssey, 1968, Stanley Kubrick',
	    	'This is a "fake" movie title, 1957, Sidney Lumet',
	    	'Alien, 1979, Ridley Scott',
	    	'The Sequel to "Dances With Wolves.", 1982, Ridley Scott',
	    	'Caine Mutiny, 1954, Dymtryk "the King", Edward"',
        );
         
        foreach ($movies as $movie) {
            	
            list($title, $year, $producer) = explode(',', $movie);

            $myMovie = new Movie();
            $myMovie->setTitle($title);
            $myMovie->setIsPublished(true);

            $manager->persist($myMovie);
            $manager->flush();
        }

    }
}