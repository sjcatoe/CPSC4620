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

function showMedia($mediaID, $title, $description, $pathway, $date, $category, $numViews, $userID, $type) {
    $mediaHTML = "<div class='container'>
                    <h2>$title</h2>
                </div>";
    if ($type == "IMAGE") {
        $mediaHTML = $mediaHTML . "<div class='container border'>
                                        <img width='600' height='450' src=$pathway>
                                    </div>";
                    
    } else if ($type == "VIDEO") {
        $mediaHTML = $mediaHTML . "<div class='container border'>
                                        <video width='600' height='450' controls>
                                            <source src=$pathway type='video/mp4'>
                                                Your browser does not support the video tag.
                                        </video> 
                                    </div>";
                    
    } else if ($type == "AUDIO") {
        $mediaHTML = $mediaHTML . "<div class='container border'>
                                        <audio controls autoplay>
                                            <source src=$pathway type='audio/ogg'>
                                            <source src=$pathway type='audio/mpeg'>
                                            <source src=$pathway type='audio/wav'>
                                            Audio type not supported.
                                        </audio>
                                    </div>";
                    
    }
    $mediaHTML = $mediaHTML . "<div class='container'>
                                <h6>$numViews view(s)</h6>
                            </div>";
    $mediaHTML = $mediaHTML . "<div class='container border-bottom'>
                                <h6>$userID</h6>
                            </div>";
    $mediaHTML = $mediaHTML . "<div class='container border-bottom'>
                                <h6>Uploaded on $date</h6>
                            </div>";
    $mediaHTML = $mediaHTML . "<div class='container'>
                                <h6>$description</h6>
                            </div>";
    $mediaHTML = $mediaHTML . "<div class='container-fluid'>
                                <a href=$pathway class='btn btn-primary' role='button' aria-pressed='true'>Download</a>
                            </div>";

    return $mediaHTML;
}

function MediaList($mediaID, $title, $description, $pathway, $date, $category, $numViews, $publisherID, $type) {
    $query = array(
        "id" => $mediaID
    );
    $link = "./viewMedia.php?".http_build_query($query);
    if ($type == "VIDEO") {
        $pathway = "./img/video-thumbnail.jpg";
    } else if ($type == "AUDIO") {
        $pathway = "./img/audio-thumbnail.png";
    }
    $render = "";
    $query = array(
        "id" => $publisherID
    );
    $channel = "./channel.php?".http_build_query($query);

    return "<div class='border'>
                <div class='d-flex'>
                    <a href=$link>
                        <img src=$pathway alt=$description width='100' height='100'>
                    </a>
                    <div class='p-2'>
                        <div class='d-inline'>
                            <h6 class='d-inline'>$title | </h6>
                            <a class='d-inline' style='color:blue' href=$channel>$publisherID</a>
                        </div>
                        <p>$description</p>
                    </div>
                </div>
            </div>";
}