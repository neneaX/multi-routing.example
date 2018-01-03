<?php
namespace Example;

use Illuminate\Contracts\Container\Container as ContainerInterface;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcherInterface;

use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Events\Dispatcher as EventsDispatcher;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use MultiRouting\Adapters\Main\Adapter as MainAdapter;
use MultiRouting\Adapters\Main\Adapter;
use MultiRouting\Adapters\Soap\Adapter as SoapAdapter;
use MultiRouting\Adapters\JsonRpc\Adapter as JsonRpcAdapter;
use MultiRouting\AdapterService;
use MultiRouting\Router as MultiRouter;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class App
{
    /**
     * The application IoC container
     *
     * @var Container
     */
    protected $container;

    /**
     * @var EventsDispatcherInterface
     */
    protected $eventsDispatcher;

    /**
     * The application router
     *
     * @var MultiRouter
     */
    protected $router;

    /**
     * @return static
     */
    public static function build()
    {
        $container = new Container();
        $eventsDispatcher = new EventsDispatcher();

        $adapterService = new AdapterService($container);
        $baseRouter = new Router($eventsDispatcher, $container);
        $multiRouter = new MultiRouter($baseRouter, $adapterService);

        return new static(
            $container,
            $eventsDispatcher,
            $multiRouter
        );
    }

    /**
     * App constructor.
     *
     * @param ContainerInterface $container
     * @param Dispatcher         $eventsDispatcher
     * @param MultiRouter        $multiRouter
     *
     * @throws \Exception
     */
    public function __construct(
        ContainerInterface $container,
        EventsDispatcherInterface $eventsDispatcher,
        MultiRouter $multiRouter
    ) {
        $this->setContainer($container);
        $this->setEventsDispatcher($eventsDispatcher);
        $this->setRouter($multiRouter);

        $this->initRoutes();
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return EventsDispatcherInterface
     */
    public function getEventsDispatcher()
    {
        return $this->eventsDispatcher;
    }

    /**
     * @param EventsDispatcherInterface $eventsDispatcher
     */
    protected function setEventsDispatcher(EventsDispatcherInterface $eventsDispatcher)
    {
        $this->eventsDispatcher = $eventsDispatcher;
    }

    /**
     * @return MultiRouter
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Set the application router
     *
     * @param MultiRouter $multiRouter
     *
     * @throws \Exception
     */
    protected function setRouter(MultiRouter $multiRouter)
    {
        $this->router = $multiRouter;

        $this->router->allowAdapters([
            MainAdapter::name,
            JsonRpcAdapter::name,
            SoapAdapter::name,
            'Rest'
        ]);
    }

    protected function initRoutes()
    {
        require __DIR__ . '/resources/routes.php';
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function run()
    {
        $request = Request::createFromBase(Request::createFromGlobals());

        return $this->handle($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        try {
            $response = $this->router->dispatch($request);
        } catch (NotFoundHttpException $e) {
            // parse request to return an error according to protocol
            $response = new Response('Page not found', 404);
        } catch (MethodNotAllowedHttpException $e) {
            $response = new Response('Page not found', 404);
        }

        $response->sendHeaders();
        $response->send();

        return $response;
    }
}

