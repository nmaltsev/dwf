<?php return array(
	'index' => 'index::a_index',
	'forbidden' => 'error::a403',
	'not_found' => 'error::a404',

	'{path}' => 'rewrite::a_js'

	// 'display' => 'transparent::a_index1',
	// 'display/json' => 'transparent::a_index2',
	// 'display/dev' => 'transparent::devAction',
	// 'pics/screenshot{param1:w}' => 'aaa::bbb',
	// 'pics/screenshot{param1:w}{param2:d}{param3}' => 'aaa::bbb',
	// 'pics//screenshot2' => 'aaa::bbb',
	// 'folder/user_{username}/' => 'folder::index',
	// 'folder/update_{uid:d}_{gid:d}/' => 'folder::update_a',
	// 'folder/{username}/' => 'folder::index',
	// 'folder/{username}/profile/' => 'folder::profile',
	// 'dir/test_{uid:d}' => 'dir::index',
);