<?php

    function select($query, $conn){
        
        $row = mysqli_query($conn, $query);

        if (mysqli_num_rows($row) > 0){
            return true;
        }else{
            // echo $conn->error . "\n";
            return false;
        }
        
        return false;
    }
    
    function insert($query, $conn){
        
        if ($conn->query($query) == TRUE) {
            return true;
        } else {
            echo $conn->error . "\n";
            return false;
        }
        
        return false;
    }

    function update($query, $conn){
        
        if ($conn->query($query) == TRUE) {
            return true;
        } else {
            echo $conn->error . "\n";
            return false;
        }
        
        return false;
    }

    function selectValue($query, $conn){

        $result = $conn->query($query);

        if ($result->num_rows > 0) {

            while($row = $result->fetch_assoc()) {
                return $row;
            }
            
        } else {
            return null;
        }

    }
    
    function create($query, $conn){
        
        if ($conn->query($query) === TRUE) {
            return true;
        } else {
            return false;
            // echo "Error creating table: " . $conn->error;
        }
        
        return false;
    }

?>