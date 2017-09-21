<?php

use Silex\WebTestCase;

class controllersTest extends WebTestCase
{
    public function testGetHomepage()
    {
        $client = $this->createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertContains('Twig - Assignment #2', $crawler->filter('title')->text());
        $this->assertContains('Welcome to', $crawler->filter('body')->text());
        $this->assertContains('Assignment #2', $crawler->filter('body')->text());
        $this->assertContains('!', $client->getResponse()->getContent());
    }

    public function test404page()
    {
        $client = $this->createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/nothere');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertContains('NotFoundHttpException', $crawler->filter('body')->text());
        $this->assertContains("Sorry, the page you are looking for could not be found.", $crawler->filter('body')->text());
    }

    public function testCookiesTemplate()
    {
        $client = $this->createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertContains('Time: ', $crawler->filter('body')->text());
        $this->assertContains('Time ', $crawler->filter('body')->text());
        $this->assertContains('cookies', $crawler->filter('body')->text());
    }

    public function createApplication()
    {
        $app = require __DIR__.'/../src/app.php';
        require __DIR__.'/../config/dev.php';
        require __DIR__.'/../src/controllers.php';
        $app['session.test'] = true;

        return $this->app = $app;
    }
}
