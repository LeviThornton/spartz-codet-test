<?php
/**
 * Simple REST controller for the user actions
 */
class UsersController extends BaseController {

   /**
    * output a list of all the cities for this user id
    * @param  [type] $id [description]
    * @return [type]     [description]
    */
    public function getIndex($id) {
			
		$results = DB::table('visited')
        ->join('cities', 'visited.cid', '=', 'cities.id')
        ->where('visited.uid', '=', $id)
        ->get();

        /*
        check for results
         */
        if(count($results)>0) {

        	$results['success'] = true;
			$output = $results;

        } else $output = array("success"=>false,"message"=>"No results");

		// now return to user
        return Response::json($output);
    }

    /**
     * set some locations so the user can indicate they have visited a particular city
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function postIndex($id) {
	
		// get all the input
        $data = Input::all();

        // do some basic checking
        if( (count($data) > 0) && (count($data) <= 100) ) {
    
			foreach($data as $entry) {

				$city = $entry['city'];
				$state = $entry['state'];

				// as we did before, lets compare each entry to our known cities
				$city = DB::table('cities')
	            ->where('cities.state', '=', $state)
	            ->where('cities.name', '=', $city)
	            ->get();

	            if($city[0]->id) {

	            	$cid = $city[0]->id;

	            	$visited = new Visited;
	            	$visited->uid = $id;
	            	$visited->cid = $cid;
	            	$visited->save();
	            }
			}
        		// done and done
        		$output = array("success"=>true,"message"=>"Visits recorded");

        } else $output = array("success"=>false,"message"=>"Incorrect input type");
        
        // little extra logic just to give the end user even more details
        if(count($data) > 100) $output = array("success"=>false,"message"=>"Max a 100 inserts per query");
        
        // now return to user
        return Response::json($output);
    }
}