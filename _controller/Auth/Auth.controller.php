<?php
class Auth extends \Core\AController {
	
	function __construct(){}
	function a_login(){
		// POST {user: ..., password: ...}
		return new \Core\JsonAction(array());
	}
	function a_logout(){
		return new \Core\JsonAction([]);
	}
	function a_whoami(){
		return new \Core\JsonAction([
			'whoami' => [
				'user'=> 'nick'
			]
		]);
	}

	private function getRandomItem($list){
		return $list[rand(0, count($list) - 1)];
	}
	private function getRandomBool(){
		return rand(0, 10) > 5;
	}
	private $testData = array(
		'report_object' => ["/home/dbanschikov/wrk/eicar/eicar.com2", "/tmp/test_folder/file", '/opt/drweb/virus.avi', '/usr/bin/sh'],
		'history_result' => ['Object cannot be cured', 'File has already been quarantined', 'Success'],
	);

	function a_threats(){
		$list = array();

		for($i = 0; $i < 100; $i++){
			array_push($list, rand(1, 1000));
		}

		return new \Core\JsonAction($list);
	}

	function a_threat_info($conf){
		$id = $conf['id'];
		$object = $this->getRandomItem($this->testData['report_object']);
		$size = rand(100, 4096);
		$heuristic = $this->getRandomBool();

		return new \Core\JsonAction([
			'threat_id' =>  $id,
			'detection_time' => time(),
			'report' =>  [
		        'object' => $object,
		        'size' => $size,
		        'virus' => [
		            [
		                'type' => "Known virus",
		                'name' => "EICAR Test File (NOT a Virus!)"
		            ]
		        ],
		        'heuristic_analysis' => $heuristic,
		    ],
		    'stat' => [
		        'dev' => 2049,
		        'ino' => 1059677,
		        'size' => $size,
		        'uid' => 1003,
		        'gid' => 1004,
		        'mode' => 33204,
		        'mtime' => time(),
		        'ctime' => time()
		    ],
		    'origin' => "Ctl",
		    'origin_pid' => 26526,
		    'task_id' => 1,
		    'history' => [
		        [
		            'action' => "Cure",
		            'action_time' => time(),
		            'result' => $this->getRandomItem($this->testData['history_result']),
		            'cure_report' => [
		                'object' => $object,
		                'size' => $size,
		                'virus' => [
		                    [
		                        'type' => "Known virus",
		                        'name' => "EICAR Test File (NOT a Virus!)"
		                    ]
		                ],
		                'error' => "Cannot be cured",
		                'heuristic_analysis' => $heuristic
		            ]
		        ]
		    ]

		]);
	}
}

