<?php

use Phinx\Seed\AbstractSeed;

class Session extends AbstractSeed
{

	public function run(): void
	{
		$this
			->table('Session')
			->insert([
				[
					'ID' => 1,
					'Name' => 'Session One',
					'TimeOfEvent' => date('Y-m-d H:i:s'),
					'Places' => 0,
				],
				[
					'id' => 2,
					'Name' => 'Session Two',
					'TimeOfEvent' => date('Y-m-d H:i:s'),
					'Places' => 2,
				],
			])
			->save()
		;
	}

}
