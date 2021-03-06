<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Factories\ResponseFactory;
use App\Http\Requests\Api\RideRequest;
use App\Repositories\RidesRepository;
use App\Transformers\Api\RidesTransformer;
use App\Transformers\Api\RideTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class RidesController extends ApiController
{
    public const RIDE = 'ride';

    /** @var RidesRepository */
    private $ridesRepository;

    public function __construct(ResponseFactory $response, RidesRepository $ridesRepository)
    {
        parent::__construct($response);
        $this->ridesRepository = $ridesRepository;
    }

    public function index() : Response
    {
        $rides   = $this->ridesRepository->get();
        $manager = $this->createManager();

        $resources = new Collection($rides, new RidesTransformer(), 'Rides');
        $resources->setMeta($this->createMetaData());

        $data = $manager->createData($resources)->toArray();

        $etag = $this->createEtag($data);

        return $this->resourcesFoundResponse($data, $etag);
    }

    public function fetch(Request $request) : Response
    {
        $ride = $this->ridesRepository->fetch($request->id);

        if (! $ride) {
            return $this->resourceNotFoundResponse(self::RIDE, (int) $request->id);
        }

        $manager = $this->createManager();

        if ($request->query('includes') === 'park') {
            $manager->parseIncludes('park');
        }

        $resource = new Item($ride, new RideTransformer(), 'Rides');
        $resource->setMeta($this->createMetaData());

        $data = $manager->createData($resource)->toArray();
        $etag = $this->createEtag($data);

        return $this->resourcesFoundResponse($data, $etag);
    }

    public function create(RideRequest $request) : Response
    {
        $ride = $this->ridesRepository->create($request);

        return $this->resourceCreatedResponse($ride, self::RIDE);
    }

    public function edit(RideRequest $request) : Response
    {
        $ride = $this->ridesRepository->edit($request);

        return $this->resourceCreatedResponse($ride, self::RIDE);
    }


    public function destroy(Request $request) : Response
    {
        $this->ridesRepository->destroy($request->id);

        return $this->resourceDeletedResponse();
    }
}
