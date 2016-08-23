<?php

/**
 * This file is part of HAB Reconciliation Services.
 *
 * HAB Reconciliation Services is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * HAB Reconciliation Services is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with HAB Reconciliation Services.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */

namespace HAB\Reconciliation;

use InvalidArgumentException;

/**
 * Reconciliation query.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class Query
{
    /**
     * Query string.
     *
     * @var string
     */
    private $queryString;

    /**
     * Create query from array.
     *
     * @throws InvalidArgumentException Invalid query data
     *
     * @param  array $data
     * @return self
     */
    public static function createFromArray (array $data)
    {
        if (!array_key_exists('query', $data)) {
            throw new InvalidArgumentException('Invalid query data: Required property "query" is missing');
        }
        return new Query($data['query']);
    }

    /**
     * Constructor.
     *
     * @param  string $queryString
     * @return void
     */
    public function __construct ($queryString)
    {
        $this->queryString = $queryString;
    }

    /**
     * Return query string.
     *
     * @return string
     */
    public function getQueryString ()
    {
        return $this->queryString;
    }
}