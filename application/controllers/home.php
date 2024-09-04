<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends App_Controller
{
	public function __construct()
	{
		parent::__construct();
                 //date_default_timezone_set('America/Los_Angeles');
	}

	public function index()
	{
		$this->body_class[] = 'home';
 //date_default_timezone_set('Europe/Berlin');
		$this->page_title = 'Welcome!';

		$this->current_section = 'home';
                                   //$this->load->library('rssparser');   // load library
			
			// codes to fetch feeds
			
			//thedieline feed url 
			/*$feedurl1 = 'http://feeds.feedblitz.com/thedieline';
			$data["feeddieline"] = $this->get_feeds($feedurl1,NULL);
			$data["feeddieline_genre"] = "Interior design";
			$data["feeddieline_class"] = "interiordesign_list";
			
			
			$feedurl2 = 'http://feeds.feedburner.com/design-milk';
			$data["designmilk"] = $this->get_feeds($feedurl2,70);
			$data["designmilk_genre"] = "Interior design";
			$data["designmilk_class"] = "interiordesign_list";
			
			
			$feedurl3  =  'http://www.wamda.com/feed/all';
			$data["wamda"] =  $this->get_feeds($feedurl3,70);
			$data["wamda_genre"] = "Digital";
			$data["wamda_class"] = "digital_list";
			
			$feedurl4 = 'http://feeds.mashable.com/Mashable';
			$data["mashable"] = $this->get_feeds($feedurl4,70);
			$data["mashable_genre"] = "Digital";
			$data["mashable_class"] = "digital_list";
			$data["genre"]  =  Array("digital"=>"Digital","interiordesign"=>"Interior design","typography"=>"Typography","technology"=>"Technology");  */
			
		$this->render_page('home/index');
	}
	



    public function get_feeds($feedurl,$noofwords) 
{
    // Load RSS Parser
    $this->load->library('rssparser');

    // Get 6 items from feed url
    $rss = $this->rssparser->set_feed_url($feedurl)->set_cache_life(30)->getFeed(10);
    $i=0;
    foreach ($rss as $item)
    {
        $result[$i]['title'] =  $item['title'];
      if($noofwords  != NULL) // no of word to be displayed in the feed. Thae value is passed while calling this function in the index function
                    { 
        
                    $trimmed_description =   implode(' ', array_slice(explode(' ', $item['description']), 0, $noofwords));	
                    $trimmed_description_stripped  =  strip_tags($trimmed_description,'<p><div><a></a><img>');	
                    }	
	   else 
	   {
        
                    $trimmed_description_stripped =  $item['description'];
                }
       $result[$i]['description']=  $trimmed_description_stripped;				
       $result[$i]['author']=  $item['author'];    
       $result[$i]['pubDate']=  $item['pubDate'];
       $result[$i]['link']=  $item['link'];
        $i++;
    }
	
	return $result;
}  

	/*public function get_design_news() 
	{
		// Load RSS Parser
		$this->load->library('rssparser');

		// Get RSS
		$rss[] = $this->rssparser->set_feed_url('http://feeds.feedblitz.com/thedieline')->set_cache_life(30)->getFeed(8);
		$rss[] = $this->rssparser->set_feed_url('http://feeds.feedburner.com/design-milk')->set_cache_life(30)->getFeed(5);
		$i = 0;
		$k = 0;
		foreach ($rss as $feed)
			{
				foreach ($feed as $item)
				{
					$result[$k]['title'] =  $item['title'];
					$trimmed_description = implode(' ', array_slice(explode(' ', $item['description']), 0, 10));
					print_r($trimmed_description);
					$result[$k]['description']=  $trimmed_description."...";
					$result[$k]['author']=  $item['author'];    
					$result[$k]['pubDate']=  $item['pubDate'];
					$result[$k]['link']=  $item['link'];
					$k++;
				}
		$i++;
		}
	print_r($result);
	return $result;
}*/
}