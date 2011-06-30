<?php
namespace Everyman\Neo4j;

/**
 * Holds the parameters for running a traversal
 */
class Traversal
{
	const ReturnTypeNode = 'node';
	const ReturnTypeRelationship = 'relationship';
	const ReturnTypePath = 'path';
	const ReturnTypeFullPath = 'fullpath';

	const OrderDepthFirst = 'depth_first';
	const OrderBreadthFirst = 'breadth_first';

	const UniquenessNone = 'none';
	const UniquenessNodeGlobal = 'node_global';
	const UniquenessRelationshipGlobal = 'relationship_global';
	const UniquenessNodePath = 'node_path';
	const UniquenessRelationshipPath = 'relationship_path';

	const PruneNone = 'none';

	const ReturnAll = 'all';
	const ReturnAllButStart = 'all_but_start_node';

	protected $client = null;

	protected $order = null;
	protected $uniqueness = null;
	protected $maxDepth = null;
	protected $relationships = array(
		'type'=>null,
		'direction'=>null
	);

	protected $pruneEvaluator = self::PruneNone;
	protected $returnFilter = self::ReturnAll;

	/**
	 * Build the traversal and set its client
	 *
	 * @param Client $client
	 */
	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	/**
	 * Get the finder's client
	 *
	 * @return Client
	 */
	public function getClient()
	{
		return $this->client;
	}

	/**
	 * Get the maximum allowed path length
	 *
	 * @return integer
	 */
	public function getMaxDepth()
	{
		return $this->maxDepth;
	}

	/**
	 * Return the order in which to traverse
	 *
	 * @return string
	 */
	public function getOrder()
	{
		return $this->order;
	}

	/**
	 * Get the prune evaluator
	 * Returns either a string representing a builtin evaluator
	 * or an array('language'=>..., 'body'=>...)
	 *
	 * @return mixed
	 */
	public function getPruneEvaluator()
	{
		return $this->pruneEvaluator;
	}

	/**
	 * Get the relationship type and description
	 *
	 * @return array ('type'=>..., 'direction'=>...)
	 */
	public function getRelationships()
	{
		return $this->relationships;
	}

	/**
	 * Run the traversal, and return the results
	 *
	 * @param Node $startNode
	 * @param string $returnType
	 * @return array
	 */
	public function getResults(Node $startNode, $returnType)
	{
		return $this->client->executeTraversal($this, $startNode, $returnType);
	}

	/**
	 * Get the return filter
	 * Returns either a string representing a builtin filter
	 * or an array('language'=>..., 'body'=>...)
	 *
	 * @return mixed
	 */
	public function getReturnFilter()
	{
		return $this->returnFilter;
	}

	/**
	 * Run the traversal, and return the first result
	 *
	 * @param Node $startNode
	 * @param string $returnType
	 * @return mixed
	 */
	public function getSingleResult(Node $startNode, $returnType)
	{
		$results = $this->getResults($startNode, $returnType);
		return $results ? $results[0] : null;
	}

	/**
	 * Return the uniqueness of the traversal
	 *
	 * @return string
	 */
	public function getUniqueness()
	{
		return $this->uniqueness;
	}

	/**
	 * Set the maximum allowed path length
	 *
	 * @param integer $max
	 * @return Traversal
	 */
	public function setMaxDepth($max)
	{
		$this->maxDepth = $max;
		return $this;
	}

	/**
	 * Set the order in which to traverse
	 *
	 * @param string $order
	 * @return Traversal
	 */
	public function setOrder($order)
	{
		$this->order = $order;
		return $this;
	}

	/**
	 * Set the prune evaluator
	 * If no language is specified, sets the evaluator to the 'none' builtin
	 *
	 * @param string $language
	 * @param string $body
	 * @return Traversal
	 */
	public function setPruneEvaluator($language=null, $body=null)
	{
		if (!$language) {
			$this->pruneEvaluator = self::PruneNone;
		} else {
			$this->pruneEvaluator = array(
				'language' => $language,
				'body' => $body,
			);
		}
	}

	/**
	 * Set the relationship type and description
	 *
	 * @param string $type
	 * @param string $direction
	 * @return Traversal
	 */
	public function setRelationships($type, $direction=null)
	{
		$this->relationships = array(
			'type' => $type,
			'direction' => $direction,
		);
		return $this;
	}

	/**
	 * Set the return filter
	 * If no $body parameter is specified, assumes the
	 * $language parameter is a string name of a builtin filter
	 *
	 * @param string $language
	 * @param string $body
	 * @return Traversal
	 */
	public function setReturnFilter($language, $body=null)
	{
		if (!$body) {
			$this->returnFilter = $language;
		} else {
			$this->returnFilter = array(
				'language' => $language,
				'body' => $body,
			);
		}
	}

	/**
	 * Set the uniquenss
	 *
	 * @param string $uniqueness
	 * @return Traversal
	 */
	public function setUniqueness($uniqueness)
	{
		$this->uniqueness = $uniqueness;
		return $this;
	}
}
