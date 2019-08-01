<?php

namespace Model;

use Core\Model;

class News extends Model
{

	/**
	 * @var string
	 */
	protected static $tableName = 'News';

	/**
	 * @ColumnName ID
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * @ColumnName ParticipantId
	 *
	 * @var int
	 */
	protected $participantId;

	/**
	 * @ColumnName NewsTitle
	 *
	 * @var string
	 */
	protected $newsTitle;

	/**
	 * @ColumnName NewsMessage
	 *
	 * @var string
	 */
	protected $newsMessage;

	/**
	 * @ColumnName LikesCounter
	 *
	 * @var int
	 */
	protected $likesCounter;

	/**
	 * @return string[]
	 */
	public function jsonSerialize(): array
	{
		return [
			'ID' => $this->id,
			'ParticipantId' => $this->participantId,
			'NewsTitle' => $this->newsTitle,
			'NewsMessage' => $this->newsMessage,
			'LikesCounter' => $this->likesCounter,
		];
	}

}
