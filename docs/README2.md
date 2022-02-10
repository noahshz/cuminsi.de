# Documentation

This documentation describes the functionality and the process of the cuminsi.de site.
It is explicitly written for the developers so that they can understand and edit the program flow.


# error & message codes

	The error & message codes are send from the logic.php to another site. For example:
	logic.php (action logout) -> send message code to location site (login.php)

	In the logic.php you send the error/message code via GET method:
	 (https://url.net/site?error_code=1001)
	 (https://url.net/site?message=9002)
	 
	To request and customize the error/message codes u need to check if the code isset via
	$_GET['message']/$_GET['error_code']. Via switch it is possible to customize all of the
	error codes.
	 
	

*signup codes (error)*

> **1001**
> "user already exists"

> **1002**
> "email is already used"

> **1003**
> "the passwords aren't equal to each other"

*login codes (error)*

> **2001**
> "user does not exist"

> **2002**
> "password incorrect"

*message codes*

> **9001**
> "user succesfully created"

> **9002**
> "successfully logged out"
