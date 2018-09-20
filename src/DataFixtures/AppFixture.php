<?php
/**
 * Created by PhpStorm.
 * User: jacopo
 * Date: 9/20/18
 * Time: 7:53 PM
 */

namespace App\DataFixtures;


use App\Entity\Album;
use App\Entity\Artist;
use App\Entity\Song;
use App\Utils\TokenGenerator;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $url = "https://gist.githubusercontent.com/fightbulc/9b8df4e22c2da963cf8ccf96422437fe/raw/8d61579f7d0b32ba128ffbf1481e03f4f6722e17/artist-albums.json";

        $list = json_decode(file_get_contents($url));

        // TODO create a storage function in the repositories instead of polluting the fixture
        foreach ($list as $artistJson) {
            $artist = new Artist();
            $artist->setName($artistJson->name);

            if (isset($artistJson->token)) {
                $artist->setToken($artistJson->token);
            }
            else  {
                $artist->setToken(TokenGenerator::generate(6));
            }

            $manager->persist($artist);
            $manager->flush();

            foreach ($artistJson->albums as $albumJson) {
                $album = new Album();
                $album->setTitle($albumJson->title);
                $album->setCover($albumJson->cover);
                $album->setDescription($albumJson->description);
                $album->setArtist($artist);

                if (isset($albumJson->token)) {
                    $album->setToken($albumJson->token);
                }
                else  {
                    $album->setToken(TokenGenerator::generate(6));
                }

                $manager->persist($album);
                $manager->flush();

                foreach ($albumJson->songs as $songJson) {
                    $song = new Song();

                    $song->setTitle($songJson->title);

                    //Convert length string to seconds
                    $length = explode(':', $songJson->length);
                    $seconds = $length[0]*60 + $length[1];

                    $song->setLength($seconds);
                    $song->setAlbum($album);

                    $manager->persist($song);
                    $manager->flush();
                }
            }
        }
    }
}