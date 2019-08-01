<?php

use Phinx\Seed\AbstractSeed;

class Participant extends AbstractSeed
{

	public function run(): void
	{
		$this
			->table('Participant')
			->insert([
				[
					'ID' => 1,
					'Email' => 'user@example.com',
					'Name' => 'The first user',
				],
				[
					'ID' => 2,
					'Email' => 'jonkofee@icloud.com',
					'Name' => 'jonkofee',
				],
			])
			->save()
		;
	}

}
