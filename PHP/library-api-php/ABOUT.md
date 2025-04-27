# About

---
- Using PHP 8.3, however, some features have not been used
due to time constraint (example, not used properties' promotion in constructor).


- Init a project with a composer which allows structuring it in a more modern way 
and provide class autoloading which allows removing require statements.


- Provided strong typing.

Provided simple storage for data as REST API is stateless. If that bevaviour is unwanted
it can be disabled in the file 
```php 
App\Data\Data.php
>const ENABLE_STORAGE = 1;
```
Due to time constraint, not installed any ENV library that allows storing that in .env file
