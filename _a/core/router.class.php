<?php

namespace Core\Utils;

// Router class v9 10.08.2025	
// Copyright Maltsev N. 2014 - 2025

class Router{

	public function __construct($file, $debug = false){
		$this->loadRouts($file, $debug);
	}
	
	public function loadRouts($file, $debug = false){
		if (!$debug && file_exists($file . '.dat')) {
			$this->route = unserialize(file_get_contents($file . '.dat'));
		} else if (file_exists($file)) {
			$routeMap = SourceStore::restore($file);
			$this->route = $this->restoreRoutes($routeMap);
			file_put_contents($file . '.dat', serialize($this->route));
		} else {
			throw new Exception('File "' . $file . '" not found');
		}		
	}
	
	//	@memberOf Router - restore route collection by url string
	//	@param {String}	$url
	//	@return {Array}
	public function get($url) {
		$segmentCollection = array_filter(explode('/', $url), 'strlen');
		
		if (empty($segmentCollection)) {
			$result = $this->index();
		} else {
			$result = $this->get_params($segmentCollection, $this->route);
		}
		// echo ' <pre style="background:yellow;">';
		// print_r($result);
		// echo '</pre>';
		return $result;
	}
	
	//	@memberOf Router - get the route data by segment
	//	@return {Array}
	private function get_params(& $segmentCollection, $route) {
		if (!empty($segmentCollection)) {
			$segment = array_shift($segmentCollection);
			
			if (isset($route['routes'])) {
				// A segment without variables

				if (isset($route['routes'][$segment])) { 
					$buf = $route['routes'][$segment];
					
					if (!empty($segmentCollection)) {
						// continue recursion
						return $this->get_params($segmentCollection, $buf);
					} else {
						// echo '<pre style=background:red>---';
						// print_r($buf);
						// echo '</pre>';
						if (!isset($buf['controller'])) {
							return $this->not_found();
						}
						return array(
							'controller' => $buf['controller'],
							'action' => $buf['action'],
						);
					}
				} 
				else {
					// The check of the route segment by a regular expression:
					foreach($route['routes'] as $subRoute) {
						if (isset($subRoute['match'])) {
							if (preg_match($subRoute['match'], $segment, $temp_values)) {
								// $temp_values = ['full match', 'first capturing group']
								$res = null;
								
								if (!empty($segmentCollection)) {
									// continue recursion
									$res = $this->get_params($segmentCollection, $subRoute);
								} else {
									if (!isset($subRoute['controller'])) {
										// return $this->not_found();
										continue;
									}
									$res = array(
										'controller' => $subRoute['controller'],
										'action' => $subRoute['action']
									);
								}
								
								if (!isset($res['vars'])) {
									$res['vars'] = array();
								}
								
								$res['vars'][$subRoute['vars'][0]] = $temp_values[0];
								// echo "<pre style=background:gray> $segment";
								// print_r($temp_values);
								// print_r($subRoute);
								// print_r($res);
								// echo '</pre>';
								
								return $res;
							}
							// continue if not caught by the regular expression
						} else {
							continue;
							// return $this->not_found();
						}
					}
					return $this->not_found();
				}
			} else {
				return $this->not_found();
			}
		}
	}
	
	// for plain routes such as default
	//	@return {Array}
	private function getSimpleRoute($route) {
		if (isset($this->route['routes'][$route])) {
			return $this->route['routes'][$route];
		} else {
			throw new Exception('System route `' . $route . '` not found in the router collection. Create it for normal work.');
		}
	}

	//	@return {Array}
	private function not_found() {
		return $this->getSimpleRoute('not_found');
	}
	
	//	@return {Array}
	private function forbidden() {
		return $this->getSimpleRoute('forbidden');
	}
	
	//	@return {Array}
	private function index() {
		return $this->getSimpleRoute('index');
	}
	
	// @memberOf Router - create tree
	// @param {Array} $rArray
	// @return {Array} $routTree
	private function restoreRoutes($rArray) {
		$routTree = array();

		foreach($rArray as $routStr => $actionController) {
			$segments = explode('/', $routStr);
			$currentTree = &$routTree;

			foreach($segments as $segment) {
				if (empty($segment)) continue;
				$routeName = $segment;
				
				if (strpos($segment, '{') !== false) {	
					preg_match_all('/\{(.+)(?:\:(w|d))?\}/siuU', $segment, $match_attrs, PREG_SET_ORDER);
					$match = $segment;
					$variables = array();

					foreach($match_attrs as $key => $variable) {
						$match = str_replace('{' . $variable[1] . '}', '(.+)', $match);
						$variables []= $variable[1];
			
						if (empty($variable[2])) {
							$match = str_replace('{' . $variable[1] . '}', '(.+)', $match);
						} else {
	
							if ($variable[2] == 'd') {
								$match = str_replace('{' . $variable[1] . ':d}', '(\d*)', $match);
							} else {
								$match = str_replace('{' . $variable[1] . ':' . $variable[2] . '}', '([\w\d%\_\-]*)', $match);
							}
						}
					}
					$routeName = crc32($match);
					$match = '/^' . $match .'$/iu';
				}
				
				if (empty($currentTree['routes'])) { // First child
					$currentTree['routes'] = array(
						$routeName => array()
					);
				} else if(empty($currentTree['routes'][$routeName])) {
					$currentTree['routes'][$routeName] = array();
				}
				
				if (!empty($match)) { // $variables exist in same time with $match
					$currentTree['routes'][$routeName]['match'] = $match;
					$match = null;
				}
	
				$currentTree = &$currentTree['routes'][$routeName];
				if (!empty($variables)) {
					$currentTree['vars'] = $variables;
				}
			}
			
			$controllerDataArray = explode('::', $actionController);
			
			if (count($controllerDataArray) == 2) {
				$currentTree['controller'] = $controllerDataArray[0]; 
				$currentTree['action'] = $controllerDataArray[1];
			}
		}
		
		return $routTree;
	}
	
	public function storeRoutes($treeArray){
		// TODO
	}
	
	public function isRouteExist($urlStr) {
		$segmentCollection = array_filter(explode('/', $urlStr), 'mb_strlen');
		$currentTree = $this->route;
		
		print_r($segmentCollection );
		$num = count($segmentCollection);
		if ($num == 0) {
			return false;
		}
		
		foreach($segmentCollection as $index => $segment) {
			
			if (isset($currentTree['routes'])) {
				
				if (isset($currentTree['routes'][$segment])) {
					// without vars
					$currentTree = &$currentTree['routes'][$segment];
				} else {
					$relevantSubRoute = null;
					foreach ($currentTree['routes'] as &$segmentObj) {
						if (isset($segmentObj['match']) && preg_match($segmentObj['match'], $segment, $temp_values)) {
							// segment of route exist
							$relevantSubRoute = &$segmentObj;
							break;
						}
					}
					
					if ($relevantSubRoute) {
						$currentTree = &$relevantSubRoute;
					} else {
						return false;
					}
				}
				
				if (!empty($currentTree['routes'])) {
					continue;
				} else { 
					// check if all segments 
					return $index == $num ? true : false; 
				}
			} else {
				// no child routes (Maybe)
				return true;
			}
		}
		
		// take last segment and check for action attribute
		if (isset($currentTree['action'])) {
			return true;
		} else {
			return false;
		}
	}
	
	public function appendRoute($urlStr){
		// TODO
	}
	
	public function removeRoute($urlStr){
		// TODO
	}
}
