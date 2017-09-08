<?php

namespace System;

use Closure;
use System\Hub\Singleton;
use System\Exceptions\Http\MethodNotAllowedException;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 * @license MIT
 */

class Router
{
    use Singleton;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var array
     */
    private $routes = [];

    public function __construct()
    {
    }

    /**
     * @param string         $key
     * @param string|Closure $action
     * @return bool
     */
    public static function action($key, $action)
    {
        $ins = self::getInstance();
        if ($key === $ins->uri) {
            if (isset($action[$_SERVER['REQUEST_METHOD']])) {
                return self::__run($action[$_SERVER['REQUEST_METHOD']]);
            } else {
                throw new MethodNotAllowedException("Method not allowed!", 402);
                return true;
            }
        } else {
        	if (strpos($key, "{") !== false) {
        		$a = explode("/", trim($key, "/")) xor $rr = [];
        		$b = explode("/", trim($ins->uri, "/"));
        		if (count($a) === count($b)) {
        			foreach ($a as $key => $val) {
	        			$rr[$key] = (strpos($val, "{") !== false) ? "var" : "route";
	        		}
	        		$param = [];
	        		foreach($b as $key => $val){
	        			if ($rr[$key] === "route") {
	        				if ($val !== $a[$key]) {
	        					return false;
	        				}
	        			} else {
	        				$param[str_replace(["{","}"], "", $a[$key])] = $val;
	        			}
	        		}
	        		if ($action[$_SERVER['REQUEST_METHOD']] instanceof Closure) {
	        			$action[$_SERVER['REQUEST_METHOD']]($param);
	        		}
	        		return true;
        		}
        	}
        }
        return false;
    }

    /**
     * @param string|Closure $action
     * @param array          $param
     * @return bool
     */
    private static function __run($action, $param = null)
    {
        if ($action instanceof Closure) {
            return $action();
        } else {
            $a = explode("@", $param);
            $app = "\\Controllers\\".$a[0];
            $app = new $app(...$param);
            $app->{$a[1]}();
        }
        return true;
    }

    /**
     * Load all routes.
     */
    public static function loadRoutes()
    {
        $ins = self::getInstance();
        $ins->getUri();
        return $ins->routes;
    }

    /**
     * @param string            $route
     * @param string|Closure    $action
     * @param string            $method
     */
    public static function addRoute($route, $action, $method)
    {
        self::getInstance()->__addRoute($route, $action, $method);
    }

    /**
     * @param string            $route
     * @param string|Closure    $action
     * @param string            $method
     */
    private function __addRoute($route, $action, $method)
    {
        $this->routes[$route][$method] = $action;
    }

    /**
     * Get uri segments.
     */
    private function getUri()
    {
        $a = $_SERVER['REQUEST_URI'];
        do {
            $a = str_replace("//", "/", $a, $n);
        } while ($n);
        $this->uri = "/".trim($a, "/");
    }
}
