<?php

namespace App\Http\Controllers\Seller;

use App\Classes\Helper\CommonUtil;
use App\Enums\ContactBoardStatus;
use App\Http\Controllers\Controller;
use App\Models\BoardContact;
use App\Models\Contact;
use App\Models\ContactLog;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MetaTag;
use Illuminate\Support\Facades\Storage;

class LeaderboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        MetaTag::set('title', config('app.rankup.company_title')." - ".__('Leaderboards'));
        MetaTag::set('description', config('app.rankup.company_title').' Leaderboards Page');
        MetaTag::set('image', asset(config('app.rankup.company_logo_path')));
        return view('seller.leaderboard.index');
    }

    /**
     * Leaderboard common data
     *
     * @param array $requestData
     *
     * @return array
     */
    public function leaderBoardCommonData($requestData) 
    {
        $leaderBoardData = [];
        $leaderBoardData['switchType'] = 'month';
        $todayUserEndDateTime = getCarbonTodayEndDateTimeForUser();

        if (isset($requestData['dateFilterType'])) {
            if ($requestData['dateFilterType'] == 'Week') {
                $leaderBoardData['switchType'] = 'Week';
                $leaderBoardData['start'] = convertDateFormatWithTimezone($todayUserEndDateTime->clone()->startOfWeek()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
                $leaderBoardData['end'] = convertDateFormatWithTimezone($todayUserEndDateTime->clone()->endOfWeek()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
                $leaderBoardData['pageName'] = '<h4>' . __('Leaderboards') . ' - ' . __("per week") . ' </h4>';
            }
            if ($requestData['dateFilterType'] == 'Month') {
                $leaderBoardData['switchType'] = 'Month';
                $leaderBoardData['start'] = convertDateFormatWithTimezone($todayUserEndDateTime->clone()->startOfMonth()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
                $leaderBoardData['end'] = convertDateFormatWithTimezone($todayUserEndDateTime->clone()->endOfMonth()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
                $leaderBoardData['pageName'] = '<h4>' . __('Leaderboards') . ' - ' . __("per month") . ' </h4>';
            }
            if ($requestData['dateFilterType'] == 'customRange') {
                $leaderBoardData['switchType'] = 'customRange';
                $leaderBoardData['start'] = convertDateFormatWithTimezone(Carbon::parse($requestData['start'])->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
                $leaderBoardData['end'] = convertDateFormatWithTimezone(Carbon::parse($requestData['end'])->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
                $leaderBoardData['pageName'] = '<h4>' . __('Leaderboards') . ' - ' . __("per selected Dates") . ' </h4>';
            }
            if ($requestData['dateFilterType'] == 'Day') {
                $leaderBoardData['switchType'] = 'Day';
                $leaderBoardData['start'] = $todayUserEndDateTime->clone()->subDays(1)->format('Y-m-d H:i:s');
                $leaderBoardData['end'] = $todayUserEndDateTime->clone()->format('Y-m-d H:i:s');
                $leaderBoardData['pageName'] = '<h4>' . __('Leaderboards') . ' - ' . __("per day") . ' </h4>';
            }
        }

        $leaderBoardData['year'] = $todayUserEndDateTime->translatedFormat('Y');

        $downlineIds = Auth::user()->getDownlineIds();
        $user = Auth::user();
        array_push($downlineIds, $user->id);

        $leaderBoardData['downlineIds'] = array_filter($downlineIds);
        return $leaderBoardData;
    }

    /**
     * Top presentations given: highest count of events created and completed
     *
     * @param Request $request
     *
     * @return array
     */
    public function presentationGivenData(Request $request)
    {
        $requestData = $request->all();
        $leaderBoardData = $this->leaderBoardCommonData($requestData);
        $switchType = $leaderBoardData['switchType'];
        $year = $leaderBoardData['year'];
        $start = $leaderBoardData['start'];
        $end = $leaderBoardData['end'];
        $data['pageName'] = $leaderBoardData['pageName'];
        $downlineIds = $leaderBoardData['downlineIds'];

        $presentation = $top10Presentation  = [];

        $presentation = Event::whereYear('created_at', $year)
            ->when($switchType == 'Week' || $switchType == 'Month', function ($q) use ($start,$end) {
                return $q->where('created_at', '>=', $start)
                    ->where('created_at', '<=', $end);
            })
            ->when($switchType == 'Day'  || $switchType == 'customRange', function ($q) use ($start,$end) {
                return $q->where('created_at', '>=', $start)
                    ->where('created_at', '<', $end);
            })
            ->whereRaw('user_id IN ('.getDownlinesStr(implode(',', $downlineIds)).')')
            ->selectRaw('user_id, count(user_id) as count')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $i = 1;
        foreach ($presentation as $key => $value) {
            $country = '';
            if (isset($value->user->country)) {
                $country = ', ' . $value->user->country;
            }
            $top10Presentation[$i++] = array(
                'count' => $value->count,
                'name' => $value->user->getFullName(),
                'image' => isset($value->user->thumbnail_image) && Storage::disk('public')->exists($value->user->thumbnail_image) ? CommonUtil::getUrl($value->user->thumbnail_image) : asset((config('app.rankup.company_default_image_file'))),
                'uplineName' => $value->user->getUplineName(),
                'location' => $value->user->city . $country,
            );
        }

        $data['view'] = view('seller.leaderboard._top_presentation_given', compact('top10Presentation'))->render();
        return $data;
    }

    /**
     * Top customer acquisition: highest count of contacts dragged in “Nouveau client”
     *
     * @param Request $request
     *
     * @return array
     */
    public function customerAcquisitionData(Request $request)
    {
        $requestData = $request->all();
        $leaderBoardData = $this->leaderBoardCommonData($requestData);
        $switchType = $leaderBoardData['switchType'];
        $year = $leaderBoardData['year'];
        $start = $leaderBoardData['start'];
        $end = $leaderBoardData['end'];
        $data['pageName'] = $leaderBoardData['pageName'];
        $downlineIds = $leaderBoardData['downlineIds'];

        $cAcquisition = $top10CAcquisition = [];

        $cAcquisition = ContactLog::where('status', ContactBoardStatus::NEW_CLIENT)
            ->whereRaw('user_id IN ('.getDownlinesStr(implode(',', $downlineIds)).')')
            ->when($switchType == 'Week' || $switchType == 'Month', function ($q) use ($start,$end) {
                return $q->where('created_at', '>=', $start)
                    ->where('created_at', '<=', $end);
            })
            ->when($switchType == 'Day'  || $switchType == 'customRange', function ($q) use ($start,$end) {
                return $q->where('created_at', '>=', $start)
                    ->where('created_at', '<', $end);
            })
            ->whereYear('created_at', $year)
            ->selectRaw('user_id, count(contact_id) as count')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        $i = 1;
        foreach ($cAcquisition as $key => $value) {
            $country = '';
            if (isset($value->user->user->country)) {
                $country = ', ' . $value->user->user->country;
            }
            $top10CAcquisition[$i++] = array(
                'count' => $value->count,
                'name' => $value->user->getFullName(),
                'image' => isset($value->user->thumbnail_image) && Storage::disk('public')->exists($value->user->thumbnail_image) ? CommonUtil::getUrl($value->user->thumbnail_image) : asset((config('app.rankup.company_default_image_file'))),
                'uplineName' => $value->user->getUplineName(),
                'location' => $value->user->city . $country,
            );

        }

        $data['view'] = view('seller.leaderboard._top_customer_acquisition', compact('top10CAcquisition'))->render();
        return $data;
    }

    /**
     * Top distributor acquisition: highest count of contacts dragged in “Nouveau distributer”
     *
     * @param Request $request
     *
     * @return array
     */
    public function distributorAcquisitionData(Request $request)
    {
        $requestData = $request->all();
        $leaderBoardData = $this->leaderBoardCommonData($requestData);
        $switchType = $leaderBoardData['switchType'];
        $year = $leaderBoardData['year'];
        $start = $leaderBoardData['start'];
        $end = $leaderBoardData['end'];
        $data['pageName'] = $leaderBoardData['pageName'];
        $downlineIds = $leaderBoardData['downlineIds'];

        $dAcquisition = $top10DAcquisition = [];

        $dAcquisition = ContactLog::where('status', ContactBoardStatus::NEW_DISTRIBUTOR)
            ->whereRaw('user_id IN ('.getDownlinesStr(implode(',', $downlineIds)).')')
            ->when($switchType == 'Week' || $switchType == 'Month', function ($q) use ($start,$end) {
                return $q->where('created_at', '>=', $start)
                    ->where('created_at', '<=', $end);
            })
            ->when($switchType == 'Day'  || $switchType == 'customRange', function ($q) use ($start,$end) {
                return $q->where('created_at', '>=', $start)
                    ->where('created_at', '<', $end);
            })
            ->whereYear('created_at', $year)
            ->selectRaw('user_id, count(contact_id) as count')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        $i = 1;
        foreach ($dAcquisition as $key => $value) {
            $country = '';
            if (isset($value->user->country)) {
                $country = ', ' . $value->user->country;
            }
            $top10DAcquisition[$i++] = array(
                'count' => $value->count,
                'name' => $value->user->getFullName(),
                'image' => isset($value->user->thumbnail_image) && Storage::disk('public')->exists($value->user->thumbnail_image) ? CommonUtil::getUrl($value->user->thumbnail_image) : asset((config('app.rankup.company_default_image_file'))),
                'uplineName' => $value->user->getUplineName(),
                'location' => $value->user->city . $country,
            );

        }

        $data['view'] = view('seller.leaderboard._top_distributor_acquisition', compact('top10DAcquisition'))->render();
        return $data;
    }

    /**
     * Top presentations: Rank presentations by how many people attended it (how many contacts were created from this event)
     *
     * @param Request $request
     *
     * @return array
     */
    public function presentationsData(Request $request)
    {
        $requestData = $request->all();
        $leaderBoardData = $this->leaderBoardCommonData($requestData);
        $switchType = $leaderBoardData['switchType'];
        $year = $leaderBoardData['year'];
        $start = $leaderBoardData['start'];
        $end = $leaderBoardData['end'];
        $data['pageName'] = $leaderBoardData['pageName'];
        $downlineIds = $leaderBoardData['downlineIds'];
        $pAttended = $top10pAttended = [];

        $pAttended = Contact::whereYear('created_at', $year)
            ->when($switchType == 'Week' || $switchType == 'Month', function ($q) use ($start,$end) {
                return $q->where('created_at', '>=', $start)
                    ->where('created_at', '<=', $end);
            })
            ->when($switchType == 'Day'  || $switchType == 'customRange', function ($q) use ($start,$end) {
                return $q->where('created_at', '>=', $start)
                    ->where('created_at', '<', $end);
            })
            ->whereRaw('user_id IN ('.getDownlinesStr(implode(',', $downlineIds)).')')
            ->whereNotNull('event_id')
            ->selectRaw('event_id, count(event_id) as count')
            ->groupBy('event_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $i = 1;
        foreach ($pAttended as $key => $value) {
            $country = '';
            if (isset($value->event->user->country)) {
                $country = ', ' . $value->event->user->country;
            }
            $top10pAttended[$i++] = array(
                'count' => $value->count,
                'name' => $value->event->user->getFullName(),
                'image' => isset($value->event->user->thumbnail_image) && Storage::disk('public')->exists($value->event->user->thumbnail_image) ? CommonUtil::getUrl($value->event->user->thumbnail_image) : asset((config('app.rankup.company_default_image_file'))),
                'uplineName' => $value->event->user->getUplineName(),
                'location' => $value->event->user->city . $country,
            );

        }
        $data['view'] = view('seller.leaderboard._top_presentation', compact('top10pAttended'))->render();
        return $data;
    }

    /**
     * Message sent data
     *
     * @param Request $request
     *
     * @return array
     */
    public function messageSentData(Request $request)
    {
        $requestData = $request->all();
        $leaderBoardData = $this->leaderBoardCommonData($requestData);
        $switchType = $leaderBoardData['switchType'];
        $year = $leaderBoardData['year'];
        $start = $leaderBoardData['start'];
        $end = $leaderBoardData['end'];
        $data['pageName'] = $leaderBoardData['pageName'];
        $downlineIds = $leaderBoardData['downlineIds'];
        $mEnvoy = $top10mEnvoy = [];

        /**  START : Top messages envoyés: highest count of contacts dragged in “Message envoyé” */
        $mEnvoy = ContactLog::
            where('status', ContactBoardStatus::MESSAGE_SENT)
            ->whereRaw('user_id IN ('.getDownlinesStr(implode(',', $downlineIds)).')')
            ->when($switchType == 'Week' || $switchType == 'Month', function ($q) use ($start,$end) {
                return $q->where('created_at', '>=', $start)
                    ->where('created_at', '<=', $end);
            })
            ->when($switchType == 'Day'  || $switchType == 'customRange', function ($q) use ($start,$end) {
                return $q->where('created_at', '>=', $start)
                    ->where('created_at', '<', $end);
            })
            ->whereYear('created_at', $year)
            ->selectRaw('user_id, count(contact_id) as count')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        $i = 1;
        foreach ($mEnvoy as $key => $value) {
            $country = '';
            if (isset($value->user->country)) {
                $country = ', ' . $value->user->country;
            }
            $top10mEnvoy[$i++] = array(
                'count' => $value->count,
                'name' => $value->user->getFullName(),
                'image' => isset($value->user->thumbnail_image) && Storage::disk('public')->exists($value->user->thumbnail_image) ? CommonUtil::getUrl($value->user->thumbnail_image) : asset((config('app.rankup.company_default_image_file'))),
                'uplineName' => $value->user->getUplineName(),
                'location' => $value->user->city . $country,
            );

        }

        $data['view'] = view('seller.leaderboard._top_message_envoye', compact('top10mEnvoy'))->render();
        return $data;
    }

    /**
     * Present at zoom data
     *
     * @param Request $request
     *
     * @return array
     */
    public function presentAtZoomData(Request $request)
    {
        $requestData = $request->all();
        $leaderBoardData = $this->leaderBoardCommonData($requestData);
        $switchType = $leaderBoardData['switchType'];
        $year = $leaderBoardData['year'];
        $start = $leaderBoardData['start'];
        $end = $leaderBoardData['end'];
        $data['pageName'] = $leaderBoardData['pageName'];
        $downlineIds = $leaderBoardData['downlineIds'];
        $present = $top10present = [];

        /**  START : Top présent au zoom: highest count of contacts dragged in “Présents/Présent au Zoom” */
        $present = ContactLog::
            where('status', ContactBoardStatus::ATTENDED_THE_ZOOM)
            ->whereRaw('user_id IN ('.getDownlinesStr(implode(',', $downlineIds)).')')
            ->when($switchType == 'Week' || $switchType == 'Month', function ($q) use ($start,$end) {
                return $q->where('created_at', '>=', $start)
                    ->where('created_at', '<=', $end);
            })
            ->when($switchType == 'Day'  || $switchType == 'customRange', function ($q) use ($start,$end) {
                return $q->where('created_at', '>=', $start)
                    ->where('created_at', '<', $end);
            })
            ->whereYear('created_at', $year)
            ->selectRaw('user_id, count(contact_id) as count')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        $i = 1;
        foreach ($present as $key => $value) {
            $country = '';
            if (isset($value->user->country)) {
                $country = ', ' . $value->user->country;
            }
            $top10present[$i++] = array(
                'count' => $value->count,
                'name' => $value->user->getFullName(),
                'image' => isset($value->user->thumbnail_image) && Storage::disk('public')->exists($value->user->thumbnail_image) ? CommonUtil::getUrl($value->user->thumbnail_image) : asset((config('app.rankup.company_default_image_file'))),
                'uplineName' => $value->user->getUplineName(),
                'location' => $value->user->city . $country,
            );

        }

        $data['view'] = view('seller.leaderboard._top_present_zoom', compact('top10present'))->render();
        return $data;
    }
}