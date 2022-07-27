<?php 
    //Any time a change is made to a work order, log that change by calling this function. Stores change in the changelog table
    function addChange($userID, $changeDateTime, $woNum, $changeDescription){
        global $db;
        $results = "";

        $sql = "INSERT INTO ChangeLog_Table SET userID = :userID, 
                woNum = :woNum, changeDescription = :changeDescription"; 

        $binds = array(
            ":userID" => $userID,
            ":woNum" => $woNum,
            ":changeDescription" => $changeDescription
        );

        $stmt = $db->prepare($sql);
        if($stmt->execute($binds) && $stmt->rowCount() > 0)
            $results = "Change Logged";

        return $results;
    }
?>