<?php

function view_msg($senderID, $receiverID, $message, $messageID, $replyID) {
    $sender = "<div class='d-flex'>
                        <div>From: 
                            <span class='font-weight-bold'>
                                $senderID
                            </span>
                        </div>
                        <a id='$messageID $senderID' class='ml-auto' href=''>Reply</a>
                    </div>";
    if ($senderID == '') {
        $sender = "";
    }
    return "<div style='margin-left:calc(0*10px);' id='$messageID' class='border-bottom pb-2'>
                <div>To: 
                    <span class='font-weight-bold'>$receiverID</span>
                </div>
                $sender
                <div>$message</div>
            </div>
    ";
}

function view_msg_sent($senderID, $receiverID, $message, $messageID, $replyID) {
    $sender = "<div class='d-flex'>
                        <div>From: 
                            <span class='font-weight-bold'>
                                $senderID
                            </span>
                        </div>
                    </div>";
    if ($senderID == '') {
        $sender = "";
    }
    return "<div id='$messageID' class='border-bottom pb-2'>
                <div>To: 
                    <span class='font-weight-bold'>$receiverID</span>
                </div>
                $sender
                <div>$message</div>
            </div>
    ";
}

function view_reply($senderID, $receiverID, $message, $messageID, $replyID) {
    return "<div style='margin-left:calc(2*10px);' id='$messageID' class='border-bottom pb-2'>
                <div>Reply from: 
                    <span class='font-weight-bold'>
                        $senderID
                    </span>
                </div>
                <div>
                    $message
                </div>
            </div>
            ";
}