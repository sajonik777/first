<?php

/**
 * Class EscalatesComponent
 */
class EscalatesComponent
{
    /**
     * @param $request Request
     * @param $escalate Escalates
     *
     * @return bool
     */
    public static function reaction(&$request, $escalate)
    {
        $attributes = [];
        $mails = [];
        if ($escalate->manager) {
            $attributes['Managers_id'] = $escalate->manager->Username;
            $attributes['mfullname'] = $escalate->manager->fullname;
            if ($escalate->manager->sendmail) {
                $mails[] = $escalate->manager->Email;
            }
        } else {
            $attributes['Managers_id'] = null;
            $attributes['mfullname'] = null;
        }
        if ($escalate->group) {
            $attributes['groups_id'] = $escalate->group->id;
            $attributes['gfullname'] = $escalate->group->name;
            if ($escalate->group->send) {
                $mails[] = $escalate->group->email;
            }
        }
        $user = CUsers::model()->findByAttributes(['Username' => $request->CUsers_id]);
        if ($user->sendmail) {
            $mails[] = $user->Email;
        }
        $manager = CUsers::model()->findByAttributes(['Username' => $request->Managers_id]);
        if ($manager->sendmail) {
            $mails[] = $manager->Email;
        }
        if (!empty($attributes)) {
            $attributes['lastactivity'] = date('Y-m-d H:i:s');
            $attributes['wasescalated'] = 1;
            if (Request::model()->updateByPk($request->id, $attributes)) {
                $requestEscalate = new RequestEscalates();
                $requestEscalate->request_id = $request->id;
                $requestEscalate->escalate_id = $escalate->id;
                $requestEscalate->save();

                self::sendMail($mails, $request, $escalate->manager->fullname);

                return true;
            }
        }

        return false;
    }

    /**
     * @param $mails array
     * @param $request_id
     * @param $manager
     */
    public static function sendMail($mails, $request, $manager)
    {
        $template = Messages::model()->findByAttributes(['name' => '{escalate}']);
        $umessage = Email::MessageGen($template->content, $manager, $request);
        $subject = Email::MessageGen($template->subject, $manager, $request);
        foreach ($mails as $mail) {
            if ($mail) {
                if (isset(Yii::app()->params['smqueue']) && Yii::app()->params['smqueue'] == 1) {
                    $afiles = NULL;
                    Yii::app()->mailQueue->push($mail, $subject, $umessage, $priority = 0, $from = '', $afiles, null);
                } else {
                    Email::send($mail, $subject, $umessage, []);
                }
            }
        }
    }
}
