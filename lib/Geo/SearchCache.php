<?php
/**
 * This file is part of the GeoAdapter software.
 * (c) 2011 Francesco Trucchia <francesco@trucchia.it>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geo;

/**
 * SearchCache class can cache the Geo\Search queries
 *
 * @package    geoadapter
 * @subpackage search
 * @author     Francesco Trucchia <francesco@trucchia.it>
 */
class SearchCache
{
  private $search;
  private $cached_queries;
  private $user;
  
  public function __construct(Search $search, \myUser $user)
  {
    $this->user = $user;
    $this->search = $search;
    $this->cached_queries = $user->getAttribute('results');
  }

  public function query($q, $service_index = 0, $e = null)
  {
    $q = strtolower($q);
    
    $this->user->setAttribute('last_search', $q);

    if (isset($this->cached_queries[$q]))
    {
      return $this->search->setResults($this->cached_queries[$q]);
    }
    
    $this->search->query($q, $service_index, $e);
    $this->cached_queries[$q] = $this->search->getResults();
  }

  public function getFirst()
  {
    return $this->search->getFirst();
  }

  public function getCachedQueries()
  {
    return $this->cached_queries;
  }

  public function __destruct()
  {
    $this->user->setAttribute('results', $this->cached_queries);
  }
}
