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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Reconciliation webservice controller.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class Controller
{
    /**
     * Reconciliation service.
     *
     * @var ServiceInterface
     */
    private $serivce;

    /**
     * Constructor.
     *
     * @param  ServiceInterface $service
     * @return void
     */
    public function __construct (ServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Handle request and return response.
     *
     * @param  Request $request
     * @return Response
     */
    public function handle (Request $request)
    {
        if ($request->getMethod() === 'GET' or $request->getMethod() === 'HEAD') {
            $query = $request->query;
        } else if ($request->getMethod() === 'POST') {
            $query = $request->request;
        } else {
            return new Response('Method Not Allowed', 405, array('Allow' => 'GET, HEAD, POST'));
        }

        if ($query->has('query') and $query->has('queries')) {
            return new Response('You must not use both the "query" and the "queries" parameter', 400);
        }

        $response = new JsonResponse();
        if ($query->has('callback')) {
            $callback = $query->get('callback');
            try {
                $response->setCallback($callback);
            } catch (InvalidArgumentException $e) {
                return new Response('Unable to initiate JSONP callback', 400);
            }
        }

        if ($query->has('query')) {
            // Single Query Mode
            $queries = array(null => $this->deserializeSingleQuery($query->get('query')));
            $payload = $this->service->reconcile($queries);
        } else if ($query->has('queries')) {
            // Multi Query Mode
            $queries = $this->deserializeMultiQuery($query->get('queries'));
            $payload = $this->service->reconcile($queries);
        } else {
            // Provide description
            $payload = $this->service->getDescription();
        }

        $response->setData($payload);

        return $response;
    }

    /**
     * Deserialize single query.
     *
     * @param  string $query
     * @return Query
     */
    public function deserializeSingleQuery ($query)
    {
        if ($query and $query[0] === '{') {
            $data = json_decode($query, true);
            if (is_array($data)) {
                return Query::createFromArray($data);
            }
        }
        return Query::createFromArray(array('query' => $query));
    }

    /**
     * Deserialize multiple queries.
     *
     * @param  string $queries
     * @return array
     */
    public function deserializeMultiQuery ($queries)
    {
        $data = json_decode($queries, true);
        if (!is_array($data)) {
            throw new InvalidArgumentException("Invalid query data: {$queries}");
        }

        $queries = array();
        foreach ($data as $key => $query) {
            $queries[$key] = Query::createFromArray($query);
        }
        return $queries;
    }
}