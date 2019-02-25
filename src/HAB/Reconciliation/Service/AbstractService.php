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
 * @copyright (c) 2017-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */

namespace HAB\Reconciliation\Service;

use HAB\Reconciliation\ServiceDescriptionTrait;
use HAB\Reconciliation\ServiceInterface;

use HAB\Reconciliation\Query;
use HAB\Reconciliation\Result;

/**
 * Abstract base class of simple reconciliation services.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2017-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
abstract class AbstractService implements ServiceInterface
{
    use ServiceDescriptionTrait;

    /**
     * {@inheritDoc}
     */
    public function reconcile (array $queries)
    {
        $results = array();
        foreach ($queries as $key => $query) {
            $results[$key] = array('result' => (array)$this->execute($query));
        }
        return $results;
    }

    /**
     * Execute reconciliation query and return its result.
     *
     * @param  Query $query
     * @return Result
     */
    abstract protected function execute (Query $query);

}
