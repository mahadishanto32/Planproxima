<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDaily_schedule_commentAPIRequest;
use App\Http\Requests\API\UpdateDaily_schedule_commentAPIRequest;
use App\Models\Daily_schedule_comment;
use App\Models\DailySchedule;
use App\Models\Notification;
use App\Models\MosData;
use App\Models\User;
use App\Repositories\Daily_schedule_commentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response , Auth  ;
use App\Myclass\PHPMailer;

/**
 * Class Daily_schedule_commentController
 * @package App\Http\Controllers\API
 */

class Daily_schedule_commentAPIController extends AppBaseController
{
    /** @var  Daily_schedule_commentRepository */
    private $dailyScheduleCommentRepository;

    public function __construct(Daily_schedule_commentRepository $dailyScheduleCommentRepo)
    {
        $this->dailyScheduleCommentRepository = $dailyScheduleCommentRepo;
    }

    /**
     * Display a listing of the Daily_schedule_comment.
     * GET|HEAD /dailyScheduleComments
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $dailyScheduleComments = $this->dailyScheduleCommentRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($dailyScheduleComments->toArray(), 'Daily Schedule Comments retrieved successfully');
    }

    /**
     * Store a newly created Daily_schedule_comment in storage.
     * POST /dailyScheduleComments
     *
     * @param CreateDaily_schedule_commentAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDaily_schedule_commentAPIRequest $request)
    {
        $user_data = Auth::user(); 
        $request['user_id'] = $user_data->id ; 
        $request['user_name'] = $user_data->name ; 
        $input = $request->all();

        $scheduleinfo = DailySchedule::find($request->input('daily_schedule_id'));
        $task_user =  User::find($scheduleinfo->user_id);
        $notification = Notification::create([
            'user_id' => $user_data->id,
            'dept_id' => $scheduleinfo->dept_id,
            'notification_type' => 'Follow-Up',
            'notification_section' => "",
            'status' => 0,
            'details' => $user_data->name . " Commented in your work schedules.",
            'notif_receiver' => $scheduleinfo->user_id
        ]);
        //if($task_user->phone){
            $task = "আপনার BPT প্যানেল চেক করুন, দৈনিক কাজের তালিকায় একটি নতুন মন্তব্য যোগ করা হয়েছে।";

            $this->sendSms($task_user->phone, $task);
        //}
        
                $phpMail = new PHPMailer();
                $message = "";
                $phpMail->AddAddress( $task_user->ad_mail,  $task_user->name);
                $nextmonth = "";
                $data['contena'] =  $request->comment.'
                    


                    Regards,
                    Team BPT
                    ';
                $message = view('mail.default_theme')->with(['data' => $data]); 
                $phpMail->AddReplyTo($user_data->ad_mail, "Check Mail");

                $msg = nl2br($message);

                $phpMail->FromName =  $user_data->name ;
                $phpMail->From = "management.desk@ssgbd.com";
                $phpMail->Sender = $user_data->ad_mail ;
                $phpMail->IsHTML(true);
                $phpMail->Host = "mail.ssgbd.com:25";
                $phpMail->IsSMTP();
                $phpMail->Mailer  = "smtp";
                $phpMail->Subject = "BPT Daily Work Schedule feedback";
                $phpMail->Body = $msg;
                $phpMail->SMTPAuth = false;

                if (!$phpMail->Send()) {
                    echo "Message could not be sent.";
                    echo "Mailer Error: " . $phpMail->ErrorInfo;
                    // exit;
                }




       

        $dailyScheduleComment = $this->dailyScheduleCommentRepository->create($input);

        return $this->sendResponse($dailyScheduleComment->toArray(), 'Daily Schedule Comment saved successfully');
    }
    public function sendSms($contacts, $msg){////Note: Curl Configuration sms
        $api_key  = "C20016585b5d65039143f5.68321617";
        $senderid = 'Super Star';
        $URL      = "www.bangladeshsms.com/smsapi?api_key=" . urlencode($api_key) . "&type=text&contacts=" . urlencode($contacts) . "&senderid=" . urlencode($senderid) . "&msg=" . urlencode($msg);
        return $responses = $this->curlFunc($URL);
    }
    public static function curlFunc($url) {//Note: Curl Resoponce MSG
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => array("Content-Type: text/html; charset=utf-8"),
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; rv:19.0) Gecko/20100101 Firefox/19.0',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSLVERSION => 3
        ));
        $output = curl_exec($ch);
        curl_close($ch);
        if (!$output) {
            $output = file_get_contents($url);
        }
        return $output;
    } 
    
    public function achivement_notification(Request $request)
    {
        $user_data = Auth::user(); 
        $request['user_id'] = $user_data->id; 
        $request['user_name'] = $user_data->name; 
        $input = $request->all(); 
        $previous_date = date("Y-m-d", strtotime ( '-1 month' , strtotime ( date('Y-m-d') ) ));

        $notification = Notification::create([
            'user_id' => $user_data->id,
            'dept_id' => $user_data->dept_id,
            'notification_type' => 'Achivement Notification',
            'notification_section' => date('M', strtotime((date('m')-1))),
            'status' => 0,
            'details' => $user_data->name." ".(date('F',strtotime($previous_date)))." Achivement",
            'notif_receiver' => 3,
        ]);
        return $this->sendResponse($notification->toArray(), 'Monthly Achivement Notification Send successfully');
    }

    public function achivement_update(Request $request)
    {
        $items = $request->items;
        foreach($items as $key=>$item){
            $MosData =  MosData::find($item['mosachievementjoin']['id']);
            $MosData->april = $item['mosachievementjoin']['april'];
            $MosData->save();
        }
        return $this->sendResponse($items, 'Monthly Achivement Updated successfully');
    }

    /**
     * Display the specified Daily_schedule_comment.
     * GET|HEAD /dailyScheduleComments/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Daily_schedule_comment $dailyScheduleComment */
        $dailyScheduleComment = $this->dailyScheduleCommentRepository->find($id);

        if (empty($dailyScheduleComment)) {
            return $this->sendError('Daily Schedule Comment not found');
        }

        return $this->sendResponse($dailyScheduleComment->toArray(), 'Daily Schedule Comment retrieved successfully');
    }

    /**
     * Update the specified Daily_schedule_comment in storage.
     * PUT/PATCH /dailyScheduleComments/{id}
     *
     * @param int $id
     * @param UpdateDaily_schedule_commentAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDaily_schedule_commentAPIRequest $request)
    {
        $input = $request->all();

        /** @var Daily_schedule_comment $dailyScheduleComment */
        $dailyScheduleComment = $this->dailyScheduleCommentRepository->find($id);

        if (empty($dailyScheduleComment)) {
            return $this->sendError('Daily Schedule Comment not found');
        }

        $dailyScheduleComment = $this->dailyScheduleCommentRepository->update($input, $id);

        return $this->sendResponse($dailyScheduleComment->toArray(), 'Daily_schedule_comment updated successfully');
    }

    /**
     * Remove the specified Daily_schedule_comment from storage.
     * DELETE /dailyScheduleComments/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Daily_schedule_comment $dailyScheduleComment */
        $dailyScheduleComment = $this->dailyScheduleCommentRepository->find($id);

        if (empty($dailyScheduleComment)) {
            return $this->sendError('Daily Schedule Comment not found');
        }

        $dailyScheduleComment->delete();

        return $this->sendSuccess('Daily Schedule Comment deleted successfully');
    }
}
