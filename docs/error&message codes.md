# error & message codes

### Definition
The error & message codes are there for the user as feedback on his actions.
They are send from the **logic.php** to the target site. For example:

 - logic.php (action: logout) **---[9002]--->** login.php -> display transferred code

<hr>

### Sending codes
In the logic.php you **send** the error/message code via GET method:

     header('Location: https://url.net/site?error_code=1001');
     header('Location: https://url.net/site?message=9002');
 <hr>
 
### Requesting codes
To **request** and customize the error/message codes u need to check if the code isset via

	if(isset($_GET['error_code']) {
		echo $_GET['error_code'];
	}

<hr>

### Cusomizing codes
Via switch it is possible to **customize** all of the error codes:

    if(isset($_GET['error_code']) {
    	switch($_GET['error_code']) {
			case '1001':
				echo 'user already exists';
			case '1002':
				echo 'email is already used';
		}
    }
  <hr>

### Code list
	
**signup codes (error)**

 - > **1001**
   > "user already exists"
 - > **1002**
   > "email is already used"
 - > **1003**
   > "the passwords aren't equal to each other"

**login codes (error)**

 - > **2001**
   > "user does not exist"
   
 - > **2002**
   > "password incorrect"

**settings codes (error)**

- > **8002**
   > "could net send mail: already verified"

**message codes**

 - > **8001**
   > "verification email resend"

 - > **8001**
   > "email changed"

 - > **9001**
   > "user succesfully created"

 - > **9002**
   > "successfully logged out"

 - > **9003**
   > "successfully verified please log in again"

