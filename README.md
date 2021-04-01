# JSON to AppSettings ENV
![Maintained By: BossLabs Ltd](https://img.shields.io/badge/MaintainedBy-BossLabs%20Ltd-blueviolet)
![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)
## Introduction
This command line tool allows you to convert a valid JSON object into "double underscore" notation environment variables for DotNet Core applications.

## Use Cases
- Kubernetes Config Maps for appsetting overrides
- Terraform injection of environment variables into dotnet core applications
- ...

## Example
Passing in a JSON string such as `{"test":1, "test2":[{"test3":3}]}` will return
```
{
	"test":1,
	"test2__0__test3":3
}
```

## Installation
Either run in a PHP 7.4 environment or use the provided docker files.
#### PHP
```
git clone https://github.com/bosslabs/appsettings-flatten
cd appsettings-flatten
composer install
```
#### Docker
```
docker pull docker.pkg.github.com/bosslabs/appsettings-flatten/appsettings-flatten:latest
```

## Usage

#### Manual PHP Method
`php run.php app:convert --json '{"test":1, "test2":[{"test3":3}]}'`

#### Docker
```
docker run docker.pkg.github.com/bosslabs/appsettings-flatten/appsettings-flatten:latest --json  '{}'
```

### Development
```
#First clone the repo
git clone https://github.com/bosslabs/appsettings-flatten

#Build the docker environment
docker build -f Dockerfile-development -t appsettings-flatten .

#Run the docker environment and map in the application code as a volume
docker run -v <path-to-repo>/appsettings-flatten:/app appsettings-flatten
```

### Testing
#### ...TODO

## License
This project is licensed under the terms of the MIT license.