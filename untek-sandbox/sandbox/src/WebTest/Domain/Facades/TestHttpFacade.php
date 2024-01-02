<?php

namespace Untek\Sandbox\Sandbox\WebTest\Domain\Facades;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelBrowser;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Process\PhpProcess;
use Untek\Sandbox\Sandbox\WebTest\Domain\Encoders\IsolateEncoder;
use Untek\Sandbox\Sandbox\WebTest\Domain\Libs\ConsoleHttpKernel;

class TestHttpFacade
{

    public static function createHttpKernel(string $pathToIsolated = null, string $kernelClass = null): HttpKernelInterface
    {
        $encoder = new IsolateEncoder();
        return new ConsoleHttpKernel($encoder, $pathToIsolated, $kernelClass);
    }

    private static function createHttpKernelBrowser(string $pathToIsolated = null, string $kernelClass = null): HttpKernelBrowser
    {
        $httpKernel = self::createHttpKernel($pathToIsolated, $kernelClass);
        $httpKernelBrowser = new HttpKernelBrowser($httpKernel);
        $httpKernelBrowser->followRedirects();
        return $httpKernelBrowser;
    }

    /*public static function handleRequestViaIsolateKernel(Request $request): Response
    {
        $kernel = self::createHttpKernel();
        return $kernel->handle($request);
    }*/

    public static function handleRequestViaBrowser(Request $request, string $pathToIsolated = null, string $kernelClass = null): Response
    {
        $httpKernelBrowser = self::createHttpKernelBrowser($pathToIsolated, $kernelClass);
        $httpKernelBrowser->request(
            $request->getMethod(),
            $request->getUri(),
            $request->request->all(),
            [],
            $request->server->all(),
            $request->getContent()
        );
        return $httpKernelBrowser->getResponse();
    }

    public static function handleRequestViaPhpProcess(Request $request, string $kernelFactory, array $env = []): Response
    {
        $serializedRequest = var_export(serialize($request), true);
//        $kernelFactory = \App\Application\Common\Libs\HttpServer::class;

        $serializedEnv = var_export(serialize($env), true);

        $code = <<<EOF
<?php

use Symfony\Component\Console\Application;
use Untek\Core\App\Libs\ZnCore;
use Untek\Core\Container\Libs\Container;
use Untek\Sandbox\Sandbox\WebTest\Commands\RequestReceiverCommand;

\$_SERVER['MICRO_TIME'] = microtime(true);
\$_SERVER['REQUEST_ID'] = uniqid();

require __DIR__ . '/vendor/autoload.php';

\$container = new Container();
\$Untek\Core = new ZnCore(\$container);
\$znCore->init();

\$request = unserialize($serializedRequest);

\$appFactory = new $kernelFactory(\$container);
\$env = unserialize($serializedEnv);
foreach(\$env as \$key => \$value) {
    putenv(\$key . '=' . \$value);
}
\$framework = \$appFactory->createKernelInstance(\$request);

\$response = \$framework->handle(\$request);

echo serialize(\$response);

EOF;

        $process = new PhpProcess($code);
        $process->run();
        $serializedResponse = $process->getOutput();
        $response = unserialize($serializedResponse);
        return $response;
    }
}
