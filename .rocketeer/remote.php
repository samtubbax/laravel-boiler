<?php return array(

	// Remote server
	//////////////////////////////////////////////////////////////////////

	// Variables about the servers. Those can be guessed but in
	// case of problem it's best to input those manually
	'variables' => array(
		'directory_separator' => '/',
		'line_endings'        => "\n",
	),

	// The root directory where your applications will be deployed
	'root_directory'   => '/var/www/apps',

	// The name of the application to deploy
	// This will create a folder of the same name in the root directory
	// configured above, so be careful about the characters used
	'application_name' => 'new_project',

	// The number of releases to keep at all times
	'keep_releases'    => 4,

	// A list of folders/file to be shared between releases
	// Use this to list folders that need to keep their state, like
	// user uploaded data, file-based databases, etc.
	'shared' => array(
		'{path.storage}/logs',
		'{path.storage}/sessions',
	),

	'permissions' => array(

		// The folders and files to set as web writable
		// You can pass paths in brackets, so {path.public} will return
		// the correct path to the public folder
		'files' => array(
			'app/database/production.sqlite',
            '{path.storage}',
            '{path.public}',
            '{path.cache}',
            '{path.logs}'
		),

		// Here you can configure what actions will be executed to set
		// permissions on the folder above. The Closure can return
		// a single command as a string or an array of commands
		'callback' => function ($task, $file) {
			return array(
				sprintf('chmod -R 775 %s', $file),
				sprintf('chmod -R g+s %s', $file),
				sprintf('chown -R sites:www-data %s', $file),
			);
		},

	),

);
