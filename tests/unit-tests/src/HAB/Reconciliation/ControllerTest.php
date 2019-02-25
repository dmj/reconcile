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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * Unit tests for the Controller class.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class ControllerTest extends TestCase
{
    public function testMethodNotAllowed ()
    {
        $request = Request::create('/', 'PUT');
        $service = $this->getMockForAbstractClass('HAB\Reconciliation\ServiceInterface');
        $controller = new Controller($service);
        $response = $controller->handle($request);
        $this->assertEquals(Response::HTTP_METHOD_NOT_ALLOWED, $response->getStatusCode());
    }

    public function testInvalidCallback ()
    {
        $request = Request::create('/', 'GET', array('callback' => ''));
        $service = $this->getMockForAbstractClass('HAB\Reconciliation\ServiceInterface');
        $controller = new Controller($service);
        $response = $controller->handle($request);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testAmbiguousParameters ()
    {
        $request = Request::create('/', 'GET', array('query' => '', 'queries' => ''));
        $service = $this->getMockForAbstractClass('HAB\Reconciliation\ServiceInterface');
        $controller = new Controller($service);
        $response = $controller->handle($request);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testDescription ()
    {
        $request = Request::create('/', 'GET');
        $service = $this->getMockForAbstractClass('HAB\Reconciliation\ServiceInterface');
        $service
            ->expects($this->once())
            ->method('getDescription')
            ->will($this->returnValue(new ServiceDescription('Example service', 'http://example.org', 'http://rdf.freebase.com/ns/type.object.id')));

        $controller = new Controller($service);
        $response = $controller->handle($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
