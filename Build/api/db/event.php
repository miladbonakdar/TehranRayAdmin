<?php
/**
 * Created by PhpStorm.
 * User: -MR-
 * Date: 10/05/2016
 * Time: 12:06 PM
 */

function getUserLastNotifications($db, $uid, $limit){

    $resQ = $db->makeQuery("select 
event.EventDate , file_storage.FullPath , user.FullName , forum_question.Title , forum_question.ID as QuestionID
 , event_type.EventTypeFA, event_type.HasQuestion , event.ID as EventID
from event 
left join event_type on event_type.ID = event.EventTypeID
inner join forum_question on forum_question.ID = event.EvenLinkID
left join user on user.ID = event.EventCauseID
left join file_storage on file_storage.ID = user.AvatarID
where event.EventUserID='$uid' and event.EventSeen='0' 
order by event.EventDate desc 
limit $limit");

    $arr = [];
    while($r = $resQ->fetch_assoc())
        $arr[] = $r;

    return $arr;
}

function getUserTotalNotifications($db, $uid){
    return $db->makeQuery("select count(*) as Total from event
inner join forum_question on forum_question.ID = event.EvenLinkID
where event.EventUserID='$uid' and event.EventSeen='0'")->fetch_assoc()['Total'];
}
?>