<?php
/**
 * Simple REST controller for the city actions
 */
class StatesController extends BaseController {

    /**
     *  output a list of cities in the state
     * @param  [type] $state [description]
     * @return [type]        [description]
     */
    public function getStateCities($state)
    {
        $results = DB::table('cities')
        ->where('cities.state', '=', $state)
        ->get();

        /*
        check for results
         */
        if(count($results)>0) {

            $output = $results;

        } else $output = array("success"=>false,"message"=>"No results");

        // now return to user
        return Response::json($output);
    }
    /**
     * output a list of all cities within x radius of a city
     * @param  [type] $state [description]
     * @param  [type] $city  [description]
     * @return [type]        [description]
     */
    public function getCitiesByRadius($state, $city)
    {
        // get the radius
        $radius = Input::get('radius');

        if($radius) {

            /*
            a little exausted from work, its 2am here,
            so I cant think up how I create my querry to 
            get the city and its lat/long at the same time to perform one query,
            so doing it in two parts - sorry.
             */
            $city = DB::table('cities')
            ->where('cities.state', '=', $state)
            ->where('cities.name', '=', $city)
            ->get();

            if($city[0]->latitude && $city[0]->longitude) {
                
                $latitude   = $city[0]->latitude;
                $longitude  = $city[0]->longitude;
            /*
            now that we have the city lat and long lets go find some 
             cities in the area, including the one we posted with above, 
             did we want that excluded(?)
             */
             $output = DB::select( DB::raw("SELECT *, ( 3959 * acos ( cos ( radians(:latitude) )
                        * cos( radians( latitude ) ) * cos( radians( longitude )
                        - radians(:longitude) ) + sin ( radians(:latitude) )
                        * sin( radians( latitude ) ) )) AS distance FROM cities HAVING distance <= :miles"), 
                array('miles' => $radius), "longitude" => $longitude, "latitude" => $latitude);


            } else $output = array("success"=>false,"message"=>"Failed to match city");

        } else $output = array("success"=>false,"message"=>"No radius provided");
  
        // now return to user
        return Response::json($output);
    }

}