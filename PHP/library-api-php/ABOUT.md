# About

Php build in server can be run with php -S 127.0.0.1:8000

### When clone run to prepare autoloading provided by composer ###
>composer dump-autoload

No need to install anything

#### The code contains a lot of extra code which was not needed for purpose of the task but just wanted to show that know how most of the MVC frameworks works under the hood.

---
- Using PHP 8.3, however, some features have not been used
due to time constraint (example, not used properties' promotion in constructor).


- Init a project with a composer which allows structuring it in a more modern way 
and provide class autoloading which allows removing require statements.


- Provided strong typing.


Provided simple storage for data as REST API is stateless. If that bevaviour is unwanted
it can be disabled in the file 
#### If the file was created need to be removed to disable storage ####
```php 
App\Data\Data.php
>const ENABLE_STORAGE = 1;
```
Due to time constraint, not installed any ENV library that allows storing that in .env file

In the application introduced a Repository pattern that allows keeping 
controller tiny, also if more logic is needed than just filtering here, we
move business logic into reusable services.

```php
class LoanController {
  
    public function index(): void {
        /**
         * In full scale application, we would inject the repository, 
         * not instantiate it here.
         */
        $loanRepository = new LoanRepository();
        $activeLoans = $loanRepository->getActiveLoans();

        JsonResponse::send($activeLoans);
    }
```

All properties in models are public for simplicity, but objects should be encapsulated
and expose only what is needed. Also, not all setters were created when needed but for 
simplicity used direct assigment to properties.

When need to pass any parameter to the endpoint using query string for simplicity like

>/loans/return?bookId=1&borrowerId=1

but we can also send data in different way depending on a use case as for url there is a limit
2083 for older clients.

Application also has not implemented container for services like a popular framework, so when
want to use a service using creation of new instance. Code also can be improved by installing
dependencies (for example we can install a dependency-injection container the one used by symfony under the hood and allow configure services in more elegant way)
