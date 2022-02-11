# classes

### Definition
All global classes are stored in the **classes.php**. This file is automaticly included by using the **requirements.php**.
<hr>

### Session class

The session class is used to handle the PHP-sessions more comfortable.
By adding a constructor which starts a new session u can create a new one by initializing the class:

	$session = new Session();

**Set session variables:**

To set session variables u just call the method **set** where u pass the variablename and the value:

	$session->set('username', 'dummy');

**Get session variables:**

To get session variables u just call the method **get** where u pass the variablename:

	echo $session->get('username');

	Output: "dummy"

**Check session activity:**

To check if the session is currently active u use the **isset** method.
This method returns true or false.

	echo $session->isset();
	
	Output: true

**Destroy session:**

To destroy the session u use the **destroy** method:
	
	$session->destroy();
<hr>

### Code:

     class Session {
		function __construct() {
			session_start();
		}
		function set($vname, $value) {
			$_SESSION[$vname] = $value;
		}
		function get($vname) {
			return $_SESSION[$vname];
		}
		function destroy() {
			session_unset();
			session_destroy();
		}
		function isset() {
			if(isset($_SESSION['uid'])) {
				return true;
			}
			return false;
		}
	}
 <hr>

