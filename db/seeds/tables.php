<?php

use Phinx\Seed\AbstractSeed;

class Tables extends AbstractSeed
{

	public function run(): void
	{
		$this
			->table('Tables')
			->insert([
				[
					'ID' => 1,
					'Name' => 'News',
					'Hide' => 0,
				],
				[
					'ID' => 2,
					'Name' => 'Participant',
					'Hide' => 1,
				],
			])
			->save()
		;
	}

}
