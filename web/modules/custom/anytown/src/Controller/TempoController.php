<?php

declare(strict_types=1);

namespace Drupal\anytown\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Logger\RfcLogLevel;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for anytown.tempo_controller route.
 */
class TempoController extends ControllerBase {
  /**
   * HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  private $httpClient;

  /**
   * Logging service, set to 'anytown' channel.
   *
   * @var \Psr\Log\LoggerInterface
   */
  private $logger;

  /**
   * WeatherPage controller constructor.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   HTTP client.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger
   *   Logger factory service.
   */
  public function __construct(ClientInterface $http_client, LoggerInterface $logger) {
    $this->httpClient = $http_client;
    $this->logger = $logger;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client'),
      $container->get('logger.factory')->get('anytown')
    );
  }
  /**
   * Builds the response.
   */
  public function build(string $style): array {
    // Style should be one of 'short', or 'extended'. And default to 'short'.
    $style = (in_array($style, ['short', 'extended'])) ? $style : 'short';

    $url = 'http://my-drupal-site.ddev.site/modules/custom/anytown/data/weather_forecast.json';

    try {
      $response = $this->httpClient->get($url);
      $data = json_decode($response->getBody()->getContents());
    }
    catch (RequestException $e) {
      $this->logger->log(RfcLogLevel::WARNING, $e->getMessage());
    }

    if ($data) {
      $forecast = '<ul>';
      foreach ($data->list as $day) {
        $weekday = ucfirst($day->day);
        $description = array_shift($day->weather)->description;
        // Convert units in Kelvin to Fahrenheit.
        $high = round(($day->main->temp_max - 273.15) * 9 / 5 + 32);
        $low = round(($day->main->temp_min - 273.15) * 9 / 5 + 32);
        $forecast .= "<li>$weekday will be <em>$description</em> with a high of $high and a low of $low.</li>";
      }
      $forecast .= '</ul>';
    }
    else {
      $forecast = '<p>Could not get the weather forecast. Dress for anything.</p>';
    }

    $output = "<p>Check out this weekend's weather forecast and come prepared. The market is mostly outside, and takes place rain or shine.</p>";
    $output .= $forecast;

    return [
      '#markup' => $output,
    ];

    $build['content'] = [
      '#type' => 'markup',
      '#markup' => '<p>The weather forecast for this week is sunny with a chance of meatballs.</p>',
    ];

    if ($style === 'extended') {
      $build['content_extended'] = [
        '#type' => 'markup',
        '#markup' => '<p><strong>Extended forecast:</strong> Looking ahead to next week we expect some snow.</p>',
      ];
    }

    return $build;
  }

}