<?php namespace Illuminate\Validation;

use Illuminate\Database\ConnectionResolverInterface;
use Illuminate\Support\Str;

class DatabasePresenceVerifier implements PresenceVerifierInterface {

	/**
	 * The database connection instance.
	 *
	 * @var  \Illuminate\Database\ConnectionResolverInterface
	 */
	protected $db;

	/**
	 * The database connection to use.
	 *
	 * @var string
	 */
	protected $connection = null;

	/**
	 * Create a new database presence verifier.
	 *
	 * @param  \Illuminate\Database\ConnectionResolverInterface  $db
	 * @return void
	 */
	public function __construct(ConnectionResolverInterface $db)
	{
		$this->db = $db;
	}

	/**
	 * Count the number of objects in a collection having the given value.
	 *
	 * @param  string  $collection
	 * @param  string  $column
	 * @param  string  $value
	 * @param  int     $excludeId
	 * @param  string  $idColumn
	 * @param  array   $extra
	 * @return int
	 */
	public function getCount($collection, $column, $value, $excludeId = null, $idColumn = null, array $extra = array())
	{
		$query = $this->table($collection, $extra)->where($column, '=', $value);

		if ( ! is_null($excludeId) && $excludeId != 'NULL')
		{
			$query->where($idColumn ?: 'id', '<>', $excludeId);
		}

		return $query->count();
	}

	/**
	 * Count the number of objects in a collection with the given values.
	 *
	 * @param  string  $collection
	 * @param  string  $column
	 * @param  array   $values
	 * @param  array   $extra
	 * @return int
	 */
	public function getMultiCount($collection, $column, array $values, array $extra = array())
	{
		$query = $this->table($collection, $extra)->whereIn($column, $values);

		return $query->count();
	}

	/**
	 * Get a query builder for the given table with optional extra where clauses.
	 *
	 * @param  string  $table
	 * @param  array  $extra
	 * @return \Illuminate\Database\Query\Builder
	 */
	protected function table($table, array $extra = array())
	{
		$query = $this->db->connection($this->connection)->table($table);

		foreach ($extra as $key => $extraValue)
		{
			switch(Str::upper($extraValue)) {
				case 'NULL':
					$query->whereNull($key);
					break;
				case 'NOTNULL':
					$query->whereNotNull($key);
					break;
				default:
					$query->where($key, $extraValue);
					break;
			}
		}

		return $query;

	}

	/**
	 * Set the connection to be used.
	 *
	 * @param  string  $connection
	 * @return void
	 */
	public function setConnection($connection)
	{
		$this->connection = $connection;
	}

}