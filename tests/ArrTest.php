<?php declare(strict_types=1);

use Antares\Support\Arr;
use Antares\Support\AssociativeCollection;
use Antares\Support\SimpleCollection;
use PHPUnit\Framework\TestCase;

final class ArrTest extends TestCase
{
    private function getWorkArray()
    {
        $wa = [
            'fruits' => [
                'apple',
                'banana',
                'mango',
                'avocado',
                'grape',
                'cherries',
            ],
            'projects' => [
                'alpha' => [
                    'classified' => 'secret',
                    'subject' => 'z-ray',
                ],
                'beta' => [
                    'classified' => 'ultrasecret',
                    'subject' => 'teleportation',
                    'state' => 'advanced',
                ],
                'delta' => [
                    'classified' => 'topsecret',
                    'subject' => 'telekinesis',
                    'state' => 'started',
                ],
            ],
            'cars' => [
                ['car' => ['id' => 'ford/fusion',   'name' => 'fusion', 'brand' => 'ford']],
                ['car' => ['id' => 'hyundai/azera', 'name' => 'azera',  'brand' => 'hyndai']],
                ['car' => ['id' => 'vw/passat',     'name' => 'passat', 'brand' => 'vw']],
                ['car' => ['id' => 'porsche/911',   'name' => '911',    'brand' => 'porsche']],
            ],
            'stars' => [
                'alcyone',
                'antares',
                'canopus',
                'capella',
                'sirius',
            ],
        ];

        return $wa;
    }

    public function testArrAccessibleMethod()
    {
        $wa = $this->getWorkArray();

        // accesible
        $this->assertTrue(Arr::accessible($wa));
    }

    public function testArrAddMethod()
    {
        $wa = $this->getWorkArray();

        // add
        $wa = Arr::add($wa, 'fruits', ['orange', 'melon']);
        $wa = Arr::add($wa, 'brands', ['ferrari', 'porshce', 'bmw', 'bugatti', 'audi', 'lamborghini', 'vw']);

        $this->assertEquals(5, count($wa));
        $this->assertEquals(6, count(Arr::get($wa, 'fruits')));
        $this->assertEquals(7, count(Arr::get($wa, 'brands')));
    }

    public function testArrCollapseMethod()
    {
        $wa = $this->getWorkArray();

        // collapse
        $this->assertIsArray(Arr::collapse($wa));
    }

    public function testArrDotMethod()
    {
        $wa = $this->getWorkArray();

        // dot
        $this->assertIsArray(Arr::dot($wa));
    }

    public function testArrExceptMethod()
    {
        $wa = $this->getWorkArray();

        // except
        $this->assertEquals(3, count(Arr::except($wa, 'projects')));
        $this->assertEquals(2, count(Arr::except($wa, ['fruits', 'cars'])));
    }

    public function testArrExistsMethod()
    {
        $wa = $this->getWorkArray();

        // exists
        $this->assertTrue(Arr::exists($wa, 'fruits'));
        $this->assertFalse(Arr::exists($wa, 'notExists'));
    }

    public function testArrFirstMethod()
    {
        $wa = $this->getWorkArray();

        // first
        $temp = Arr::first($wa, function ($value, $key) {
            return ($key == 'stars');
        });
        $this->assertIsArray($temp);
        $this->assertTrue($temp[1] == 'antares');
    }

    public function testArrLastMethod()
    {
        $wa = $this->getWorkArray();

        // last
        $temp = Arr::last(Arr::get($wa, 'fruits'), function ($value, $key) {
            return true;
        });
        $this->assertTrue($temp == 'cherries');
    }

    public function testArrFlattenMethod()
    {
        $wa = $this->getWorkArray();

        // flatten
        $temp = Arr::flatten($wa);
        $this->assertIsArray($temp);
        $this->assertEquals(31, count($temp));
        $this->assertEquals('sirius', end($temp));
    }

    public function testArrForgetMethod()
    {
        $wa = $this->getWorkArray()['projects'];

        // forget
        Arr::forget($wa, ['alpha', 'delta']);
        $this->assertIsArray($wa);
        $this->assertEquals(1, count($wa));
        $this->assertTrue(array_key_exists('beta', $wa));
    }

    public function testArrGetMethod()
    {
        $wa = $this->getWorkArray();

        // get
        $this->assertEquals('banana', Arr::get($wa, 'fruits.1'));
        $this->assertEquals('advanced', Arr::get($wa, 'projects.beta.state'));
    }

    public function testArrHasMethod()
    {
        $wa = $this->getWorkArray();

        // has
        $this->assertTrue(Arr::has($wa, 'fruits'));
        $this->assertTrue(Arr::has($wa, 'fruits.0'));
        $this->assertTrue(Arr::has($wa, 'projects'));
        $this->assertTrue(Arr::has($wa, 'projects.delta'));
        $this->assertTrue(Arr::has($wa, 'projects.delta.subject'));

        $this->assertFalse(Arr::has($wa, 'projects.delta.subject.invalid'));
        $this->assertFalse(Arr::has($wa, 'projects.delta.invalid'));
    }

