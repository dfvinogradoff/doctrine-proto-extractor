syntax = "{protoVersion}";

package {package};

option go_package = "{goPackage}";

service {service} {
{methods}
}

message ReplyResponse {
}

message Uuid {
    string id = 1;
}

{messages}