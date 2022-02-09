# Documentation

# classes.php
> in "classes.php" all global classes are defined

## Session-Class
### Functions:
    - construct:                starts the session
    - set(varname, value):      sets an session variable
    - get(varname):             returns the value of session variable
    - destroy:                  unsets and destroys session
    - isset:                    checks if $_SESSION['uid'] is set
---
# constants.php
> in constants.php all global constans are defined

    - HASH                      defines the used hash algorithm
---
# db.php
> in db.php the mysql connection string is stored and executed
>
> the connection string is available with $pdo
