<?php

namespace Bonfix\DaliliAnalytics;

use App\Http\Controllers\Controller;
use Bonfix\DaliliAnalytics\Models\AnalyticsSession;
use Bonfix\DaliliAnalytics\Models\AnalyticsSessionActivity;
use Bonfix\DaliliAnalytics\Models\AnalyticsSessionBasket;
use Bonfix\DaliliAnalytics\Models\AnalyticsSessionComparison;
use Bonfix\DaliliAnalytics\Models\AnalyticsSessionItem;
use Bonfix\DaliliAnalytics\Models\AnalyticsSessionOrder;
use Bonfix\DaliliAnalytics\Models\AnalyticsSessionPage;
use Bonfix\DaliliAnalytics\Models\AnalyticsSessionSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnalyticsSessionController extends Controller
{
    public function store(Request $request)
    {
        $analyticsInput = $request->analyticsObject;

        $analyticsObject = json_decode(json_encode((object) $analyticsInput), FALSE);

        //meta
        $meta = $analyticsInput['meta'];

        //loop through the sessions
        $as = null;
        foreach ($analyticsObject->sessions as $sessionObject) {
            //create a session model object
            $as = [];//new stdClass();;//new AnalyticsSession();
            $as['app'] = 'DALILI';
            $as['duration'] = $sessionObject->duration;
            //common meta
            $as = array_merge($as, $meta);
            //session meta
            $as = array_merge($as, (array) $sessionObject->meta);
            //save session
            $sessionModel = AnalyticsSession::create($as);

            //activities
            $this->createActivities($sessionObject->activities, $sessionModel->id);
            //pages
            $this->createPages($sessionObject->pages, $sessionModel->id);
            //searches
            $this->createSearches($sessionObject->searches, $sessionModel->id);
            //orders
            $this->createOrders($sessionObject->orders, $sessionModel->id);
            //createComparisons
            $this->createComparisons($sessionObject->comparisons, $sessionModel->id);
            //saved baskets
            $this->createSavedBaskets($sessionObject->saved_baskets, $sessionModel->id);
        }
        //Log::error(json_encode($act->id));


        return response()->success();
    }
    function createActivities($activities, $sessionId){
        foreach ($activities as $activity) {
            AnalyticsSessionActivity::create($this->createColumnsDataArray($activity, $sessionId));
        }
    }
    function createPages($items, $sessionId){
        foreach ($items as $item) {
            AnalyticsSessionPage::create($this->createColumnsDataArray($item, $sessionId));
        }
    }
    function createSearches($items, $sessionId){
        foreach ($items as $item) {
            AnalyticsSessionSearch::create($this->createColumnsDataArray($item, $sessionId));
        }
    }
    function createComparisons($items, $sessionId){
        foreach ($items as $item) {
            $basketItems = $item->items;
            $res = AnalyticsSessionComparison::create($this->createColumnsDataArray($item, $sessionId));
            //save items in the basket
            $this->saveItemsArray($basketItems, 'AnalyticsSessionComparison', $res->id);
        }
    }
    function createOrders($items, $sessionId){
        foreach ($items as $item) {
            $basketItems = $item->items;
            $res = AnalyticsSessionOrder::create($this->createColumnsDataArray($item, $sessionId));
            //save items in the basket
            $this->saveItemsArray($basketItems, 'AnalyticsSessionOrder', $res->id);
        }
    }
    function createSavedBaskets($items, $sessionId){
        foreach ($items as $item) {
            $basketItems = $item->items;
            $res = AnalyticsSessionBasket::create($this->createColumnsDataArray($item, $sessionId));
            //save items in the basket
            $this->saveItemsArray($basketItems, 'AnalyticsSessionBasket', $res->id);
        }
    }

    function saveItemsArray($items, $type, $typeId){
        foreach ($items as $item) {
            $itemArray = (array)$item;
            $itemArray['activity_id'] = $typeId;
            $itemArray['activity'] = $type;
            Log::error(json_encode(array_keys($itemArray)));
            //save
            AnalyticsSessionItem::create($itemArray);
        }
    }
    function createColumnsDataArray($item, $sessionId){
        $itemArray = (array)$item;
        $itemArray['session_id'] = $sessionId;
        //correct timestamp => at
        $itemArray['at'] = $item->timestamp;
        unset($itemArray['timestamp']);
        //remove items array
        if(isset($itemArray['items']))
            unset($itemArray['items']);
        Log::error(json_encode(array_keys($itemArray)));
        return $itemArray;
    }
}
