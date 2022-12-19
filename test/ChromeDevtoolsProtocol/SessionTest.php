<?php
namespace ChromeDevtoolsProtocol;

use ChromeDevtoolsProtocol\Exception\ErrorException;
use ChromeDevtoolsProtocol\Instance\Launcher;
use ChromeDevtoolsProtocol\Model\Network\GetCookiesRequest;
use ChromeDevtoolsProtocol\Model\Page\NavigateRequest;

class SessionTest extends TestCase
{

	public function testSessionIsolation()
	{
		$ctx = Context::withTimeout(Context::background(), 10);
		$launcher = new Launcher();
		$this->setExecutable($launcher);
		$instance = $launcher->launch($ctx);
		try {
			$session = $instance->createSession($ctx);
			try {
				$session->page()->enable($ctx);
				$session->page()->navigate(
					$ctx,
					NavigateRequest::builder()
						->setUrl("https://httpbin.org/cookies/set?myCookie=1")
						->build()
				);
				$session->page()->awaitLoadEventFired($ctx);

				$cookies = $session->network()->getCookies(
					$ctx,
					GetCookiesRequest::builder()
						->setUrls(["https://httpbin.org/"])
						->build()
				);

				$this->assertCount(1, $cookies->cookies);
				$this->assertEquals("myCookie", $cookies->cookies[0]->name);
				$this->assertEquals("1", $cookies->cookies[0]->value);

			} finally {
				$session->close();
			}

			unset($session);

			$session = $instance->createSession($ctx);
			try {
				$session->page()->enable($ctx);
				$session->page()->navigate(
					$ctx,
					NavigateRequest::builder()
						->setUrl("https://httpbin.org/cookies/set?myDifferentCookie=2")
						->build()
				);
				$session->page()->awaitLoadEventFired($ctx);

				$cookies = $session->network()->getCookies(
					$ctx,
					GetCookiesRequest::builder()
						->setUrls(["https://httpbin.org/"])
						->build()
				);

				$this->assertCount(1, $cookies->cookies);
				$this->assertEquals("myDifferentCookie", $cookies->cookies[0]->name);
				$this->assertEquals("2", $cookies->cookies[0]->value);

			} finally {
				$session->close();
			}

		} finally {
			$instance->close();
		}
	}

	public function testErrorHandling()
	{
		$this->expectException(ErrorException::class);

		$ctx = Context::withTimeout(Context::background(), 10);
		$launcher = new Launcher();
		$this->setExecutable($launcher);
		$instance = $launcher->launch($ctx);
		try {
			$session = $instance->createSession($ctx);
			try {

				$session->executeCommand($ctx, "SomeCommand.thatDoesNotExist", new \stdClass());

			} finally {
				$session->close();
			}
		} finally {
			$instance->close();
		}
	}

}
