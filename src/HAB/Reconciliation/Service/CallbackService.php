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
 * @copyright (c) 2017 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */

namespace HAB\Reconciliation\Service;

use HAB\Reconciliation\Query;
use HAB\Reconciliation\Result;

/**
 * Reconciliation service that uses a callback for query execution.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2017 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class CallbackService extends AbstractService
{
    private $callback;

    /**
     * Constructor.
     *
     * @param  callable $callback
     * @return void
     */
    public function __construct ($callback)
    {
        $this->callback = $callback;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute (Query $query)
    {
        return call_user_func($this->callback, $query);
    }
}