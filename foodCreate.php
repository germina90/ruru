<?php
// Include config file
require_once 'config.php';


// Define variables and initialize with empty values
$item = "";
$item_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate item
    $input_item = trim($_POST["item"]);
    if(empty($input_item)){
        $item_err = "Please enter an item.";
    } elseif(!filter_var(trim($_POST["item"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $item_err = 'Please enter a valid item.';
    } else{
        $item = $input_item;
    }
        
    // Check input errors before inserting in database
    echo "hello 1a";
    echo $item_err;
    if(empty($item_err)){
        echo "hello 2";
        // Prepare an insert statement
        $sql = "INSERT INTO food (item) VALUES (?)";
 
        if($stmt = $mysqli->prepare($sql)){
            echo "hello 3";
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_item);
            
            // Set parameters
            $param_item = $item;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                echo "hello 4";
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add a food record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($item_err)) ? 'has-error' : ''; ?>">
                            <label>Item</label>
                            <input type="text" name="item" class="form-control" value="<?php echo $item; ?>">
                            <span class="help-block"><?php echo $item_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>