<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Measurement;

class MeasurementTest extends TestCase
{
    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $measurement= new Measurement();
        $measurement->setCelsius($celsius);
        $this->assertEquals($expectedFahrenheit,$measurement->getFahrenheit());
    }

    public function dataGetFahrenheit(): array
    {
        return [
            ['0', 32],           // 0°C = 32°F
            ['-100', -148],      // -100°C = -148°F
            ['100', 212],        // 100°C = 212°F
            ['-40', -40],        // -40°C = -40°F (punkt równości)
            ['37', 98.6],        // 37°C = 98.6°F (przybliżona temperatura ciała)
            ['0.5', 32.9],       // 0.5°C = 32.9°F
            ['-10.5', 13.1],     // -10.5°C = 13.1°F
            ['20.3', 68.54],     // 20.3°C = 68.54°F
            ['-17.78', 0],        // -17.78°C = 0°F (przybliżony punkt zera Fahrenheita)
            ['50.5', 122.9],     // 50.5°C = 122.9°F
        ];
    }

}
