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

message RelatedEntity {
    string id = 1;
    string type = 2;
    string field = 3;
}

message Relation {
    RelatedEntity from = 1;
    RelatedEntity to = 2;
}

{messages}