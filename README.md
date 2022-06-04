# TechPods Interview task

## Notes

Maximum decimal number precision is 10.
All calculations after that are not performed.
This precision is on single operation level, not only on the final result.

As the assignment does not require it, grouping operations with brackets is not implemented.

## Requirements

- PHP 8.1
- Docker (Optional)

## Building the project

### Container build

Make sure you are in the root project directory and run:

`docker build . -t techpods-interview-task`

### Installing project dependencies

`docker run -itu 1000 --rm -v "$PWD":/app -w /app techpods-interview-task composer install`

## How to run the project

Make sure you have executed the required steps in [Building the project](#building-the-project)

`docker run -itu 1000 -p 8000:8000 --rm -v "$PWD":/app -w /app techpods-interview-task symfony server:start`

Now the project should be accessible on http://127.0.0.1:8000/calculator

## Executing the automated tests

`docker run -itu 1000 --rm -v "$PWD":/app -w /app techpods-interview-task bin/phpunit --coverage-html .code-coverage`

After executing the tests, the code coverage report will be available under `.code-coverage` directory.

## Debug the project (on linux only)

Add to the docker run command:

`--add-host=host.docker.internal:host-gateway` so xdebug can connect to the host IDE

## Static code analysis tools

### PHP Mess Detector

`docker run -itu 1000 --rm -v "$PWD":/app -w /app techpods-interview-task vendor/bin/phpmd src text phpmd.xml`

### PHP Code Sniffer

`docker run -itu 1000 --rm -v "$PWD":/app -w /app techpods-interview-task vendor/bin/phpcs -p`

### Psalm with error level 1 (the highest possible)

`docker run -itu 1000 --rm -v "$PWD":/app -w /app techpods-interview-task vendor/bin/psalm`
