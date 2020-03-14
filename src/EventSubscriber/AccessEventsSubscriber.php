<?php

namespace Drupal\smile_entity\EventSubscriber;

use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class AccessEventsSubscriber
 *
 * @package Drupal\custom_redirect\EventSubscriber
 */
class AccessEventsSubscriber implements EventSubscriberInterface {

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::CONTROLLER][] = ['checkForAccess'];
    return $events;
  }

  /**
   * Disallow access to /smile/id page for all users whose HTTP Header "Referer" not equal to site-url
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function checkForAccess() {
    $currentRoute = \Drupal::routeMatch()->getRouteName();
    // Get the hostname.
    $host = \Drupal::request()->getHost();
    $previousUrl = \Drupal::request()->server->get('HTTP_REFERER');
    // Or instead use check $previousUrl equal null.
    if ($currentRoute === 'entity.smile.canonical' && strpos($previousUrl, $host) === FALSE) {
      $redirectUrl = new Url('system.403');
      $response = new RedirectResponse($redirectUrl->toString());
      return $response->send();
    }
  }

}
