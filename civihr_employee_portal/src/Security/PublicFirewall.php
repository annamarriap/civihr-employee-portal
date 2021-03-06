<?php

namespace Drupal\civihr_employee_portal\Security;

/**
 * This class is responsible for checking if anonymous users can access
 * certain routes
 */
class PublicFirewall {

  /**
   * Anonymous users can access these paths.
   * Routes starting with @ will be treated as '@' delimited regular expressions
   *
   * @var array
   */
  public static $publicRoutes = [
    'welcome-page',
    'sites/default/files/logo.jpg', // if logo is missing
    'request_new_account/ajax', // from login page
    '@^user((?!\/register).)*$@', // user* except user/register
    'yoti', // yoti login plugin
    '@^yoti\/.*@', // anything under yoti
    '@^yoti-connect*@', // also yoti login plugin, where the user is redirected after login
    'civicrm/calendar-feed' //Leave request Calendar feed URL
  ];

  /**
   * Checks against all public routes, returning true if any match
   *
   * @param \stdClass $user
   *  The user to check access for
   * @param string $route
   *  The route to check
   *
   * @return bool
   */
  public function canAccess($user, $route) {
    $isAnonymous = is_null($user) || $user->uid == 0;

    if (!$isAnonymous) {
      return TRUE;
    }

    return $this->canAccessAnonymously($route);
  }

  /**
   * Check if route matches against all public routes
   *
   * @param string $route
   * @return bool
   */
  private function canAccessAnonymously($route) {
    foreach (self::$publicRoutes as $pattern) {
      if ($pattern[0] == '@' && preg_match($pattern, $route)) {
        return TRUE;
      }
      elseif ($route === $pattern) {
        return TRUE;
      }
    }

    return FALSE;
  }

}
