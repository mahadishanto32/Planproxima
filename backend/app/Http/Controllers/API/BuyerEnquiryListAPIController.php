<?php

namespace App\Http\Controllers\API;
use GuzzleHttp\Client;
use App\Http\Requests\API\CreateBuyerEnquiryListAPIRequest;
use App\Http\Requests\API\UpdateBuyerEnquiryListAPIRequest;
use App\Models\BuyerEnquiryList;
use App\Repositories\BuyerEnquiryListRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;
use Auth, DB;
use App\Models\User;
use App\Models\Department;
use App\Models\BuyerContactShare;
use App\Models\buyerEnquiryColumn;
/**
 * Class BuyerEnquiryListController
 * @package App\Http\Controllers\API
 */

class BuyerEnquiryListAPIController extends AppBaseController
{
    /** @var  BuyerEnquiryListRepository */
    private $buyerEnquiryListRepository;

    public function __construct(BuyerEnquiryListRepository $buyerEnquiryListRepo)
    {
        $this->buyerEnquiryListRepository = $buyerEnquiryListRepo;
    }

    /**
     * Display a listing of the BuyerEnquiryList.
     * GET|HEAD /buyerEnquiryLists
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $BuyerContactShare = BuyerContactShare::where('user_id' , $user->id)->pluck('b_id');
        $buyerEnquiryLists = BuyerEnquiryList::select('users.name' , 'buyer_enquiry_lists.*')
        ->join('users' , 'users.id' ,'buyer_enquiry_lists.user_id')
        ->where('buyer_enquiry_lists.user_id' , $user->id)
        ->orWhere(function ($query) use ($BuyerContactShare) {
            $query->whereIn('buyer_enquiry_lists.id', $BuyerContactShare);
        })
        ->with('BuyerColumnjoin')->get();
        return $this->sendResponse($buyerEnquiryLists->toArray(), 'Buyer Enquiry Lists retrieved successfully');
    }

    /**
     * Store a newly created BuyerEnquiryList in storage.
     * POST /buyerEnquiryLists
     *
     * @param CreateBuyerEnquiryListAPIRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $user_data = Auth::user();        
        $input = $request->all();
        $buyerEnquiryList = $this->buyerEnquiryListRepository->create($input);
        $tasks = $request->tasks;
        
        foreach ($tasks as $key => $value) {
            if (isset($value['column_name'])) {
                $EnquiryColumn = new buyerEnquiryColumn();
                $EnquiryColumn->buyer_enquiry_id = $buyerEnquiryList->id;
                $EnquiryColumn->column_name = $value['column_name'];
                $EnquiryColumn->column_value = $value['column_value'];
                $EnquiryColumn->save();
            }
        }
        return $this->sendResponse($buyerEnquiryList->toArray(), 'Buyer Enquiry List saved successfully');
    }

    /**
     * Display the specified BuyerEnquiryList.
     * GET|HEAD /buyerEnquiryLists/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var BuyerEnquiryList $buyerEnquiryList */
        $buyerEnquiryList = BuyerEnquiryList::with('BuyerColumnjoin')->where('id',$id)->first();

        if (empty($buyerEnquiryList)) {
            return $this->sendError('Buyer Enquiry List not found');
        }

        return $this->sendResponse($buyerEnquiryList->toArray(), 'Buyer Enquiry List retrieved successfully');
    }

    /**
     * Update the specified BuyerEnquiryList in storage.
     * PUT/PATCH /buyerEnquiryLists/{id}
     *
     * @param int $id
     * @param UpdateBuyerEnquiryListAPIRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $input = $request->all();
        /** @var BuyerEnquiryList $buyerEnquiryList */
        $buyerEnquiryList = BuyerEnquiryList::find($id);
        $tasks = $request->tasks;
        
        foreach ($tasks as $key => $value) {
            if (isset($value['column_name'])) {
                $EnquiryColumn = buyerEnquiryColumn::find($value['id']);
                $EnquiryColumn->column_name = $value['column_name'];
                $EnquiryColumn->column_value = $value['column_value'];
                $EnquiryColumn->save();
            }
        }        
        if (empty($buyerEnquiryList)) {
            return $this->sendError('Buyer Enquiry List not found');
        }
        $buyerEnquiryList = $this->buyerEnquiryListRepository->update($input, $id);
        return $this->sendResponse($buyerEnquiryList->toArray(), 'BuyerEnquiryList updated successfully');
    }

    /**
     * Remove the specified BuyerEnquiryList from storage.
     * DELETE /buyerEnquiryLists/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var BuyerEnquiryList $buyerEnquiryList */
        $buyerEnquiryList = $this->buyerEnquiryListRepository->find($id);

        if (empty($buyerEnquiryList)) {
            return $this->sendError('Buyer Enquiry List not found');
        }

        $buyerEnquiryList->delete();

        return $this->sendSuccess('Buyer Enquiry List deleted successfully');
    }

    public function dept_user(Request $request){
        $b_id = $request->b_id ;
        $dept_id = $request->dept_id ;
        $dept_data = User::where('dept_id', $dept_id)->where('status',1)->get();
        $contactShare = BuyerContactShare::where('b_id', $b_id)->get();
        $data = [
            'dept_data' => $dept_data,
            'contactShare' => $contactShare,
        ];

        return $this->sendResponse($data, 'User List Retrived successfully');
    }

    public function all_dept(Request $request){
        $q = Department::select('departments.*' , 'departments.name as text')
        ->where('status', 1);
        if($request->dept_id){
            $q->where('id', $request->dept_id);
        }
        $departments = $q->get();

        return $this->sendResponse($departments->toArray(), 'Departments retrieved successfully');
    }   
    
    public function country_api_list(Request $request){
        $client = new Client(['verify' => false]);

        $response = $client->get('https://restcountries.com/v3.1/independent');
        $data = [];
        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            // do something with the data
        } else {
            // handle the error
        }
        foreach ($data as $key => $value) {
            $data[$key]['text'] = $value['name']['common'];
            $data[$key]['id'] = $value['name']['common'];
        }
        return $this->sendResponse($data, 'Departments retrieved successfully');
    }       
}
