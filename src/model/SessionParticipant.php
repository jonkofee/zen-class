<?php

namespace Model;

use Core\Model;

class SessionParticipant extends Model
{

	/**
	 * @var string
	 */
	protected static $tableName = 'SessionParticipant';

	/**
	 * @ColumnName ID
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * @ColumnName SessionId
	 *
	 * @var int
	 */
	protected $sessionId;

	/**
	 * @ColumnName ParticipantId
	 *
	 * @var int
	 */
	protected $participantId;

}
