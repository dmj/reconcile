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

use JsonSerializable;

/**
 * Reconciliation service description.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class ServiceDescription implements JsonSerializable
{
    /**
     * Description metadata.
     *
     * @var array
     */
    private $metadata;

    /**
     * Constructor.
     *
     * @param  string $name
     * @param  string $identifierSpace
     * @param  string $schemaSpace
     * @return void
     */
    public function __construct ($name, $identifierSpace, $schemaSpace)
    {
        $this->metadata['name'] = $name;
        $this->metadata['identifierSpace'] = $identifierSpace;
        $this->metadata['schemaSpace'] = $schemaSpace;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize ()
    {
        return $this->metadata;
    }
}