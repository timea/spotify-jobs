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
     * @Route("/", name="blog_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $jobs = $this->getJobs('https://www.spotify.com/es/jobs/opportunities/all/all/singapore-singapore');
        $locale = $request->getLocale();
        $username = $this->getUser()->getUsername();
        $alert = count($jobs);

        return $this->render(
            'jobs/jobs.html.twig',
          [
            'jobs' => $jobs,
            'locale' => $locale,
            'username' => $username,
            'alert' => $alert
          ]);
    }
    /**
     * pulling jobs from a specific URL
     */
    private function getJobs(string $url) {
        $html = file_get_contents($url);

        $crawler = new Crawler($html);

        // apply css selector filter, taking out the title and the link
        $job_title = $crawler->filter('h3.job-title a')->extract(array('_text', 'href'));
        // apply css selector filter, taking out description
        $job_description = $crawler->filter('.job-listing p')->extract(array('_text'));

        //declaring in advance the array we will return
        $merged = array();

        //making sure the file_get_contents returned content
        if($job_title && $job_description) {
          for ($i=0; $i < count($job_title); $i++) {
            $tmp = array('title'=>$job_title[$i][0], 'link'=>$job_title[$i][1], 'description'=>$job_description[$i]);
            array_push($merged, $tmp);
          }
        }

        //if file_get_contents failed for some reason, like no internet, here is some dummy data to fill the table for the sake of this test, not for peoduction
        if(!$merged) {
          $tmp = array(
            'title'=>'Revenue Operations Manager',
            'link'=>'/es/jobs/view/on214fwe/',
            'description' => 'Spotify is looking for a Revenue Operations Manager support the APAC Order To Cash (OTC) operations, reporting to Global OTC team, located in New York. We believe that you are a positive, driven and accurate self-starter who enjoy working in a dynamic environment. In return, we will offer you a stimulating and challenging role in an exciting and fast-paced international organization where development opportunities are endless.This role will be located in our Singapore office.');
          array_push($merged, $tmp);
            $tmp = array(
              'title' => 'APAC Payroll and Expenses Accountant',
              'link' => '/es/jobs/view/oKfj4fw6/',
              'description' => 'Spotify is expanding in Asia Pacific region and looking to create a regional hub from the Singapore office to serve this unique and growing region and is looking for a Payroll & Expenses Accountant. This is an amazing opportunity for someone looking for growth and challenges, and be part of a global team in one of the fastest growing tech companies. The position is based in Singapore.');
          array_push($merged, $tmp);
        }
        return $merged;
    }

}
