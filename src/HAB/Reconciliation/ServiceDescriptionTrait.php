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
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */

namespace HAB\Reconciliation;

/**
 * Manage a service description.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
trait ServiceDescriptionTrait
{
    /**
     * Service description.
     *
     * @var ServiceDescription
     */
    private $description;

    /**
     * Return service description.
     *
     * @return ServiceDescription
     */
    public function getDescription ()
    {
        if ($this->description === null) {
            $this->setDescription(new ServiceDescription(null, null, null));
        }
        return $this->description;
    }

    /**
     * Set service description.
     *
     * @param  ServiceDescription $description
     * @return void
     */
    public function setDescription (ServiceDescription $description)
    {
        $this->description = $description;
    }
}
