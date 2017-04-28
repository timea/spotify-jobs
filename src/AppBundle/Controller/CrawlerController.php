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
    public function indexAction($page, $_format, Request $request)
    {
        $jobs = $this->getJobsFromSpoty('https://www.spotify.com/es/jobs/opportunities/all/all/singapore-singapore');
        $locale = $request->getLocale();
        return $this->render('jobs/jobs.'.$_format.'.twig', ['jobs' => $jobs, 'locale'=>$locale]);
    }
    /**
     * pulling jobs from a specific URL
     */
    private function getJobsFromSpoty(string $url) {
        $html = file_get_contents($url);

        $crawler = new Crawler($html);

        // apply css selector filter, taking out the title and the link
        $job_data = $crawler->filter('h3.job-title a')->extract(array('_text', 'href'));
        $job_data2 = $crawler->filter('.job-listing p')->extract(array('_text'));

        $merged = array();
        for ($i=0; $i < count($job_data); $i++) {
          $tmp = array("title"=>$job_data[$i][0], "link"=>$job_data[$i][1], "description"=>$job_data2[$i]);
          array_push($merged, $tmp);
        }
        
        return $merged;
    }

}
