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

namespace HAB\Reconciliation\ResultFactory;

use DOMDocument;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * Unit tests for the XPath based result object factory.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class XPathTest extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testCreateResultFromExternalDataInvalidExternalData ()
    {
        $factory = new XPath(array(), array());
        $factory->createResultFromExternalData(null);
    }

    public function testCreateResultFromExternalData ()
    {
        $document = new DOMDocument();
        $document->load(__DIR__ . '/example.xml');

        $factory = new XPath(
            array(
                'id'    => 'string(ns1:name/@ns2:id)',
                'name'  => 'string(ns1:name)',
                'score' => 'number(ns1:score)'
            ),
            array(
                'ns1' => 'http://example.org/ns/1',
                'ns2' => 'http://example.org/ns/2'
            )
        );

        $record = $document->getElementsByTagNameNS('http://example.org/ns/1', 'record')->item(0);
        $result = $factory->createResultFromExternalData($record);
        $this->assertEquals('0xdeadbeef', $result->get('id'));
        $this->assertEquals('Made up example record', $result->get('name'));
        $this->assertEquals(1.31, $result->get('score'));
        $this->assertInternalType('float', $result->get('score'));
    }
}