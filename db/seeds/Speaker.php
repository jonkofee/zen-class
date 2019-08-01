<?php

use Phinx\Seed\AbstractSeed;

class Speaker extends AbstractSeed
{

	public function run(): void
	{
		$this
			->table('Speaker')
			->insert([
				[
					'ID' => 1,
					'Name' => 'Watson',
				],
				[
					'id' => 2,
					'Name' => 'Arnold',
				],
			])
			->save()
		;
	}

}
