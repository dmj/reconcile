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

use InvalidArgumentException;
use SplObjectStorage;
use Traversable;
use DOMDocument;
use DOMElement;
use DOMXPath;

use HAB\Reconciliation\Result;
use HAB\Reconciliation\ResultFactoryInterface;

/**
 * XPath based result object factory.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class XPath implements ResultFactoryInterface
{
    /**
     * Namespace bindings.
     *
     * @var array
     */
    private $nsBindings;

    /**
     * Properties.
     *
     * @var array
     */
    private $properties;

    /**
     * XPath processor cache.
     *
     * @var SplObjectStorage
     */
    private $processorCache;

    /**
     * Constructor.
     *
     * @param  array $properties
     * @param  array $namespaces
     * @return void
     */
    public function __construct (array $properties, array $namespaces)
    {
        $this->processorCache = new SplObjectStorage();
        foreach ($properties as $name => $expr) {
            $this->defineProperty($name, $expr);
        }
        foreach ($namespaces as $prefix => $namespaceUri) {
            $this->defineNamespace($namespaceUri, $prefix);
        }
    }

    /**
     * Define a namespace.
     *
     * @param  string $nsUri
     * @param  string $prefix
     * @return void
     */
    public function defineNamespace ($nsUri, $prefix)
    {
        $this->nsBindings[$prefix] = $nsUri;
    }

    /**
     * Define a property.
     *
     * @param  string  $name
     * @param  string  $expr
     * @return void
     */
    public function defineProperty ($name, $expr)
    {
        $this->properties[$name] = $expr;
    }

    /**
     * {@inheritDoc}
     */
    public function createResultFromExternalData ($record)
    {
        if (!$record instanceof DOMElement) {
            $type = is_object($record) ? get_class($record) : gettype($record);
            throw new InvalidArgumentException(sprintf('Unexpected type of argument: DOMElement, %s', $type));
        }
        $processor = $this->getXPathProcessor($record->ownerDocument);
        $result = new Result();
        foreach ($this->properties as $name => $expr) {
            if (is_array($expr) or $expr instanceof Traversable) {
                $value = array();
                foreach ($expr as $subExpr) {
                    $value []= $processor->evaluate($expr, $record);
                }
            } else {
                $value = $processor->evaluate($expr, $record);
            }
            $result->set($name, $value);
        }
        return $result;
    }

    /**
     * Return XPath processor for document.
     *
     * @param  DOMDocument $document
     * @return DOMXPath
     */
    private function getXPathProcessor (DOMDocument $document)
    {
        if (!$this->processorCache->contains($document)) {
            $processor = new DOMXPath($document);
            foreach ($this->nsBindings as $prefix => $namespaceUri) {
                $processor->registerNamespace($prefix, $namespaceUri);
            }
            $this->processorCache->attach($document, $processor);
        }
        return $this->processorCache->offsetGet($document);
    }
}