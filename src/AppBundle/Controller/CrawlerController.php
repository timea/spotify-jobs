<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * Controller used to manage the crawler.
 *
 * @Route("/jobs")
 *
 */
class CrawlerController extends Controller
{
    /**
     * @Route("/", defaults={"page": "1", "_format"="html"}, name="blog_index")
     * @Route("/rss.xml", defaults={"page": "1", "_format"="xml"}, name="blog_rss")
     * @Route("/page/{page}", defaults={"_format"="html"}, requirements={"page": "[1-9]\d*"}, name="blog_index_paginated")
     * @Method("GET")
     * @Cache(smaxage="10")
     *
     * NOTE: For standard formats, Symfony will also automatically choose the best
     * Content-Type header for the response.
     * See http://symfony.com/doc/current/quick_tour/the_controller.html#using-formats
     */
    public function indexAction($page, $_format)
    {
        $jobs = $this->getJobsFromSpoty();
        //$this->crawl($site);
        return $this->render('base.'.$_format.'.twig', ['jobs' => $jobs]);
        // $posts = $this->getDoctrine()->getRepository(Post::class)->findLatest($page);
        //
        // // Every template name also has two extensions that specify the format and
        // // engine for that template.
        // // See https://symfony.com/doc/current/templating.html#template-suffix
        // return $this->render('blog/index.'.$_format.'.twig', ['posts' => $posts]);
    }

    private function getJobsFromSpoty() {
        $html = file_get_contents('https://www.spotify.com/es/jobs/opportunities/all/all/singapore-singapore');

        $crawler = new Crawler($html);

        // apply css selector filter
        $job_data = $crawler->filter('h3.job-title a')->extract(array('_text', 'href'));

        var_dump($job_data);

        return $job_data;
    }

}
