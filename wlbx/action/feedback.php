<?php 
if($_POST["action"] == NULL)
{
	echo_404($_SERVER['PHP_SELF']);
}
else if($_POST["action"] == "post_feedback")
{
    $name = base64_encode($_POST["name"]);
    $id = base64_encode($_POST["id"]);
    $contact = base64_encode($_POST["contact"]);
    $detail = base64_encode($_POST["detail"]);
    $sql = "INSERT INTO feedback (
                `name`,
                `identity`,
                `contact`,
                `detail`
            )
            VALUES
                (
                    '$name',
                    '$id',
                    '$contact',
                    '$detail'
                )";
    $mysqli->real_query($sql);
    echo json_encode(array("error" => 0,"name"=>$name,"b"=>$detail));
}
else if($_POST["action"] == "get_feedback")
{
    $sql = "SELECT
            feedback.`name`,
            feedback.identity,
            feedback.contact,
            feedback.detail
            FROM
            feedback
            ORDER BY
            feedback.id DESC";
    $mysqli->real_query($sql);
    $result = $mysqli->store_result();
    $data = array("error" => 0,"contentlen" => $result->num_rows);
    while($row = $result->fetch_array())
    {
        array_push($data,
            array("name" => $row["name"],
                  "id" => $row["identity"],
                  "contact" => $row["contact"],
                  "detail" => $row["detail"]
                 )
        );
    }
    echo json_encode($data);
}
else
{
    echo_404($_SERVER['PHP_SELF']);
}
?>