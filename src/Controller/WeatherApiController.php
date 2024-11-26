<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Measurement;
use App\Service\WeatherUtil;

class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => '/^[a-zA-Z-]+$/'])] string  $city,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => '/^[a-zA-Z]{2}$/'])] string $country,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => '/^(csv|json)$/'])] string  $format,
        WeatherUtil $weatherUtil,
        #[MapQueryParameter('twig')] bool $twig = false,

    ): Response
    {
        $measurements = $weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($format === 'json') {
            if ($twig === true) {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurements,
                ]);
            }

            return $this->json([
                'city' => $city,
                'country' => $country,
                'measurements' => array_map(fn(Measurement $m) => [
                    'date' => $m->getDate()->format('Y-m-d'),
                    'celsius' => $m->getCelsius(),
                    'fahrenheit' => $m->getFahrenheit(),
                ], $measurements),
            ]);
        } elseif ($format === 'csv') {
            $csv = "city,country,celsius,date\n";
            foreach ($measurements as $m) {
                $csv .= sprintf(
                    "%s,%s,%s,%s\n",
                    $city,
                    $country,
                    $m->getCelsius(),
                    $m->getDate()->format('Y-m-d'),
                    $m->getFahrenheit(),

                );
            }

            return new Response($csv, 200, ['Content-Type' => 'text/csv']);
        }
        return $this->json(['error' => 'Invalid format specified.'], 400);
    }
}