    public function testArrIsAssocMethod()
    {
        $wa = $this->getWorkArray();

        // isAssoc
        $this->assertTrue(Arr::isAssoc($wa));
        $this->assertFalse(Arr::isAssoc($wa['fruits']));
    }

    public function testArrOnlyMethod()
    {
        $wa = $this->getWorkArray();

        // only
        $wa = Arr::only($wa, ['fruits', 'stars']);
        $this->assertIsArray($wa);
        $this->assertEquals(2, count($wa));
        $this->assertTrue(array_key_exists('fruits', $wa));
        $this->assertTrue(array_key_exists('stars', $wa));
    }

    public function testArrPluckMethod()
    {
        $wa = $this->getWorkArray();

        // pluck
        $wa = Arr::pluck($wa['cars'], 'car.id');
        $this->assertIsArray($wa);
        $this->assertEquals(4, count($wa));
        $this->assertTrue(array_search('porsche/911', $wa) !== false);
    }

    public function testArrPrependMethod()
    {
        $wa = $this->getWorkArray();

        // prepend
        $fruits = Arr::prepend($wa['fruits'], 'orange');
        $this->assertIsArray($fruits);
        $this->assertEquals(7, count($fruits));
        $this->assertEquals('orange', reset($fruits));
    }

    public function testArrPullMethod()
    {
        $wa = $this->getWorkArray();

        // pull
        $pulled = Arr::pull($wa['fruits'], 1);
        $this->assertEquals(5, count($wa['fruits']));
        $this->assertEquals('banana', $pulled);
    }

    public function testArrRandomMethod()
    {
        $wa = $this->getWorkArray();

        // random
        $stars = Arr::random($wa['stars'], 3);
        $this->assertIsArray($stars);
        $this->assertEquals(3, count($stars));
        $this->assertTrue(array_search($stars[0], $wa['stars']) !== false);
    }

    public function testArrSetMethod()
    {
        $wa = $this->getWorkArray();

        // set
        Arr::set($wa, 'projects.delta.codename', 'acrux');
        Arr::set($wa, 'projects.delta.state', 'operational');
        $this->assertEquals(4, count(Arr::get($wa, 'projects.delta')));
        $this->assertEquals('acrux', Arr::get($wa, 'projects.delta.codename'));
        $this->assertEquals('operational', Arr::get($wa, 'projects.delta.state'));
    }

    public function testArrShuffleMethod()
    {
        $wa = $this->getWorkArray();

        // shuffle
        $shuffled = Arr::shuffle(Arr::get($wa, 'fruits'));
        $this->assertIsArray($shuffled);
        $this->assertEquals(count(Arr::get($wa, 'fruits')), count($shuffled));
    }

    public function testArrQueryMethod()
    {
        $wa = Arr::get($this->getWorkArray(), 'cars.0.car');

        // query
        $query = Arr::query($wa);
        $this->assertIsString($query);
        $this->assertEquals('id=ford%2Ffusion&name=fusion&brand=ford', $query);
    }

    public function testArrWhereMethod()
    {
        $wa = Arr::get($this->getWorkArray(), 'cars');

        // where
        $temp = Arr::where($wa, function ($value, $key) {
            return (Arr::get($value, 'car.brand') == 'ford');
        });
        $this->assertIsArray($temp);
        $this->assertEquals(1, count($temp));
        $this->assertEquals('fusion', arr::get(Arr::first($temp), 'car.name'));
    }

    public function testArrWrapMethod()
    {
        // wrap
        $temp = Arr::wrap(null);
        $this->assertIsArray($temp);
        $this->assertEquals(0, count($temp));

        $temp = Arr::wrap([]);
        $this->assertIsArray($temp);
        $this->assertEquals(0, count($temp));

        $temp = Arr::wrap('');
        $this->assertIsArray($temp);
        $this->assertEquals(1, count($temp));

        $temp = Arr::wrap(['one', 'two']);
        $this->assertIsArray($temp);
        $this->assertEquals(2, count($temp));
    }

    public function testArrWithCollections()
    {
        $wa = $this->getWorkArray();

        $collection = new SimpleCollection('string');
        foreach ($wa['fruits'] as $item) {
            $collection->add($item);
        }
        $wa = Arr::add($wa, 'collections.fruits', $collection);

        $collection = new AssociativeCollection('array');
        foreach ($wa['projects'] as $key => $item) {
            $collection->add($key, $item);
        }
        $wa = Arr::add($wa, 'collections.projects', $collection);

        $this->assertEquals(count($this->getWorkArray()) + 1, count($wa));
        $this->assertEquals(2, count(Arr::get($wa, 'collections')));

        $this->assertEquals(count($this->getWorkArray()['fruits']), count(Arr::get($wa, 'collections.fruits')));
        $this->assertEquals('mango', Arr::get($wa, 'collections.fruits.2'));

        $this->assertEquals(count($this->getWorkArray()['projects']), count(Arr::get($wa, 'collections.projects')));
        $this->assertEquals('telekinesis', Arr::get($wa, 'collections.projects.delta.subject'));
    }
}
