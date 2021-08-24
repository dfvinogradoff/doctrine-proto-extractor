# doctrine-proto-extractor
Tool for extract Protobuff from PHP Doctrine framework entity files

# Install
Easy way is inside Docker container run `composer install` and then execute  
```shell
cp ./config.dist.yaml ./config.yaml
./bin doctrine-proto:extract ./config.yaml -vvv
```